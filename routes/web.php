<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/health_declaration_form', 'HealthDeclationFormController@index')->name('health_declaration_form');

Route::get('/HealthDeclarationFormQr', 'HealthDeclationFormController@indexQr')->name('HealthDeclarationFormQr');

Route::get('/get-session-autolock-screen', 'HealthDeclationFormController@autolockScreen');
Route::post('/unlock-screen', 'HealthDeclationFormController@unlockScreen');

Route::get('/health_declaration_form_ic', 'HealthDeclationFormController@index_ic')->name('health_declaration_form_ic');

Route::get('/fetch-hdf-employee-session', 'HealthDeclationFormController@fetchHDFEmployeeSession');
Route::post('/fetch-filter-employee-health', 'HealthDeclationFormController@fetchEmployees');

Route::post('/fetch-filter-employee-health-overide', 'HealthDeclationFormController@fetchEmployeesOveride');

Route::post('/fetch-filter-ic-employee-health-overide', 'HealthDeclationFormController@fetchEmployeesOverideIC');

Route::post('/save-health-declaration', 'HealthDeclationFormController@saveDeclaration');

Route::post('/save-health-declaration-ic', 'HealthDeclationFormController@saveDeclarationIC');

Route::post('/enable-door-access-overide', 'HealthDeclationFormController@enableDoorAccessOveride');
Route::post('/disable-door-access-overide', 'HealthDeclationFormController@disableDoorAccessOveride');

Route::post('/enable-face-access-overide', 'HealthDeclationFormController@enableFaceAccessOveride');
Route::post('/disable-face-access-overide', 'HealthDeclationFormController@disableFaceAccessOveride');

Route::post('/save-health-declaration-overide', 'HealthDeclationFormController@saveDeclarationOveride');


Route::post('/fetch-form-list', 'HealthDeclationFormController@fetchFormList');

Route::post('/fetch-form-list-ic', 'HealthDeclationFormController@fetchFormListIC');

Route::get('/user-access', 'HealthDeclationFormController@getUserAccess');

Route::get('/face-user-access', 'HealthDeclationFormController@fetchFaceUserAccess');

Route::get('/send_message', 'HealthDeclationFormController@send_message');


Route::post('/ic-employees', 'HealthDeclationFormController@fetchICEmployees');

Route::get('/get-ic-employees', 'HealthDeclationFormController@getICEmployees');


Route::post('/employee-update-status', 'HealthDeclationFormController@employeeUpdateStatus');

Route::post('/ic-employee-update-status', 'HealthDeclationFormController@employeeICUpdateStatus');


Route::post('/fetch-apply-filter-hdf-employee', 'HealthDeclationFormController@fetchApplyFilterHDFEmployee');


Route::post('/fetch-apply-filter-hdf-ic-employee', 'HealthDeclationFormController@fetchApplyFilterHDFICEmployee');

//Surveys
Route::get('/survey', 'SurveyController@index');
Route::get('/get-survey', 'SurveyController@getSurvey');
Route::get('/user-survey', 'SurveyController@userSurvey');
Route::get('/all-user-survey', 'SurveyController@allUserSurvey');
Route::post('/save-user-survey', 'SurveyController@saveUserSurvey');

//Survey Culture
Route::get('/survey-culture', 'SurveyController@surveyCulture');
Route::get('/get-user-survey-culture', 'SurveyController@getUserSurveyCulture');
Route::post('/save-survey-culture', 'SurveyController@saveSurveyCulture');
Route::get('/export-survey-culture', 'SurveyController@exportSurveyCulture');
Route::get('/all-survey-culture', 'SurveyController@allSurveyCulture');

//Survey Nov 2021 Culture
Route::get('/survey-nov-2021-culture', 'SurveyController@surveyNov2021Culture');
Route::get('/get-user-survey-nov-2021-culture', 'SurveyController@getUserSurveyNov2021Culture');
Route::post('/save-survey-nov-2021-culture', 'SurveyController@saveSurveyNov2021Culture');
Route::get('/export-survey-nov-2021-culture', 'SurveyController@exportSurveyNov2021Culture');
Route::get('/all-survey-nov-2021-culture', 'SurveyController@allSurveyNov2021Culture');

