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

// Authenticated Routes
Route::group(['middleware' => 'auth'], function(){
    Route::get('/home', 'HomeController@index')->name('home');

     // Users
     Route::get('/users', 'UserController@index')->name('users');
     Route::get('/users-all', 'UserController@indexData');
     Route::post('/user', 'UserController@store');
     Route::patch('/user/{user}', 'UserController@update');
     Route::delete('/user/{user}', 'UserController@destroy');
     Route::post('/change-password', 'UserController@changePassword');

    // Roles
    Route::get('/roles', 'RoleController@index');

    // Employees
        Route::get('/employees', 'EmployeeController@index')->name('employees');
        Route::get('/employees-all', 'EmployeeController@indexData');
        Route::get('/employees-index-count', 'EmployeeController@employeeindexCount');
        Route::get('/employees-inactive-count', 'EmployeeController@employeeInactiveCount');
        Route::get('/employees-new-count', 'EmployeeController@employeeNewCount');
        Route::get('/employees-update-count', 'EmployeeController@employeeUpdateCount');


        Route::get('/add-employee', 'EmployeeController@add');

        Route::post('/employee', 'EmployeeController@store');
        Route::patch('/employee/{employee}', 'EmployeeController@update');
        Route::delete('/employee/{employee}', 'EmployeeController@destroy');


        Route::get('/employee_ids', 'EmployeeController@employee_id_index');
        Route::get('/employee-ids', 'EmployeeController@employeeIdIndex');
        
        //All Approvers
        Route::get('/employee-head-approvers', 'EmployeeController@employeeHeadApprovers');  

        //Employee Approvers
        Route::get('/employee-approvers/{employee}', 'EmployeeController@employeeApprovers');  

        //Employee Dependents
        Route::get('/employee-dependents/{employee}', 'EmployeeController@employeeDependents');  

        //Employee Filter
        Route::post('filter-employee', 'EmployeeController@employeeFilter');

        //Employee Print ID
        Route::get('employee_print_id/{employee}',['as'=>'employee.print','uses'=>'EmployeeController@print_id']);
        
    // Settings
    Route::get('settings', 'SettingsController@index')->name('settings');
    /**
     * companies routes
     */
    Route::get('companies-all', 'SettingsController@allCompany')->name('company');
    Route::post('company','SettingsController@storeCompany');
    Route::delete('company/{company}', 'SettingsController@destroyCompany');
    Route::patch('company/{company}','SettingsController@updateCompany');

    Route::get('division-options/{id}', 'SettingsController@getDivision')->name('division');

    Route::get('divisions-all/{id}', 'SettingsController@allDivision')->name('division_all');

    /**
     * departments routes
     */
    Route::get('departments-all', 'SettingsController@allDepartment')->name('department');
    Route::post('department','SettingsController@storeDepartment');
    Route::delete('department/{department}', 'SettingsController@destroyDepartment');
    Route::patch('department/{department}','SettingsController@updateDepartment');


    /**
     * locations routes
     */

    Route::get('locations-all', 'SettingsController@allLocation')->name('location');
    Route::post('location','SettingsController@storeLocation');
    Route::delete('location/{location}', 'SettingsController@destroyLocation');
    Route::patch('location/{location}','SettingsController@updateLocation');
    Route::get('location-address', 'SettingsController@getLocationAddress')->name('location_address');

    /**
     * addresses routes
     */
    Route::get('addresses-all', 'SettingsController@allAddress')->name('address');
    Route::post('address','SettingsController@storeAddress');
    Route::delete('address/{address}', 'SettingsController@destroyAddress');
    Route::patch('address/{address}','SettingsController@updateAddress');


    /**
     * heads routes
     */
    Route::get('heads-all', 'SettingsController@allHead')->name('head');
    Route::post('head','SettingsController@storeHead');
    Route::delete('head/{head}', 'SettingsController@destroyHead');
    Route::patch('head/{head}','SettingsController@updateHead');


    /**
     * levels routes
     */
    Route::get('levels-all', 'SettingsController@allLevel')->name('level');
    Route::get('levels-options', 'SettingsController@allLevelOptions')->name('leveloptions');
    Route::post('level','SettingsController@storeLevel');
    Route::delete('level/{level}', 'SettingsController@destroyLevel');
    Route::patch('level/{level}','SettingsController@updateLevel');

    /**
     * maritals routes
     */
    Route::get('maritals-all', 'SettingsController@allMarital')->name('marital');
    Route::get('maritals-options', 'SettingsController@allMaritalOptions')->name('maritaloption');
    Route::post('marital','SettingsController@storeMarital');
    Route::delete('marital/{marital}', 'SettingsController@destroyMarital');
    Route::patch('marital/{marital}','SettingsController@updateMarital');

    /**
     * Activities routes
     */

    Route::get('activities', 'ActivityController@index');
    Route::get('activities-all', 'ActivityController@allActivities')->name('activities');
    Route::get('activities-username/{user_id}', 'ActivityController@getUsername');


    /**
     * User Profile
     */

    Route::get('user_profile', 'HomeController@user_profile');
    Route::get('user-profile', 'HomeController@userProfile');
    Route::get('employee-approval-request/{employee}', 'EmployeeController@employeeApprovalRequest');
    Route::patch('employee-user-profile/{employee}','EmployeeController@storeEmployeeUserProfile');
    Route::delete('employee-user-profile/{employee}', 'EmployeeController@destroyEmployeeUserProfile');

    Route::get('user-pendings/{employee}', 'EmployeeController@userProfileRequestPending');


    //Dashboard

    Route::get('employee-approval-requests-pending-all', 'HomeController@userProfileRequestPendingData');
    Route::get('employee-approval-requests-all', 'HomeController@employeeApprovalRequestData');
    Route::get('employee_approval_requests', 'HomeController@employeeApprovalRequests');

    //Employee Approval Request
    Route::patch('employee_approval/{employee_request}','HomeController@employeeApproval');
    

});




