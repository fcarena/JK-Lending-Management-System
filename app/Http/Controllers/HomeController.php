<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RemittanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('loans.active');
    }


    public function currentLoansList()
    {
        $companies = (new CompanyController)->getCompanies();

        $remittanceDates = (new RemittanceController)->getDates();

        return view('loans/current')->with('companies', $companies)->with('remittanceDates', $remittanceDates);
    }

    public function finishedLoansList()
    {
        return view('loans/finished');
    }
}