//Survey Dec 2021 Culture
Route::get('/survey-dec-2021-cqa-culture', 'SurveyController@surveyDec2021CqaCulture');
Route::get('/get-user-survey-dec-2021-cqa-culture', 'SurveyController@getUserSurveyDec2021CqaCulture');
Route::post('/save-survey-dec-2021-cqa-culture', 'SurveyController@saveSurveyDec2021CqaCulture');
Route::get('/export-survey-dec-2021-cqa-culture', 'SurveyController@exportSurveyDec2021CqaCulture');
Route::get('/all-survey-dec-2021-cqa-culture', 'SurveyController@allSurveyDec2021CqaCulture');

//Survey Legal Questionnaire
Route::get('/survey-legal-questionnaire', 'SurveyController@surveyLegalQuestionnaire');
Route::get('/get-survey-legal-questionnaire', 'SurveyController@getsurveyLegalQuestionnaire');

Route::get('/get-user-survey', 'SurveyController@getUserSurvey');
Route::post('/save-survey-legal-questionnaire', 'SurveyController@saveSurveyLegalQuestionnaire');
Route::get('/survey-legal-questionnaire-user', 'SurveyController@surveyLegalQuestionnaireUser');
Route::get('/get-survey-legal-questionnaire-users', 'SurveyController@getSurveyLegalQuestionnaireUser');
Route::post('/change-legal-user-status', 'SurveyController@changeSurveyLegalQuestionnaireUser');

//Survey Exit Interview Form
Route::get('/survey-exit-interview-form', 'SurveyController@surveyExitInterviewForm');
Route::post('/save-survey-exit-interview-form', 'SurveyController@saveSurveyExitInterviewForm');
Route::get('/get-user-survey-exit-interview-form', 'SurveyController@getUserSurveyExitInterviewForm');
Route::get('/survey-exit-interview-form-reports', 'SurveyController@surveyExitInterviewReports');
Route::get('/all-survey-exit-interview', 'SurveyController@allSurveyExitInterview');


Auth::routes();
Route::get('logout', function(){
    return redirect('/');
});

