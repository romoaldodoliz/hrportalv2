<template>
<div>
    <loader v-if="loading"></loader>
    <div class="header bg-gradient-success pb-8 pt-5 pt-md-8 container-list"></div>
    <div class="container-fluid mt--9">
        <div class="header-body mb-5">
            <div class="row">
                <div class="col-xl-12 col-lg-6">
                    <div class="card shadow">
                        <div class="card-header">
                            <h2 class="col-12 modal-title text-center" id="addCompanyLabel">EMPLOYEE INFORMATION</h2>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center mb-2 mt-2">
                                <img :src="profile_image" @error="profileImageLoadError()" class="rounded-circle" style="width:150px;height:150px;border:2px dotted ;">
                            </div>

                            <div class="nav-wrapper">
                                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="fas fa-user-tie mr-2"></i>PERSONAL</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="fas fa-briefcase mr-2"></i>WORK</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false"><i class="fas fa-address-book mr-2"></i>CONTACT</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-4-tab" data-toggle="tab" href="#tabs-icons-text-4" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false"><i class="fas fa-id-card mr-2"></i>IDENTIFICATION</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card shadow">
                                    <div class="card-body">
                                        <div class="tab-content" id="myTabContent">
                                            <!-- Personal -->
                                            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                                <div class="row">
                                                    

                                                    <div class="col-md-12 mb-5" style="border:1px solid;border-radius:6px;border-color:#cad1d7;">
                                                        <div class="col-md-12 mt-3 justify-content-center">
                                                            <div class="form-group">
                                                                <label for="role">Profile Image</label> 
                                                                <input type="file" accept="image/*" id="profile_image_file" class="form-control" ref="file" v-on:change="profileHandleFileUpload()"/>
                                                                <span class="text-danger" v-if="errors.employee_image">{{ errors.employee_image[0] }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-center mb-2 mt-2">
                                                            <img :src="signature_image" @error="signatureImageLoadError()" style="width:250px;height:auto;border-radius:6px;border:2px dotted;">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="role">Signature</label> 
                                                                <input type="file" accept="image/*" id="signature_image_file" class="form-control" ref="file" v-on:change="signatureHandleFileUpload()"/>
                                                                <span class="text-danger" v-if="errors.employee_signature">{{ errors.employee_signature[0] }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">First Name*</label> 
                                                            <input type="text"  class="form-control" v-model="employee.first_name"   >
                                                            <span class="text-danger" v-if="errors.first_name">{{ errors.first_name[0] }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="role">Middle Name</label> 
                                                            <input type="text"  class="form-control" v-model="employee.middle_name"   >
                                                            <span class="text-danger" v-if="errors.middle_name">{{ errors.middle_name[0] }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="role">Middle Initial</label> 
                                                            <input type="text"  class="form-control" v-model="employee.middle_initial"   >
                                                            <span class="text-danger" v-if="errors.middle_initial">{{ errors.middle_initial[0] }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Last Name*</label> 
                                                            <input type="text"  class="form-control" v-model="employee.last_name"   >
                                                            <span class="text-danger" v-if="errors.last_name">{{ errors.last_name[0] }}</span>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Suffix</label> 
                                                            <input type="text" class="form-control" v-model="employee.name_suffix">
                                                            <span class="text-danger" v-if="errors.name_suffix">{{ errors.name_suffix[0] }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Marital Status*</label> 
                                                            <select class="form-control" v-model="employee.marital_status" id="marital_status" @change="validateMartialStatus()">
                                                                <option value="">Choose Marital Status</option>
                                                                <option v-for="(maritals) in marital_statuses" v-bind:key="maritals" :value="maritals"> {{ maritals }}</option>
                                                            </select>
                                                            <span class="text-danger" v-if="errors.marital_status">{{ errors.marital_status[0] }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Marital Attachment</label> 
                                                            <input type="file" :disabled="marital_attachment_validate" id="marital_file" class="form-control" ref="file" v-on:change="maritalHandleFileUpload()"/>
                                                            <span class="text-danger" v-if="errors.marital_status_attachment">{{ errors.marital_status_attachment[0] }}</span>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Date of Birth*</label> 
                                                            <input type="date" class="form-control" v-model="employee.birthdate">
                                                            <span class="text-danger" v-if="errors.birthdate">{{ errors.birthdate[0] }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Gender*</label> 
                                                            <select class="form-control" v-model="employee.gender" id="marital_status">
                                                                <option value="">Choose Gender</option>
                                                                <option value="MALE"> MALE</option>
                                                                <option value="FEMALE"> FEMALE</option>
                                                            </select>
                                                            <span class="text-danger" v-if="errors.gender">{{ errors.gender[0] }}</span>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="role">Birthplace</label> 
                                                            <textarea  class="form-control" v-model="employee.birthplace"></textarea>
                                                            <span class="text-danger" v-if="errors.birthplace">{{ errors.birthplace[0] }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <h2 class="col-12 modal-title text-center" id="addCompanyLabel">Educational Background</h2>
                                                    
                                                    <div class="col-md-12">
                                                        <h2 class="ml-0 pl-0">Tertiary</h2>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Name of School</label>
                                                            <input type="text" class="form-control" v-model="employee.school_graduated">
                                                            <span class="text-danger" v-if="errors.school_graduated">{{ errors.school_graduated[0] }}</span> 
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Course</label>
                                                            <input type="text" class="form-control" v-model="employee.school_course">
                                                            <span class="text-danger" v-if="errors.school_course">{{ errors.school_course[0] }}</span> 
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Year Graduated</label>
                                                            <input type="text" class="form-control" v-model="employee.school_year">
                                                            <span class="text-danger" v-if="errors.school_year">{{ errors.school_year[0] }}</span> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <h2 class="ml-0 pl-0">Vocational Course</h2>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Name of School</label>
                                                            <input type="text" class="form-control" v-model="employee.vocational_graduated">
                                                            <span class="text-danger" v-if="errors.vocational_graduated">{{ errors.vocational_graduated[0] }}</span> 
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Course</label>
                                                            <input type="text" class="form-control" v-model="employee.vocational_course">
                                                            <span class="text-danger" v-if="errors.vocational_course">{{ errors.vocational_course[0] }}</span> 
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Year Graduated</label>
                                                            <input type="text" class="form-control" v-model="employee.vocational_year">
                                                            <span class="text-danger" v-if="errors.vocational_year">{{ errors.vocational_year[0] }}</span> 
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <!-- Work -->
                                            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Company*</label>
                                                            <select class="form-control" v-model="employee.company_list" id="company" @change="fetchDivisions()">
                                                                <option value="">Choose Company</option>
                                                                <option v-for="(company,b) in companies" v-bind:key="b" :value="company.id"> {{ company.name }}</option>
                                                            </select>

                                                            <span class="text-danger" v-if="errors.company_list">{{ errors.company_list[0] }}</span> 
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Division</label>  
                                                            <select class="form-control" v-model="employee.division" id="marital_status">
                                                                <option value="">Choose Division</option>
                                                                <option v-for="(division) in divisions" v-bind:key="division" :value="division"> {{ division }}</option>
                                                            </select>

                                                            <span class="text-danger" v-if="errors.division">{{ errors.division[0] }}</span> 
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Department*</label>
                                                            <select class="form-control" v-model="employee.department_list" id="department">
                                                                <option value="">Choose Department</option>
                                                                <option v-for="(department,b) in departments" v-bind:key="b" :value="department.id"> {{ department.name }}</option>
                                                            </select>
                                                            <span class="text-danger" v-if="errors.department_list">{{ errors.department_list[0] }}</span> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Employee Number</label>
                                                            <input type="text" class="form-control" v-model="employee.employee_number">
                                                            <span class="text-danger" v-if="errors.employee_number">{{ errors.employee_number[0] }}</span> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">ESS Employee No.</label>
                                                            <input type="text" class="form-control" v-model="employee.ess_ee_number">
                                                            <span class="text-danger" v-if="errors.ess_ee_number">{{ errors.ess_ee_number[0] }}</span> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Position</label>
                                                            <input type="text" class="form-control" v-model="employee.position">
                                                            <span class="text-danger" v-if="errors.position">{{ errors.position[0] }}</span> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Classification</label>
                                                            <select class="form-control" v-model="employee.classification" id="department">
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
                                                            <input type="date" class="form-control" v-model="employee.date_hired">
                                                            <span class="text-danger" v-if="errors.date_hired">{{ errors.date_hired[0] }}</span> 
                                                        </div>
                                                    </div>
                                                

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Level</label>
                                                            <select class="form-control" v-model="employee.level" id="level">
                                                                <option value="">Choose Level</option>
                                                                <option v-for="(level) in levels" v-bind:key="level" :value="level"> {{ level }}</option>
                                                            </select>

                                                            <span class="text-danger" v-if="errors.level">{{ errors.level[0] }}</span> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Location / Site*</label>
                                                            <select class="form-control" v-model="employee.location_list" id="location">
                                                                <option value="">Choose Location</option>
                                                                <option v-for="(location,b) in locations" v-bind:key="b" :value="location.id"> {{ location.name }}</option>
                                                            </select>

                                                            <span class="text-danger" v-if="errors.location_list">{{ errors.location_list[0] }}</span> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Area</label>
                                                            <input type="text" class="form-control" v-model="employee.area">
                                                            <span class="text-danger" v-if="errors.area">{{ errors.area[0] }}</span> 
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Bank Account Number</label>
                                                            <input type="text" class="form-control" v-model="employee.bank_account_number">
                                                            <span class="text-danger" v-if="errors.bank_account_number">{{ errors.bank_account_number[0] }}</span> 
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Bank Name</label>
                                                            <input type="text" class="form-control" v-model="employee.bank_name">
                                                            <span class="text-danger" v-if="errors.bank_name">{{ errors.bank_name[0] }}</span> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Status</label>
                                                            <select class="form-control" v-model="employee.status" id="status">
                                                                <option value="">Choose Status</option>
                                                                <option value="Active">Active</option> 
                                                                <option value="Inactive">Inactive</option> 
                                                                <option value="On-hold">On-hold</option> 
                                                                <option value="Notification">Notification</option> 
                                                            </select>
                                                            <span class="text-danger" v-if="errors.status">{{ errors.status[0] }}</span> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Date Regularized</label>
                                                            <input type="date" class="form-control" v-model="employee.date_regularized">
                                                            <span class="text-danger" v-if="errors.date_regularized">{{ errors.date_regularized[0] }}</span> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Date Resigned</label>
                                                            <input type="date" class="form-control" v-model="employee.date_resigned">
                                                            <span class="text-danger" v-if="errors.date_resigned">{{ errors.date_resigned[0] }}</span> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <h4>System Approvers</h4>
                                                        <button type="button" class="btn btn-success btn-sm mb-2" style="float: right;" @click="fetchApprovers()"><i class="fas fa-redo" title="Refresh Approver"></i></button>
                                                        <button type="button" class="btn btn-primary btn-sm mb-2" style="float: right;" @click="addApprover()">Add Row</button>
                                                        <div class="table-responsive">
                                                            <table class="table table-hover" id="tab_assign_head">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">
                                                                            Name
                                                                        </th>
                                                                        <th class="text-center">
                                                                            Position
                                                                        </th>
                                                                        <th class="text-center"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="(row,index) in approvers" v-bind:key="index">
                                                                        <td>
                                                                            <select class="form-control" v-model="row.employee_head_id" id="location">
                                                                                <option value="">Choose Approver</option>
                                                                                <option v-for="(approver,b) in employee_head_approvers" v-bind:key="b" :value="approver.id"> {{ approver.last_name + " " + approver.first_name }}</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control" v-model="row.head_id" id="location">
                                                                                <option value="">Choose Position</option>
                                                                                <option v-for="(position,b) in employee_position_approvers" v-bind:key="b" :value="position.id"> {{ position.name }}</option>
                                                                            </select> 
                                                                        </td>
                                                                        <td width="5%">
                                                                            <button type="button" class="btn btn-danger btn-sm mt-2" style="float:right;" v-if="row.id" @click="removeApprover(index,row.id)">Remove</button>
                                                                            <button type="button" v-else class="btn btn-success btn-sm mt-2" style="float:right;" @click="removeApprover(index)">Remove</button>  
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>    
                                                        </div>    
                                                    </div>                

                                                </div>
                                            </div>
                                            <!-- Contact -->
                                            <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="role">Current Address</label>
                                                            <textarea class="form-control" v-model="employee.current_address"></textarea>
                                                            <span class="text-danger" v-if="errors.current_address">{{ errors.current_address[0] }}</span> 
                                                        </div>
                                                    </div>    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="role">Permanent Address</label>
                                                            <textarea class="form-control" v-model="employee.permanent_address"></textarea>
                                                            <span class="text-danger" v-if="errors.permanent_address">{{ errors.permanent_address[0] }}</span> 
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="role">Landline</label>
                                                            <input type="text" class="form-control" v-model="employee.phone_number">
                                                            <span class="text-danger" v-if="errors.phone_number">{{ errors.phone_number[0] }}</span> 
                                                        </div>
                                                    </div>    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="role">Mobile Number</label>
                                                            <input type="text" class="form-control" v-model="employee.mobile_number">
                                                            <span class="text-danger" v-if="errors.mobile_number">{{ errors.mobile_number[0] }}</span> 
                                                        </div>
                                                    </div>   

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Contact Person</label>
                                                            <input type="text" class="form-control" v-model="employee.contact_person">
                                                            <span class="text-danger" v-if="errors.contact_person">{{ errors.contact_person[0] }}</span> 
                                                        </div>
                                                    </div>    

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Contact Relation</label>
                                                            <input type="text" class="form-control" v-model="employee.contact_relation">
                                                            <span class="text-danger" v-if="errors.contact_relation">{{ errors.contact_relation[0] }}</span> 
                                                        </div>
                                                    </div>  

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Contact Number</label>
                                                            <input type="text" class="form-control" v-model="employee.contact_number">
                                                            <span class="text-danger" v-if="errors.contact_number">{{ errors.contact_number[0] }}</span> 
                                                        </div>
                                                    </div>    
                                                    <div class="col-md-12">
                                                        <h4>HMO Dependents (By hierarchy *For Single - Mother, Father, Child *For Married - Spouse, Child)</h4>
                                                        <button type="button" class="btn btn-success btn-sm mb-2" style="float: right;" @click="fetchDependents()"><i class="fas fa-redo" title="Refresh HMO Dependents"></i></button>
                                                        <button type="button" class="btn btn-primary btn-sm mb-2" style="float: right;" @click="addDependent()">Add Row</button>
                                                        <div class="table-responsive">
                                                            <table class="table table-hover" id="tab_hmo_dependent">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">
                                                                            Name
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
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="(row,index) in dependents" v-bind:key="index">
                                                                        <td>
                                                                        <input type="text" class="form-control" v-model="row.dependent_name">     
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control" v-model="row.dependent_gender" id="dependent_gender">
                                                                                <option value="">Choose Gender</option>
                                                                                <option value="MALE">MALE</option>
                                                                                <option value="FEMALE">FEMALE</option>
                                                                            </select> 
                                                                        </td>
                                                                        <td>
                                                                            <input type="date" class="form-control" v-model="row.bdate">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" class="form-control" v-model="row.relation">
                                                                        </td>
                                                                        <td with="5%">
                                                                            <button type="button" class="btn btn-danger btn-sm mt-2" style="float:right;" v-if="row.id" @click="removeDependent(index,row.id)">Remove</button>
                                                                            <button type="button" v-else class="btn btn-success btn-sm mt-2" style="float:right;" @click="removeDependent(index)">Remove</button>  
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Identification -->
                                            <div class="tab-pane fade" id="tabs-icons-text-4" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">SSS</label>
                                                            <input type="text" class="form-control" v-model="employee.sss_number">
                                                            <span class="text-danger" v-if="errors.sss_number">{{ errors.sss_number[0] }}</span> 
                                                        </div>
                                                    </div>    
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">HDMF</label>
                                                            <input type="text" class="form-control" v-model="employee.hdmf">
                                                            <span class="text-danger" v-if="errors.hdmf">{{ errors.hdmf[0] }}</span> 
                                                        </div>
                                                    </div>    
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Philhealth</label>
                                                            <input type="text" class="form-control" v-model="employee.phil_number">
                                                            <span class="text-danger" v-if="errors.hdmf">{{ errors.phil_number[0] }}</span> 
                                                        </div>
                                                    </div>    
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">TIN</label>
                                                            <input type="text" class="form-control" v-model="employee.tax_number">
                                                            <span class="text-danger" v-if="errors.tax_number">{{ errors.tax_number[0] }}</span> 
                                                        </div>
                                                    </div>    
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="role">Tax Status*</label>
                                                            <select class="form-control" v-model="employee.tax_status" id="tax_status">
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
                                                    </div>    
                                                </div>
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

                        
                            <div class="row  mb-5">
                                <div class="col-md-12 text-center">
                                    <button :disabled="saveEmployee" type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="storeEmployee(employee)" style="width:150px;">Save</button>
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>


<script>
    import loader from './Loader'
    import Swal from 'sweetalert2'
    import VueContentPlaceholders from 'vue-content-placeholders'

    export default {
        components: {
            loader,
            VueContentPlaceholders
        },
        data(){
            return {
                employees: [],
                employee: [],
                copied_role: [],
                roles: [],
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: "",
                loading: false,
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
                locations : [],
                levels : [],
                saveEmployee: true,
                termsConditions: false,
                employee_head_approvers: [],
                employee_position_approvers: [],
                approvers : [],
                dependents : [],
                table_loading : true,
            }
        },
        created(){
            this.fetchMaritalStatuses();
            this.fetchCompanies();
            this.fetchDepartments();
            this.fetchLocations();
            this.fetchLevels();
            this.fetchHeadApprovers();
            this.fetchPositionApprovers();
            this.resetForm();
        },
        methods:{
            profileImageLoadError(){
                this.profile_image = 'storage/default.png';
            },
            signatureImageLoadError(){
                this.signature_image = 'storage/image_not_available.png';
            },
            resetForm(){
                this.errors = [];
                this.employee = [];
                this.employee.company_list = "";
                this.employee.marital_status = "";
                this.employee.gender = "";
                this.employee.division = "";
                this.employee.department_list = "";
                this.employee.classification = "";
                this.employee.level = "";
                this.employee.location_list = "";
                this.employee.status = "";
                this.employee.tax_status = "";
                this.approvers = [];
                this.dependents = [];
            },
            storeEmployee(employee){
             
                this.errors = [];
                this.employee_error = false;
                this.loading = true;
                let formData = new FormData();

                //Personal
                if(this.profile_image_file){
                    formData.append('employee_image', this.profile_image_file);
                }
                if(this.signature_image_file){
                    formData.append('employee_signature', this.signature_image_file);
                }
                
                formData.append('first_name', employee.first_name ? employee.first_name : "");
                formData.append('middle_name', employee.middle_name ? employee.middle_name : "-");
                formData.append('middle_initial', employee.middle_initial ? employee.middle_initial : "");
                formData.append('last_name', employee.last_name ? employee.last_name : "");
                formData.append('name_suffix', employee.name_suffix ? employee.name_suffix : "-");
                formData.append('marital_status', employee.marital_status ? employee.marital_status : "");

                if(this.marital_file){
                    formData.append('marital_status_attachment', this.marital_file);
                }
                formData.append('birthdate', employee.birthdate ? employee.birthdate : "");
                formData.append('gender', employee.gender ? employee.gender : "");
                formData.append('birthplace', employee.birthplace ? employee.birthplace : "-");
                formData.append('school_graduated', employee.school_graduated ? employee.school_graduated : "-");
                formData.append('school_course', employee.school_course ? employee.school_course : "-");
                formData.append('school_year', employee.school_year ? employee.school_year : "-");
                formData.append('vocational_graduated', employee.vocational_graduated ? employee.vocational_graduated : "-");
                formData.append('vocational_course', employee.vocational_course ? employee.vocational_course : "-");
                formData.append('school_year', employee.school_year ? employee.school_year : "-");
                formData.append('vocational_graduated', employee.vocational_graduated ? employee.vocational_graduated : "-");
                formData.append('vocational_course', employee.vocational_course ? employee.vocational_course : "-");
                formData.append('vocational_year', employee.vocational_year ? employee.vocational_year : "-");
                //Work
                formData.append('company', "-");
                formData.append('department', "-");
                formData.append('location', "-");
                formData.append('job_remarks', "-");
                formData.append('id_remarks', "-");
                formData.append('bank_name', "-");

                formData.append('company_list', employee.company_list ? employee.company_list : "");
                formData.append('division', employee.division ? employee.division : "");
                formData.append('department_list', employee.department_list ? employee.department_list : "");
                formData.append('employee_number', employee.employee_number ? employee.employee_number : "-");
                formData.append('ess_ee_number', employee.ess_ee_number ? employee.ess_ee_number : "-");
                formData.append('position', employee.position ? employee.position : "-");
                formData.append('classification', employee.classification ? employee.classification : "-");
                formData.append('date_hired', employee.date_hired ? employee.date_hired : "");
                formData.append('level', employee.level ? employee.level : "-");
                formData.append('location_list', employee.location_list ? employee.location_list : "");
                formData.append('area', employee.area ? employee.area : "-");
                formData.append('bank_account_number', employee.bank_account_number ? employee.bank_account_number : "-");
                formData.append('bank_name', employee.bank_name ? employee.bank_name : "-");
                formData.append('status', employee.status ? employee.status : "-");

                //Contact
                formData.append('current_address', employee.current_address ? employee.current_address : "-");
                formData.append('permanent_address', employee.permanent_address ? employee.permanent_address : "-");
                formData.append('phone_number', employee.phone_number ? employee.phone_number : "-");
                formData.append('mobile_number', employee.mobile_number ? employee.mobile_number : "-");
                formData.append('contact_person', employee.contact_person ? employee.contact_person : "-");
                formData.append('contact_number', employee.contact_number ? employee.contact_number : "-");
                formData.append('contact_relation', employee.contact_relation ? employee.contact_relation : "-");
                //Identification
                formData.append('sss_number', employee.sss_number ? employee.sss_number : "-");
                formData.append('phil_number', employee.phil_number ? employee.phil_number : "-");
                formData.append('hdmf', employee.hdmf ? employee.hdmf : "-");
                formData.append('tax_number', employee.tax_number ? employee.tax_number : "-");
                formData.append('tax_status', employee.tax_status ? employee.tax_status : "");

                //Approvers
                formData.append('head_approvers', this.approvers ? JSON.stringify(this.approvers) : "");
                
                //Dependents
                formData.append('dependents', this.dependents ? JSON.stringify(this.dependents) : "");

              
                axios.post(`/employee`, 
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }  
                )
                .then(response => {
                    this.loading = false;
                    this.resetForm();
                    Swal.fire({
                        title: 'Success!',
                        text: 'Employee saved Successfully',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Unable to add Employee. Check Entries and then try again.',
                        icon: 'error',
                        confirmButtonText: 'Okay'
                    })
                })
            },
            
            validateMartialStatus(){
                if(this.employee.marital_status){
                    if(this.employee.marital_status == "Married" || this.employee.marital_status == "Divorced"){
                        this.marital_attachment_validate = false;
                        this.marital_attachment_view = true;
                        
                    }else{
                        this.marital_attachment_validate = true;
                        this.marital_attachment_view = false;
                    }
                }else{
                    this.marital_attachment_validate = true;
                    this.marital_attachment_view = false;
                }     
            },
            fetchApprovers(){
                this.approvers = [];
            },
            fetchHeadApprovers(){
                 axios.get('/employee-head-approvers')
                .then(response => { 
                    this.employee_head_approvers = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchPositionApprovers(){
                 axios.get('/heads-all')
                .then(response => { 
                    this.employee_position_approvers = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            addApprover(){
                this.approvers.push({
                    id: "",
                    employee_head_id: "",
                    head_id: "",
                });
            },
            removeApprover: function(index) {
                this.approvers.splice(index, 1);
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
            removeDependent: function(index) {
                this.dependents.splice(index, 1);
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
            fetchDivisions(){
                axios.get('/division-options/'+this.employee.company_list)
                .then(response => { 
                    this.divisions = response.data;
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
        }
    }
</script>
