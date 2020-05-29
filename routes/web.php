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

Route::get('/health_declaration_form_ic', 'HealthDeclationFormController@index_ic')->name('health_declaration_form_ic');

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

Route::get('/health_declaration_form_users_disable_set_up', 'HealthDeclationFormController@health_declaration_form_users_disable_set_up');

Route::post('/fetch-form-list', 'HealthDeclationFormController@fetchFormList');

Route::post('/fetch-form-list-ic', 'HealthDeclationFormController@fetchFormListIC');

Route::get('/user-access', 'HealthDeclationFormController@getUserAccess');

Route::get('/face-user-access', 'HealthDeclationFormController@fetchFaceUserAccess');

Route::get('/send_message', 'HealthDeclationFormController@send_message');


Route::post('/ic-employees', 'HealthDeclationFormController@fetchICEmployees');


Route::post('/employee-update-status', 'HealthDeclationFormController@employeeUpdateStatus');

Route::post('/ic-employee-update-status', 'HealthDeclationFormController@employeeICUpdateStatus');


Route::post('/fetch-apply-filter-hdf-employee', 'HealthDeclationFormController@fetchApplyFilterHDFEmployee');


Auth::routes();
Route::get('logout', function(){
    return redirect('/');
});

// Auth::routes(['verify' => true]);

    // Authenticated Admin Routes
    
    Route::group(['middleware' => ['auth']], function () {
        //Home
        Route::get('/home', 'HomeController@index')->name('home');

        // Users
        Route::get('/users',['as'=>'users.index','uses'=>'UserController@index','middleware' => ['role:Administrator']]);
        Route::get('/users-all',['as'=>'users.indexData','uses'=>'UserController@indexData','middleware' => ['role:Administrator']]);
        Route::post('/user',['as'=>'users.store','uses'=>'UserController@store','middleware' => ['role:Administrator']]);
        Route::patch('/user/{user}',['as'=>'users.update','uses'=>'UserController@update','middleware' => ['role:Administrator']]);
        Route::delete('/user/{user}',['as'=>'users.destroy','uses'=>'UserController@destroy','middleware' => ['role:Administrator']]);
        
        Route::post('/change-password','UserController@changePassword');
        
        Route::get('/change_password',['as'=>'users.change_password','uses'=>'UserController@change_password','middleware' => ['role:Administrator|HR Staff|User']]);

        // Roles
        Route::get('/roles',['as'=>'roles.index','uses'=>'RoleController@index','middleware' => ['role:Administrator']]);

        // Employees
            Route::get('/employees',['as'=>'employees.index','uses'=>'EmployeeController@index','middleware' => ['role:Administrator|HR Staff']]);
            Route::get('/employees-all',['as'=>'employees.indexData','uses'=>'EmployeeController@indexData','middleware' => ['role:Administrator|HR Staff']]);

            Route::get('/export-employees',['as'=>'employees.exportEmployees','uses'=>'EmployeeController@exportEmployees','middleware' => ['role:Administrator|HR Staff']]);

            Route::get('/employees-index-count',['as'=>'employees.employeeindexCount','uses'=>'EmployeeController@employeeindexCount','middleware' => ['role:Administrator|HR Staff']]);
            Route::get('/employees-inactive-count',['as'=>'employees.employeeInactiveCount','uses'=>'EmployeeController@employeeInactiveCount','middleware' => ['role:Administrator|HR Staff']]);
            Route::get('/employees-new-count',['as'=>'employees.employeeNewCount','uses'=>'EmployeeController@employeeNewCount','middleware' => ['role:Administrator|HR Staff']]);
            Route::get('/employees-update-count',['as'=>'employees.employeeUpdateCount','uses'=>'EmployeeController@employeeUpdateCount','middleware' => ['role:Administrator|HR Staff']]);

            Route::get('/add-employee',['as'=>'employees.add','uses'=>'EmployeeController@add','middleware' => ['role:Administrator|HR Staff']]);
            Route::post('/employee',['as'=>'employees.store','uses'=>'EmployeeController@store','middleware' => ['role:Administrator|HR Staff']]);
            Route::patch('/employee/{employee}',['as'=>'employees.update','uses'=>'EmployeeController@update','middleware' => ['role:Administrator|HR Staff']]);
            Route::delete('/employee/{employee}',['as'=>'employees.destroy','uses'=>'EmployeeController@destroy','middleware' => ['role:Administrator|HR Staff']]);

            //All Approvers
            Route::get('/employee-head-approvers', 'EmployeeController@employeeHeadApprovers');
              
            //Employee Approvers
            Route::get('/employee-approvers/{employee}', 'EmployeeController@employeeApprovers');  

            //Employee Dependents
            Route::get('/employee-dependents/{employee}', 'EmployeeController@employeeDependents'); 

            Route::get('/employee-dependents-attachments/{employee}', 'EmployeeController@employeeDependentsAttachments');  

            //Employee Filter
            Route::post('/filter-employee',['as'=>'employees.employeeFilter','uses'=>'EmployeeController@employeeFilter','middleware' => ['role:Administrator|HR Staff|Administrator Printer']]);
            
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
            
            //Organizational Chart
            Route::get('/org-chart/{employee}',['as'=>'employees.org_chart','uses'=>'EmployeeController@orgChart','middleware'=>['role:Administrator|HR Staff']]);
        
        
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

    });