// Auth::routes(['verify' => true]);

    // Authenticated Admin Routes
    
    Route::group(['middleware' => ['auth']], function () {
        //HDF
        // Route::get('/health_declaration_form_users_disable_set_up',['as'=>'hdf.index','uses'=>'HealthDeclationFormController@health_declaration_form_users_disable_set_up','middleware' => ['role:HDF Admin']]);

        //Home
        Route::get('/home', 'HomeController@index')->name('home');

        //HDF
        Route::get('/health_declaration_form_users_disable_set_up',['as'=>'hdf.index','uses'=>'HealthDeclationFormController@health_declaration_form_users_disable_set_up','middleware' => ['role:HDF Admin']]);

        // Users
        Route::get('/users',['as'=>'users.index','uses'=>'UserController@index','middleware' => ['role:Administrator']]);
        Route::get('/users-all',['as'=>'users.indexData','uses'=>'UserController@indexData','middleware' => ['role:Administrator']]);
        Route::post('/user',['as'=>'users.store','uses'=>'UserController@store','middleware' => ['role:Administrator']]);
        Route::patch('/user/{user}',['as'=>'users.update','uses'=>'UserController@update','middleware' => ['role:Administrator']]);
        Route::delete('/user/{user}',['as'=>'users.destroy','uses'=>'UserController@destroy','middleware' => ['role:Administrator']]);
        
        Route::post('/change-password','UserController@changePassword');
        
        Route::get('/change_password','UserController@change_password');

        // Roles
        Route::get('/roles',['as'=>'roles.index','uses'=>'RoleController@index','middleware' => ['role:Administrator']]);

        // Employees
            Route::get('/employees',['as'=>'employees.index','uses'=>'EmployeeController@index','middleware' => ['role:Administrator|HR Staff|Cluster Head|BU Head|Immediate Superior']]);
            Route::get('/employees-all',['as'=>'employees.indexData','uses'=>'EmployeeController@indexData','middleware' => ['role:Administrator|HR Staff|Cluster Head|BU Head|Immediate Superior']]);
            Route::get('/get-employee',['as'=>'employees.getEmployee','uses'=>'EmployeeController@getEmployee','middleware' => ['role:Administrator|HR Staff|Cluster Head|BU Head|Immediate Superior']]);

            Route::get('/export-employees',['as'=>'employees.exportEmployees','uses'=>'EmployeeController@exportEmployees','middleware' => ['role:Administrator|HR Staff|Cluster Head|BU Head|Immediate Superior']]);
            
            Route::get('/export-inactive-employees',['as'=>'employees.exportInactiveEmployees','uses'=>'EmployeeController@exportInactiveEmployees','middleware' => ['role:Administrator|HR Staff|Cluster Head|BU Head|Immediate Superior']]);

            Route::get('/employees-index-count',['as'=>'employees.employeeindexCount','uses'=>'EmployeeController@employeeindexCount','middleware' => ['role:Administrator|HR Staff']]);
            Route::get('/employees-inactive-count',['as'=>'employees.employeeInactiveCount','uses'=>'EmployeeController@employeeInactiveCount','middleware' => ['role:Administrator|HR Staff']]);
            Route::get('/employees-new-count',['as'=>'employees.employeeNewCount','uses'=>'EmployeeController@employeeNewCount','middleware' => ['role:Administrator|HR Staff']]);
            Route::get('/employees-update-count',['as'=>'employees.employeeUpdateCount','uses'=>'EmployeeController@employeeUpdateCount','middleware' => ['role:Administrator|HR Staff']]);

            Route::get('/add-employee',['as'=>'employees.add','uses'=>'EmployeeController@add','middleware' => ['role:Administrator|HR Staff']]);
            Route::post('/employee',['as'=>'employees.store','uses'=>'EmployeeController@store','middleware' => ['role:Administrator|HR Staff']]);
            Route::patch('/employee/{employee}',['as'=>'employees.update','uses'=>'EmployeeController@update','middleware' => ['role:Administrator|HR Staff']]);
            Route::delete('/employee/{employee}',['as'=>'employees.destroy','uses'=>'EmployeeController@destroy','middleware' => ['role:Administrator|HR Staff']]);


            Route::get('/decrypt-monthly-basic-salary/{employee}',['as'=>'employees.decryptMonthlyBasicSalary','uses'=>'EmployeeController@decryptMonthlyBasicSalary','middleware' => ['role:Administrator|HR Staff|Cluster Head|BU Head|Immediate Superior']]);
            Route::get('/decrypt-monthly-basic-salary-record/{employee}',['as'=>'employees.decryptMonthlyBasicSalaryRecord','uses'=>'EmployeeController@decryptMonthlyBasicSalaryRecord','middleware' => ['role:Administrator|HR Staff|Cluster Head|BU Head|Immediate Superior']]);

            //All Approvers
            Route::get('/employee-head-approvers', 'EmployeeController@employeeHeadApprovers');
              
            //Employee Approvers
            Route::get('/employee-approvers/{employee}', 'EmployeeController@employeeApprovers');  

            //Employee Dependents
            Route::get('/employee-dependents/{employee}', 'EmployeeController@employeeDependents'); 

            Route::get('/employee-dependents-attachments/{employee}', 'EmployeeController@employeeDependentsAttachments');

            //Employee Dependents Reports
            Route::get('/employee-dependents-reports', 'ReportController@employeeDependentReports'); 

            Route::get('/employee-dependents-reports-data', 'ReportController@employeeDependentReportsData');
            
            //Employee Transfer Reports
            Route::get('/employee-transfer-reports', 'ReportController@employeeTransferReports'); 

            Route::get('/employee-transfer-reports-data', 'ReportController@employeeTransferReportsData');
            
            //Employee NPA Reports
            Route::get('/employee-npa-reports', 'ReportController@employeeNpaReports'); 

            Route::get('/employee-npa-reports-data', 'ReportController@employeeNpaDataReportsData'); 
            
            //Employee 201 Files
            Route::get('/employee-201-file-attachments/{employee}', 'EmployeeController@employee201FileAttachments');

            //Employee Filter
            Route::post('/filter-employee',['as'=>'employees.employeeFilter','uses'=>'EmployeeController@employeeFilter','middleware' => ['role:Administrator|HR Staff|Administrator Printer|Cluster Head|BU Head|Immediate Superior']]);
            
             //Employee Filter ID
            Route::post('/filter-employee-id',['as'=>'employees.employeeFilterID','uses'=>'EmployeeController@employeeFilterID','middleware' => ['role:Administrator|HR Staff|Administrator Printer']]);
            Route::post('/print-id-logs',['as'=>'employees.print_id_logs','uses'=>'EmployeeController@print_id_logs','middleware' => ['role:Administrator|HR Staff|Administrator Printer']]);


            //Employee Print ID
            Route::get('employee_print_id/{employee}',['as'=>'employees.print','uses'=>'EmployeeController@print_id','middleware' => ['role:Administrator|Administrator Printer']]);
            Route::get('/employee_ids',['as'=>'employees.employee_id_index','uses'=>'EmployeeController@employee_id_index','middleware' => ['role:Administrator|Administrator Printer']]);
            Route::get('/employee-ids',['as'=>'employees.employeeIdIndex','uses'=>'EmployeeController@employeeIdIndex','middleware' => ['role:Administrator|Administrator Printer']]);
        
            //Transfer Employee 
            Route::patch('/transfer-employee/{employee}', ['as'=>'employees.transfer_employee','uses'=>'EmployeeController@transferEmployee','middleware'=>['role:Administrator|HR Staff']]);

            //Transfer Employee Logs
            Route::get('/transfer-employee-logs/{employee}',['as'=>'employees.transfer_employee','uses'=>'EmployeeController@transferEmployeeLogs','middleware'=>['role:Administrator|HR Staff']]);
            
            
            Route::get('/pdf_employee_transfer_logs/{employee}',['as'=>'employees.transfer_employee','uses'=>'EmployeeController@pdfTransferEmployeeLogs','middleware'=>['role:Administrator|HR Staff']]);

            //Organizational Chart
            Route::get('/org-chart/{employee}',['as'=>'employees.org_chart','uses'=>'EmployeeController@orgChart','middleware'=>['role:Administrator|HR Staff|Cluster Head|BU Head|Immediate Superior']]);
            
            Route::get('/org-chart-under-employee/{employee}',['as'=>'employees.org_chart_under_employee','uses'=>'EmployeeController@orgChartUnderEmployee','middleware'=>['role:Administrator|HR Staff|Cluster Head|BU Head|Immediate Superior']]);
        
        
            // Settings
        Route::get('settings',['as'=>'settings.index','uses'=>'SettingsController@index','middleware' => ['role:Administrator']]);

        /**
         * companies routes
         */
        Route::get('companies-all', 'SettingsController@allCompany');
        Route::post('company',['as'=>'settings.storeCompany','uses'=>'SettingsController@storeCompany','middleware' => ['role:Administrator']]);
        Route::delete('company/{company}',['as'=>'settings.destroyCompany','uses'=>'SettingsController@destroyCompany','middleware' => ['role:Administrator']]);
        Route::patch('company/{company}',['as'=>'settings.updateCompany','uses'=>'SettingsController@updateCompany','middleware' => ['role:Administrator']]);

        //Division
        Route::get('division-options/{id}', 'SettingsController@getDivision')->name('division');
        Route::get('divisions-all/{id}', 'SettingsController@allDivision')->name('division_all');

        /**
         * departments routes
         */
        Route::get('departments-all', 'SettingsController@allDepartment')->name('department');
        Route::post('department',['as'=>'settings.storeDepartment','uses'=>'SettingsController@storeDepartment','middleware' => ['role:Administrator']]);
        Route::delete('department/{department}',['as'=>'settings.destroyDepartment','uses'=>'SettingsController@destroyDepartment','middleware' => ['role:Administrator']]);
        Route::patch('department/{department}',['as'=>'settings.updateDepartment','uses'=>'SettingsController@updateDepartment','middleware' => ['role:Administrator']]);

        /**
         * locations routes
         */

        Route::get('locations-all', 'SettingsController@allLocation')->name('location');
        Route::post('location',['as'=>'settings.storeLocation','uses'=>'SettingsController@storeLocation','middleware' => ['role:Administrator']]);
        Route::delete('location/{location}',['as'=>'settings.destroyLocation','uses'=>'SettingsController@destroyLocation','middleware' => ['role:Administrator']]);
        Route::patch('location/{location}',['as'=>'settings.updateLocation','uses'=>'SettingsController@updateLocation','middleware' => ['role:Administrator']]);

        Route::get('location-address', 'SettingsController@getLocationAddress')->name('location_address');

        /**
         * addresses routes
         */
        Route::get('addresses-all', 'SettingsController@allAddress')->name('address');
        Route::post('address',['as'=>'settings.storeAddress','uses'=>'SettingsController@storeAddress','middleware' => ['role:Administrator']]);
        Route::delete('address/{address}',['as'=>'settings.destroyAddress','uses'=>'SettingsController@destroyAddress','middleware' => ['role:Administrator']]);
        Route::patch('address/{address}',['as'=>'settings.updateAddress','uses'=>'SettingsController@updateAddress','middleware' => ['role:Administrator']]);

        /**
         * heads routes
         */
        Route::get('heads-all', 'SettingsController@allHead')->name('head');
        Route::post('head',['as'=>'settings.storeHead','uses'=>'SettingsController@storeHead','middleware' => ['role:Administrator']]);
        Route::delete('head/{head}',['as'=>'settings.destroyHead','uses'=>'SettingsController@destroyHead','middleware' => ['role:Administrator']]);
        Route::patch('head/{head}',['as'=>'settings.updateHead','uses'=>'SettingsController@updateHead','middleware' => ['role:Administrator']]);

        /**
         * levels routes
         */
        Route::get('levels-all', 'SettingsController@allLevel')->name('level');
        Route::get('levels-options', 'SettingsController@allLevelOptions')->name('leveloptions');
        Route::post('level',['as'=>'settings.storeLevel','uses'=>'SettingsController@storeLevel','middleware' => ['role:Administrator']]);
        Route::delete('level/{level}',['as'=>'settings.destroyLevel','uses'=>'SettingsController@destroyLevel','middleware' => ['role:Administrator']]);
        Route::patch('level/{level}',['as'=>'settings.updateLevel','uses'=>'SettingsController@updateLevel','middleware' => ['role:Administrator']]);

        /**
         * maritals routes
         */
        Route::get('maritals-all', 'SettingsController@allMarital')->name('marital');
        Route::get('maritals-options', 'SettingsController@allMaritalOptions')->name('maritaloption');
        Route::post('marital',['as'=>'settings.storeMarital','uses'=>'SettingsController@storeMarital','middleware' => ['role:Administrator']]);
        Route::delete('marital/{marital}',['as'=>'settings.destroyMarital','uses'=>'SettingsController@destroyMarital','middleware' => ['role:Administrator']]);
        Route::patch('marital/{marital}',['as'=>'settings.updateMarital','uses'=>'SettingsController@updateMarital','middleware' => ['role:Administrator']]);


        /**
         * clusters routes
         */
        Route::get('clusters-all', 'SettingsController@allCluster')->name('cluster');
        Route::get('clusters-options', 'SettingsController@allClusterOptions')->name('clusteroption');
        Route::post('cluster',['as'=>'settings.storeCluster','uses'=>'SettingsController@storeCluster','middleware' => ['role:Administrator']]);
        Route::delete('cluster/{cluster}',['as'=>'settings.destroyCluster','uses'=>'SettingsController@destroyCluster','middleware' => ['role:Administrator']]);
        Route::patch('cluster/{cluster}',['as'=>'settings.updateCluster','uses'=>'SettingsController@updateCluster','middleware' => ['role:Administrator']]);

        /**
         * Activities routes
         */
        Route::get('activities',['as'=>'activities.index','uses'=>'ActivityController@index','middleware' => ['role:Administrator']]);
        Route::get('activities-all',['as'=>'activities.allActivities','uses'=>'ActivityController@allActivities','middleware' => ['role:Administrator']]);
        Route::get('activities-username/{user_id}',['as'=>'activities.getUsername','uses'=>'ActivityController@getUsername','middleware' => ['role:Administrator']]);

        Route::post('filter-activities-all',['as'=>'activities.filterAllActivities','uses'=>'ActivityController@filterAllActivities','middleware' => ['role:Administrator']]);
        /**
         * User Profile
         */

        Route::get('user_profile', 'HomeController@user_profile');
        Route::get('user-profile', 'HomeController@userProfile');
        Route::get('employee-approval-request/{employee}', 'EmployeeController@employeeApprovalRequest');
        Route::patch('employee-user-profile/{employee}', 'EmployeeController@storeEmployeeUserProfile');
        Route::delete('employee-user-profile/{employee}', 'EmployeeController@destroyEmployeeUserProfile');


        Route::patch('employee-user-profile-verification/{employee}', 'EmployeeController@updateEmployeeUserProfileVerification');

        Route::get('user-pendings/{employee}', 'EmployeeController@userProfileRequestPending');

        //Dashboard
        Route::get('employee-approval-requests-pending-all', 'HomeController@userProfileRequestPendingData');
        Route::get('employee-approval-requests-all', 'HomeController@employeeApprovalRequestData');
        
        Route::get('employee_approval_requests',['as'=>'home.employeeApprovalRequests','uses'=>'HomeController@employeeApprovalRequests','middleware' => ['role:Administrator|HR Staff']]);

        //Employee Approval Request
        Route::patch('employee_approval/{employee_request}', 'HomeController@employeeApproval');

        //Employee All Verification/Request
        Route::get('download-data-request-verification', 'HomeController@downloadDataRequestVerification');
        

        //Verify Employee
        Route::post('verify_employee', 'HomeController@verifyEmployee');

        Route::get('verified_employees', 'HomeController@verifiedEmployees');

        Route::get('verified-employees', 'HomeController@verifiedEmployeeList');

        Route::get('view_user_profile/{employee}','HomeController@viewUserProfile');
        Route::get('view-user-profile-data/{employee}','HomeController@viewUserProfileData');


        Route::get('rfid_number','HomeController@getRfidNumber');


        Route::post('save-rfid','HomeController@saveRFID');
        Route::get('scan-rfids','HomeController@scansRFID');


        //Demographic Head Count
        Route::get('year-to-date-head-count','HomeController@getYearToDateHeadcount');

        //Demographic Age Count
        Route::get('employee-age-count','HomeController@getEmployeeAgeCount');

        //Demographic Region Count
        Route::get('employee-region-count','HomeController@getEmployeeRegionCount');

        //Demographic Gender Count
        Route::get('employee-gender-count','HomeController@getEmployeeGenderCount');

         //Demographic Marital Status Count
        Route::get('employee-marital-status-count','HomeController@getEmployeeMaritaStatusCount');

        //Demographic Cluster Count
        Route::get('employee-cluster-count','HomeController@getEmployeeClusterCount');

        //Demographic Cluster Count
        Route::get('employee-for-regular-notif','HomeController@getForRegularNotification');


        //Access Rights
        Route::get('user-access-rights','UserController@getUserAccessRights');

        //Send NPA Requests
        Route::post('update-employee-npa-request','EmployeeController@updateemployeeNpaRequest');
        Route::post('employee-npa-request','EmployeeController@employeeNpaRequest');
        Route::get('get-npa-requests/{employee_id}','EmployeeController@getNPARequestLists');
        Route::delete('npa_request/{npa_request}', 'EmployeeController@destroyNPARequest');

        Route::get('print-employee-npa-requests/{employee}','NpaRequestController@printEmployeeNPARequest');

        //HR Employees
        Route::get('hr-employees','EmployeeController@getHREmployees');

        //BU HEADS
        Route::get('bu-heads','EmployeeController@getBUHead');

        Route::get('get-npa-request/{npa_request}','EmployeeController@viewNPARequest');

        Route::get('approved-by-hr-recommend/{npa_request}','EmployeeController@approvedByHRRecommend');
        Route::get('approved-by-hr-approver/{npa_request}','EmployeeController@approveByHRApprover');
        Route::get('approved-by-bu-head/{npa_request}','EmployeeController@approveByBUHead');

        Route::get('hr_email',function(){
            $hr_receivers = App\HrReceiverNotification::select('email')->get();

            $emails = [];

            foreach($hr_receivers as $receiver){
                array_push($emails , $receiver['email']);
            }
            return $emails;
        });

        Route::get('year_to_end',function(){
            $jan = App\Employee::select('date_hired','status')->where('status','Active')->where('date_hired','<=','2020-01-30')->count();
            $feb = App\Employee::select('date_hired','status')->where('status','Active')->where('date_hired','<=','2020-02-29')->count();
            $mar = App\Employee::select('date_hired','status')->where('status','Active')->where('date_hired','<=','2020-03-31')->count();
            $apr = App\Employee::select('date_hired','status')->where('status','Active')->where('date_hired','<=','2020-04-30')->count();
            $may = App\Employee::select('date_hired','status')->where('status','Active')->where('date_hired','<=','2020-05-31')->count();
            $jun = App\Employee::select('date_hired','status')->where('status','Active')->where('date_hired','<=','2020-06-30')->count();
            
            $months = [
                'jan'=> $jan,
                'feb'=> $feb,
                'mar'=> $mar,
                'apr'=> $apr,
                'may'=> $may,
                'jun'=> $jun,
            ];
            
            return $months;
        });

        Route::get('overideBiometricsID/{employee}','EmployeeController@overideBiometricsID');

        Route::get('addCardBiometricsID/{employee}','EmployeeController@addCardBiometricsID');

        //Print QR
        Route::post('print_qr','EmployeeController@printEmployeeQR');
        Route::get('preview_qr/{qrlog}','EmployeeController@printPreviewEmployeeQR');

    });


    //RFID FACE BIOMETRICS
    Route::get('/employee_rfids', 'EmployeeRfidController@index');
    Route::get('/get-rfid-users', 'EmployeeRfidController@getUsers');
    Route::get('/get-rfid-user-card', 'EmployeeRfidController@getUserCard');
    Route::get('/get-rfid-unassigned', 'EmployeeRfidController@getUnassigned');
    Route::get('/get-rfid-devices', 'EmployeeRfidController@getBiometricDevices');
    Route::get('/get-rfid-device-users', 'EmployeeRfidController@getBiometricDeviceUsers');
    Route::get('/employee-rfid-results', 'EmployeeRfidController@searchEmployee');
    Route::get('/biometric-user-results', 'EmployeeRfidController@biometricUser');
    Route::post('/save-biometric-user', 'EmployeeRfidController@saveBiometricID');

    //RFID DOOR BIOMETRICS
    Route::get('/get-rfid-door-devices', 'EmployeeRfidController@getDoorBiometricDevices');
    Route::get('/get-rfid-door-device-users', 'EmployeeRfidController@getBiometricDoorDeviceUsers');
    Route::get('/get-rfid-door-unassigned', 'EmployeeRfidController@getDoorUnassigned');
    Route::get('/get-rfid-door-user-card', 'EmployeeRfidController@getDoorUserCard');