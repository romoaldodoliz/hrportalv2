<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-success opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-9 col-md-9">
                    <h1 class="display-2 text-white text-uppercase">Hello, {{ employee_copied.first_name }}</h1>
                    <p class="text-white mt-0 mb-5"> This is your profile page. You can see the progress you've made with your account and manage your personal information.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content -->

    <div class="container-fluid mt--7">
        <div class="row">
            <!-- Title -->
            <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                <div class="card card-profile shadow">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                            <a href="#">
                                <img :src="profile_image" style="background-color:white;"  @error="profileImageLoadError()" class="rounded-circle">
                            </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                        <div class="d-flex justify-content-right">
                            <a href="#" class="btn btn-sm btn-info float-right">{{ employee_copied.status}}</a>
                        </div>
                    </div>

                    <div class="card-body pt-0 pt-md-4">
                        <div class="row">
                            <div class="col-md-12 text-center mt-3">
                                <h3>{{ employee_copied.first_name + " " +  employee_copied.last_name}}<span class="font-weight-light"></span>
                                </h3>
                                    <div class="h5 font-weight-300">
                                        {{ employee_copied.position}}
                                    </div>
                                    <div class="h5 mt-4">
                                       {{ employee_copied.departments ? employee_copied.departments[0].name : ""}}
                                    </div>
                                    <div>
                                    {{ employee_copied.companies ? employee_copied.companies[0].name : ""}}
                                    <div class="h5 font-weight-300">
                                        <i class="ni location_pin"></i>{{ employee_copied.division}}
                                    </div>
                                    </div>
                                    <hr class="my-4">
                                    <div class="row justify-content-center mb-2 mt-2">
                                        <img :src="signature_image" @error="signatureImageLoadError()" style="width:250px;height:auto;border-radius:6px;border:1px solid #8898aa;">
                                        <div class="col-md-12 h5 font-weight-300">
                                            Signature   
                                        </div>
                                    </div>
                                   
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
           
            <!-- Content -->
            
            <div class="col-xl-8 order-xl-1">
                <div class="card bg-secondary shadow  mb-5">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0 text-uppercase">My account</h3>
                                <div class="col text-right" v-if="employee_copied.verification">
                                    <span v-if="employee_copied.verification.verification == '1'" class="badge badge-success">Your employee information has been verified.</span>
                                </div>
                                <div class="col text-right" v-else>
                                    <span class="badge badge-danger" v-if="employee_requests_pending > 0">Your employee information has been sent to HR for verification.</span>
                                    <span class="badge badge-danger" v-else>Please verify your information.</span>
                                </div>
                            </div>
                            <div class="col-4 text-right">
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#employeeRequestsModal"><span class="badge badge-pill badge-warning" v-if="employee_requests_pending > 0"><i class="fas fa-exclamation"></i></span> Employee Update Request(s)</button>
                            </div>

                            
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Personal information -->
                        <h6 class="heading-small text-muted mb-4">Personal information</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="role">Profile Image</label> 
                                        <input type="file" accept="image/*" id="profile_image_file" class="form-control" ref="file" v-on:change="profileHandleFileUpload()" :disabled="user_disabled"/>
                                        <span class="text-danger" v-if="errors.employee_image">{{ errors.employee_image[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="role">Signature</label> 
                                        <input type="file" accept="image/*" id="signature_image_file" class="form-control" ref="file" v-on:change="signatureHandleFileUpload()" :disabled="user_disabled"/>
                                        <span class="text-danger" v-if="errors.employee_signature">{{ errors.employee_signature[0] }}</span>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="role">First Name*</label> 
                                        <input type="text"  class="form-control" v-model="employee_copied.first_name"  :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.first_name">{{ errors.first_name[0] }}</span>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="role">Last Name*</label> 
                                        <input type="text"  class="form-control" v-model="employee_copied.last_name" :disabled="gender_disabled">
                                        <span class="text-danger" v-if="errors.last_name">{{ errors.last_name[0] }}</span>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="role">Middle Name</label> 
                                        <input type="text"  class="form-control" v-model="employee_copied.middle_name" :disabled="gender_disabled">
                                        <span class="text-danger" v-if="errors.middle_name">{{ errors.middle_name[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="role">Middle Initial</label> 
                                        <input type="text"  class="form-control" v-model="employee_copied.middle_initial" :disabled="gender_disabled" >
                                        <span class="text-danger" v-if="errors.middle_initial">{{ errors.middle_initial[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="role">Suffix</label> 
                                        <input type="text" class="form-control" v-model="employee_copied.name_suffix" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.name_suffix">{{ errors.name_suffix[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="role">Nick Name</label> 
                                        <input type="text"  class="form-control" v-model="employee_copied.nick_name" >
                                        <span class="text-danger" v-if="errors.nick_name">{{ errors.nick_name[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="role">Personal Email</label> 
                                        <input type="text"  class="form-control" v-model="employee_copied.personal_email" >
                                        <span class="text-danger" v-if="errors.personal_email">{{ errors.personal_email[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="role">Marital Status*</label> 
                                        <select class="form-control" v-model="employee_copied.marital_status" id="marital_status" @change="validateMartialStatus()">
                                            <option value="">Choose Marital Status</option>
                                            <option v-for="(maritals) in marital_statuses" v-bind:key="maritals" :value="maritals"> {{ maritals }}</option>
                                        </select>
                                        <span class="text-danger" v-if="errors.marital_status">{{ errors.marital_status[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="role">Document Attachment <a target="_blank" :href="'storage/marital_attachments/'+employee_copied.marital_status_attachment" v-if="employee_copied.marital_status_attachment"><span v-if="marital_attachment_view" class="badge badge-primary">View</span></a></label> 
                                        <input type="file" :disabled="marital_attachment_validate" id="marital_file" class="form-control" ref="file" v-on:change="maritalHandleFileUpload()"/>
                                        <span class="text-danger" v-if="employee_copied.marital_status == 'MARRIED' || employee_copied.marital_status == 'DIVORCED' ||  employee_copied.marital_status == 'WIDOW'">*Attach original copy</span>
                                        <span class="text-danger" v-if="errors.marital_status_attachment">{{ errors.marital_status_attachment[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="role">Date of Birth*</label> 
                                        <input type="date" class="form-control" v-model="employee_copied.birthdate" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.birthdate">{{ errors.birthdate[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="role">Age <span class="text-danger" v-if="employee_copied.age">{{ employee_copied.ageRange }}</span></label> 
                                        <input type="text" disabled class="form-control" v-model="employee_copied.age" >
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="role">Gender*</label> 
                                        <select class="form-control" v-model="employee_copied.gender" id="gender" @change="disabledByGender()">
                                            <option value="">Choose Gender</option>
                                            <option value="MALE"> MALE</option>
                                            <option value="FEMALE"> FEMALE</option>
                                            <!-- <option value="UNKNOWN"> UNKNOWN</option> -->
                                        </select>
                                        <span class="text-danger" v-if="errors.gender">{{ errors.gender[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="role">Birthplace</label> 
                                        <textarea  class="form-control" v-model="employee_copied.birthplace" :disabled="user_disabled"></textarea>
                                        <span class="text-danger" v-if="errors.birthplace">{{ errors.birthplace[0] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                         <!-- Educational Background -->
                        <h6 class="heading-small text-muted mb-4">Educational Background</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="heading-small text-muted mb-4">Tertiary</h6>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="role">Name of School</label>
                                        <input type="text" class="form-control" v-model="employee_copied.school_graduated" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.school_graduated">{{ errors.school_graduated[0] }}</span> 
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Course</label>
                                        <input type="text" class="form-control" v-model="employee_copied.school_course" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.school_course">{{ errors.school_course[0] }}</span> 
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="role">Year Graduated</label>
                                        <input type="text" class="form-control" v-model="employee_copied.school_year" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.school_year">{{ errors.school_year[0] }}</span> 
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <h6 class="heading-small text-muted mb-4">Vocational Course</h6>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="role">Name of School</label>
                                        <input type="text" class="form-control" v-model="employee_copied.vocational_graduated" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.vocational_graduated">{{ errors.vocational_graduated[0] }}</span> 
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Course</label>
                                        <input type="text" class="form-control" v-model="employee_copied.vocational_course" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.vocational_course">{{ errors.vocational_course[0] }}</span> 
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="role">Year Graduated</label>
                                        <input type="text" class="form-control" v-model="employee_copied.vocational_year" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.vocational_year">{{ errors.vocational_year[0] }}</span> 
                                    </div>
                                </div>

                            </div>
                        </div>       
                        <hr class="my-4">
                        <!-- Address -->
                        <h6 class="heading-small text-muted mb-4">Work information</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Company*</label>
                                        <select class="form-control" v-model="employee_copied.company_list" id="company" :disabled="user_disabled">
                                            <option value="">Choose Company</option>
                                            <option v-for="(company,b) in companies" v-bind:key="b" :value="company.id"> {{ company.name }}</option>
                                        </select>

                                        <span class="text-danger" v-if="errors.company_list">{{ errors.company_list[0] }}</span> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Division</label>  
                                        <input type="text" class="form-control" v-model="employee_copied.division" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.division">{{ errors.division[0] }}</span> 
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Department*</label>
                                        <select class="form-control" v-model="employee_copied.department_list" id="department" :disabled="user_disabled">
                                            <option value="">Choose Department</option>
                                            <option v-for="(department,b) in departments" v-bind:key="b" :value="department.id"> {{ department.name }}</option>
                                        </select>
                                        <span class="text-danger" v-if="errors.department_list">{{ errors.department_list[0] }}</span> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Employee Number</label>
                                        <input type="text" class="form-control" v-model="employee_copied.employee_number" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.employee_number">{{ errors.employee_number[0] }}</span> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">ESS Employee No.</label>
                                        <input type="text" class="form-control" v-model="employee_copied.ess_ee_number" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.ess_ee_number">{{ errors.ess_ee_number[0] }}</span> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Position</label>
                                        <input type="text" class="form-control" v-model="employee_copied.position" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.position">{{ errors.position[0] }}</span> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Classification</label>
                                        <select class="form-control" v-model="employee_copied.classification" id="department" :disabled="user_disabled">
                                            <option value="">Choose Classification</option>
                                            <option value="Probationary">Probationary</option>
                                            <option value="Regular">Regular</option>
                                            <option value="Consultant">Consultant</option>
                                            <option value="Project">Project Based</option>
                                        </select>
                                        <span class="text-danger" v-if="errors.classification">{{ errors.classification[0] }}</span> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Date Hired</label>
                                        <input type="date" class="form-control" v-model="employee_copied.date_hired" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.date_hired">{{ errors.date_hired[0] }}</span> 
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Tenure</label>
                                        <input type="text" disabled class="form-control" v-model="employee_copied.tenure">
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Level</label>
                                        <select class="form-control" v-model="employee_copied.level" id="level" :disabled="user_disabled">
                                            <option value="">Choose Level</option>
                                            <option v-for="(level) in levels" v-bind:key="level" :value="level"> {{ level }}</option>
                                        </select>

                                        <span class="text-danger" v-if="errors.level">{{ errors.level[0] }}</span> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Location / Site*</label>
                                        <select class="form-control" v-model="employee_copied.location_list" id="location" :disabled="user_disabled">
                                            <option value="">Choose Location</option>
                                            <option v-for="(location,b) in locations" v-bind:key="b" :value="location.id"> {{ location.name }}</option>
                                        </select>

                                        <span class="text-danger" v-if="errors.location_list">{{ errors.location_list[0] }}</span> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Area</label>
                                        <input type="text" class="form-control" v-model="employee_copied.area" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.area">{{ errors.area[0] }}</span> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Bank Account Number</label>
                                        <input type="text" class="form-control" v-model="employee_copied.bank_account_number" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.bank_account_number">{{ errors.bank_account_number[0] }}</span> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Bank Name</label>
                                        <input type="text" class="form-control" v-model="employee_copied.bank_name" :disabled="user_disabled">
                                        <span class="text-danger" v-if="errors.bank_name">{{ errors.bank_name[0] }}</span> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <!-- Contact -->
                        <h6 class="heading-small text-muted mb-4">Contact Information</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Current Address</label>
                                        <textarea class="form-control" v-model="employee_copied.current_address"></textarea>
                                        <span class="text-danger" v-if="errors.current_address">{{ errors.current_address[0] }}</span> 
                                    </div>
                                </div>    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Permanent Address</label>
                                        <textarea class="form-control" v-model="employee_copied.permanent_address"></textarea>
                                        <span class="text-danger" v-if="errors.permanent_address">{{ errors.permanent_address[0] }}</span> 
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Landline</label>
                                        <input type="text" class="form-control" v-model="employee_copied.phone_number">
                                        <span class="text-danger" v-if="errors.phone_number">{{ errors.phone_number[0] }}</span> 
                                    </div>
                                </div>    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Mobile Number</label>
                                        <input type="text" class="form-control" v-model="employee_copied.mobile_number">
                                        <span class="text-danger" v-if="errors.mobile_number">{{ errors.mobile_number[0] }}</span> 
                                    </div>
                                </div>   
                                 <div class="col-md-12">
                                    <h4>In case of Emergency</h4>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Contact Person</label>
                                        <input type="text" class="form-control" v-model="employee_copied.contact_person">
                                        <span class="text-danger" v-if="errors.contact_person">{{ errors.contact_person[0] }}</span> 
                                    </div>
                                </div>    

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Relationship</label>
                                        <input type="text" class="form-control" v-model="employee_copied.contact_relation">
                                        <span class="text-danger" v-if="errors.contact_relation">{{ errors.contact_relation[0] }}</span> 
                                    </div>
                                </div>  

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Contact Number</label>
                                        <input type="text" class="form-control" v-model="employee_copied.contact_number">
                                        <span class="text-danger" v-if="errors.contact_number">{{ errors.contact_number[0] }}</span> 
                                    </div>
                                </div>    

                                 <div class="table-responsive">
                                    <table class="table table-hover" id="tab_hmo_dependent">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    Full Name
                                                </th>
                                                <th class="text-center">
                                                    First Name
                                                </th>
                                                <th class="text-center">
                                                    Last Name
                                                </th>
                                                <th class="text-center">
                                                    Middle Name
                                                </th>
                                                <th class="text-center">
                                                    Gender
                                                </th>
                                                <th class="text-center">
                                                    Date of birth
                                                </th>
                                                <th class="text-center">
                                                    Relationship
                                                </th>
                                                <th class="text-center">
                                                    Action
                                                </th>
                                                <th class="text-center">
                                                    Civil Status
                                                </th>
                                                <th class="text-center">
                                                    HMO Enrollment
                                                </th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row,index) in dependents" v-bind:key="index">
                                                <td>
                                                    <input type="text" class="form-control" v-model="row.dependent_name" style="width:150px;">     
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" v-model="row.first_name" style="width:150px;">     
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" v-model="row.last_name" style="width:150px;">     
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" v-model="row.middle_name" style="width:150px;">     
                                                </td>
                                                <td>
                                                    <select class="form-control" v-model="row.dependent_gender" id="dependent_gender" style="width:150px;">
                                                        <option value="">Choose Gender</option>
                                                        <option value="MALE">MALE</option>
                                                        <option value="FEMALE">FEMALE</option>
                                                        <!-- <option value="UNKNOWN">UNKNOWN</option> -->
                                                    </select> 
                                                </td>
                                                <td>
                                                    <input type="date" class="form-control" v-model="row.bdate">
                                                </td>
                                                <td>
                                                    <select class="form-control" v-model="row.relation" id="relation" style="width:150px;">
                                                        <option value="">Choose Relationship</option>
                                                        <option value="MOTHER">MOTHER</option>
                                                        <option value="FATHER">FATHER</option>
                                                        <option value="BROTHER">BROTHER</option>
                                                        <option value="SISTER">SISTER</option>
                                                        <option value="SPOUSE">SPOUSE</option>
                                                        <option value="CHILD">CHILD</option>
                                                    </select> 
                                                </td>
                                                <td>
                                                    <select class="form-control" v-model="row.dependent_status" id="dependent_status" style="width:150px;">
                                                        <option value="">Choose Status</option>
                                                        <option value="NEW">NEW</option>
                                                        <option value="RENEW">RENEW</option>
                                                    </select>  
                                                </td>
                                                <td>
                                                    <select class="form-control" v-model="row.civil_status" id="hmo_marital_status" style="width:150px;">
                                                        <option value="">Choose Marital Status</option>
                                                        <option v-for="(maritals) in marital_statuses" v-bind:key="maritals" :value="maritals"> {{ maritals }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control" v-model="row.hmo_enrollment" id="hmo_enrollment" style="width:150px;">
                                                        <option value="">For HMO Enrollment</option>
                                                        <option value="YES">YES</option>
                                                        <option value="NO">NO</option>
                                                    </select>  
                                                    
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm mt-2" style="float:right;" v-if="row.id" @click="removeDependent(index,row)">x</button>
                                                    <button type="button" v-else class="btn btn-success btn-sm mt-2" style="float:right;" @click="removeDependent(index)">x</button>  
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                               
                            </div>

                            <div class="col-md-12 mt-5">
                                <h4>Attachment(s)  - Birth Certificates</h4>
                            </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="file" multiple="multiple" id="dependents_attachments" class="form-control dependents-attachments-edit" @change="uploadDependentAttachments" placeholder="Attach file"><br>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                <div class="table-responsive mt-3">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>File</th>
                                                <th class="text-center"></th>
                                                <th class="text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(attachment, index) in employee_dependent_attachments" v-bind:key="index">
                                                <td>{{ index + 1 }}</td>
                                                <td> {{ attachment.file }}</td>
                                                <td class="text-center"> <a target="_blank" :href="'storage/dependents_attachments/'+attachment.file"><span class="btn btn-info btn-sm mt-2"> View </span></a></td>
                                                <td class="text-center"> <button type="button" class="btn btn-danger btn-sm mt-2" style="float:right;"  @click="removeDependentAttachment(index,attachment)">Remove</button></td>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                    
                            </div>

                        </div>
                        <hr class="my-4">
                        <!-- Identification  -->
                        <h6 class="heading-small text-muted mb-4">Identification</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">SSS</label>
                                        <input type="text" class="form-control" v-model="employee_copied.sss_number">
                                        <span class="text-danger" v-if="errors.sss_number">{{ errors.sss_number[0] }}</span> 
                                    </div>
                                </div>    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">HDMF</label>
                                        <input type="text" class="form-control" v-model="employee_copied.hdmf">
                                        <span class="text-danger" v-if="errors.hdmf">{{ errors.hdmf[0] }}</span> 
                                    </div>
                                </div>    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Philhealth</label>
                                        <input type="text" class="form-control" v-model="employee_copied.phil_number">
                                        <span class="text-danger" v-if="errors.hdmf">{{ errors.phil_number[0] }}</span> 
                                    </div>
                                </div>    
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">TIN</label>
                                        <input type="text" class="form-control" v-model="employee_copied.tax_number">
                                        <span class="text-danger" v-if="errors.tax_number">{{ errors.tax_number[0] }}</span> 
                                    </div>
                                </div>    
                                <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Tax Status*</label>
                                        <select class="form-control" v-model="employee_copied.tax_status" id="tax_status" :disabled="user_disabled">
                                            <option value="">Choose Tax Status</option>
                                            <option value="S">S</option> 
                                            <option value="S1">S1</option> 
                                            <option value="S2">S2</option> 
                                            <option value="S3">S3</option> 
                                            <option value="S4">S4</option> 
                                            <option value="M">M4</option> 
                                            <option value="M1">M1</option> 
                                            <option value="M2">M3</option> 
                                            <option value="M3">M4</option> 
                                            <option value="M4">M4</option> 
                                            
                                        </select>

                                        <span class="text-danger" v-if="errors.tax_status">{{ errors.tax_status[0] }}</span> 
                                    </div>
                                </div> -->
                            </div>         
                        </div> 
                         <hr class="my-4">
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="role">NOTES/REMARKS</label>
                                        <textarea class="form-control" v-model="employee_copied.remarks" placeholder="Add Notes/Remarks to HR" rows="4"></textarea>
                                        <span class="text-danger" v-if="errors.remarks">{{ errors.remarks[0] }}</span> 
                                    </div>
                                </div> 
                            </div>        
                        </div>        

                        <div class="col-md-12 text-center mt-3 pt-3 pb-3" style="background-color:#f4f5f7;border-radius:5px;">
                            <h4>Terms and Conditions</h4>
                            <div class="custom-control custom-checkbox mb-3">
                                <input class="custom-control-input" id="terms_conditions" v-model="termsConditions" @change="termsConditionsValidate" type="checkbox">
                                <label class="custom-control-label" for="terms_conditions">I certify that the information provided is true and correct to the best of my knowledge.</label>
                            </div>     
                        </div>
           
                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-white border-0">
                        <div class="row">
                            <div class="col-md-12 text-center"  v-if="user_access_rights.edit == 'YES'">
                                <button type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="updateEmployee(employee_copied)" style="width:150px;">Update</button>
                               
                                <button id="verify_btn" :disabled="saveEmployee" v-if="!employee_copied.verification && employee_requests_pending == 0" type="button" class="btn btn-info btn-round btn-fill btn-lg" @click="verifyEmployee(employee_copied)" style="width:150px;">Verify</button>
                              
                            </div>
                        </div>
                    </div>


                </div>
                </div>
        </div>
    </div>

    <!-- Employee Request Approvals -->
    <div class="modal fade" id="employeeRequestsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-employee" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <h3 class="mb-0">EMPLOYEE REQUESTS</h3>
                            <small class="text-muted">List of all your requests</small>
                        </div> 

                        <div class="col-xl-12 mb-2 mt-3">
                            <input type="text" name="employee_request" class="form-control" placeholder="Search Status" autocomplete="off" v-model="keywords" id="name">
                        </div> 
                    </div>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                            <th></th>
                            <th>Date Created</th>
                            <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr v-for="(employee_request, index) in filteredQueuesEmployeeRequests" v-bind:key="index">
                                <td class="text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" data-toggle="modal" data-target="#viewEmployeeRequestModal" style="cursor: pointer" @click="copyObjectEmployeeRequest(employee_request)"><i class="fas fa-eye"></i> View</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="#deleteEmployeeRequestModal" style="cursor: pointer" @click="removeEmployeeRequest(index,employee_request.id)" v-if="employee_request.status == 'Pending'"><i class="fas fa-trash"></i> Delete</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ employee_request.created_at }}
                                </td>
                                <td>
                                    {{ employee_request.status }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row mb-3 mt-3 ml-1" v-if="filteredemployeerequests.length">
                        <div class="col-6">
                            <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                                <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                            <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                        </div>
                        <div class="col-6 text-right">
                            <span class="mr-2">Filtered employee request(s) : {{ filteredemployeerequests.length }} </span><br>
                            <span class="mr-2">Total employee request(s) : {{ employee_requests.length }}</span>
                        </div>
                    </div>
                </div>    
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-md" data-dismiss="modal" aria-label="Close" >Close</button>

                </div>    
            </div>    
        </div>    
    </div>

    <!-- Employee Request View -->
    <div class="modal fade" id="viewEmployeeRequestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog modal-dialog-centered modal-md modal-employee-request" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <h3 class="mb-0">View Employee Request</h3>
                            <small class="text-muted">Date Modified: {{ employee_request.created_at }}</small>
                        </div> 
                    </div>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                        
                                <th width="20%;">*Field</th>
                                <th><span class="label label-warning">Old</span></th>
                                <th><span class="label label-success">New</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td align="left"> NICK NAME</td>
                                <td align="left"> {{ employee_request_original.nick_name }}</td>
                                <td align="left"> <i v-if="employee_request_original.nick_name != employee_request_approval.nick_name" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.nick_name }}</td>
                            </tr>
                            <tr>
                                <td align="left"> LAST NAME</td>
                                <td align="left"> {{ employee_request_original.last_name }}</td>
                                <td align="left"> <i v-if="employee_request_original.last_name != employee_request_approval.last_name" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.last_name }}</td>
                            </tr>
                            <tr>
                                <td align="left"> MIDDLE NAME</td>
                                <td align="left"> {{ employee_request_original.middle_name }}</td>
                                <td align="left"> <i v-if="employee_request_original.middle_name != employee_request_approval.middle_name" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.middle_name }}</td>
                            </tr>
                            <tr>
                                <td align="left"> MIDDLE INITIAL</td>
                                <td align="left"> {{ employee_request_original.middle_initial }}</td>
                                <td align="left"> <i v-if="employee_request_original.middle_initial != employee_request_approval.middle_initial" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.middle_initial }}</td>
                            </tr>
                            <tr>
                                <td align="left"> MARITAL STATUS</td>
                                <td align="left"> {{ employee_request_original.marital_status }}</td>
                                <td align="left"> <i v-if="employee_request_original.marital_status != employee_request_approval.marital_status" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.marital_status }}</td>
                            </tr>
                            <tr>
                                <td align="left"> MARITAL STATUS ATTACHMENT</td>
                                <td align="left"> 
                                    <a  v-if="employee_request_original.marital_status == 'MARRIED' || employee_request_original.marital_status == 'DIVORCED' || employee_request_original.marital_status == 'WIDOW'" :href="'storage/marital_attachments/temps/'+employee_request_original.marital_status_attachment" target="_blank">{{ employee_request_original.marital_status_attachment }}</a>
                                </td>
                                <td align="left"> 
                                    <i v-if="employee_request_original.marital_status_attachment != employee_request_approval.marital_status_attachment" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i>

                                    <a  v-if="employee_request_approval.marital_status == 'MARRIED' || employee_request_approval.marital_status == 'DIVORCED' || employee_request_original.marital_status == 'WIDOW'" :href="'storage/marital_attachments/temps/'+employee_request_approval.marital_status_attachment" target="_blank">{{ employee_request_approval.marital_status_attachment }}</a>
                                    </td>
                            </tr>
                            <tr>
                                <td align="left"> GENDER</td>
                                <td align="left"> {{ employee_request_original.gender }}</td>
                                <td align="left"> <i v-if="employee_request_original.gender != employee_request_approval.gender" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.gender }}</td>
                            </tr>
                            <tr>
                                <td align="left"> CURRENT ADDRESS</td>
                                <td align="left"  width="100px;"> {{ employee_request_original.current_address }}</td>
                                <td align="left" width="40%"> <i v-if="employee_request_original.current_address != employee_request_approval.current_address" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.current_address }}</td>
                            </tr>
                            <tr>
                                <td align="left"> PERMANENT ADDRESS</td>
                                <td align="left"> {{ employee_request_original.permanent_address }}</td>
                                <td align="left"> <i v-if="employee_request_original.permanent_address != employee_request_approval.permanent_address" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.permanent_address }}</td>
                            </tr>
                            <tr>
                                <td align="left"> LANDLINE</td>
                                <td align="left"> {{ employee_request_original.phone_number }}</td>
                                <td align="left"> <i v-if="employee_request_original.phone_number != employee_request_approval.phone_number" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.phone_number }}</td>
                            </tr>
                            <tr>
                                <td align="left"> MOBILE NUMBER</td>
                                <td align="left"> {{ employee_request_original.mobile_number }}</td>
                                <td align="left"> <i v-if="employee_request_original.mobile_number != employee_request_approval.mobile_number" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.mobile_number }}</td>
                            </tr>
                            <tr>
                                <td align="left"> EMERGENCY CONTACT PERSON</td>
                                <td align="left"> {{ employee_request_original.contact_person }}</td>
                                <td align="left"> <i v-if="employee_request_original.contact_person != employee_request_approval.contact_person" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.contact_person }}</td>
                            </tr>
                            <tr>
                                <td align="left"> CONTACT RELATIONSHIP</td>
                                <td align="left"> {{ employee_request_original.contact_relation }}</td>
                                <td align="left"> <i v-if="employee_request_original.contact_relation != employee_request_approval.contact_relation" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.contact_relation }}</td>
                            </tr>
                            <tr>
                                <td align="left"> CONTACT NUMBER</td>
                                <td align="left"> {{ employee_request_original.contact_number }}</td>
                                <td align="left"> <i v-if="employee_request_original.contact_number != employee_request_approval.contact_number" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.contact_number }}</td>
                            </tr>
                            <tr>
                                <td align="left"> SSS</td>
                                <td align="left"> {{ employee_request_original.sss_number }}</td>
                                <td align="left"> <i v-if="employee_request_original.sss_number != employee_request_approval.sss_number" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.sss_number }}</td>
                            </tr>
                            <tr>
                                <td align="left"> HDMF</td>
                                <td align="left"> {{ employee_request_original.hdmf }}</td>
                                <td align="left"> <i v-if="employee_request_original.hdmf != employee_request_approval.hdmf" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.hdmf }}</td>
                            </tr>
                            <tr>
                                <td align="left"> Philhealth</td>
                                <td align="left"> {{ employee_request_original.phil_number }}</td>
                                <td align="left"> <i v-if="employee_request_original.phil_number != employee_request_approval.phil_number" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.phil_number }}</td>
                            </tr>
                            <tr>
                                <td align="left"> TIN</td>
                                <td align="left"> {{ employee_request_original.tax_number }}</td>
                                <td align="left"> <i v-if="employee_request_original.tax_number != employee_request_approval.tax_number" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.tax_number }}</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>

                    <div class="table-responsive mt-3">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th colspan="5"><h4 class="text-success">NEW/MODIFIED - HMO DEPENDENTS</h4> </th>
                            </tr>
                            <tr>
                                <th width="20%;">#</th>
                                <th width="20%;">Name</th>
                                <th width="20%;">Gender</th>
                                <th width="20%;">Date of Birth</th>
                                <th width="20%;">Relationship</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(request_approval_dependent, index) in employee_request_approval.dependents" v-bind:key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ request_approval_dependent.dependent_name }}</td>
                                <td>{{ request_approval_dependent.dependent_gender }}</td>
                                <td>{{ request_approval_dependent.bdate }}</td>
                                <td>{{ request_approval_dependent.relation }}</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th colspan="5"><h4 class="text-danger">DELETED - HMO DEPENDENTS</h4> </th>
                                </tr>
                                <tr>
                                    <th width="20%;">#</th>
                                    <th width="20%;">Name</th>
                                    <th width="20%;">Gender</th>
                                    <th width="20%;">Date of Birth</th>
                                    <th width="20%;">Relationship</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(request_approval_deleted_dependent, index) in employee_request_approval.deleted_dependents" v-bind:key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ request_approval_deleted_dependent.dependent_name }}</td>
                                    <td>{{ request_approval_deleted_dependent.dependent_gender }}</td>
                                    <td>{{ request_approval_deleted_dependent.bdate }}</td>
                                    <td>{{ request_approval_deleted_dependent.relation }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-3" v-if="employee_request_approval.dependent_attachments">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th colspan="5"><h4 class="text-success">NEW - HMO DEPENDENTS ATTACHMENT</h4> </th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>FILE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(request_approval_dependent_attachment, index) in employee_request_approval.dependent_attachments" v-bind:key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ request_approval_dependent_attachment }}</td>
                                    <td class="text-center"> <a target="_blank" :href="'storage/dependents_attachments/temps/'+request_approval_dependent_attachment"><span class="btn btn-info btn-sm mt-2"> View </span></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-3" v-if="employee_request_approval.deleted_dependent_attachments">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th colspan="5"><h4 class="text-danger">DELETED - HMO DEPENDENTS ATTACHMENT</h4> </th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>FILE</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(request_approval_deleted_dependent_attachment, index) in employee_request_approval.deleted_dependent_attachments" v-bind:key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ request_approval_deleted_dependent_attachment.file }}</td>
                                    <td class="text-center"> <a target="_blank" :href="'storage/dependents_attachments/'+request_approval_deleted_dependent_attachment.file"><span class="btn btn-info btn-sm mt-2"> View </span></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-5" v-if="employee_request_approval.remarks">
                        <div class="col-md-12">
                         
                            <div data-notify="container" class="alert alert-primary alert-with-icon"><i class="fas fa-sticky-note"></i> <strong>Notes/Remarks:</strong>
                            <br>
                            <span data-notify="message">
                                &nbsp;&nbsp;&nbsp;&nbsp;{{employee_request_approval.remarks}}
                            </span>
                            </div>
                        </div>
                    </div>
                
                </div>    
                <div class="modal-footer">
                </div>    
            </div>    
        </div>    
    </div>    
