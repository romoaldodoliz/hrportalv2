<template>
<div>
    <loader v-if="loading"></loader>
    <div class="header bg-gradient-success pb-8 pt-5 pt-md-8 container-list"></div>
        <div class="container-fluid mt--9">
            <div class="header-body">
                <div class="row">
                    <div class="col-xl-12">
                         <div class="card shadow">
                                <div class="card-header border-0">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="mb-0">USERS</h3>
                                            <small class="text-muted">List of all users</small>
                                        </div> 
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-xl-4 mb-2 mt-3 float-right">
                                            <input type="text" name="keyword" class="form-control" placeholder="Search" autocomplete="off" v-model="keywords" id="keyword">
                                        </div> 
                                        <div class="col-md-12">
                                             <download-excel
                                                :data   = "users"
                                                :fields = "json_fields"
                                                class   = "btn btn-sm btn-default mt-3 ml-3 mr-3 float-right"
                                                name    = "Users.xls">
                                                    Export to excel
                                            </download-excel>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <!-- Users table -->
                                    <table class="table align-items-center table-flush">
                                        <thead class="thead-light">
                                            <tr>
                                               
                                                <th scope="col"></th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Role</th>
                                                <th scope="col">Creation Date</th>
                                             
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="table_loading">
                                                <td colspan="15">
                                                    <content-placeholders>
                                                        <content-placeholders-text :lines="3" />
                                                    </content-placeholders>
                                                </td>
                                            </tr>
                                             <tr v-for="(user, i) in filteredQueues" :key="i">
                                            
                                                <td class="text-center">
                                                    <div class="dropdown">
                                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                            <a class="dropdown-item" data-toggle="modal" data-target="#editModal" style="cursor: pointer" @click="copyObject(user)">Edit</a>
                                                            <!-- <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(user)">Delete</a> -->
                                                            <a class="dropdown-item" data-toggle="modal" data-target="#changePasswordModal" style="cursor: pointer" @click="copyObject(user)">Change Password</a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ user.name }}</td>
                                                <td>{{ user.email }}</td>
                                                <td>
                                                    <div v-if="user.roles.length > 0">
                                                         {{ user.roles[0] ? user.roles[0].name : "" }}
                                                    </div>
                                                    <div v-else>
                                                        Set Role
                                                    </div>
                                                   
                                                </td>
                                                <td>{{ user.created_at }}</td>  
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
                                        <span class="mr-2">Filtered User(s) : {{ filteredQueues.length }} </span><br>
                                        <span class="mr-2">Total User(s) : {{ users.length }}</span>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

         <!-- Edit User Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center" id="addCompanyLabel">Edit User</h2>
                    </div>
                    <div class="modal-body">
                         <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Name*</label> 
                                    <input type="text"  class="form-control" v-model="user_copied.name" style="text-transform:uppercase">
                                    <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class=row>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Email*</label> 
                                    <input type="text" class="form-control" v-model="user_copied.email">
                                    <span class="text-danger" v-if="errors.email">{{ errors.email[0] }}</span>
                                </div>
                            </div>
                        </div>

                        <div class=row>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Role*</label> 
                                    <select class="form-control" v-model="user_copied.role" id="role">
                                        <option value="">Choose Role</option>
                                        <option v-for="(roles) in roles" v-bind:key="roles" :value="roles.id"> {{ roles.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.role">{{ errors.role[0] }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="border:1px solid;border-radius:5px;">

                            <h4 class="ml-3 mt-2">Access Rights for HR Staff/Administrator/Cluster HEAD/BU HEAD/Immediate Superior</h4>

                            <div class="col-md-6">
                                <div class="form-group" >
                                    <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                        <input id="view_confidential" class="custom-control-input" v-model="user_copied.view_confidential" true-value="YES" false-value="NO" type="checkbox">
                                        <label class="custom-control-label" for="view_confidential">View Confidential Employee</label>
                                    </div>

                                    <span class="text-danger" v-if="errors.view_confidential">{{ errors.view_confidential[0] }}</span> 
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                        <input id="create" class="custom-control-input" v-model="user_copied.create" true-value="YES" false-value="NO" type="checkbox">
                                        <label class="custom-control-label" for="create">Create Employee</label>
                                    </div>

                                    <span class="text-danger" v-if="errors.create">{{ errors.create[0] }}</span> 
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                        <input id="edit" class="custom-control-input" v-model="user_copied.edit" true-value="YES" false-value="NO" type="checkbox">
                                        <label class="custom-control-label" for="edit">Edit Employee</label>
                                    </div>

                                    <span class="text-danger" v-if="errors.edit">{{ errors.edit[0] }}</span> 
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                        <input id="read" class="custom-control-input" v-model="user_copied.read" true-value="YES" false-value="NO" type="checkbox">
                                        <label class="custom-control-label" for="read">Read Employee</label>
                                    </div>

                                    <span class="text-danger" v-if="errors.read">{{ errors.read[0] }}</span> 
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                        <input id="search" class="custom-control-input" v-model="user_copied.search" true-value="YES" false-value="NO" type="checkbox">
                                        <label class="custom-control-label" for="search">Search Employee</label>
                                    </div>

                                    <span class="text-danger" v-if="errors.search">{{ errors.search[0]}}</span> 
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                        <input id="download_export" class="custom-control-input" v-model="user_copied.download_export" true-value="YES" false-value="NO" type="checkbox">
                                        <label class="custom-control-label" for="download_export">Download / Export</label>
                                    </div>

                                    <span class="text-danger" v-if="errors.download_export">{{ errors.download_export[0] }}</span> 
                                </div>
                            </div>

                            <div class="col-md-12">
                                <h4>Employee Information Access</h4>
                            </div>
                            

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                        <input id="personal_info" class="custom-control-input" v-model="user_copied.personal_info" true-value="YES" false-value="NO" type="checkbox">
                                        <label class="custom-control-label" for="personal_info">Personal Information</label>
                                    </div>

                                    <span class="text-danger" v-if="errors.personal_info">{{ errors.personal_info[0] }}</span> 
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                            <input id="personal_info_edit" class="custom-control-input" v-model="user_copied.personal_info_edit" true-value="YES" false-value="NO" type="checkbox">
                                            <label class="custom-control-label" for="personal_info_edit">Allow Edit Personal Information</label>
                                        </div>

                                        <span class="text-danger" v-if="errors.personal_info_edit">{{ errors.personal_info_edit[0] }}</span> 
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                        <input id="work_info" class="custom-control-input" v-model="user_copied.work_info" true-value="YES" false-value="NO" type="checkbox">
                                        <label class="custom-control-label" for="work_info">Work Information</label>
                                    </div>

                                    <span class="text-danger" v-if="errors.work_info">{{ errors.work_info[0] }}</span> 
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                            <input id="work_info_edit" class="custom-control-input" v-model="user_copied.work_info_edit" true-value="YES" false-value="NO" type="checkbox">
                                            <label class="custom-control-label" for="work_info_edit">Allow Edit Work Information</label>
                                        </div>

                                        <span class="text-danger" v-if="errors.work_info_edit">{{ errors.work_info_edit[0] }}</span> 
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                        <input id="contact_info" class="custom-control-input" v-model="user_copied.contact_info" true-value="YES" false-value="NO" type="checkbox">
                                        <label class="custom-control-label" for="contact_info">Contact Information</label>
                                    </div>

                                    <span class="text-danger" v-if="errors.contact_info">{{ errors.contact_info[0] }}</span> 
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                            <input id="contact_info_edit" class="custom-control-input" v-model="user_copied.contact_info_edit" true-value="YES" false-value="NO" type="checkbox">
                                            <label class="custom-control-label" for="contact_info_edit">Allow Edit Contact Information</label>
                                        </div>

                                        <span class="text-danger" v-if="errors.contact_info_edit">{{ errors.contact_info_edit[0] }}</span> 
                                    </div>
                                </div>

                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                        <input id="identification_info" class="custom-control-input" v-model="user_copied.identification_info" true-value="YES" false-value="NO" type="checkbox">
                                        <label class="custom-control-label" for="identification_info">Identification Information</label>
                                    </div>

                                    <span class="text-danger" v-if="errors.identification_info">{{ errors.identification_info[0] }}</span> 
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                            <input id="identification_info_edit" class="custom-control-input" v-model="user_copied.identification_info_edit" true-value="YES" false-value="NO" type="checkbox">
                                            <label class="custom-control-label" for="identification_info_edit">Allow Edit Identification</label>
                                        </div>

                                        <span class="text-danger" v-if="errors.identification_info_edit">{{ errors.identification_info_edit[0] }}</span> 
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                        <input id="employee_201_file" class="custom-control-input" v-model="user_copied.employee_201_file" true-value="YES" false-value="NO" type="checkbox">
                                        <label class="custom-control-label" for="employee_201_file">201 Files</label>
                                    </div>

                                    <span class="text-danger" v-if="errors.employee_201_file">{{ errors.employee_201_file[0] }}</span> 
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                            <input id="employee_201_file_edit" class="custom-control-input" v-model="user_copied.employee_201_file_edit" true-value="YES" false-value="NO" type="checkbox">
                                            <label class="custom-control-label" for="employee_201_file_edit">Allow Edit 201 File</label>
                                        </div>

                                        <span class="text-danger" v-if="errors.employee_201_file_edit">{{ errors.employee_201_file_edit[0] }}</span> 
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <h4>Allow Hidden Fields</h4>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                        <input id="npa_request" class="custom-control-input" v-model="user_copied.npa_request" true-value="YES" false-value="NO" type="checkbox">
                                        <label class="custom-control-label" for="npa_request">Transfer / Npa Requests</label>
                                    </div>

                                    <span class="text-danger" v-if="errors.npa_request">{{ errors.npa_request[0] }}</span> 
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                        <input id="monthly_basic_salary" class="custom-control-input" v-model="user_copied.monthly_basic_salary" true-value="YES" false-value="NO" type="checkbox">
                                        <label class="custom-control-label" for="monthly_basic_salary">Monthly Basic Salary</label>
                                    </div>

                                    <span class="text-danger" v-if="errors.monthly_basic_salary">{{ errors.monthly_basic_salary[0] }}</span> 
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox mt-2 ml-2 mb-3">
                                        <input id="bank_account_details" class="custom-control-input" v-model="user_copied.bank_account_details" true-value="YES" false-value="NO" type="checkbox">
                                        <label class="custom-control-label" for="bank_account_details">Bank Account Details</label>
                                    </div>

                                    <span class="text-danger" v-if="errors.bank_account_details">{{ errors.bank_account_details[0] }}</span> 
                                </div>
                            </div>
                        </div>     
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateUser(user_copied)">Save</button>
                    </div>
                </div>
            </div>
        </div>

         <!-- Delete User Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Are you sure you want to delete this User?
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-warning" @click="deleteUser">Delete</button>
                </div>
                </div>
            </div>
        </div>

        <!-- Change password Modal -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center" id="addCompanyLabel">Change Password</h2>
                    </div>
                    <div class="modal-body">
                         <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">New password*</label> 
                                    <input type="password"  class="form-control" v-model="user.new_password">
                                    <span class="text-danger" v-if="errors.new_password">{{ errors.new_password[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class=row>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Confirm password*</label> 
                                    <input type="password" class="form-control" v-model="user.new_password_confirmation">
                                    <span class="text-danger" v-if="errors.new_password_confirmation">{{ errors.new_password_confirmation[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="changePassword(user)" >Save</button>
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

    export default {
         components: {
            loader,
            'downloadExcel': JsonExcel
        },
        data(){
            return {
                users: [],
                user: [],
                user_copied: [],
                copied_role: [],
                roles: [],
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: "",
                loading: false,
                user_id: '',
                table_loading : true,
                json_fields: {
                    'Name' : 'name',
                    'Email' : 'email',
                    'Role' :  {
                        callback: (value) => {
                            if(value.roles[0]){
                                return `${value.roles[0].name}`;
                            }
                        }
                    },
                    'Creation Date' : 'created_at'
                }
            }
        },
        created(){
            this.fetchUsers();
            this.fetchRoles();
        },
        methods:{
            resetForm(){
                this.errors = [];
                this.user = [];
            },
            changePassword(user){
                axios.post('/change-password', {
                    user_id: this.user_id,
                    new_password: user.new_password,
                    new_password_confirmation: user.new_password_confirmation
                })
                .then(response => {
                    $('#changePasswordModal').modal('hide');
                    this.resetForm();
                    Swal.fire({
                        title: 'Success!',
                        text: 'User saved Successfully',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    console.log(this.errors);
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Unable to save user. Please try again.',
                        icon: 'warning',
                        confirmButtonText: 'Okay'
                    })
                })
            },
            deleteUser(){
                var index = this.users.findIndex(item => item.id == this.user_id);
                axios.delete(`/user/${this.user_id}`)
                .then(response => {
                    $('#deleteModal').modal('hide');
                    Swal.fire({
                        title: 'Success!',
                        text: 'User deleted Successfully',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                    this.users.splice(index,1);
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Unable to delete user. Please try again.',
                        icon: 'warning',
                        confirmButtonText: 'Okay'
                    })
                })
            },
            updateUser(user_copied){
                this.errors = [];
               
                this.edit_updated = false;
                this.loading = true;
                document.getElementById('edit_btn').disabled = true;
                var index = this.users.findIndex(item => item.id == user_copied.id);

                axios.post(`/user/${user_copied.id}`, {
                    name: user_copied.name,
                    email: user_copied.email,
                    role: user_copied.role,
                    view_confidential: user_copied.view_confidential,
                    create: user_copied.create,
                    edit: user_copied.edit,
                    read: user_copied.read,
                    search: user_copied.search,
                    download_export: user_copied.download_export,
                    personal_info: user_copied.personal_info,
                    work_info: user_copied.work_info,
                    contact_info: user_copied.contact_info,
                    identification_info: user_copied.identification_info,
                    employee_201_file: user_copied.employee_201_file,
                    npa_request: user_copied.npa_request,
                    monthly_basic_salary: user_copied.monthly_basic_salary,
                    bank_account_details: user_copied.bank_account_details,
                    personal_info_edit: user_copied.personal_info_edit,
                    work_info_edit: user_copied.work_info_edit,
                    contact_info_edit: user_copied.contact_info_edit,
                    identification_info_edit: user_copied.identification_info_edit,
                    employee_201_file_edit: user_copied.employee_201_file_edit,
                    _method: 'PATCH'
                })
                .then(response => {
                    
                    this.users.splice(index,1,response.data);
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;

                    Swal.fire({
                        title: 'Success!',
                        text: 'User updated Successfully',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    document.getElementById('edit_btn').disabled = false;
                    this.loading = false;

                    Swal.fire({
                        title: 'Warning!',
                        text: 'Unable to update user. Please try again.',
                        icon: 'warning',
                        confirmButtonText: 'Okay'
                    })
                })
            },
            copyObject(user){
                this.errors = [];
                this.user_copied = Object.assign({}, user);
                this.user_copied.role = this.user_copied.roles[0].id;
                this.user_id = user.id;
            },
            fetchRoles(){
                axios.get('/roles')
                .then(response => { 
                    this.roles = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchUsers(){
                this.table_loading = true;
                axios.get('/users-all')
                .then(response => { 
                    this.users = response.data;
                    this.table_loading = false;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
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
            filteredUsers(){
                let self = this;
                return Object.values(self.users).filter(user => {
                    return user.name.toLowerCase().includes(this.keywords.toLowerCase()) || user.email.toLowerCase().includes(this.keywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(Object.values(this.filteredUsers).length / this.itemsPerPage)
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredUsers.slice(index, index + this.itemsPerPage);

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