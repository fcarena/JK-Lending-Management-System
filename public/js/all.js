function addLoanValidate(a){if("addLoanRecordForm1"==a){var r=!0,e=function(){var a=null;return $.ajax({global:!1,async:!1,data:{name:$("#addBorrowerName1").val(),company:$("#addBorrowerCompany1").val()},url:"http://jklending.prod:81/add/loan/check/borrower",method:"POST",success:function(r){a=r},error:function(a){console.log("An error occured")}}),a}();return""==$("#addBorrowerName1").val()||0!=/[0-9 -()+]+$/.test($("#addBorrowerName1").val())||1==e?($("#addBorrowerName1").addClass("is-invalid"),1==e&&$("#name-error-msg").html("Borrower already exists, try the existing borrower tab"),r=!1):""!=$("#addBorrowerName1").val()&&0==/[0-9 -()+]+$/.test($("#addBorrowerName1").val())&&$("#addBorrowerName1").removeClass("is-invalid"),""==$("#addLoanAmount1").val()||$("#addLoanAmount1").val()<=0||$("#addLoanAmount1").val()>1e7?($("#addLoanAmount1").addClass("is-invalid"),r=!1):""!=$("#addLoanAmount1").val()&&$("#addLoanAmount1").val()>0&&$("#addLoanAmount1").val()<=1e7&&$("#addLoanAmount1").removeClass("is-invalid"),""==$("#addBorrowerPercentage1").val()||$("#addBorrowerPercentage1").val()<=0||$("#addBorrowerPercentage1").val()>100?($("#addBorrowerPercentage1").addClass("is-invalid"),r=!1):""!=$("#addBorrowerPercentage1").val()&&$("#addBorrowerPercentage1").val()>0&&$("#addBorrowerPercentage1").val()<=100&&$("#addBorrowerPercentage1").removeClass("is-invalid"),""==$("#addBorrowerTerm1").val()||$("#addBorrowerTerm1").val()<=0||$("#addBorrowerTerm1").val()>99.5?($("#addBorrowerTerm1").addClass("is-invalid"),r=!1):""!=$("#addBorrowerTerm1").val()&&$("#addBorrowerTerm1").val()>0&&$("#addBorrowerTerm1").val()<=99.5&&$("#addBorrowerTerm1").removeClass("is-invalid"),r}var r=!0;return""==$("#addLoanAmount2").val()||$("#addLoanAmount2").val()<=0||$("#addLoanAmount2").val()>1e7?($("#addLoanAmount2").addClass("is-invalid"),r=!1):""!=$("#addLoanAmount2").val()&&$("#addLoanAmount2").val()>0&&$("#addLoanAmount2").val()<=1e7&&$("#addLoanAmount2").removeClass("is-invalid"),""==$("#addBorrowerPercentage2").val()||$("#addBorrowerPercentage2").val()<=0||$("#addBorrowerPercentage2").val()>100?($("#addBorrowerPercentage2").addClass("is-invalid"),r=!1):""!=$("#addBorrowerPercentage2").val()&&$("#addBorrowerPercentage2").val()>0&&$("#addBorrowerPercentage2").val()<=100&&$("#addBorrowerPercentage2").removeClass("is-invalid"),""==$("#addBorrowerTerm2").val()||$("#addBorrowerTerm2").val()<=0||$("#addBorrowerTerm2").val()>99.5?($("#addBorrowerTerm2").addClass("is-invalid"),r=!1):""!=$("#addBorrowerTerm2").val()&&$("#addBorrowerTerm2").val()>0&&$("#addBorrowerTerm2").val()<=99.5&&$("#addBorrowerTerm2").removeClass("is-invalid"),r}function addBorrowerValidate(){var a=!0,r=function(){var a=null;return $.ajax({global:!1,async:!1,data:{name:$("#addBorrowerFormName").val(),company:$("#addBorrowerFormCompany").val()},url:"http://jklending.prod:81/add/loan/check/borrower",method:"POST",success:function(r){a=r},error:function(a){console.log("An error occured")}}),a}();return""!=$("#addBorrowerFormName").val()&&0==/[0-9 -()+]+$/.test($("#addBorrowerFormName").val())&&1!=r||($("#addBorrowerFormName").addClass("is-invalid"),$("#name-error-msg").html("Please enter a valid borrower"),1==r&&$("#name-error-msg").html("Borrower already exists, try the existing borrower tab"),a=!1),a}function addCompanyValidate(){var a=!0,r=function(){var a=null;return $.ajax({global:!1,async:!1,data:{name:$("#addCompanyFormName").val()},url:"http://jklending.prod:81/add/company/check/company",method:"POST",success:function(r){a=r},error:function(a){console.log("error")}}),a}();return""==$("#addCompanyFormName").val()&&($("#name-error-msg").html("Please enter a valid company name"),$("#addCompanyFormName").addClass("is-invalid"),a=!1),1==r&&($("#name-error-msg").html($("#addCompanyFormName").val()+" is already in the database. Please\tuse another name"),$("#addCompanyFormName").addClass("is-invalid"),a=!1),a}