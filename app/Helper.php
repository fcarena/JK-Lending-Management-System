<?php 

use App\Events\Remittance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


/*
|--------------------------------------------------------------------------
| Custom Helper Functions
|--------------------------------------------------------------------------
|
| Here is where all the custom helper functions are located. This made
| to be separate from Laravel's own helper function for cleanliness.
| Made by Eron Tancioco <tancioco.eron@gmail.com> 
| 
| Best Regards :)
*/

/**
* Check if the current loan id is already in the active remittance table.
*
* @param  string  $id
* @return bool
*/
function check_active_duplicate($id)
{
    return DB::table('active_remittable_loans')
               ->select('id')
               ->where('loan_id', $id)
               ->exists();
}

/**
* Gets the due date from a given date
*
* @param  int $day
* @param  array $dates
* @param  int $type
* @param  int $term
* @return Carbon instance
*/
function getDueDate($day, $term, $dates = null, $type = 1)
{
    $nextRemittance = null;

    // If the term type of the loan is 'by month'
    if($type == 1)
    {
        // Sort the dates in ascending order
        sort($dates);
    
        // Loop through the array of dates to check the remittance date
        foreach($dates as $date)
        {
            if( ( (int)$date - (int)$day ) >= 0 )
            {
                $nextRemittance = $date; 

                break;
            }
          
        }

        if($nextRemittance == null)
            $nextRemittance = $dates[0];

        // Adjust the current date to the first remittance date
        $remittanceDate = ($day > $nextRemittance) ? 
        date('Y-m', strtotime('+1 month')).'-'.$nextRemittance : date('Y-m').'-'.$nextRemittance;

        // Add the term to the first remittance date to get the due date
        $remittanceDate = Carbon::parse($remittanceDate)->addMonths(floor($term));

        // If the term has no decimal in it
        if(isset( explode(".", $term)[1] ) == false )
        {
            $ctr = 0;

            foreach($dates as $date)
            {

                if( $remittanceDate->format('d') != $date )    
                {
                    $remittanceDate->day = $date;

                    if($ctr != 0)
                        $remittanceDate->month = $remittanceDate->month - 1;

                    break;
                }

                $ctr++;
              
            }
        }

        return $remittanceDate;
    }

    // $remittanceDate = (date('d') > $nextRemittance) ? 
    //     date('Y-m', strtotime('+1 month')).'-'.$nextRemittance : date('Y-m').'-'.$nextRemittance;

    // return Carbon::parse($remittanceDate)->addMonths()->addDays() ; 

};

/**
* Display the Peso Sign
*
* @param  null
* @return char
*/
function peso()
{
    return ('&#8369;');
}

/**
* Ready the active remittance table for insertion and update.
*
* @param  null
* @return null
*/
function ready_active_table()
{
    // Before inserting rows into the active table, delete all remitted loans from the table.
    // $clean_active_table = \DB::table('active_remittable_loans')
    //                         ->where('active_remittable_loans.isRemitted', 1)
    //                         ->delete();

    // Check if the active table is empty or not
    $check_if_rows_exists = DB::table('active_remittable_loans')
                              ->select('id')
                              ->exists();

    // If the active table is empty, truncate it to reset the id increment
    if(! $check_if_rows_exists)
    {
        $reset_active_table = DB::table('active_remittable_loans')
                                ->truncate();
    }

    //---------------------------------------------------------//
    // This is where the operations for late loans are located //
    //---------------------------------------------------------//
    else
    {
        // Get all the remaining loans in the active table
        $get_remaining_loans = DB::table('active_remittable_loans')
                                ->select('loan_id')
                                ->get();

        // Update each loan's status to late and add a zero remittance for yesterday
        foreach ($get_remaining_loans as $loan) 
        {
            // Update loan status to late
            $update_loan_status = DB::table('loans')
                                   ->where('id', $loan->loan_id)
                                   ->update(['loan_status_id' => 3]);

            // Add zero remittances for each loan
            $add_zero_remittance = DB::table('loan_remittances')
                                    ->insert([
                                        [
                                            'loan_id' => $loan->loan_id,
                                            'date' => Carbon::yesterday('Asia/Manila'),
                                            'amount' => 0.00
                                        ]
                                    ]);

            // Check if there are already late remittances for the loan 
            $check_late_amount = DB::table('late_remittance_amount')
                                 ->select('amount')
                                 ->where('loan_id', $loan->loan_id)
                                 ->first();

            // Get the loan's deduction amount
            $get_loan_deduction = DB::table('loans')
                                 ->select('deduction')
                                 ->where('id', $loan->loan_id)
                                 ->first();

            // If there are no late remittances yet
            if($check_late_amount == null)
            {                 
                                            
                // Add a new late remittance for the loan
                $add_late_remittance = DB::table('late_remittance_amount')
                                        ->insert([
                                            [
                                                'loan_id' => $loan->loan_id,
                                                'amount' => $get_loan_deduction->deduction
                                            ]
                                        ]);
            }
            else
            {   
                // Get the current late remittance amount for the loan
                $current_late_remittance_amount = DB::table('late_remittance_amount')
                                                    ->select('amount')
                                                    ->where('loan_id', $loan->loan_id)
                                                    ->first();

                // Update the current late remittance amount by adding the loan's deduction
                $update_late_remittance = DB::table('late_remittance_amount')
                                          ->where('loan_id', $loan->loan_id)
                                          ->update(
                                            [
                                                'amount' => 
                                                    ($current_late_remittance_amount->amount + 
                                                     $get_loan_deduction->deduction)
                                            ]
                                          );
            }
            
            // Fire the remittance event to change the loan's loan status badge
            event(new Remittance($loan->loan_id, 0.00, false));
        }
    }
}


/**
* Set the corresponding remittance date id for the current date
*
* @param  null
* @return array
*/
function remittance_date_id() 
{
    $arr = [];

    $date = Carbon::today('Asia/Manila')->format('d');
    // $date = "1";

    $remittanceDates = DB::table('remittance_dates')->select('*')->get();

    // If the current date is any of the Twice-a-Month (TAC) Remittance Dates
    for($i = 0; $i < count($remittanceDates); $i++)
    {
    	// Explode the string to separate the two dates
        $temp = explode('/', (string)$remittanceDates[$i]->remittance_date);

        if(isset($temp[1]))
        {
            if($date == $temp[0] || $date == $temp[1])
            {
                $arr[] = (string)$remittanceDates[$i]->id;
            }
        }   
    }

    // If the current day is a Friday
    if(Carbon::today('Asia/Manila')->dayOfWeek == 5)
    {
        $arr[] = 10;
    }
    // Or if its a Saturday
    elseif(Carbon::today('Asia/Manila')->dayOfWeek == 6)
    {
        $arr[] = 11;
    }

    return $arr;
}


/**
* Set the active states for Navigation Menus
*
* @param  string  $uri
* @return string
*/
function set_active($uri) 
{
    return (Request::is($uri) ? 'active' : '');
}