</div>
</template>

<script>
    import loader from './Loader'
    import Swal from 'sweetalert2'
    export default {
        props:['useremployeeid','url'],
        data(){
            return {
                user_access_rights: [],
                employee: [],
                employee_copied: [],
                errors: [],
                employee_id: '',
                marital_statuses : [],
                marital_file : '',
                profile_image_file : '',
                signature_image_file : '',
                profile_image: '',
                signature_image: '',
                marital_attachment_validate : true,
                marital_attachment_view : false,
                companies : [],
                divisions : [],
                departments : [],
                userformData: new FormData(),
                locations : [],
                levels : [],
                saveEmployee: true,
                termsConditions: false,
                dependents : [],
                deletedDependent : [],
                table_loading : true,
                user_disabled : true,
                gender_disabled : true,
                employee_requests : [],
                employee_request: [],
                employee_request_original: [],
                employee_request_approval: [],
                keywords : '',
                currentPage: 0,
                itemsPerPage: 5,
                employee_requests_pending : 0,
                employee_dependent_attachments: [],
                deleted_dependent_attachments: [],
                dependent_attachments: [],
                fileSize: 0,
            }
        },
        created(){
            this.fetchEmployee();
            this.fetchMaritalStatuses();
            this.fetchCompanies();
            this.fetchDepartments();
            this.fetchLocations();
            this.fetchLevels();
            this.disabledByGender();
            this.fetchUserAccessRights();
        },
        methods:{
            fetchUserAccessRights(){
                this.user_access_rights = [];
                axios.get('/user-access-rights')
                .then(response => { 
                    this.user_access_rights = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            disabledByGender(){
                if(this.employee_copied.gender == 'FEMALE'){
                    if(this.employee_copied.marital_status == 'MARRIED' || this.employee_copied.marital_status == 'DIVORCED' || this.employee_copied.marital_status == 'WIDOW'){
                         this.gender_disabled = false;
                    }else{
                         this.gender_disabled = true;
                    }
                }else{
                    this.gender_disabled = true;
                }
            },
            verifyEmployee(employee_copied){
                Swal.fire({
                        title: 'Are you sure you want to verify this information?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#2dce89',
                        cancelButtonColor: '#f5365c',
                        confirmButtonText: 'Yes, Verify'
                        }).then((result) => {
                        if (result.value) {
                            let formData = new FormData();
                            formData.append('employee_id', employee_copied.id);
                            formData.append('verification', '1');

                            axios.post(`/verify_employee`,formData)
                            .then(response => {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Your Information has been Verified. Thank you.',
                                    icon: 'success',
                                    confirmButtonText: 'Okay'
                                });
                                this.employee_copied = response.data; 
                                document.getElementById("terms_conditions").checked = false;

                                this.termsConditionsValidate();
                            })
                            .catch(error => {
                            
                                this.errors = error.response.data.errors;
                                Swal.fire({
                                    title: 'Warning!',
                                    text: 'Unable to verify Employee.',
                                    icon: 'error',
                                    confirmButtonText: 'Okay'
                                })
                                
                            })

                            
                        }
                    })
            },
            uploadDependentAttachments(e){

                var files = e.target.files || e.dataTransfer.files;

                if(!files.length)
                    return;
                
                for (var i = files.length - 1; i >= 0; i--){
                    this.dependent_attachments.push(files[i]);
                    this.fileSize = this.fileSize+files[i].size / 1024 / 1024;
                }
                if(this.fileSize > 5){
                    alert('File size exceeds 5 MB');
                    document.getElementById('dependents_attachments').value = "";
                    this.dependent_attachments = [];
                    this.fileSize = 0;
                }
                
            },
            prepareDependentsAttachment(){
                if(this.dependent_attachments.length > 0){
                    for(var i = 0; i < this.dependent_attachments.length; i++){
                        let dependent_attachments = this.dependent_attachments[i];
                        this.userformData.append('dependent_attachments[]', dependent_attachments);
                    }
                } 
            },
            updateEmployee(employee_copied){
                this.errors = [];
                document.getElementById('edit_btn').disabled = true;

                //Personal
                if(this.profile_image_file){
                    this.userformData.append('employee_image', this.profile_image_file);
                }
                if(this.signature_image_file){
                    this.userformData.append('employee_signature', this.signature_image_file);
                }
                
                this.userformData.append('middle_name', employee_copied.middle_name);
                
                this.userformData.append('middle_initial', employee_copied.middle_initial);
                
                this.userformData.append('last_name', employee_copied.last_name ? employee_copied.last_name : "");
                this.userformData.append('marital_status', employee_copied.marital_status ? employee_copied.marital_status : "");
                this.userformData.append('gender', employee_copied.gender ? employee_copied.gender : "");

                this.userformData.append('nick_name', employee_copied.nick_name ? employee_copied.nick_name : "");
                this.userformData.append('personal_email', employee_copied.personal_email ? employee_copied.personal_email : "");



                if(this.marital_file){
                    this.userformData.append('marital_status_attachment', this.marital_file);
                }
            
                //Contact
                this.userformData.append('current_address', employee_copied.current_address ? employee_copied.current_address : "-");
                this.userformData.append('permanent_address', employee_copied.permanent_address ? employee_copied.permanent_address : "-");
                this.userformData.append('phone_number', employee_copied.phone_number ? employee_copied.phone_number : "-");
                this.userformData.append('mobile_number', employee_copied.mobile_number ? employee_copied.mobile_number : "-");
                this.userformData.append('contact_person', employee_copied.contact_person ? employee_copied.contact_person : "-");
                this.userformData.append('contact_number', employee_copied.contact_number ? employee_copied.contact_number : "-");
                this.userformData.append('contact_relation', employee_copied.contact_relation ? employee_copied.contact_relation : "-");

                //Dependents
                this.userformData.append('dependents', this.dependents ? JSON.stringify(this.dependents) : "");
                this.userformData.append('deleted_dependents', this.deletedDependent ? JSON.stringify(this.deletedDependent) : "");

                this.userformData.append('deleted_dependent_attachments', this.deleted_dependent_attachments ? JSON.stringify(this.deleted_dependent_attachments) : "");

                this.prepareDependentsAttachment();

                //IDENTIFICATION
                this.userformData.append('sss_number', employee_copied.sss_number ? employee_copied.sss_number : "-");
                this.userformData.append('phil_number', employee_copied.phil_number ? employee_copied.phil_number : "-");
                this.userformData.append('tax_number', employee_copied.tax_number ? employee_copied.tax_number : "-");
                this.userformData.append('hdmf', employee_copied.hdmf ? employee_copied.hdmf : "-");

                this.userformData.append('remarks', employee_copied.remarks ? employee_copied.remarks : "");

                this.userformData.append('_method', 'PATCH');

                axios.post(`/employee-user-profile/${employee_copied.id}`, 
                    this.userformData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }  
                )
                .then(response => {
                    document.getElementById('edit_btn').disabled = false;
                    document.getElementById('dependents_attachments').value = "";
                    this.copyObject(response.data);

                    Swal.fire({
                        title: 'Success!',
                        html: 'Your employee update has been sent to HR for Verification/Approval. Click <a href="#" data-toggle="modal" data-target="#employeeRequestsModal"><u><strong class="text-primary">Employee Requests</strong></u></a> to view your requests. Thank you.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    document.getElementById('edit_btn').disabled = false;
                    
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Unable to Update Employee. Check Entries and then try again.',
                        icon: 'error',
                        confirmButtonText: 'Okay'
                    });

                })

            },
            profileImageLoadError(){
                this.profile_image = this.url + '/storage/default.png';
            },
            signatureImageLoadError(){
                this.signature_image = this.url + '/storage/image_not_available.png';
            },
            fetchEmployee(){
                axios.get('/view-user-profile-data/' + this.useremployeeid)
                .then(response => { 
                    this.employee = response.data;
                    this.copyObject(response.data);
                })
                .catch(error => { 
                    this.errors = error;
                })
            },
            copyObject(employee){
                this.errors = [];
                this.termsConditions = false;
                this.saveEmployee = true;

                //Config Employee Fields
                this.employee_copied = Object.assign({}, employee);
                this.employee_copied.age = this.getAge(this.employee_copied.birthdate); 
                this.employee_copied.birthdate = this.getDateFormat(this.employee_copied.birthdate); 
                this.employee_copied.date_hired = this.getDateFormat(this.employee_copied.date_hired); 
                this.employee_copied.tenure = this.getTenure(this.employee_copied.date_hired);
                this.employee_copied.date_regularized = this.getDateFormat(this.employee_copied.date_regularized);
                this.employee_copied.date_resigned = this.getDateFormat(this.employee_copied.date_resigned);
                this.employee_copied.company_list = this.employee_copied.companies[0].id; 
                this.employee_copied.department_list = this.employee_copied.departments[0].id; 
                this.employee_copied.location_list = this.employee_copied.locations[0].id; 
                this.employee_id = employee.id;

                //Attachment
                var num = Math.random();
                this.profile_image = this.url + '/storage/id_image/employee_image/' + this.useremployeeid + '.png?v='+num;
                this.signature_image = this.url + '/storage/id_image/employee_signature/' + this.useremployeeid + '.png?v='+num;
                

                //Get Dependents
                this.fetchDependents();
                this.fetchDependentAttachments();

                //Validate Marital Status
                this.validateMartialStatus();

                var profile =  document.getElementById("profile_image_file");
                var signature =  document.getElementById("signature_image_file");
                var marital_status =  document.getElementById("marital_file");
                profile.value = '';
                signature.value = '';
                marital_status.value = ''; 

                document.getElementById('dependents_attachments').value = "";

                //Employee Request
                this.fetchEmployeeRequest(employee.id);
                this.fetchEmployeeRequestPending(employee.id);
            },
            validateMartialStatus(){
                if(this.employee_copied.marital_status){
                    if(this.employee_copied.marital_status == "MARRIED" || this.employee_copied.marital_status == "DIVORCED" || this.employee_copied.marital_status == "WIDOW"){
                        this.marital_attachment_validate = false;
                        this.marital_attachment_view = true;

                        if(this.employee_copied.gender == "FEMALE"){
                            this.gender_disabled = false;
                        }else{
                            this.gender_disabled = true;
                        }
                        
                    }else{
                        this.marital_attachment_validate = true;
                        this.marital_attachment_view = false;
                        this.gender_disabled = true;
                    }
                }else{
                    this.marital_attachment_validate = true;
                    this.marital_attachment_view = false;
                }     
            },
            fetchEmployees(){
                this.table_loading = true;
                axios.get('/employees-all')
                .then(response => { 
                    this.employees = response.data;
                    this.table_loading = false;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchDependents(){
                let v = this;
                this.dependents = [];
                this.deletedDependent = [];
                axios.get('/employee-dependents/'+this.employee_copied.id)
                .then(response => { 
                    if(response.data.length > 0){
                        var dependents_arr = [];
                        response.data.forEach(element => {
                            v.dependents.push({
                                id: element.id,
                                dependent_name: element.dependent_name,
                                first_name: element.first_name,
                                last_name: element.last_name,
                                middle_name: element.middle_name,
                                dependent_gender: element.dependent_gender,
                                bdate: v.getDateFormat(element.bdate),
                                relation: element.relation,
                                dependent_status: element.dependent_status,
                                civil_status: element.civil_status,
                                hmo_enrollment: element.hmo_enrollment,
                            });
                        });
                    }
                    
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchDependentAttachments(){
                let v = this;
                this.employee_dependent_attachments = [];
                this.deleted_dependent_attachments = [];
                axios.get('/employee-dependents-attachments/'+this.employee_copied.id)
                .then(response => { 
                    if(response.data.length > 0){
                        this.employee_dependent_attachments = response.data;
                    }
                    
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },

            addDependent(){
                this.dependents.push({
                    id: "",
                    dependent_name: "",
                    dependent_gender: "",
                    bdate: "",
                    relation: "",
                });
            },
            removeDependent: function(index,dependent) {
                if(dependent){
                    Swal.fire({
                        title: 'Are you sure you want to remove this dependent?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Remove'
                        }).then((result) => {
                        if (result.value) {

                            this.deletedDependent.push({
                                id: dependent.id,
                                dependent_name: dependent.dependent_name,
                                dependent_gender: dependent.dependent_gender,
                                bdate: dependent.bdate,
                                relation: dependent.relation
                            });
                            this.dependents.splice(index, 1);    
                        }
                    })
                }else{
                    this.dependents.splice(index, 1);
                } 
            },
            removeDependentAttachment: function(index,attachment) {
                if(attachment){
                    Swal.fire({
                        title: 'Are you sure you want to remove this attachment?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Remove'
                        }).then((result) => {
                        if (result.value) {

                            this.deleted_dependent_attachments.push({
                                id: attachment.id,
                                file: attachment.file,
                            });

                            this.employee_dependent_attachments.splice(index, 1);    
                        }
                    })
                }
            },
            profileHandleFileUpload(){
                var profile = document.getElementById("profile_image_file");
                this.profile_image = window.URL.createObjectURL(profile.files[0]);
                this.profile_image_file = profile.files[0];
            },
            signatureHandleFileUpload(){
                var signature = document.getElementById("signature_image_file");
                this.signature_image = window.URL.createObjectURL(signature.files[0]);
                this.signature_image_file = signature.files[0];
            },
            maritalHandleFileUpload(){
                var marital = document.getElementById("marital_file");
                this.marital_file = marital.files[0];
            },
            fetchMaritalStatuses(){
                axios.get('/maritals-options')
                .then(response => { 
                    this.marital_statuses = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchCompanies(){
                axios.get('/companies-all')
                .then(response => { 
                    this.companies = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchDepartments(){
                axios.get('/departments-all')
                .then(response => { 
                    this.departments = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchLocations(){
                axios.get('/locations-all')
                .then(response => { 
                    this.locations = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchLevels(){
                axios.get('/levels-options')
                .then(response => { 
                    this.levels = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            termsConditionsValidate(){
                var termsCondition = document.getElementById("terms_conditions");
                if(termsCondition.checked){
                    this.saveEmployee = false;
                }else{
                    this.saveEmployee = true;
                }
            },
            getDateFormat(dateString){

                if(dateString != null && dateString != "0000-00-00 00:00:00")
                {
                    var d = new Date(dateString),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();

                    if (month.length < 2) 
                        month = '0' + month;
                    if (day.length < 2) 
                        day = '0' + day;

                    var date_created = [year, month, day].join('-');

                    return date_created;
                }
                else{
                    return "";
                }
                
            },
            getAge(dateString) 
            {
                let v = this;
                var today = new Date();
                var birthDate = new Date(dateString);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) 
                {
                    age--;
                }
                v.employee_copied.ageRange = v.getAgeRange(age);
                return age;
            },
            getAgeRange(get_age){
                var age = parseInt(get_age,10);
                let check = "";
                if(age >= 1 && age <= 10){
                   check = "(1-10 YEARS OLD)";
                }
                else if(age >= 11 && age <= 20){
                    check = "(11-20 YEARS OLD)";
                }
                else if(age >= 21 && age <= 30){
                    check = "(21-30 YEARS OLD)";
                }
                else if(age >= 31 && age <= 40){
                    check = "(31-40 YEARS OLD)";
                }
                else if(age >= 41 && age <= 50){
                    check = "(41-50 YEARS OLD)";
                }
                else if(age >= 51 && age <= 60){
                    check = "(51-60 YEARS OLD)";
                }
                else if(age >= 61 && age <= 70){
                    check = "(61-70 YEARS OLD)";
                }
                else if(age >= 71 && age <= 80){
                    check = "(71-80 YEARS OLD)";
                }
                else if(age >= 81 && age <= 90){
                    check = "(81-90 YEARS OLD)";
                }
                else if(age >= 91 && age <= 100){
                    check = "(91-100 YEARS OLD)";
                }
                else if(age >= 101 && age <= 110){
                    check = "(101-110 YEARS OLD)";
                }
                else if(age >= 121 && age <= 130){
                    check = "(121-130 YEARS OLD)";
                }
                return check;
            },
            getTenure(dateString) 
            {
                if(dateString){
                    var to = new Date();
                    var from = new Date(dateString);

                    let
                        endYear = to.getFullYear(),
                        endMonth = to.getMonth(),
                        years = endYear - from.getFullYear(),
                        months = endMonth - from.getMonth(),
                        days = to.getDate() - from.getDate();

                    if (months < 0)
                    {
                        years--;
                        months += 12;
                    }
                    if (days < 0)
                    {
                        months--;
                        days += new Date(endYear, endMonth, 0).getDate();
                    }
                    return years + ' year(s) ' + months + ' month(s)';
                }else{
                    return "";
                }
            },
            copyObjectEmployeeRequest(employee_request){
                this.employee_request = employee_request;
                //Original Data
                var original_employee_data = JSON.parse(employee_request.original_employee_data);
                this.employee_request_original = original_employee_data;
                this.employee_request_original.dependents = original_employee_data.dependents;
                
                //Approval Data
                var employee_data = JSON.parse(employee_request.employee_data);
                this.employee_request_approval = employee_data;
                this.employee_request_approval.dependents = JSON.parse(employee_data.dependents);
                this.employee_request_approval.deleted_dependents = JSON.parse(employee_data.deleted_dependents);
                this.employee_request_approval.deleted_dependent_attachments = JSON.parse(employee_data.deleted_dependent_attachments);
            },
            fetchEmployeeRequestPending(employee_id){
                this.employee_requests_pending = 0;
                axios.get('/user-pendings/' + employee_id)
                .then(response => { 
                    this.employee_requests_pending = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchEmployeeRequest(employee_id){
                this.employee_requests = [];
                axios.get('/employee-approval-request/' + employee_id)
                .then(response => { 
                    this.employee_requests = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            removeEmployeeRequest(index,employee_request_id){
                Swal.fire({
                        title: 'Are you sure you want to delete this request?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {

                        if (result.value) {
                            axios.delete(`/employee-user-profile/${employee_request_id}`)
                            .then(response => {
                                this.employee_requests.splice(index, 1);

                                this.fetchEmployeeRequestPending(this.employee_copied.id);
                                Swal.fire(
                                'Deleted!',
                                'Your request has been deleted.',
                                'success'
                                )
                            })
                            .catch(error => {
                                this.errors = error.response.data.errors;
                                Swal.fire({
                                    title: 'Warning!',
                                    text: 'Unable to delete request. Please try again.',
                                    icon: 'warning',
                                    confirmButtonText: 'Okay'
                                })
                            })  
                        }
                    })
            },
            setPage(pageNumber) {
                this.currentPage = pageNumber;
            },

            resetStartRow() {
                this.currentPage = 0;
            },

            showPreviousLink() {
                return this.currentPage == 0 ? false : true;
            },

            showNextLink() {
                return this.currentPage == (this.totalPages - 1) ? false : true;
            }  
        },
        computed:{
            filteredemployeerequests(){
                let self = this;
                return Object.values(self.employee_requests).filter(employee_request => {
                    return employee_request.status.toLowerCase().includes(this.keywords.toLowerCase()) 
                });
            },
            totalPages() {
                return Math.ceil(Object.values(this.employee_requests).length / this.itemsPerPage)
            },
            filteredQueuesEmployeeRequests() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredemployeerequests.slice(index, index + this.itemsPerPage);

                if(this.currentPage >= this.totalPages) {
                    this.currentPage = this.totalPages - 1
                }

                if(this.currentPage == -1) {
                    this.currentPage = 0;
                }

                return queues_array;
            },
        }
    }
</script>

<style>
    @media (min-width: 992px){
        .modal-lg {
            max-width: 700px!important;
        }
        .modal-employee-request {
            max-width: 1200px!important;
        }
        .modal-employee {
            max-width: 1200px!important;
        }
    }
</style>