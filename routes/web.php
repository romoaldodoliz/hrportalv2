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
        
        Route::post('/change-password',['as'=>'users.changePassword','uses'=>'UserController@changePassword','middleware' => ['role:Administrator']]);

        // Roles
        Route::get('/roles',['as'=>'roles.index','uses'=>'RoleController@index','middleware' => ['role:Administrator']]);

        // Employees
            Route::get('/employees',['as'=>'employees.index','uses'=>'EmployeeController@index','middleware' => ['role:Administrator|HR Staff']]);
            Route::get('/employees-all',['as'=>'employees.indexData','uses'=>'EmployeeController@indexData','middleware' => ['role:Administrator|HR Staff']]);

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

            //Employee Filter
            Route::post('/filter-employee',['as'=>'employees.employeeFilter','uses'=>'EmployeeController@employeeFilter','middleware' => ['role:Administrator|HR Staff']]);
    
            //Employee Print ID
            Route::get('employee_print_id/{employee}',['as'=>'employees.print','uses'=>'EmployeeController@print_id','middleware' => ['role:Administrator|Administrator Printer']]);
            Route::get('/employee_ids',['as'=>'employees.employee_id_index','uses'=>'EmployeeController@employee_id_index','middleware' => ['role:Administrator|Administrator Printer']]);
            Route::get('/employee-ids',['as'=>'employees.employeeIdIndex','uses'=>'EmployeeController@employeeIdIndex','middleware' => ['role:Administrator|Administrator Printer']]);
    
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
         * Activities routes
         */
        Route::get('activities',['as'=>'activities.index','uses'=>'ActivityController@index','middleware' => ['role:Administrator']]);
        Route::get('activities-all',['as'=>'activities.allActivities','uses'=>'ActivityController@allActivities','middleware' => ['role:Administrator']]);
        Route::get('activities-username/{user_id}',['as'=>'activities.getUsername','uses'=>'ActivityController@getUsername','middleware' => ['role:Administrator']]);

        /**
         * User Profile
         */

        Route::get('user_profile', 'HomeController@user_profile');
        Route::get('user-profile', 'HomeController@userProfile');
        Route::get('employee-approval-request/{employee}', 'EmployeeController@employeeApprovalRequest');
        Route::patch('employee-user-profile/{employee}', 'EmployeeController@storeEmployeeUserProfile');
        Route::delete('employee-user-profile/{employee}', 'EmployeeController@destroyEmployeeUserProfile');
        Route::get('user-pendings/{employee}', 'EmployeeController@userProfileRequestPending');

        //Dashboard
        Route::get('employee-approval-requests-pending-all', 'HomeController@userProfileRequestPendingData');
        Route::get('employee-approval-requests-all', 'HomeController@employeeApprovalRequestData');
        
        Route::get('employee_approval_requests', 'HomeController@employeeApprovalRequests');

        Route::get('employee_approval_requests',['as'=>'home.employeeApprovalRequests','uses'=>'HomeController@employeeApprovalRequests','middleware' => ['role:Administrator|HR Staff']]);

        //Employee Approval Request
        Route::patch('employee_approval/{employee_request}', 'HomeController@employeeApproval');
    });