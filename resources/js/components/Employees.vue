<template>
<div>
    <div class="header bg-gradient-success pb-8 pt-5 pt-md-8 container-list"></div>
        <div class="container-fluid mt--9">
            <div class="header-body">
                <div class="row">
                    <div class="col-xl-12">
                         <div class="card shadow">
                                <div class="card-header border-0">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="mb-0">EMPLOYEES</h3>
                                            <small class="text-muted">List of all employees</small>
                                        </div> 
                                  
                                        <div class="col-xl-12 float-right text-right">

                                            <a v-if="user_access_rights.create == 'YES'" href="/add-employee" class="btn btn-sm btn-primary">Add Employee</a>

                                            <download-excel
                                                v-if="export_employees.length > 0 && user_access_rights.download_export == 'YES'"
                                                :data   = "export_employees"
                                                :fields = "json_fields"
                                                class   = "btn btn-sm btn-default"
                                                name    = "All HR Portal Employees.xls">
                                                    Export to excel (Active)
                                            </download-excel>

                                            <download-excel
                                                v-if="export_inactive_employees.length > 0 && user_access_rights.download_export == 'YES'"
                                                :data   = "export_inactive_employees"
                                                :fields = "json_fields"
                                                class   = "btn btn-sm btn-default"
                                                name    = "All HR Portal Inactive Employees.xls">
                                                    Export to excel (Inactive)
                                            </download-excel>
                                            
                                        </div> 
                                    </div>

                                    <div class="row align-items-center">
                                        <div class="col-xl-12 mb-2 mt-3 float-right">
                                            <div class="col-xl-6 mb-2 mt-3 float-left">
                                                <input v-if="user_access_rights.search == 'YES'" type="text" name="employee" class="form-control" placeholder="Search by Name" autocomplete="off" v-model="keywords" id="name">
                                                <input v-else type="text" name="employee" class="form-control" placeholder="Search by Name" disabled >
                                            </div>
                                        </div>

                                        <div class="col-xl-12 mb-2 mt-3 float-right" v-if="user_access_rights.roles">
                                            <div v-if="user_access_rights.roles[0].name == 'Administrator' || user_access_rights.roles[0].name == 'HR Staff'">
                                                <h4 v-if="user_access_rights.search == 'YES'">Filter by: </h4>
                                                <div class="col-xl-3 mb-2 float-left" v-if="user_access_rights.search == 'YES'">
                                                    <select class="form-control" v-model="company" id="company">
                                                        <option value="">Choose Company</option>
                                                        <option v-for="(company,v) in companies" v-bind:key="v" :value="company.id"> {{ company.name }}</option>
                                                    </select>
                                                </div>
                                                <div class="col-xl-3 mb-2 float-left" v-if="user_access_rights.search == 'YES'">
                                                    <select class="form-control" v-model="department" id="department">
                                                        <option value="">Choose Deparment</option>
                                                        <option v-for="(department,v) in departments" v-bind:key="v" :value="department.id"> {{ department.name }}</option>
                                                    </select>
                                                </div>
                                                <div class="col-xl-3 mb-2 float-left" v-if="user_access_rights.search == 'YES'">
                                                    <select class="form-control" v-model="location" id="location">
                                                        <option value="">Choose Location</option>
                                                        <option v-for="(location,v) in locations" v-bind:key="v" :value="location.id"> {{ location.name }}</option>
                                                    </select>
                                                </div> 
                                                <div class="col-xl-3 mb-2 float-left" v-if="user_access_rights.search == 'YES'">
                                                    <select class="form-control" v-model="employee_status" id="employee_status">
                                                        <option value="">Choose Status</option>
                                                        <option value="ACTIVE">Active</option>
                                                        <option value="INACTIVE">Inactive</option>
                                                    </select>
                                                </div> 
                                                <div class="col-xl-12" v-if="user_access_rights.search == 'YES'">
                                                    <button class="btn btn-sm btn-primary mt-3 float-right" @click="fetchFilterEmployee"> Apply Filter</button>
                                                </div> 
                                            </div>  
                                        </div>  
                                    </div>
                                </div>

                                <div class="table-responsive" style="min-height: 300px!important;">
                                    <!-- employees table -->
                                    <table class="table align-items-center table-flush mb-5">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Position</th>
                                                <th scope="col">Company</th>
                                                <th scope="col">Department</th>
                                                <th scope="col">Location</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="table_loading">
                                                <td colspan="15">
                                                    <content-placeholders>
                                                        <content-placeholders-text :lines="3" />
                                                    </content-placeholders>
                                                    <h4>Loading Employee Records.. Please wait a moment... </h4>
                                                </td>
                                            </tr>
                                             <tr v-for="(employee, u) in filteredQueues" v-bind:key="u">
                                                <td class="text-center">
                                                    <div class="dropdown">
                                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-pen" style="color:#5e72e4"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                            <div v-if="user_access_rights.roles">
                                                                <a v-if="user_access_rights.read == 'YES'" class="dropdown-item" data-toggle="modal" data-target="#editModal" style="cursor: pointer" @click="copyObject(employee)"><i class="fas fa-user-edit"></i> Edit</a>
                                                                <a v-if="user_access_rights.read == 'YES'" class="dropdown-item" data-toggle="modal" data-target="#orgChartModal"  style="cursor: pointer" @click="orgChartEmployee(employee)"><i class="fas fa-sitemap"></i> Organizational Chart</a>
                                                                <a v-if="user_access_rights.read == 'YES' && user_access_rights.roles[0].name == 'Administrator'" class="dropdown-item" :href="'/view_user_profile/' + employee.id" target="_blank" style="cursor: pointer;color:#525F7F;"><i class="fas fa-user ml-1"></i>  View Profile</a>    
                                                                <a v-if="user_access_rights.npa_request == 'YES'"  class="dropdown-item" data-toggle="modal" data-target="#transferModal"  style="cursor: pointer" @click="transferEmployee(employee)"><i class="fas fa-user-cog"></i> Transfer Company</a>
                                                                <a v-if="user_access_rights.npa_request == 'YES'" class="dropdown-item" data-toggle="modal" data-target="#npaRequestModal"  style="cursor: pointer" @click="npaRequest(employee)"><i class="fas fa-user-cog"></i> NPA Request</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ employee.id_number }}</td>
                                                <td>{{ employee.first_name + " "+ employee.last_name }}</td>
                                                <td>{{ employee.position }}</td>
                                                <td>{{ employee.companies[0] ? employee.companies[0].name : "" }}</td>
                                                <td>{{ employee.departments[0] ? employee.departments[0].name : ""  }}</td>
                                                <td>{{ employee.locations[0] ? employee.locations[0].name : '' }}</td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mb-3 mt-3 ml-1" v-if="filteredQueues.length ">
                                    <div class="col-6">
                                        <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                                            <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                                        <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                                    </div>
                                    <div class="col-6 text-right">
                                        <span class="mr-2">Filtered employee(s) : {{ filteredQueues.length }} </span><br>
                                        <span class="mr-2">Total employee(s) : {{ employees.length }}</span>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit employee Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-employee" role="document" style="width:80%!important;">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center" id="addCompanyLabel">EMPLOYEE INFORMATION</h2> 
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center mb-2">
                            <img :src="profile_image" @error="profileImageLoadError()" class="rounded-circle" style="width:150px;height:150px;border:2px dotted ;">
                        </div>
                        
                        <div class="nav-wrapper">
                            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                                <li class="nav-item" v-if="user_access_rights.personal_info == 'YES'">
                                    <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="fas fa-user-tie mr-2"></i>PERSONAL</a>
                                </li>
                                <li class="nav-item" v-if="user_access_rights.work_info == 'YES'">
                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="fas fa-briefcase mr-2"></i>WORK</a>
                                </li>
                                <li class="nav-item" v-if="user_access_rights.contact_info == 'YES'">
                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false"><i class="fas fa-address-book mr-2"></i>CONTACT</a>
                                </li>
                                <li class="nav-item" v-if="user_access_rights.identification_info == 'YES'">
                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-4-tab" data-toggle="tab" href="#tabs-icons-text-4" role="tab" aria-controls="tabs-icons-text-4" aria-selected="false"><i class="fas fa-id-card mr-2"></i>IDENTIFICATION</a>
                                </li>
                                <li class="nav-item" v-if="user_access_rights.employee_201_file == 'YES'">
                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-5-tab" data-toggle="tab" href="#tabs-icons-text-5" role="tab" aria-controls="tabs-icons-text-5" aria-selected="false"><i class="fas fa-folder mr-2"></i>201 FILES</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent">
                                    <!-- Personal -->
                                    <div v-if="user_access_rights.personal_info == 'YES'" class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
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
                                                    <img :src="signature_image" @error="signatureImageLoadError()" style="width:250px;height:150px;border-radius:6px;border:2px dotted;">
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
                                                    <input type="text"  class="form-control" v-model="employee_copied.first_name"   >
                                                    <span class="text-danger" v-if="errors.first_name">{{ errors.first_name[0] }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="role">Middle Name</label> 
                                                    <input type="text"  class="form-control" v-model="employee_copied.middle_name"   >
                                                    <span class="text-danger" v-if="errors.middle_name">{{ errors.middle_name[0] }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="role">Middle Initial</label> 
                                                    <input type="text"  class="form-control" v-model="employee_copied.middle_initial"   >
                                                    <span class="text-danger" v-if="errors.middle_initial">{{ errors.middle_initial[0] }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Last Name*</label> 
                                                    <input type="text"  class="form-control" v-model="employee_copied.last_name"   >
                                                    <span class="text-danger" v-if="errors.last_name">{{ errors.last_name[0] }}</span>
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Nickname</label> 
                                                    <input type="text" class="form-control" v-model="employee_copied.nick_name">
                                                    <span class="text-danger" v-if="errors.nick_name">{{ errors.nick_name[0] }}</span>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Suffix</label> 
                                                    <input type="text" class="form-control" v-model="employee_copied.name_suffix">
                                                    <span class="text-danger" v-if="errors.name_suffix">{{ errors.name_suffix[0] }}</span>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Marital Status*</label> 
                                                    <select class="form-control" v-model="employee_copied.marital_status" id="marital_status" @change="validateMaritalStatus()">
                                                        <option value="">Choose Marital Status</option>
                                                        <option v-for="(maritals) in marital_statuses" v-bind:key="maritals" :value="maritals"> {{ maritals }}</option>
                                                    </select>
                                                    <span class="text-danger" v-if="errors.marital_status">{{ errors.marital_status[0] }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Marital Attachment <a target="_blank" :href="'storage/marital_attachments/'+employee_copied.marital_status_attachment" v-if="employee_copied.marital_status_attachment"><span v-if="marital_attachment_view" class="badge badge-primary">View</span></a></label> 
                                                    <input type="file" :disabled="marital_attachment_validate" id="marital_file" class="form-control" ref="file" v-on:change="maritalHandleFileUpload()"/>
                                                    <span class="text-danger" v-if="errors.marital_status_attachment">{{ errors.marital_status_attachment[0] }}</span>
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Date of Birth*</label> 
                                                    <input type="date" class="form-control" v-model="employee_copied.birthdate">
                                                    <span class="text-danger" v-if="errors.birthdate">{{ errors.birthdate[0] }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Age <span class="text-danger" v-if="employee_copied.age">{{ employee_copied.ageRange }}</span></label> 
                                                    <input type="text" disabled class="form-control" v-model="employee_copied.age">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Gender*</label> 
                                                    <select class="form-control" v-model="employee_copied.gender" id="marital_status">
                                                        <option value="">Choose Gender</option>
                                                        <option value="MALE"> MALE</option>
                                                        <option value="FEMALE"> FEMALE</option>
                                                        <!-- <option value="UNKNOWN"> UNKNOWN</option> -->
                                                    </select>
                                                    <span class="text-danger" v-if="errors.gender">{{ errors.gender[0] }}</span>
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="role">Birthplace</label> 
                                                    <textarea  class="form-control" v-model="employee_copied.birthplace"></textarea>
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
                                                    <input type="text" class="form-control" v-model="employee_copied.school_graduated">
                                                    <span class="text-danger" v-if="errors.school_graduated">{{ errors.school_graduated[0] }}</span> 
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Course</label>
                                                    <input type="text" class="form-control" v-model="employee_copied.school_course">
                                                    <span class="text-danger" v-if="errors.school_course">{{ errors.school_course[0] }}</span> 
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Year Graduated</label>
                                                    <input type="text" class="form-control" v-model="employee_copied.school_year">
                                                    <span class="text-danger" v-if="errors.school_year">{{ errors.school_year[0] }}</span> 
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <h2 class="ml-0 pl-0">Vocational Course</h2>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Name of School</label>
                                                    <input type="text" class="form-control" v-model="employee_copied.vocational_graduated">
                                                    <span class="text-danger" v-if="errors.vocational_graduated">{{ errors.vocational_graduated[0] }}</span> 
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Course</label>
                                                    <input type="text" class="form-control" v-model="employee_copied.vocational_course">
                                                    <span class="text-danger" v-if="errors.vocational_course">{{ errors.vocational_course[0] }}</span> 
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Year Graduated</label>
                                                    <input type="text" class="form-control" v-model="employee_copied.vocational_year">
                                                    <span class="text-danger" v-if="errors.vocational_year">{{ errors.vocational_year[0] }}</span> 
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- Work -->
                                    <div v-if="user_access_rights.work_info == 'YES'" class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <div v-if="employee_copied.id_number">
                                                    <h4>Employee Number: 
                                                        {{ employee_copied.id_number }}
                                                    </h4>
                                                </div>
                                                <div v-else>
                                                    <h4>Employee Number: </h4>
                                                    <div class="custom-control custom-checkbox mb-3" v-if="employee_copied.status == 'Active'">
                                                        <input id="confidential" class="custom-control-input" v-model="employee_copied.generate_id_number" true-value="YES" false-value="NO" type="checkbox">
                                                        <label class="custom-control-label" for="confidential">Please check to generate ID Number.</label>
                                                    </div>   
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Company*</label>
                                                    <select class="form-control" v-model="employee_copied.company_list" id="company">
                                                        <option value="">Choose Company</option>
                                                        <option v-for="(company,b) in companies" v-bind:key="b" :value="company.id"> {{ company.name }}</option>
                                                    </select>

                                                    <span class="text-danger" v-if="errors.company_list">{{ errors.company_list[0] }}</span> 
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Cluster</label>
                                                    <select class="form-control" v-model="employee_copied.cluster" id="cluster">
                                                        <option value="">Choose Cluster</option>
                                                        <option v-for="(cluster) in clusters" v-bind:key="cluster" :value="cluster"> {{ cluster }}</option>
                                                    </select>

                                                    <span class="text-danger" v-if="errors.cluster">{{ errors.cluster[0] }}</span> 
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Department*</label>
                                                    <select class="form-control" v-model="employee_copied.department_list" id="department">
                                                        <option value="">Choose Department</option>
                                                        <option v-for="(department,b) in departments" v-bind:key="b" :value="department.id"> {{ department.name }}</option>
                                                    </select>
                                                    <span class="text-danger" v-if="errors.department_list">{{ errors.department_list[0] }}</span> 
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Section</label>  
                                                    <input type="text" class="form-control" v-model="employee_copied.division">
                                                    <span class="text-danger" v-if="errors.division">{{ errors.division[0] }}</span> 
                                                </div>
                                            </div>
                                            
                                            <!-- <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Employee Number</label>
                                                    <input type="text" class="form-control" v-model="employee_copied.employee_number">
                                                    <span class="text-danger" v-if="errors.employee_number">{{ errors.employee_number[0] }}</span>
                                                </div>
                                            </div> -->

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">ESS Employee No.</label>
                                                    <input type="text" class="form-control" v-model="employee_copied.ess_ee_number">
                                                    <span class="text-danger" v-if="errors.ess_ee_number">{{ errors.ess_ee_number[0] }}</span> 
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Position</label>
                                                    <input type="text" class="form-control" v-model="employee_copied.position">
                                                    <span class="text-danger" v-if="errors.position">{{ errors.position[0] }}</span> 
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Classification</label>
                                                    <select class="form-control" v-model="employee_copied.classification" id="department">
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
                                                    <input type="date" class="form-control" v-model="employee_copied.date_hired">
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
                                                    <select class="form-control" v-model="employee_copied.level" id="level">
                                                        <option value="">Choose Level</option>
                                                        <option v-for="(level) in levels" v-bind:key="level" :value="level"> {{ level }}</option>
                                                    </select>

                                                    <span class="text-danger" v-if="errors.level">{{ errors.level[0] }}</span> 
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Location*</label>
                                                    <select class="form-control" v-model="employee_copied.location_list" id="location">
                                                        <option value="">Choose Location</option>
                                                        <option v-for="(location,b) in locations" v-bind:key="b" :value="location.id"> {{ location.name }}</option>
                                                    </select>

                                                    <span class="text-danger" v-if="errors.location_list">{{ errors.location_list[0] }}</span> 
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Area</label>
                                                    <input type="text" class="form-control" v-model="employee_copied.area">
                                                    <span class="text-danger" v-if="errors.area">{{ errors.area[0] }}</span> 
                                                </div>
                                            </div>

                                            <div class="col-md-4" v-if="user_access_rights.monthly_basic_salary == 'YES'">
                                                <div class="form-group">
                                                    <label for="role">Monthly Basic Salary</label>
                                                    <input type="number" class="form-control" v-model="employee_copied.monthly_basic_salary">
                                                    <span class="text-danger" v-if="errors.monthly_basic_salary">{{ errors.monthly_basic_salary[0] }}</span> 
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Status</label>
                                                    <select class="form-control" v-model="employee_copied.status" id="status">
                                                        <option value="">Choose Status</option>
                                                        <option value="Active">Active</option> 
                                                        <option value="Inactive">Inactive</option> 
                                                        <option value="On-hold">On-hold</option> 
                                                        <option value="Notification">Notification</option> 
                                                        <option value="Deleted">Deleted</option> 
                                                    </select>
                                                    <span class="text-danger" v-if="errors.status">{{ errors.status[0] }}</span> 
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Date Regularized</label>
                                                    <input type="date" class="form-control" v-model="employee_copied.date_regularized">
                                                    <span class="text-danger" v-if="errors.date_regularized">{{ errors.date_regularized[0] }}</span> 
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Date Resigned</label>
                                                    <input type="date" class="form-control" v-model="employee_copied.date_resigned">
                                                    <span class="text-danger" v-if="errors.date_resigned">{{ errors.date_resigned[0] }}</span> 
                                                </div>
                                            </div>

                                            <div class="col-md-4" v-if="user_access_rights.bank_account_details == 'YES'">
                                                <div class="form-group">
                                                    <label for="role">Bank Name</label>
                                                    <input type="text" class="form-control" v-model="employee_copied.bank_name">
                                                    <span class="text-danger" v-if="errors.bank_name">{{ errors.bank_name[0] }}</span> 
                                                </div>
                                            </div>

                                            <div class="col-md-4" v-if="user_access_rights.bank_account_details == 'YES'">
                                                <div class="form-group">
                                                    <label for="role">Bank Account Number</label>
                                                    <input type="text" class="form-control" v-model="employee_copied.bank_account_number">
                                                    <span class="text-danger" v-if="errors.bank_account_number">{{ errors.bank_account_number[0] }}</span> 
                                                </div>
                                            </div>

                                            
                                            <div class="col-md-12">
                                                <div class="col-md-6 mb-2"  style="border:1px solid;border-radius:5px;">
                                                    <div class="form-group mt-2">
                                                        <label for="confidential">Set as Confidential Employee</label>
                                                        <div class="custom-control custom-checkbox mb-3">
                                                            <input id="confidential" class="custom-control-input" v-model="employee_copied.confidential" true-value="YES" false-value="NO" type="checkbox">
                                                            <label class="custom-control-label" for="confidential">Confidential Employee (Ex. President CEO, Executives, etc.)</label>
                                                        </div>

                                                        <span class="text-danger" v-if="errors.confidential">{{ errors.confidential[0] }}</span> 
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-4 mb-4" v-if="user_access_rights.monthly_basic_salary == 'YES'">
                                                <h4>Salary Record</h4>
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered" id="tab_assign_head">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">
                                                                    Date
                                                                </th>
                                                                <th class="text-center">
                                                                    Old Salary
                                                                </th>
                                                                <th class="text-center">
                                                                    New Salary
                                                                </th>
                                                                <th class="text-center">
                                                                    Reason
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(record,index) in salary_records" v-bind:key="index">
                                                                <td>
                                                                    {{ record.salary_date }}
                                                                </td>
                                                                <td>
                                                                    {{ record.old_salary }}
                                                                </td>
                                                                <td>
                                                                    {{ record.new_salary }}
                                                                </td>
                                                                <td>
                                                                    {{ record.reason }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <h4>System Approvers</h4>
                                                <button type="button" class="btn btn-success btn-sm mb-2" style="float: right;" @click="fetchApprovers()"><i class="fas fa-redo" title="Refresh Approver"></i></button>
                                                <button type="button" class="btn btn-primary btn-sm mb-2" style="float: right;" @click="addApprover()">Add Row</button>
                                                 <div class="table-responsive">
                                                    <table class="table table-hover table-bordered" id="tab_assign_head">
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

                                            <!-- First Level Under            -->
                                            <div class="col-md-12" v-if="employee_unders.length > 0">
                                                <hr>
                                                <h4 class="mt-3">TRANSFER ({{employee_unders.length}}) FIRST LEVEL EMPLOYEES UNDER - {{employee_copied.first_name + ' ' + employee_copied.last_name}}</h4>
                                        
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="role" class="text-warning">Select New Approver</label>
                                                        <select class="form-control" v-model="new_approver_under" id="new_approver_under">
                                                            <option value="">Choose Approver</option>
                                                            <option v-for="(approver,b) in employee_head_approvers" v-bind:key="b" :value="approver.id"> {{ approver.last_name + " " + approver.first_name }}</option>
                                                        </select>
                                                        <span class="text-danger" v-if="errors.new_approver_under">{{ errors.new_approver_under[0] }}</span> 
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- Contact -->
                                    <div v-if="user_access_rights.contact_info == 'YES'" class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
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

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Contact Person</label>
                                                    <input type="text" class="form-control" v-model="employee_copied.contact_person">
                                                    <span class="text-danger" v-if="errors.contact_person">{{ errors.contact_person[0] }}</span> 
                                                </div>
                                            </div>    

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Contact Relation</label>
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
                                                                <th class="text-center">
                                                                    Status
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
                                                                         <!-- <option value="UNKNOWN"> UNKNOWN</option> -->
                                                                    </select> 
                                                                </td>
                                                                <td>
                                                                    <input type="date" class="form-control" v-model="row.bdate">
                                                                </td>
                                                                <td>
                                                                    <select class="form-control" v-model="row.relation" id="relation">
                                                                        <option value="">Choose Relation</option>
                                                                        <option value="MOTHER">MOTHER</option>
                                                                        <option value="FATHER">FATHER</option>
                                                                        <option value="BROTHER">BROTHER</option>
                                                                        <option value="SISTER">SISTER</option>
                                                                        <option value="SPOUSE">SPOUSE</option>
                                                                        <option value="CHILD">CHILD</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                  <select class="form-control" v-model="row.dependent_status" id="dependent_status">
                                                                        <option value="">Choose Status</option>
                                                                        <option value="NEW">NEW</option>
                                                                        <option value="RENEW">RENEW</option>
                                                                    </select>  
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
                                    </div>
                                    <!-- Identification -->
                                    <div v-if="user_access_rights.identification_info == 'YES'" class="tab-pane fade" id="tabs-icons-text-4" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab">
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="role">Tax Status*</label>
                                                    <select class="form-control" v-model="employee_copied.tax_status" id="tax_status">
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
                                    <!-- 201 Files -->
                                    <div v-if="user_access_rights.employee_201_file == 'YES'" class="tab-pane fade" id="tabs-icons-text-5" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab">
                                        <div class="row">
                                           <div class="col-md-12">
                                               <h5>201 Files</h5>
                                                <div class="form-group">
                                                    <input type="file" multiple="multiple" id="documents_201_files_attachments" class="form-control 201-files-attachments-edit" @change="upload201FilesAttachments" placeholder="Attach file"><br>
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
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="(attachment, index) in employee_201_files_attachments" v-bind:key="index">
                                                                <td>{{ index + 1 }}</td>
                                                                <td> <a :href="'storage/employee_201_files/'+employee_copied.id +'/'+attachment.file" target="_blank"><u>{{ attachment.file }}</u></a></td>
                                                                <!-- <td class="text-center"> <a target="_blank" :href="'storage/dependents_attachments/'+attachment.file"><span class="btn btn-info btn-sm mt-2"> View </span></a></td> -->
                                                                <td class="text-center"> <button type="button" class="btn btn-danger btn-sm mt-2" style="float:right;"  @click="remove201FilesAttachment(index,attachment)">Remove</button></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>     
                                            </div>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 text-center mt-3 pt-3 pb-3" style="background-color:#f4f5f7;border-radius:5px;" v-if="user_access_rights.edit == 'YES'">
                            <h4>Terms and Conditions</h4>
                            <div class="custom-control custom-checkbox mb-3">
                                <input class="custom-control-input" id="terms_conditions" v-model="termsConditions" @change="termsConditionsValidate" type="checkbox">
                                <label class="custom-control-label" for="terms_conditions">I certify that the information provided is true and correct to the best of my knowledge.</label>
                            </div>     
                        </div>
                    </div>
                  
                    <div class="modal-footer">
                        <button v-if="user_access_rights.edit == 'YES' || user_access_rights.personal_info_edit == 'YES' || user_access_rights.work_info_edit == 'YES' || user_access_rights.contact_info_edit == 'YES' || user_access_rights.identification_info_edit == 'YES'" id="edit_btn" :disabled="saveEmployee" type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="updateEmployee(employee_copied)" style="width:150px;">Update</button>
                        <button v-else id="edit_btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" disabled>Unable to Update</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transfer employee Modal -->
        <div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-employee" role="document" style="width:80%!important;">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-left">Transfer Employee</h2> 
                    </div>
                    <div class="modal-body">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1 class="col-12 text-left">{{ transferEmployeeDetails.first_name + ' ' + transferEmployeeDetails.last_name }}</h1>

                                <h4 class="col-6 text-left mt--2 text-default">{{ transferEmployeeDetails.id_number ? transferEmployeeDetails.id_number : "" }}</h4>
                                <h4 class="col-6 text-left mt--2 text-info">{{ transferEmployeeDetails.position ? transferEmployeeDetails.position : "" }}</h4>

                                <h4 class="col-6 text-left mt--2 text-danger" v-if="transferEmployeeDetails.departments">{{ transferEmployeeDetails.departments[0] ? transferEmployeeDetails.departments[0].name : "" }}</h4>
                                <h4 class="col-6 text-left mt--2 text-success" v-if="transferEmployeeDetails.companies">{{ transferEmployeeDetails.companies[0] ? transferEmployeeDetails.companies[0].name : "" }}</h4>
                                <h4 class="col-6 text-left mt--2 text-warning" v-if="transferEmployeeDetails.locations">{{ transferEmployeeDetails.locations[0] ? transferEmployeeDetails.locations[0].name : "" }}</h4>
                               
                               <div class="row mt--10 mb-3">
                                    <div class="col-md-12">
                                        <download-excel
                                            v-if="transferLogsList.length"
                                            :data   = "transferLogsList"
                                            :fields = "transfer_log_fields"
                                            style="float: right;"
                                            class   = "btn btn-sm btn-success"
                                            name    = "Transfer Logs.xls">
                                                EXCEL
                                        </download-excel>
                                        <a v-if="transferLogsList.length" :href="'pdf_employee_transfer_logs/' + transferEmployeeDetails.id" target="_blank" type="button" class="btn btn-warning btn-sm mb-2 ml-2 mt--10 " style="float: right;">PDF</a>
                                        <button v-if="viewTransferLogsList" type="button" class="btn btn-danger btn-sm mb-2 ml-2 mt--10" style="float: right;" @click="closeTransferEmployeeLogs()">Close</button>
                                        <button type="button" class="btn btn-primary btn-sm mb-2 ml-2 mt--10 " style="float: right;" @click="viewTransferEmployeeLogs()">View Transfer History/Logs</button>
                                   
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive" v-if="viewTransferLogsList">
                                            <table class="table table-hover" id="tab_transfer_logs">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">
                                                            #
                                                        </th>
                                                        <th class="text-center">
                                                            FROM
                                                        </th>
                                                        <th class="text-center">
                                                            TO
                                                        </th>
                                                        <th class="text-center">
                                                            Supporting Documents
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(log,index) in transferLogsList" v-bind:key="index">
                                                        <td>{{index+1}}</td>
                                                        <td>
                                                            <span>Company: <strong>{{ log.previous_company ? log.previous_company.name : ""}}</strong></span><br>
                                                            <span>ID Number: {{log.previous_id_number}}</span><br>
                                                            <span>Date Hired: {{log.previous_date_hired}}</span><br>
                                                            <span>Position: {{log.previous_position}}</span><br>
                                                            <span>Department: {{ log.previous_department ? log.previous_department.name : ""}}</span><br>
                                                            <span>Location: {{log.previous_location ? log.previous_location.name : ""}}</span><br>
                                                            
                                                        </td>
                                                        <td>
                                                            <span>Company: <strong>{{log.new_company ? log.new_company.name : ""}}</strong></span><br>
                                                            <span>ID Number: {{log.new_id_number}}</span><br>
                                                            <span>Date Hired: {{log.new_date_hired}}</span><br>
                                                            <span>Position: {{log.new_position}}</span><br>
                                                            <span>Department: {{log.new_department ? log.new_department.name : ""}}</span><br>
                                                            <span>Location: {{log.new_location ? log.new_location.name : ""}}</span><br>
                                                            
                                                        </td>
                                                        <td>
                                                            <div v-if="log.employee_transfer_attachments.length > 0">
                                                                <span>{{log.employee_transfer_attachments.length}} Attachments Found</span><br>
                                                                <span v-for="(attachment,index) in log.employee_transfer_attachments" v-bind:key="index">
                                                                    <a :href="'storage/' + attachment.path" target="_blank"> {{attachment.filename}} </a><br>
                                                                </span>
                                                            </div>
                                                            <div v-else>
                                                                <span>0 Attachments</span>
                                                            </div>
                                                            
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h4>Fill up fields to transfer employee.</h4>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Company*</label>
                                            <select class="form-control" v-model="transfer_employee.company_list" id="company">
                                                <option value="">Choose Company</option>
                                                <option v-for="(company,b) in companies" v-bind:key="b" :value="company.id"> {{ company.name }}</option>
                                            </select>
                                            <span class="text-danger" v-if="transfer_errors.company_list">{{ transfer_errors.company_list[0] }}</span> 
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Department*</label>
                                            <select class="form-control" v-model="transfer_employee.department_list" id="department">
                                                <option value="">Choose Department</option>
                                                <option v-for="(department,b) in departments" v-bind:key="b" :value="department.id"> {{ department.name }}</option>
                                            </select>

                                            <span class="text-danger" v-if="transfer_errors.department_list">{{ transfer_errors.department_list[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Location*</label>
                                            <select class="form-control" v-model="transfer_employee.location_list" id="location">
                                                <option value="">Choose Location</option>
                                                <option v-for="(location,b) in locations" v-bind:key="b" :value="location.id"> {{ location.name }}</option>
                                            </select>

                                            <span class="text-danger" v-if="transfer_errors.location_list">{{ transfer_errors.location_list[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Cluster</label>
                                            <input type="text" class="form-control" v-model="transfer_employee.division">
                                        </div>

                                        <span class="text-danger" v-if="transfer_errors.division">{{ transfer_errors.division[0] }}</span>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Position*</label>
                                            <input type="text" class="form-control" v-model="transfer_employee.position">

                                            <span class="text-danger" v-if="transfer_errors.position">{{ transfer_errors.position[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Date Hired*</label>
                                            <input type="date" class="form-control" v-model="transfer_employee.date_hired">
                                            <span class="text-danger" v-if="transfer_errors.date_hired">{{ transfer_errors.date_hired[0] }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <h4>New System Approvers</h4>
                        
                                        <button type="button" class="btn btn-primary btn-sm mb-2" style="float: right;" @click="addTransferApprover()">Add Row</button>
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
                                                    <tr v-for="(row,index) in transfer_approvers" v-bind:key="index">
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
                                                            <button type="button" class="btn btn-success btn-sm mt-2" style="float:right;" @click="removeTransferApprover(index)">Remove</button>  
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>    
                                        </div>    
                                    </div>

                                    <div class="col-md-12">
                                        <h4 class="mt-3">Supporting Documents*</h4>
                                        <div class="form-group">
                                            <input type="file" multiple="multiple" id="transfer_supporting_documents" class="form-control transfer-supporting-documents-edit" @change="uploadTransferSupportingDocuments" placeholder="Attach file"><br>
                                        </div>
                                    </div>
                                   
                                </div>

                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="save_trasnfer_btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="saveTransferEmployee(transfer_employee)" style="width:150px;">Transfer</button>
                        <button id="close_transfer_btn" type="button" class="btn btn-danger btn-round btn-fill btn-lg" data-dismiss="modal" aria-label="Close" style="width:150px;">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- NPA Request Modal -->
        <div class="modal fade" id="npaRequestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-employee" role="document" style="width:80%!important;">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-left">Submit NPA Requests</h2> 
                    </div>
                    <div class="modal-body">
                        <div class="card shadow">
                            <div class="card-body">
                                <h1 class="col-12">{{ npaRequestDetails.first_name + ' ' + npaRequestDetails.last_name }}</h1>
                               
                                    <small class="col-6 text-left mt--2 text-default text-center">{{ npaRequestDetails.id_number ? npaRequestDetails.id_number : "" }}</small>/
                                    <small class="col-6 text-left mt--2 text-info text-center">{{ npaRequestDetails.position ? npaRequestDetails.position : "" }}</small>/
                                    <small class="col-6 text-left mt--2 text-danger text-center" v-if="npaRequestDetails.departments">{{ npaRequestDetails.departments[0] ? npaRequestDetails.departments[0].name : "" }}</small>/
                                    <small class="col-6 text-left mt--2 text-success text-center" v-if="npaRequestDetails.companies">{{ npaRequestDetails.companies[0] ? npaRequestDetails.companies[0].name : "" }}</small>/
                                    <small class="col-6 text-left mt--2 text-warning text-center" v-if="npaRequestDetails.locations">{{ npaRequestDetails.locations[0] ? npaRequestDetails.locations[0].name : "" }}</small>
                                
                                <hr>
                                <h2 class="col-12 text-center">NOTICE OF PERSONNEL ACTION </h2> 

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Subject</label>
                                            <select class="form-control" v-model="npa_request.subject" id="subject">
                                                <option value="">TYPE OF EMPLOYEE MOVEMENT</option>
                                                <option value="REGULARIZATION">REGULARIZATION</option>
                                                <option value="PROMOTION / UPGRADE">PROMOTION / UPGRADE </option>
                                                <option value="CHANGE IN POSITION TITLE">CHANGE IN POSITION TITLE</option>
                                                <option value="CHANGE IN DEPARTMENT">CHANGE IN DEPARTMENT</option>
                                                <option value="CHANGE IN COMPANY">CHANGE IN COMPANY</option>
                                                <option value="CHANGE IN LOCATION">CHANGE IN LOCATION</option>
                                                <option value="CHANGE IN IMMEDIATE SUPERIOR/MANAGER">CHANGE IN IMMEDIATE SUPERIOR/MANAGER</option>
                                                <option value="CHANGE IN SALARY">CHANGE IN SALARY</option>
                                            </select>
                                            <span class="text-danger" v-if="npa_request_errors.subject">{{ npa_request_errors.subject[0] }}</span>
                                        </div>

                                        

                                    </div>
                                    <div class="col-md-12">
                                        This is to inform you of the following changes in your employment:

                                         <table class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">
                                                        
                                                    </th>
                                                    <th class="text-center">
                                                        FROM
                                                    </th>
                                                    <th class="text-center">
                                                        TO
                                                    </th>
                                                </tr>    
                                             </thead>
                                             <tbody>
                                                 <tr>
                                                    <td>
                                                        <h4>Type of Movement</h4>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" v-model="npa_request.from_type_of_movement" id="subject">
                                                            <option value="">TYPE OF EMPLOYEE MOVEMENT</option>
                                                            <option value="REGULARIZATION">REGULARIZATION</option>
                                                            <option value="PROMOTION / UPGRADE">PROMOTION / UPGRADE </option>
                                                            <option value="CHANGE IN POSITION TITLE">CHANGE IN POSITION TITLE</option>
                                                            <option value="CHANGE IN DEPARTMENT">CHANGE IN DEPARTMENT</option>
                                                            <option value="CHANGE IN COMPANY">CHANGE IN COMPANY</option>
                                                            <option value="CHANGE IN LOCATION">CHANGE IN LOCATION</option>
                                                            <option value="CHANGE IN IMMEDIATE SUPERIOR/MANAGER">CHANGE IN IMMEDIATE SUPERIOR/MANAGER</option>
                                                            <option value="CHANGE IN SALARY">CHANGE IN SALARY</option>
                                                        </select>
                                                        <span class="text-danger" v-if="npa_request_errors.from_type_of_movement">{{ npa_request_errors.from_type_of_movement[0] }}</span> 
                                                    </td>
                                                    <td>
                                                        <select class="form-control" v-model="npa_request.to_type_of_movement" id="subject">
                                                            <option value="">TYPE OF EMPLOYEE MOVEMENT</option>
                                                            <option value="REGULARIZATION">REGULARIZATION</option>
                                                            <option value="PROMOTION / UPGRADE">PROMOTION / UPGRADE </option>
                                                            <option value="CHANGE IN POSITION TITLE">CHANGE IN POSITION TITLE</option>
                                                            <option value="CHANGE IN DEPARTMENT">CHANGE IN DEPARTMENT</option>
                                                            <option value="CHANGE IN COMPANY">CHANGE IN COMPANY</option>
                                                            <option value="CHANGE IN LOCATION">CHANGE IN LOCATION</option>
                                                            <option value="CHANGE IN IMMEDIATE SUPERIOR/MANAGER">CHANGE IN IMMEDIATE SUPERIOR/MANAGER</option>
                                                            <option value="CHANGE IN SALARY">CHANGE IN SALARY</option>
                                                        </select>
                                                        <span class="text-danger" v-if="npa_request_errors.to_type_of_movement">{{ npa_request_errors.to_type_of_movement[0] }}</span> 
                                                    </td>
                                                 </tr>
                                                 <tr>
                                                    <td>
                                                        <h4>Company</h4>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" v-model="npa_request.from_company" id="company" disabled="disabled">
                                                            <option value="">Choose Company</option>
                                                            <option v-for="(company,b) in companies" v-bind:key="b" :value="company.id"> {{ company.name }}</option>
                                                        </select>
                                                        <span class="text-danger" v-if="npa_request_errors.from_company">{{ npa_request_errors.from_company[0] }}</span> 
                                                    </td>
                                                    <td>
                                                        <select class="form-control" v-model="npa_request.to_company" id="company">
                                                            <option value="">Choose Company</option>
                                                            <option v-for="(company,b) in companies" v-bind:key="b" :value="company.id"> {{ company.name }}</option>
                                                        </select>
                                                        <span class="text-danger" v-if="npa_request_errors.to_company">{{ npa_request_errors.to_company[0] }}</span> 
                                                    </td>
                                                 </tr>
                                                 <tr>
                                                    <td>
                                                        <h4>Location</h4>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" v-model="npa_request.from_location" id="location" disabled="disabled">
                                                            <option value="">Choose Location</option>
                                                            <option v-for="(location,b) in locations" v-bind:key="b" :value="location.id"> {{ location.name }}</option>
                                                        </select>
                                                        <span class="text-danger" v-if="npa_request_errors.from_location">{{ npa_request_errors.from_location[0] }}</span> 
                                                    </td>
                                                    <td>
                                                        <select class="form-control" v-model="npa_request.to_location" id="location">
                                                            <option value="">Choose Location</option>
                                                            <option v-for="(location,b) in locations" v-bind:key="b" :value="location.id"> {{ location.name }}</option>
                                                        </select>
                                                        <span class="text-danger" v-if="npa_request_errors.to_location">{{ npa_request_errors.to_location[0] }}</span> 
                                                    </td>
                                                 </tr>
                                                 <tr>
                                                    <td>
                                                        <h4>Position Title </h4>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" v-model="npa_request.from_position_title" disabled="disabled">
                                                        <span class="text-danger" v-if="npa_request_errors.from_position_title">{{ npa_request_errors.from_position_title[0] }}</span> 
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" v-model="npa_request.to_position_title">
                                                        <span class="text-danger" v-if="npa_request_errors.to_position_title">{{ npa_request_errors.to_position_title[0] }}</span> 
                                                    </td>
                                                 </tr>
                                                 <tr>
                                                    <td>
                                                        <h4>Date Hired</h4>
                                                    </td>
                                                    <td colspan="2">
                                                        <input type="date" class="form-control" v-model="npa_request.date_hired">
                                                    </td> 
                                                 </tr>
                                                 <tr>
                                                    <td>
                                                        <h4>Immediate Manager</h4>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" v-model="npa_request.from_immediate_manager" id="location" disabled="disabled">
                                                            <option value="">Choose Immediate Manager</option>
                                                            <option v-for="(approver,b) in employee_head_approvers" v-bind:key="b" :value="approver.id"> {{ approver.last_name + " " + approver.first_name }}</option>
                                                        </select>
                                                        <span class="text-danger" v-if="npa_request_errors.from_immediate_manager">{{ npa_request_errors.from_immediate_manager[0] }}</span> 
                                                    </td>
                                                    <td>
                                                        <select class="form-control" v-model="npa_request.to_immediate_manager" id="location">
                                                            <option value="">Choose Immediate Manager</option>
                                                            <option v-for="(approver,b) in employee_head_approvers" v-bind:key="b" :value="approver.id"> {{ approver.last_name + " " + approver.first_name }}</option>
                                                        </select>
                                                        <span class="text-danger" v-if="npa_request_errors.to_immediate_manager">{{ npa_request_errors.to_immediate_manager[0] }}</span> 
                                                    </td>
                                                 </tr>
                                                 <tr>
                                                    <td>
                                                        <h4>Department</h4>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" v-model="npa_request.from_department" id="department" disabled="disabled">
                                                            <option value="">Choose Department</option>
                                                            <option v-for="(department,b) in departments" v-bind:key="b" :value="department.id"> {{ department.name }}</option>
                                                        </select>
                                                        <span class="text-danger" v-if="npa_request_errors.from_department">{{ npa_request_errors.from_department[0] }}</span> 
                                                    </td>
                                                    <td>
                                                        <select class="form-control" v-model="npa_request.to_department" id="department">
                                                            <option value="">Choose Department</option>
                                                            <option v-for="(department,b) in departments" v-bind:key="b" :value="department.id"> {{ department.name }}</option>
                                                        </select>
                                                        <span class="text-danger" v-if="npa_request_errors.to_department">{{ npa_request_errors.to_department[0] }}</span> 
                                                    </td>
                                                 </tr>
                                                 <tr>
                                                    <td>
                                                        <h4>Effecitvity Date</h4>
                                                    </td>
                                                    <td colspan="2">
                                                        <input type="date" class="form-control" v-model="npa_request.effectivity_date">
                                                    </td>
                                                 </tr>
                                                 <tr>
                                                    <td>
                                                        <h4>Monthly Basic Salary</h4>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" v-model="npa_request.from_monthly_basic_salary">
                                                        <span class="text-danger" v-if="npa_request_errors.from_monthly_basic_salary">{{ npa_request_errors.from_monthly_basic_salary[0] }}</span> 
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" v-model="npa_request.to_monthly_basic_salary">
                                                        <span class="text-danger" v-if="npa_request_errors.to_monthly_basic_salary">{{ npa_request_errors.to_monthly_basic_salary[0] }}</span> 
                                                    </td>
                                                 </tr>
                                                 <tr>
                                                    <td>
                                                        <h4>Prepared By</h4>
                                                    </td>
                                                    <td colspan="2">
                                                        <select class="form-control" v-model="npa_request.prepared_by" id="prepared_by">
                                                            <option value="">Choose Prepared By</option>
                                                            <option v-for="(approver,b) in hr_employees" v-bind:key="b" :value="approver.id"> {{ approver.last_name + " " + approver.first_name }}</option>
                                                        </select>
                                                        <span class="text-danger" v-if="npa_request_errors.prepared_by">{{ npa_request_errors.prepared_by[0] }}</span> 
                                                    </td>
                                                 </tr>
                                                 <tr>
                                                    <td>
                                                        <h4>Recommended By</h4>
                                                    </td>
                                                    <td colspan="2">
                                                        <select class="form-control" v-model="npa_request.recommended_by" id="recommended_by">
                                                            <option value="">Choose Recommended By</option>
                                                            <option v-for="(approver,b) in hr_employees" v-bind:key="b" :value="approver.id"> {{ approver.last_name + " " + approver.first_name }}</option>
                                                        </select>
                                                        <span class="text-danger" v-if="npa_request_errors.recommended_by">{{ npa_request_errors.recommended_by[0] }}</span> 
                                                    </td>
                                                 </tr>
                                                 <tr>
                                                    <td>
                                                        <h4>Approved By</h4>
                                                    </td>
                                                    <td colspan="2">
                                                        <select class="form-control" v-model="npa_request.approved_by" id="approved_by">
                                                            <option value="">Choose Approved By</option>
                                                            <option v-for="(approver,b) in hr_employees" v-bind:key="b" :value="approver.id"> {{ approver.last_name + " " + approver.first_name }}</option>
                                                        </select>
                                                        <span class="text-danger" v-if="npa_request_errors.approved_by">{{ npa_request_errors.approved_by[0] }}</span> 
                                                    </td>
                                                 </tr>
                                                 <tr>
                                                    <td>
                                                        <h4>BU Head</h4>
                                                    </td>
                                                    <td colspan="2">
                                                        <select class="form-control" v-model="npa_request.bu_head" id="approved_by">
                                                            <option value="">Choose BU Head</option>
                                                            <option v-for="(approver,b) in bu_heads" v-bind:key="b" :value="approver.id"> {{ approver.last_name + " " + approver.first_name }}</option>
                                                        </select>
                                                        <span class="text-danger" v-if="npa_request_errors.bu_head">{{ npa_request_errors.bu_head[0] }}</span> 
                                                    </td>
                                                 </tr>
                                             </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-5">
                                        <h4>NPA Request List</h4>

                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">
                                                            Date  
                                                        </th>
                                                        <th class="text-center">
                                                            Subject
                                                        </th>
                                                        <th class="text-center">
                                                            Status
                                                        </th>
                                                        <th class="text-center">
                                                            HR Recommended Status
                                                        </th>
                                                        <th class="text-center">
                                                            HR Approved Status
                                                        </th>
                                                        <th class="text-center">
                                                            BU Head Status
                                                        </th>
                                                        <th class="text-center">
                                                            Action
                                                        </th>
                                                        <th class="text-center">
                                                            Print
                                                        </th>
                                                    </tr>    
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(npa_request, u) in npaRequestLists" v-bind:key="u">
                                                        <td>
                                                            {{ npa_request.created_at}}
                                                        </td>
                                                        <td>
                                                            {{ npa_request.subject}}
                                                        </td>
                                                        <td>
                                                            {{ npa_request.status}}
                                                        </td>
                                                        <td>
                                                            <div v-if="user_access_rights.roles">
                                                                <div v-if="user_access_rights.roles[0].name == 'Administrator' || npa_request.recommended_by == user_access_rights.employee_id">
                                                                    <button class="btn btn-sm btn-primary" v-if="npa_request.recommended_by_status == 'Approved'" disabled>Approved</button>
                                                                    <button class="btn btn-sm btn-success" v-else @click="approveByHRRecommend(npa_request)">Approve</button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div v-if="user_access_rights.roles">
                                                                <div v-if="user_access_rights.roles[0].name == 'Administrator' || npa_request.approved_by == user_access_rights.employee_id">
                                                                    <button class="btn btn-sm btn-primary" v-if="npa_request.approved_by_status == 'Approved'" disabled>Approved</button>
                                                                    <button class="btn btn-sm btn-success" v-else @click="approveByHRApprover(npa_request)">Approve</button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div v-if="user_access_rights.roles">
                                                                <div v-if="user_access_rights.roles[0].name == 'Administrator' || npa_request.bu_head == user_access_rights.employee_id">
                                                                    <button class="btn btn-sm btn-primary" v-if="npa_request.bu_head_status == 'Approved'" disabled>Approved</button>
                                                                    <button class="btn btn-sm btn-success" v-else @click="approveByBUHead(npa_request)">Approve</button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div v-if="user_access_rights.roles">
                                                                <button type="button" class="btn btn-sm btn-primary" @click="viewNPARequest(npa_request)">View</button>
                                                                <button v-if="npa_request.status == 'Pending' || user_access_rights.roles[0].name == 'Administrator'" type="button" class="btn btn-sm btn-warning" @click="editNPARequest(npa_request)">Edit</button>
                                                                <button v-if="npa_request.status == 'Pending' || user_access_rights.roles[0].name == 'Administrator'" type="button" class="btn btn-sm btn-danger" @click="deleteNPARequest(npa_request)">Delete</button>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div v-if="user_access_rights.roles">
                                                                <a :href="'print-employee-npa-requests/' + npa_request.id" target="_blank" type="button" class="btn btn-sm btn-info">Print</a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                         <div v-if="user_access_rights.roles">
                            <button v-if="npa_request_edit && user_access_rights.roles[0].name == 'Administrator'" id="save_npa_request_btn" type="button" class="btn btn-primary btn-round btn-fill btn-lg" @click="updateNPARequest(npa_request)" style="width:150px;">Update Request</button>
                            <button v-else id="save_npa_request_btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="saveNPARequest(npa_request)" style="width:150px;">Send Request</button>
                            <button v-if="npa_request_edit" type="button" class="btn btn-warning btn-round btn-fill btn-lg"  style="width:150px;" @click="cancelEditNPARequest">Cancel Edit</button>
                            <button id="close_npa_request_btn" type="button" class="btn btn-danger btn-round btn-fill btn-lg" data-dismiss="modal" aria-label="Close" style="width:150px;">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Org Chart employee Modal -->
        <div class="modal fade" id="orgChartModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-employee" role="document" style="width:80%!important;">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-left">Organizational Chart</h2> 
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12 text-center">
                            <iframe id="id-frame" :src="org_chart_src" frameborder="0" height="700px" width="100%"></iframe>
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
    import JsonExcel from 'vue-json-excel'
    import moment from 'moment';

    export default {
        components: { 'downloadExcel': JsonExcel,loader },
        data(){
            return {
                user_access_rights: [],
                employees: [],
                employee: [],
                employee_copied: [],

                //Transfer
                transfer_employee: [],
                transferEmployeeDetails: [],
                transfer_approvers : [],
                viewTransferLogsList : false,
                transferLogsList : [],
                transfer_supporting_documents : [],

                copied_role: [],
                roles: [],
                transfer_errors: [],
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: "",
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
                clusters : [],
                saveEmployee: true,
                termsConditions: false,
                employee_head_approvers: [],
                employee_position_approvers: [],
                approvers : [],
                deletedApprover : [],
                dependents : [],
                deletedDependent : [],
                table_loading : true,
                company : '',
                location : '',
                department : '',
                employee_status : '',
                org_chart_src : '',
                dependent_attachments : [],
                employee_dependent_attachments: [],
                deleted_dependent_attachments: [],
                dependent_attachments: [],
                fileSize: 0,
                export_employees : [],
                export_inactive_employees : [],
                json_fields: {
                    //Personal Info
                    'USER ID': 'user_id',
                    'ID NUMBER': 'id_number',
                    'FIRST NAME': 'first_name',
                    'MIDDLE NAME': 'middle_name',
                    'MIDDLE INITIAL': 'middle_initial',
                    'NICK NAME': 'nick_name',
                    'LAST NAME': 'last_name',
                    'FULL NAME': 'full_name',
                    'NAME SUFFIX': 'name_suffix',
                    'MARITAL STATUS' : 'marital_status',
                    'DATE OF BIRTH' : 'birthdate',
                    'EMPLOYEE AGE' : {
                        callback: (value) => {
                            if(value.age){
                                return value.age.toString();
                            }else{
                                return '';
                            }
                        }
                    },
                    'AGE RANGE' : 'age_range',
                    'GENDER' : 'gender',
                    'BIRTHPLACE' : 'birthplace',

                    //Educational Background
                    'TERTIARY (NAME OF SCHOOL)' : 'school_graduated',
                    'TERTIARY (COURSE)' : 'school_course',
                    'TERTIARY (YEAR GRADUATED)' : 'school_year',

                    'VOCATIONAL COURSE (NAME OF SCHOOL)' : 'vocational_graduated',
                    'VOCATIONAL COURSE (COURSE)' : 'vocational_course',
                    'VOCATIONAL COURSE (YEAR GRADUATED)' : 'vocational_year',


                    //Work Info
                    'COMPANY': 'company',
                    'CLUSTER': 'cluster',
                    'DEPARTMENT': 'department',
                    'SECTION': 'division',
                    'ESS Employee No.': 'ess_ee_number',
                    'POSITION': 'position',
                    'CLASSIFICATION': 'classification',
                    'BASIC SALARY': 'basic_salary',
                    'DATE HIRED': 'date_hired',
                    'DATE RESIGNED': 'date_resigned',
                    'TENURE': {
                        callback: (value) => {
                            if(value.date_hired){
                                return this.getTenureNumeric(value.date_hired);
                            }else{
                                return '';
                            }
                        }
                    },
                    '5th month': 'fifth_month',
                    '6th month': 'six_month',
                    'LEVEL': 'level',
                    'LOCATION': 'location',
                    'AREA': 'area',
                    'BANK NAME': 'bank_name',
                    'BANK ACCOUNT NUMBER': 'bank_name',
                    'EMPLOYEE STATUS' : 'employee_status',
                    'IMMEDIATE SUPERIOR' : 'immediate_superior',
                    'BU HEAD' : 'bu_head',
                    'CLUSTER HEAD' : 'cluster_head',
                    
                    //Contact Info
                    'CURRENT ADDRESS' : 'current_address',
                    'PERMANENT ADDRESS' : 'permanent_address',
                    'LANDLINE' : 'phone_number',
                    'PERSONAL PHONE NUMBER' : 'mobile_number',
                    'COMPANY ASSIGN PHONE NUMBER' : 'company_assign_phone',
                    'CONTACT PERSON' : 'contact_person',
                    'CONTACT RELATION' : 'contact_relation',
                    'CONTACT PHONE NUMBER' : 'contact_number',

                    //Identification
                    'SSS' : 'sss_number',
                    'HDMF' : 'hdmf',
                    'PHILHEALTH' : 'phil_number',
                    'TIN' : 'tax_number',
                    'TAX STATUS' : 'tax_status',
    
                    'STATUS' : 'status'
                },
                npaRequestDetails : [],
                npa_request : [],
                npa_request_errors : [],
                npaRequestLists : [],
                hr_employees : [],
                bu_heads : [],
                npa_request_detail : [],
                npa_request_edit : false,
                salary_records : [],

                //201 Files
                documents_201_files_attachments : [],
                deleted_documents_201_files_attachments : [],
                employee_201_files_attachments: [],

                //Transfer Logs
                transfer_log_fields : {
                    'employee_name':{
                        callback: (value) => {
                            if(value.employee){
                                return value.employee.first_name + ' ' + value.employee.last_name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'previous_company' : {
                        callback: (value) => {
                            if(value.previous_company){
                                return value.previous_company.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'previous_id_number' : 'previous_id_number',
                    'previous_date_hired' : 'previous_date_hired',
                    'previous_position' : 'previous_position',
                    'previous_department' : {
                        callback: (value) => {
                            if(value.previous_department){
                                return value.previous_department.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'previous_location' : {
                        callback: (value) => {
                            if(value.previous_location){
                                return value.previous_location.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'new_company' : {
                        callback: (value) => {
                            if(value.new_company){
                                return value.new_company.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'new_id_number' : 'new_id_number',
                    'new_date_hired' : 'new_date_hired',
                    'new_position' : 'new_position',
                    'new_department' : {
                        callback: (value) => {
                            if(value.new_department){
                                return value.new_department.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'new_location' : {
                        callback: (value) => {
                            if(value.new_location){
                                return value.new_location.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'supporting_documents' : {
                        callback: (value) => {
                            if(value.employee_transfer_attachments){
                                return value.employee_transfer_attachments.length + ' Attachments';
                            }else{
                                return '';
                            }
                        }
                    }
                },

                //First Level Under
                employee_unders : [],
                new_approver_under : ''
            }
        },
        created(){
            this.fetchEmployees();
            this.fetchMaritalStatuses();
            this.fetchCompanies();
            this.fetchDepartments();
            this.fetchLocations();
            this.fetchLevels();
            this.fetchClusters();
            this.fetchHeadApprovers();
            this.fetchPositionApprovers();
            this.fetchUserAccessRights();
            this.fetchHREmployees();
            this.fetchBUHeadEmployees();

            //Show Npa Approval Form
            this.showNpaApprovalForm();
        },
        methods:{
            showNpaApprovalForm(){
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                const employee_id = urlParams.get('employee_id');
                const type = urlParams.get('type');

                if(employee_id && type=='npa'){
                    axios.get('/get-employee?employee_id='+employee_id)
                    .then(response => { 
                        if(response.data){
                            $('#npaRequestModal').modal('show');
                            this.npaRequest(response.data);
                        }else{
                            alert('No Results Found');
                        }
                    })
                    .catch(error => { 
                       
                    })
                }
                
            },
            cancelEditNPARequest(){
                let v = this;
                v.npaRequest(v.npaRequestDetails);
                v.npa_request_edit = false;
            },
            editNPARequest(npa_request){
                console.log(npa_request);
                let v = this;
                v.npaRequest(v.npaRequestDetails);
                v.npa_request_edit = true;
                v.npa_request.id = npa_request.id;

                v.npa_request.subject = npa_request.subject;
                v.npa_request.from_type_of_movement = npa_request.from_type_of_movement;
                v.npa_request.from_company = npa_request.from_company;
                v.npa_request.from_position_title = npa_request.from_position_title;
                v.npa_request.from_immediate_manager = npa_request.from_immediate_manager;
                v.npa_request.from_department = npa_request.from_department;
                v.npa_request.from_monthly_basic_salary = npa_request.from_monthly_basic_salary;


                v.npa_request.date_hired = npa_request.date_hired;

                v.npa_request.effectivity_date = npa_request.effectivity_date;
                
                v.npa_request.to_type_of_movement = npa_request.to_type_of_movement;
                v.npa_request.to_company = npa_request.to_company;
                v.npa_request.to_location = npa_request.to_location;
                v.npa_request.to_position_title = npa_request.to_position_title;
                v.npa_request.to_immediate_manager = npa_request.to_immediate_manager;
                v.npa_request.to_department = npa_request.to_department;
                v.npa_request.to_monthly_basic_salary = npa_request.to_monthly_basic_salary;

                v.npa_request.prepared_by = npa_request.prepared_by;
                v.npa_request.approved_by = npa_request.approved_by;
                v.npa_request.recommended_by = npa_request.recommended_by;
                v.npa_request.bu_head = npa_request.bu_head;

            },
            approveByHRRecommend(npa_request){
                let v = this;
                Swal.fire({
                    title: 'Are you sure you want to approve this NPA request?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Approve'
                    }).then((result) => {
                    if (result.value) {

                        axios.get(`/approved-by-hr-recommend/${npa_request.id}`)
                        .then(response => {
                           this.npaRequest(v.npaRequestDetails);
                            Swal.fire({
                                title: 'Success!',
                                text: 'NPA request has been approved successfully.',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            })
                        })
                        .catch(error => {
                            this.npa_request_errors = error.response.data.errors;
                        })   
                    }
                })
            },
            approveByHRApprover(npa_request){
                let v = this;
                Swal.fire({
                    title: 'Are you sure you want to approve this NPA request?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Approve'
                    }).then((result) => {
                    if (result.value) {

                        axios.get(`/approved-by-hr-approver/${npa_request.id}`)
                        .then(response => {
                           this.npaRequest(v.npaRequestDetails);
                            Swal.fire({
                                title: 'Success!',
                                text: 'NPA request has been approved successfully.',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            })
                        })
                        .catch(error => {
                            this.npa_request_errors = error.response.data.errors;
                        })   
                    }
                })
            },
            approveByBUHead(npa_request){
                let v = this;
                Swal.fire({
                    title: 'Are you sure you want to approve this NPA request?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Approve'
                    }).then((result) => {
                    if (result.value) {
                        axios.get(`/approved-by-bu-head/${npa_request.id}`)
                        .then(response => {
                           this.npaRequest(v.npaRequestDetails);
                            Swal.fire({
                                title: 'Success!',
                                text: 'NPA request has been approved successfully.',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            })
                        })
                        .catch(error => {
                            this.npa_request_errors = error.response.data.errors;
                        })   
                    }
                })
            },

            //Employee NPA
            clearNPAForm(){
                this.npa_request_errors = [];
                this.npa_request = [];
                this.npa_request.from_company = "";
                this.npa_request.from_position_title = "";
                this.npa_request.from_immediate_manager = "";
                this.npa_request.from_department = "";
            },
            npaRequest(employee){
                this.clearNPAForm();
                this.npa_request_errors = [];
                this.npaRequestDetails = employee;
                this.npaMonthlyBasicSalary();
                this.npa_request.from_company = employee.companies[0] ? employee.companies[0].id : "";
                this.npa_request.from_location = employee.locations[0] ? employee.locations[0].id : "";
                this.npa_request.from_position_title = employee.position;
                this.npa_request.from_immediate_manager = employee.immediate_superior[0] ? employee.immediate_superior[0].employee_head_id : "";
                this.npa_request.from_department = employee.departments[0] ? employee.departments[0].id : "";
                this.npa_request.bu_head = employee.bu_head[0] ? employee.bu_head[0].employee_head_id : "";
                this.fetchNPARequestLists();
            },
            npaMonthlyBasicSalary(){
                axios.get('/decrypt-monthly-basic-salary/'+this.npaRequestDetails.id)
                .then(response => { 
                    if(response.data){
                        console.log(response.data);
                        this.npa_request.from_monthly_basic_salary = response.data;
                    }else{
                        this.npa_request.from_monthly_basic_salary = "";
                    }
                })
                .catch(error => { 
                    this.npa_request.from_monthly_basic_salary =  "";
                })
                
                
            },
            
            fetchNPARequestLists(){
                this.npaRequestLists = [];
                axios.get('/get-npa-requests/' + this.npaRequestDetails.id)
                .then(response => { 
                    this.npaRequestLists = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            viewNPARequest(npa_request){
                let v = this;
                axios.get(`/get-npa-request/${npa_request.id}`)
                .then(response => {
                    v.npa_request_detail = response.data;

                    if(v.npa_request_detail){
                        let from_type_of_movement = v.npa_request_detail.from_type_of_movement ? v.npa_request_detail.from_type_of_movement : "";
                        let to_type_of_movement = v.npa_request_detail.to_type_of_movement ? v.npa_request_detail.to_type_of_movement : "";
                        let from_company = v.npa_request_detail.from_company ? v.npa_request_detail.from_company.name : "";
                        let to_company = v.npa_request_detail.to_company ? v.npa_request_detail.to_company.name : "";

                        let from_location = v.npa_request_detail.from_location ? v.npa_request_detail.from_location.name : "";
                        let to_location = v.npa_request_detail.to_location ? v.npa_request_detail.to_location.name : "";

                        let from_position_title = v.npa_request_detail.from_position_title ? v.npa_request_detail.from_position_title : "";
                        let to_position_title = v.npa_request_detail.to_position_title ? v.npa_request_detail.to_position_title : "";

                        let date_hired = v.npaRequestDetails.date_hired ? v.npaRequestDetails.date_hired : "";

                        let from_immediate_manager = v.npa_request_detail.from_immediate_manager ? v.npa_request_detail.from_immediate_manager.first_name + ' ' + v.npa_request_detail.from_immediate_manager.last_name : "";
                        let to_immediate_manager = v.npa_request_detail.to_immediate_manager ? v.npa_request_detail.to_immediate_manager.first_name + ' ' + v.npa_request_detail.to_immediate_manager.last_name : "";
                        
                        let from_department = v.npa_request_detail.from_department ? v.npa_request_detail.from_department.name : "";
                        let to_department = v.npa_request_detail.to_department ? v.npa_request_detail.to_department.name : "";

                        let effectivity_date = v.npa_request_detail.effectivity_date ? v.npa_request_detail.effectivity_date : "";

                        let from_monthly_basic_salary = v.npa_request_detail.from_monthly_basic_salary ? v.npa_request_detail.from_monthly_basic_salary : "";

                        let to_monthly_basic_salary = v.npa_request_detail.to_monthly_basic_salary ? v.npa_request_detail.to_monthly_basic_salary : "";


                        let prepared_by = v.npa_request_detail.prepared_by ? v.npa_request_detail.prepared_by.first_name + ' ' + v.npa_request_detail.prepared_by.last_name + ' / ' + v.npa_request_detail.prepared_by.position : "";
                        
                        let recommended_by = v.npa_request_detail.recommended_by ? v.npa_request_detail.recommended_by.first_name + ' ' + v.npa_request_detail.recommended_by.last_name + ' / ' + v.npa_request_detail.recommended_by.position : "";
                        
                        let approved_by = v.npa_request_detail.approved_by ? v.npa_request_detail.approved_by.first_name + ' ' + v.npa_request_detail.approved_by.last_name + ' / ' + v.npa_request_detail.approved_by.position : "";

                        let bu_head = v.npa_request_detail.bu_head ? v.npa_request_detail.bu_head.first_name + ' ' + v.npa_request_detail.bu_head.last_name + ' / ' + v.npa_request_detail.bu_head.position  : "";

                        Swal.fire({
                        title: '<strong>NOTICE OF PERSONNEL ACTION</strong>',
                        icon: false,
                        width : '800px',
                        html: '<div class="table-responsive">'+
                                '<h4 class="text-left">Date: '+v.npa_request_detail.created_at+'</h4>'+
                                '<h4 class="text-left">Subject: '+v.npa_request_detail.subject+'</h4>'+
                                '<table class="table table-bordered text-left">'+
                                    '<tr>'+
                                        '<td></td>'+
                                        '<td>From</td>'+
                                        '<td>To</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Type of Movement</td>'+
                                        '<td>'+from_type_of_movement+'</td>'+
                                        '<td>'+to_type_of_movement+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Company</td>'+
                                        '<td>'+from_company+'</td>'+
                                        '<td>'+to_company+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Location</td>'+
                                        '<td>'+from_location+'</td>'+
                                        '<td>'+to_location+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Position Title</td>'+
                                        '<td>'+from_position_title+'</td>'+
                                        '<td>'+to_position_title+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Date Hired</td>'+
                                        '<td colspan="2">'+date_hired+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Immediate Manager</td>'+
                                        '<td>'+from_immediate_manager+'</td>'+
                                        '<td>'+to_immediate_manager+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Department</td>'+
                                        '<td>'+from_department+'</td>'+
                                        '<td>'+to_department+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Effectivity Date</td>'+
                                        '<td colspan="2">'+effectivity_date+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Monthly Basic Salary</td>'+
                                        '<td>'+from_monthly_basic_salary+'</td>'+
                                        '<td>'+to_monthly_basic_salary+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Prepared By</td>'+
                                        '<td colspan="2">'+prepared_by+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Recommended By</td>'+
                                        '<td colspan="2">'+recommended_by+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>Approved By</td>'+
                                        '<td colspan="2">'+approved_by+'</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>BU Head</td>'+
                                        '<td colspan="2">'+bu_head+'</td>'+
                                    '</tr>'+
                                '</table></div>'
                        ,
                        showCloseButton: true,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonText:
                            'Close',
                        confirmButtonAriaLabel: 'Close',
                        })
                    }
                })
                .catch(error => {
                    this.npa_request_errors = error.response.data.errors;
                })   
                   
               
            },
            deleteNPARequest(npa_request){
                let v = this;
                Swal.fire({
                    title: 'Are you sure you want to delete this NPA request?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete'
                    }).then((result) => {
                    if (result.value) {

                        axios.delete(`/npa_request/${npa_request.id}`)
                        .then(response => {
                           this.npaRequest(v.npaRequestDetails);
                            Swal.fire({
                                title: 'Success!',
                                text: 'NPA request has been deleted successfully.',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            })
                        })
                        .catch(error => {
                            this.npa_request_errors = error.response.data.errors;
                        })   
                    }
                })
            },
            updateNPARequest(npa_request){
                let v = this;
                var index = this.employees.findIndex(item => item.id == v.npaRequestDetails.id);
                Swal.fire({
                        title: 'Are you sure you want to update this request?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Update'
                        }).then((result) => {
                        if (result.value) {
                            let formData = new FormData();
                            formData.append('id', v.npa_request.id);
                            formData.append('employee_id', v.npaRequestDetails.id);
                            formData.append('subject', npa_request.subject ? npa_request.subject : "");
                            formData.append('date_hired', npa_request.date_hired ? npa_request.date_hired : "");
                            formData.append('employee_name', npa_request.employee_name ? npa_request.employee_name : "");
                            formData.append('from_type_of_movement', npa_request.from_type_of_movement ? npa_request.from_type_of_movement : "");
                            formData.append('from_company', npa_request.from_company ? npa_request.from_company : "");
                            formData.append('from_location', npa_request.from_location ? npa_request.from_location : "");
                            formData.append('from_position_title', npa_request.from_position_title ? npa_request.from_position_title : "");
                            formData.append('from_immediate_manager', npa_request.from_immediate_manager ? npa_request.from_immediate_manager : "");
                            formData.append('from_department', npa_request.from_department ? npa_request.from_department : "");
                            formData.append('effectivity_date', npa_request.effectivity_date ? npa_request.effectivity_date : "");
                            formData.append('from_monthly_basic_salary', npa_request.from_monthly_basic_salary ? npa_request.from_monthly_basic_salary : "");
                            formData.append('to_type_of_movement', npa_request.to_type_of_movement ? npa_request.to_type_of_movement : "");
                            formData.append('to_company', npa_request.to_company ? npa_request.to_company : "");
                            formData.append('to_location', npa_request.to_location ? npa_request.to_location : "");
                            formData.append('to_position_title', npa_request.to_position_title ? npa_request.to_position_title : "");
                            formData.append('to_immediate_manager', npa_request.to_immediate_manager ? npa_request.to_immediate_manager : "");
                            formData.append('to_department', npa_request.to_department ? npa_request.to_department : "");
                            formData.append('to_monthly_basic_salary', npa_request.to_monthly_basic_salary ? npa_request.to_monthly_basic_salary : "");
                            formData.append('prepared_by', npa_request.prepared_by ? npa_request.prepared_by : "");
                            formData.append('recommended_by', npa_request.recommended_by ? npa_request.recommended_by : "");
                            formData.append('approved_by', npa_request.approved_by ? npa_request.approved_by : "");
                            formData.append('bu_head', npa_request.bu_head ? npa_request.bu_head : "");
                            formData.append('_method', 'POST');

                            axios.post(`/update-employee-npa-request`, 
                                formData
                            )
                            .then(response => {
                                this.fetchEmployees();
                                this.npaRequest(v.npaRequestDetails);
                                v.npa_request_edit = false;
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Employee request has been updated successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'Okay'
                                })
                            })
                            .catch(error => {
                                this.npa_request_errors = error.response.data.errors;
                                Swal.fire({
                                    title: 'Warning!',
                                    text: 'Unable to updated Employee. Refresh the page and then try again.',
                                    icon: 'error',
                                    confirmButtonText: 'Okay'
                                })
                            })

                        }
                })
            },
            saveNPARequest(npa_request){
                let v = this;
                var index = this.employees.findIndex(item => item.id == v.npaRequestDetails.id);
                Swal.fire({
                        title: 'Are you sure you want to send this request?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Send'
                        }).then((result) => {
                        if (result.value) {
                            let formData = new FormData();
                            formData.append('employee_id', v.npaRequestDetails.id);
                            formData.append('subject', npa_request.subject ? npa_request.subject : "");
                            formData.append('date_hired', npa_request.date_hired ? npa_request.date_hired : "");
                            formData.append('employee_name', npa_request.employee_name ? npa_request.employee_name : "");
                            formData.append('from_type_of_movement', npa_request.from_type_of_movement ? npa_request.from_type_of_movement : "");
                            formData.append('from_company', npa_request.from_company ? npa_request.from_company : "");
                            formData.append('from_location', npa_request.from_location ? npa_request.from_location : "");
                            formData.append('from_position_title', npa_request.from_position_title ? npa_request.from_position_title : "");
                            formData.append('from_immediate_manager', npa_request.from_immediate_manager ? npa_request.from_immediate_manager : "");
                            formData.append('from_department', npa_request.from_department ? npa_request.from_department : "");
                            formData.append('effectivity_date', npa_request.effectivity_date ? npa_request.effectivity_date : "");
                            formData.append('from_monthly_basic_salary', npa_request.from_monthly_basic_salary ? npa_request.from_monthly_basic_salary : "");
                            formData.append('to_type_of_movement', npa_request.to_type_of_movement ? npa_request.to_type_of_movement : "");
                            formData.append('to_company', npa_request.to_company ? npa_request.to_company : "");
                            formData.append('to_location', npa_request.to_location ? npa_request.to_location : "");
                            formData.append('to_position_title', npa_request.to_position_title ? npa_request.to_position_title : "");
                            formData.append('to_immediate_manager', npa_request.to_immediate_manager ? npa_request.to_immediate_manager : "");
                            formData.append('to_department', npa_request.to_department ? npa_request.to_department : "");
                            formData.append('to_monthly_basic_salary', npa_request.to_monthly_basic_salary ? npa_request.to_monthly_basic_salary : "");
                            formData.append('prepared_by', npa_request.prepared_by ? npa_request.prepared_by : "");
                            formData.append('recommended_by', npa_request.recommended_by ? npa_request.recommended_by : "");
                            formData.append('approved_by', npa_request.approved_by ? npa_request.approved_by : "");
                            formData.append('bu_head', npa_request.bu_head ? npa_request.bu_head : "");
                            formData.append('_method', 'POST');

                            axios.post(`/employee-npa-request`, 
                                formData
                            )
                            .then(response => {
                                this.fetchEmployees();
                                this.npaRequest(v.npaRequestDetails);
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Employee request has been successfully sent.',
                                    icon: 'success',
                                    confirmButtonText: 'Okay'
                                })
                            })
                            .catch(error => {
                                this.npa_request_errors = error.response.data.errors;
                                Swal.fire({
                                    title: 'Warning!',
                                    text: 'Unable to Send Employee. Refresh the page and then try again.',
                                    icon: 'error',
                                    confirmButtonText: 'Okay'
                                })
                            })

                        }
                })
            },

            //Employee Access Rights / All Employees / Inactive Employees Export

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
            exportFetchEmployees(){
                this.export_employees = [];
                axios.get('/export-employees')
                .then(response => { 
                    if(response.data.length > 0){
                        this.export_employees = response.data;
                    }
                    
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            exportFetchInactiveEmployees(){
                this.export_employees = [];
                axios.get('/export-inactive-employees')
                .then(response => { 
                    if(response.data.length > 0){
                        this.export_inactive_employees = response.data;
                    }
                    
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },

            //Employee Dependent Attachments

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

            //Employee 201 Files
            upload201FilesAttachments(e){
                this.documents_201_files_attachments = [];
                var files = e.target.files || e.dataTransfer.files;
                if(!files.length)
                    return;
                for (var i = files.length - 1; i >= 0; i--){
                    this.documents_201_files_attachments.push(files[i]);
                    this.fileSize = this.fileSize+files[i].size / 1024 / 1024;
                }
                if(this.fileSize > 5){
                    alert('File size exceeds 5 MB');
                    document.getElementById('documents_201_files_attachments').value = "";
                    this.documents_201_files_attachments = [];
                    this.fileSize = 0;
                }
            },
            remove201FilesAttachment: function(index,attachment) {
                if(attachment){
                    Swal.fire({
                        title: 'Are you sure you want to remove this 201 File attachment?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Remove'
                        }).then((result) => {
                        if (result.value) {
                            this.deleted_documents_201_files_attachments.push({
                                id: attachment.id,
                                file: attachment.file,
                            });
                            this.employee_201_files_attachments.splice(index, 1);    
                        }
                    })
                }
            },
            fetchEmployee201FilesAttachments(){
                let v = this;
                this.employee_201_files_attachments = [];
                this.deleted_documents_201_files_attachments = [];
                axios.get('/employee-201-file-attachments/'+this.employee_copied.id)
                .then(response => { 
                    if(response.data.length > 0){
                        this.employee_201_files_attachments = response.data;
                    }
                    
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            
            //Org Chart
            orgChartEmployee(employee){
                this.org_chart_src = '/org-chart/' + employee.id;
            },

            orgChartUnderEmployee(){
                let v = this;
                this.employee_unders = [];
                axios.get('/org-chart-under-employee/'+this.employee_copied.id)
                .then(response => { 
                    if(response.data.length > 0){
                        this.employee_unders = response.data;
                    }
                    
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },

            //Employee Transfer
            clearTransferForm(){
                this.transfer_employee.company_list = "";
                this.transfer_employee.department_list = "";
                this.transfer_employee.location_list = "";
                this.transfer_employee.division = "";
                this.transfer_employee.position = "";
                this.transfer_employee.date_hired = "";
                this.transfer_approvers = [];
            },
            transferEmployee(employee){
                let v = this;
                v.transferEmployeeDetails = [];
                v.transferEmployeeDetails = employee;
            },
            saveTransferEmployee(transfer_employee){
                let v = this;
                var index = this.employees.findIndex(item => item.id == v.transferEmployeeDetails.id);
                Swal.fire({
                        title: 'Are you sure you want to transfer this employee?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Transfer'
                        }).then((result) => {
                        if (result.value) {
                            let formData = new FormData();
                            formData.append('employee_id', v.transferEmployeeDetails.id);
                            formData.append('company_list', transfer_employee.company_list ? transfer_employee.company_list : "");
                            formData.append('department_list', transfer_employee.department_list ? transfer_employee.department_list : "");
                            formData.append('location_list', transfer_employee.location_list ? transfer_employee.location_list : "");
                            formData.append('division', transfer_employee.division ? transfer_employee.division : "");
                            formData.append('position', transfer_employee.position ? transfer_employee.position : "");
                            formData.append('date_hired', transfer_employee.date_hired ? transfer_employee.date_hired : "");
                            formData.append('head_approvers', this.transfer_approvers ? JSON.stringify(this.transfer_approvers) : "");


                            //Supporting Documents
                            if(this.transfer_supporting_documents.length > 0){
                                for(var i = 0; i < this.transfer_supporting_documents.length; i++){
                                    let transfer_supporting_documents = this.transfer_supporting_documents[i];
                                    formData.append('transfer_supporting_documents[]', transfer_supporting_documents);
                                }
                            }

                            formData.append('_method', 'PATCH');

                            axios.post(`/transfer-employee/${v.transferEmployeeDetails.id}`, 
                                formData
                            )
                            .then(response => {
                                return false;
                                
                                this.fetchEmployees();
                                this.clearTransferForm();
                                this.transferEmployeeDetails = response.data;
                               
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Employee has been successfully transferred.',
                                    icon: 'success',
                                    confirmButtonText: 'Okay'
                                })
                            })
                            .catch(error => {
                                this.transfer_errors = error.response.data.errors;
                                Swal.fire({
                                    title: 'Warning!',
                                    text: 'Unable to Update Employee. Check Entries and then try again.',
                                    icon: 'error',
                                    confirmButtonText: 'Okay'
                                })
                            })

                        }
                })
            },
            viewTransferEmployeeLogs(){
                this.viewTransferLogsList = true;
                this.transferLogsList = [];
                axios.get('/transfer-employee-logs/' + this.transferEmployeeDetails.id)
                .then(response => { 
                    this.transferLogsList = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            closeTransferEmployeeLogs(){
                this.viewTransferLogsList = false;
            },

            uploadTransferSupportingDocuments(e){
                this.transfer_supporting_documents = [];
                var files = e.target.files || e.dataTransfer.files;
                if(!files.length)
                    return;
                for (var i = files.length - 1; i >= 0; i--){
                    this.transfer_supporting_documents.push(files[i]);
                    this.fileSize = this.fileSize+files[i].size / 1024 / 1024;
                }
                if(this.fileSize > 5){
                    alert('File size exceeds 5 MB');
                    document.getElementById('transfer_supporting_documents').value = "";
                    this.transfer_supporting_documents = [];
                    this.fileSize = 0;
                }
            },

            //Employee Information
            profileImageLoadError(){
                this.profile_image = 'storage/default.png';
            },
            signatureImageLoadError(){
                this.signature_image = 'storage/image_not_available.png';
            },
            resetForm(){
                this.errors = [];
                this.employee = [];
            },
            customLabelHeadApprover (head_approver) {
                return `${head_approver.first_name + " " +  head_approver.last_name}`
            },
            updateEmployee(employee_copied){
                this.errors = [];
               
                this.edit_updated = false;
                this.employee_error = false;
                document.getElementById('edit_btn').disabled = true;
                var index = this.employees.findIndex(item => item.id == employee_copied.id);

                let formData = new FormData();

                //Personal
                if(this.profile_image_file){
                    formData.append('employee_image', this.profile_image_file);
                }
                if(this.signature_image_file){
                    formData.append('employee_signature', this.signature_image_file);
                }
                
                formData.append('first_name', employee_copied.first_name);
                if(employee_copied.middle_name){
                    formData.append('middle_name', employee_copied.middle_name);     
                }
                if(employee_copied.middle_name){
                    formData.append('middle_initial', employee_copied.middle_initial);
                }
                
                formData.append('last_name', employee_copied.last_name);
                formData.append('nick_name', employee_copied.nick_name ? employee_copied.nick_name : "-");
                formData.append('name_suffix', employee_copied.name_suffix ? employee_copied.name_suffix : "-");
                formData.append('marital_status', employee_copied.marital_status);

                if(this.marital_file){
                    formData.append('marital_status_attachment', this.marital_file);
                }
                formData.append('birthdate', employee_copied.birthdate);
                formData.append('gender', employee_copied.gender);
                formData.append('birthplace', employee_copied.birthplace ? employee_copied.birthplace : "-");
                formData.append('school_graduated', employee_copied.school_graduated ? employee_copied.school_graduated : "-");
                formData.append('school_course', employee_copied.school_course ? employee_copied.school_course : "-");
                formData.append('school_year', employee_copied.school_year ? employee_copied.school_year : "-");
                formData.append('vocational_graduated', employee_copied.vocational_graduated ? employee_copied.vocational_graduated : "-");
                formData.append('vocational_course', employee_copied.vocational_course ? employee_copied.vocational_course : "-");
                formData.append('school_year', employee_copied.school_year ? employee_copied.school_year : "-");
                formData.append('vocational_graduated', employee_copied.vocational_graduated ? employee_copied.vocational_graduated : "-");
                formData.append('vocational_course', employee_copied.vocational_course ? employee_copied.vocational_course : "-");
                formData.append('vocational_year', employee_copied.vocational_year ? employee_copied.vocational_year : "-");

                //Work
                formData.append('company_list', employee_copied.company_list);
                formData.append('division', employee_copied.division ? employee_copied.division : "");
                formData.append('department_list', employee_copied.department_list);
                formData.append('employee_number', employee_copied.employee_number ? employee_copied.employee_number : "-");
                formData.append('ess_ee_number', employee_copied.ess_ee_number ? employee_copied.ess_ee_number : "-");
                formData.append('position', employee_copied.position ? employee_copied.position : "-");
                formData.append('classification', employee_copied.classification ? employee_copied.classification : "-");
                formData.append('date_hired', employee_copied.date_hired ? employee_copied.date_hired : "");
                formData.append('level', employee_copied.level ? employee_copied.level : "-");
                formData.append('location_list', employee_copied.location_list);
                formData.append('cluster', employee_copied.cluster);
                formData.append('area', employee_copied.area ? employee_copied.area : "-");
                formData.append('monthly_basic_salary', employee_copied.monthly_basic_salary ? employee_copied.monthly_basic_salary : "");
                formData.append('bank_account_number', employee_copied.bank_account_number ? employee_copied.bank_account_number : "-");
                formData.append('bank_name', employee_copied.bank_name ? employee_copied.bank_name : "-");
                formData.append('status', employee_copied.status ? employee_copied.status : "-");
                formData.append('confidential', employee_copied.confidential ? employee_copied.confidential : "NO");

                formData.append('generate_id_number', employee_copied.generate_id_number ? employee_copied.generate_id_number : "NO");

               
                formData.append('date_regularized', employee_copied.date_regularized ? employee_copied.date_regularized : "");      
                
               
                formData.append('date_resigned', employee_copied.date_resigned ? employee_copied.date_resigned : "");

                //Transfer to new approvers
                formData.append('new_approver_under', this.new_approver_under ? this.new_approver_under : "");

                //Contact
                formData.append('current_address', employee_copied.current_address ? employee_copied.current_address : "-");
                formData.append('permanent_address', employee_copied.permanent_address ? employee_copied.permanent_address : "-");
                formData.append('phone_number', employee_copied.phone_number ? employee_copied.phone_number : "-");
                formData.append('mobile_number', employee_copied.mobile_number ? employee_copied.mobile_number : "-");
                formData.append('contact_person', employee_copied.contact_person ? employee_copied.contact_person : "-");
                formData.append('contact_number', employee_copied.contact_number ? employee_copied.contact_number : "-");
                formData.append('contact_relation', employee_copied.contact_relation ? employee_copied.contact_relation : "-");
                //Identification
                formData.append('sss_number', employee_copied.sss_number ? employee_copied.sss_number : "-");
                formData.append('phil_number', employee_copied.phil_number ? employee_copied.phil_number : "-");
                formData.append('hdmf', employee_copied.hdmf ? employee_copied.hdmf : "-");
                formData.append('tax_number', employee_copied.tax_number ? employee_copied.tax_number : "-");
                formData.append('tax_status', employee_copied.tax_status);

                //Approvers
                formData.append('head_approvers', this.approvers ? JSON.stringify(this.approvers) : "");
                formData.append('deleted_approvers', this.deletedApprover ? JSON.stringify(this.deletedApprover) : "");

                //Dependents
                formData.append('dependents', this.dependents ? JSON.stringify(this.dependents) : "");
                formData.append('deleted_dependents', this.deletedDependent ? JSON.stringify(this.deletedDependent) : "");

                

                if(this.dependent_attachments.length > 0){
                    for(var i = 0; i < this.dependent_attachments.length; i++){
                        let dependent_attachments = this.dependent_attachments[i];
                        formData.append('dependent_attachments[]', dependent_attachments);
                    }
                } 

                formData.append('deleted_dependent_attachments', this.deleted_dependent_attachments ? JSON.stringify(this.deleted_dependent_attachments) : "");

                //201 Files
                if(this.documents_201_files_attachments.length > 0){
                    for(var i = 0; i < this.documents_201_files_attachments.length; i++){
                        let documents_201_files_attachments = this.documents_201_files_attachments[i];
                        formData.append('documents_201_files_attachments[]', documents_201_files_attachments);
                    }
                }
                formData.append('deleted_documents_201_files_attachments', this.deleted_documents_201_files_attachments ? JSON.stringify(this.deleted_documents_201_files_attachments) : "");

                formData.append('_method', 'PATCH');

                axios.post(`/employee/${employee_copied.id}`, 
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }  
                )
                .then(response => {
                
                    this.employees.splice(index,1,response.data);
                    document.getElementById('edit_btn').disabled = false;

                    this.dependent_attachments = [];
                    var dependents_attachment =  document.getElementById("dependents_attachments");
                    if(dependents_attachment){
                        dependents_attachment.value = '';
                    }

                    this.documents_201_files_attachments = [];
                    var documents_201_files_attachments =  document.getElementById("documents_201_files_attachments");
                    if(documents_201_files_attachments){
                        documents_201_files_attachments.value = '';
                    }
                    
                    this.copyObject(response.data);

                    Swal.fire({
                        title: 'Success!',
                        text: 'Employee Updated Successfully',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                    
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    document.getElementById('edit_btn').disabled = false;
                    document.getElementById('dependents_attachments').value = "";
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Unable to Update Employee. Check Entries and then try again.',
                        icon: 'error',
                        confirmButtonText: 'Okay'
                    })
                })
            },
            copyObject(employee){
                this.errors = [];
                this.termsConditions = false;
                this.saveEmployee = true;

                this.new_approver_under = '';
                this.profile_image_file = '';

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

                //Get Approvers
                this.fetchApprovers();

                //Get Dependents
                this.fetchDependents();
                this.fetchDependentAttachments();

                //Get 201 Files Attachments
                this.fetchEmployee201FilesAttachments();

                //Validate Marital Status
                this.validateMaritalStatus();

                //Employee basic salary
                this.fetchMonthlyBasicSalary();

                //Salary record
                this.fetchSalaryRecords();

                //Attachment
                var num = Math.random();
                this.profile_image = 'storage/id_image/employee_image/' + employee.id + '.png?v='+num;
                this.signature_image = 'storage/id_image/employee_signature/' + employee.id + '.png?v='+num;
                var profile =  document.getElementById("profile_image_file");
                var signature =  document.getElementById("signature_image_file");
                var marital_status =  document.getElementById("marital_file");

                //First Level Under
                this.orgChartUnderEmployee();

                if(profile){
                    profile.value = '';
                }
                if(signature){
                    signature.value = '';
                }
                if(marital_status){
                    marital_status.value = '';
                }
            },
            fetchSalaryRecords(){
                this.salary_records = [];
                axios.get('/decrypt-monthly-basic-salary-record/'+this.employee_copied.id)
                .then(response => { 
                   this.salary_records = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            validateMaritalStatus(){

                if(this.employee_copied.marital_status){
                    if(this.employee_copied.marital_status == "MARRIED" || this.employee_copied.marital_status == "DIVORCED"){
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
            fetchMonthlyBasicSalary(){
                this.employee_copied.monthly_basic_salary = "";
                axios.get('/decrypt-monthly-basic-salary/'+this.employee_copied.id)
                .then(response => { 
                    if(response.data){
                        this.employee_copied.monthly_basic_salary = response.data;
                    }else{
                        this.employee_copied.monthly_basic_salary = "";
                    }
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchEmployees(){
                this.table_loading = true;
                axios.get('/employees-all')
                .then(response => { 
                    this.employees = response.data;
                    this.table_loading = false;
                    this.exportFetchEmployees();
                    this.exportFetchInactiveEmployees();
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
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
            fetchHREmployees(){
                 axios.get('/hr-employees')
                .then(response => { 
                    this.hr_employees = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchBUHeadEmployees(){
                 axios.get('/bu-heads')
                .then(response => { 
                    this.bu_heads = response.data;
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
            fetchApprovers(){
                this.approvers = [];
                this.deletedApprover = [];
                axios.get('/employee-approvers/'+this.employee_copied.id)
                .then(response => { 
                    this.approvers = response.data;
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
                                dependent_gender: element.dependent_gender,
                                bdate: v.getDateFormat(element.bdate),
                                relation: element.relation,
                                dependent_status: element.dependent_status
                            });
                        });
                    }
                    
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
            removeApprover: function(index,id) {
                let head_id = id;
                if(head_id){
                    Swal.fire({
                        title: 'Are you sure you want to remove this approver?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Remove'
                        }).then((result) => {
                        if (result.value) {

                            this.deletedApprover.push({
                                id: head_id
                            });
                            this.approvers.splice(index, 1);    
                        }
                    })
                }else{
                    this.approvers.splice(index, 1);
                } 
            },
            addTransferApprover(){
                this.transfer_approvers.push({
                    id: "",
                    employee_head_id: "",
                    head_id: "",
                });
            },
            removeTransferApprover: function(index,id) {
                this.transfer_approvers.splice(index, 1);
            },
            addDependent(){
                this.dependents.push({
                    id: "",
                    dependent_name: "",
                    dependent_gender: "",
                    bdate: "",
                    relation: "",
                    dependent_status: "",
                });
            },
            removeDependent: function(index,id) {
                let dependent_id = id;
                if(dependent_id){
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
                                id: dependent_id
                            });
                            this.dependents.splice(index, 1);    
                        }
                    })
                }else{
                    this.dependents.splice(index, 1);
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
            fetchClusters(){
                axios.get('/clusters-options')
                .then(response => { 
                    this.clusters = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchFilterEmployee(){
                this.table_loading = true;
                this.employees = [];
                this.formFilterData = new FormData();
                if(this.company){
                    this.formFilterData.append('company',this.company);
                }
                if(this.department){
                    this.formFilterData.append('department',this.department);
                }
                if(this.location){
                    this.formFilterData.append('location',this.location);
                }
                if(this.employee_status){
                    this.formFilterData.append('employee_status',this.employee_status);
                }
                this.formFilterData.append('_method', 'POST');

                axios.post('/filter-employee', this.formFilterData)
                .then(response => {
                    this.employees =  response.data;
                    this.table_loading = false;
                    this.errors = [];
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.table_loading = false;
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
            getTenureNumeric(dateString) 
            {
                if(dateString){
                    var to = new Date();
                    var from = new Date(dateString);
                    var tenure = this.YEARFRAC(to,from);
                    return tenure.toFixed(2);
                }else{
                    return "";
                }
            },
            YEARFRAC(start_date, end_date, basis) {
            // Initialize parameters
            var basis = (typeof basis === 'undefined') ? 0 : basis;
            var sdate = moment(new Date(start_date));
            var edate = moment(new Date(end_date));

            // Return error if either date is invalid
            if (!sdate.isValid() || !edate.isValid()) return '#VALUE!';

            // Return error if basis is neither 0, 1, 2, 3, or 4
            if ([0,1,2,3,4].indexOf(basis) === -1) return '#NUM!';
            
            // Return zero if start_date and end_date are the same
            if (sdate === edate) return 0;
            
            // Swap dates if start_date is later than end_date
            if (sdate.diff(edate) > 0) {
                var edate = moment(new Date(start_date));
                var sdate = moment(new Date(end_date)); 
            }

            // Lookup years, months, and days
            var syear = sdate.year();
            var smonth = sdate.month();
            var sday = sdate.date();
            var eyear = edate.year();
            var emonth = edate.month();
            var eday = edate.date();

            switch (basis) {
                    case 0:
                    // US (NASD) 30/360
                    // Note: if eday == 31, it stays 31 if sday < 30
                    if (sday === 31 && eday === 31) {
                        sday = 30;
                        eday = 30;
                    } else if (sday === 31) {
                        sday = 30;
                    } else if (sday === 30 && eday === 31) {
                        eday = 30;
                    } else if (smonth === 1 && emonth === 1 && sdate.daysInMonth() === sday && edate.daysInMonth() === eday) {
                        sday = 30;
                        eday = 30;
                    } else if (smonth === 1 && sdate.daysInMonth() === sday) {
                        sday = 30;
                    }
                    return ((eday + emonth * 30 + eyear * 360) - (sday + smonth * 30 + syear * 360)) / 360;
                    break;

                    case 1:
                    // Actual/actual
                    var feb29Between = function(date1, date2) {
                        // Requires year2 == (year1 + 1) or year2 == year1
                        // Returns TRUE if February 29 is between the two dates (date1 may be February 29), with two possibilities:
                        // year1 is a leap year and date1 <= Februay 29 of year1
                        // year2 is a leap year and date2 > Februay 29 of year2
                
                        var mar1year1 = moment(new Date(date1.year(), 2, 1));
                        if (moment([date1.year()]).isLeapYear() && date1.diff(mar1year1) < 0 && date2.diff(mar1year1) >= 0) {
                        return true;
                        } 
                        var mar1year2 = moment(new Date(date2.year(), 2, 1));
                        if (moment([date2.year()]).isLeapYear() && date2.diff(mar1year2) >= 0 && date1.diff(mar1year2) < 0) {
                        return true;
                        }
                        return false;
                    };
                    var ylength = 365;
                    if (syear === eyear || ((syear + 1) === eyear) && ((smonth > emonth) || ((smonth === emonth) && (sday >= eday)))) {
                        if (syear === eyear && moment([syear]).isLeapYear()) {
                        ylength = 366;
                        } else if (feb29Between(sdate, edate) || (emonth === 1 && eday === 29)) {
                        ylength = 366;
                        }
                        return edate.diff(sdate, 'days') / ylength;
                    } else {
                        var years = (eyear - syear) + 1;
                        var days = moment(new Date(eyear + 1, 0, 1)).diff(moment(new Date(syear, 0, 1)), 'days');
                        var average = days / years;
                        return edate.diff(sdate, 'days') / average;
                    }
                    break;

                    case 2:
                    // Actual/360
                    return edate.diff(sdate, 'days') / 360;
                    break;

                    case 3:
                    // Actual/365
                    return edate.diff(sdate, 'days') / 365;
                    break;

                    case 4:
                    // European 30/360
                    if (sday === 31) sday = 30;
                    if (eday === 31) eday = 30;
                    // Remarkably, do NOT change February 28 or February 29 at ALL
                    return ((eday + emonth * 30 + eyear * 360) - (sday + smonth * 30 + syear * 360)) / 360;
                    break;
                }
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
            filteredemployees(){
                let self = this;
                return Object.values(self.employees).filter(employee => {
                    let full_name = employee.first_name + " " + employee.last_name;
                    return employee.first_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.last_name.toLowerCase().includes(this.keywords.toLowerCase()) || full_name.toLowerCase().includes(this.keywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(Object.values(this.filteredemployees).length / this.itemsPerPage)
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredemployees.slice(index, index + this.itemsPerPage);

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
    .modal-orgchart{
        width: 1400px!important;
    }
    @media (min-width: 992px){
        .modal-lg {
            max-width: 700px!important;
        }
        .modal-employee {
            max-width: 1200px!important;
        }
    }
</style>