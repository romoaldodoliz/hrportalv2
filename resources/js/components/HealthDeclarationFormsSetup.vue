<template>
    <div>
        <loader v-if="loading"></loader>
        <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
            <!-- Mask -->
            <span class="mask bg-gradient-success opacity-7"></span>
            <!-- Header container -->
            <div class="container-fluid d-flex align-items-center">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <h1 class="display-2 text-white text-uppercase">HEALTH DECLARATION FORM - USERS OVERIDE</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col-xl-12 order-xl-1">
                    <div class="card bg-secondary shadow  mb-5">
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h3 class="mb-0 text-uppercase">Search Employee</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="role">Search Employee</label> 
                                        <input type="text"  class="form-control" v-model="keyword" placeholder="Search Last Name / First Name">
                                        <span class="text-danger" v-if="errors.keyword">{{ errors.keyword[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <button type="button" class="btn btn-primary btn-md mt-4" @click="fetchEmployees">Search</button>
                                    <button type="button" class="btn btn-primary btn-md mt-4" @click="refreshDoorUsers">Refresh Door User</button>
                                    <button type="button" class="btn btn-primary btn-md mt-4" @click="refreshFaceusers">Refresh Face User</button>

                                    <button type="button" class="btn btn-success btn-md mt-4" data-toggle="modal" data-target="#reportModal">Generate Employee Report</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="font-size:14px;">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Employee Name</th>
                                                <th>Status</th>
                                                <th>Remarks</th>
                                                <th>Door Access</th>
                                                <th>Biometric Access</th>
                                                <th>Forms</th>
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
                                            <tr v-for="(employee, u) in employees" v-bind:key="u">
                                                <td>{{ employee.date_time }}</td>
                                                <td>{{ employee.name }}</td>
                                                <td>{{ employee.status }}</td>
                                                <td>{{ employee.remarks }}</td>
                                                <td>
                                                    
                                                    <button v-if="employee.user_id" class="btn btn-success btn-sm" @click="enableDoorAccess(employee)">Enable Door Access</button>
                                                    <button v-if="employee.user_id" class="btn btn-danger btn-sm" @click="disableDoorAccess(employee)">Disable Door Access</button>

                                                    <!-- <button type="button" class="btn btn-primary btn-sm" style="font-size:14px;" @click="checkEmployee(employee)" data-toggle="modal" data-target="#checkModal" >Overide Access</button> -->
                                                
                                                </td>
                                                <td>
                                                    <button class="btn btn-success btn-sm" @click="enableFaceAccess(employee)">Enable Biometric Access</button>
                                                    <button class="btn btn-danger btn-sm" @click="disableFaceAccess(employee)">Disable Biometric Access</button>
                
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm" style="font-size:14px;" @click="viewForms(employee)" data-toggle="modal" data-target="#viewFormsModal" >View Forms</button>
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
        </div>

        <div class="modal fade" id="checkModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center" id="addCompanyLabel">HEALTH DECLARATION FORM</h2> 
                    </div>
                    <div class="modal-body">
                           <h3>Are you sure you want to overide this employee to access?</h3>
                    </div>

                    <div class="modal-footer text-center">
                        <button  type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="saveCheckForm" style="width:150px;">YES</button>
                        <button type="button" class="btn btn-primary btn-round btn-fill btn-lg" data-dismiss="modal" style="width:150px;" >NO</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="viewFormsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-employee" role="document" style="width:80%!important;">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center" id="addCompanyLabel">HEALTH DECLARATION FORM - LIST</h2> 
                    </div>
                    <div class="modal-body">
                        <h4>Name: {{ employee.name }}</h4>
                           <div class="table-responsive">
                                <table class="table table-bordered" style="font-size:14px;">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Temperature</th>
                                            <th>1</th>
                                            <th>2</th>
                                            <th>3</th>
                                            <th>4</th>
                                            <th>5</th>
                                            <th>6</th>
                                            <th>6 Yes</th>
                                            <th>7</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(form, u) in forms" v-bind:key="u">
                                            <td>{{ form.created_at}}</td>
                                            <td>{{ form.temperature}}</td>
                                            <td>{{ form.one_question}}</td>
                                            <td>{{ form.two_question}}</td>
                                            <td>{{ form.three_question}}</td>
                                            <td>{{ form.four_question}}</td>
                                            <td>{{ form.five_question}}</td>
                                            <td>{{ form.six_question}}</td>
                                            <td>{{ form.six_yes_desc}}</td>
                                            <td>{{ form.seven_question}}</td>
                                            <td>{{ form.status}}</td>
                                            <td>{{ form.remarks}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12 mb-3">
                                <h4>Change Allowed / Not Allowed Status</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="remark_status_yes" name="remark_status" class="custom-control-input" value="Allowed : Overide" v-model="remarks.status_remarks">
                                    <label class="custom-control-label" for="remark_status_yes" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="remark_status_no" name="remark_status" class="custom-control-input" value="Not Allowed : Go Home" v-model="remarks.status_remarks">
                                    <label class="custom-control-label" for="remark_status_no">No</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Upload Attachment</label> 
                                        <input type="file" id="attachment_file" class="form-control" ref="file" v-on:change="attachmentHandleFileUpload()"/>
                                        <span class="text-danger" v-if="errors.attachment_file">{{ errors.attachment_file[0] }}</span>
                                    </div>
                                    
                                </div>

                                <div class="col-md-6">
                                    <label for="role">Attachment - </label> 
                                    <a target="_blank" :href="'storage/hdf_attachments/'+employee.attachment_file" v-if="employee.attachment_file">View Attachment</a>
                                </div>
                            </div>

                             <div class="col-md-12">
                                <div class="form-group">
                                    <h4>Remarks</h4> 
                                        <textarea  class="form-control" v-model="remarks.remarks" placeholder="Remarks"></textarea>
                                    <br>
                                </div>
                            </div>

                    </div>

                     <div class="modal-footer text-center">
                        <button id="edit_btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="updateEmployee(remarks)" style="width:150px;">Update</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-employee" role="document" style="width:80%!important;">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center" id="addCompanyLabel">HEALTH DECLARATION FORM</h2> 
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>From</label>
                                    <input type="date" class="form-control"  v-model="from" placeholder="Input Search" />
                                    <span class="text-danger" v-if="errors.from"> {{ errors.from[0] }} </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>To</label>
                                    <input type="date" class="form-control"  v-model="to" placeholder="Input Search" />
                                    <span class="text-danger" v-if="errors.to"> {{ errors.to[0] }} </span>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div v-if="export_employees.length > 0">
                                    <download-excel
                                        :data   = "export_employees"
                                        :fields = "json_fields"
                                        :disabled = "disableExport"
                                        class   = "btn btn-md btn-success ml-3 mr-3 mt-3 float-right"
                                        name    = "Export HDF Employee.xls"
                                    >
                                            Export to excel
                                    </download-excel>
                                </div>
                            
                                <button class="btn btn-md btn-primary mt-3 float-right" @click="fetchFilterEmployee"> Apply Filter</button>
                            </div> 
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>

        <!-- IC EMPLOYEE -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 order-xl-1">
                    <div class="card bg-secondary shadow  mb-5">
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h3 class="mb-0 text-uppercase">Search IC Employee</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="role">Search Employee</label> 
                                        <input type="text"  class="form-control" v-model="keyword_ic" placeholder="Search Last Name / First Name">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <button type="button" class="btn btn-primary btn-md mt-4" @click="fetchICEmployees">Search</button>

                                    <button type="button" class="btn btn-success btn-md mt-4" data-toggle="modal" data-target="#icreportModal">Generate IC Employee Report</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="font-size:14px;">
                                        <thead>
                                            <tr>
                                                <th>Employee Name</th>
                                                <th>Agency</th>
                                                <th>Status</th>
                                                <th>Remarks</th>
                                                <th>Forms</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="table_loading_ic">
                                                <td colspan="15">
                                                    <content-placeholders>
                                                        <content-placeholders-text :lines="3" />
                                                    </content-placeholders>
                                                    <h4>Loading Employee Records.. Please wait a moment... </h4>
                                                </td>
                                            </tr>
                                            <tr v-for="(ic_employee, u) in ic_employees" v-bind:key="u">
                                                <td>{{ ic_employee.name  }}</td>
                                                <td>{{ ic_employee.dept_bu_position }}</td>
                                                <td>{{ ic_employee.status }}</td>
                                                <td>{{ ic_employee.remarks }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm" style="font-size:14px;" @click="viewICForms(ic_employee)" data-toggle="modal" data-target="#viewICFormsModal" >View Forms</button>
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
        </div>

         <div class="modal fade" id="viewICFormsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-employee" role="document" style="width:80%!important;">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center" id="addCompanyLabel">HEALTH DECLARATION FORM - LIST</h2> 
                    </div>
                    <div class="modal-body">
                           <div class="table-responsive">
                                <table class="table table-bordered" style="font-size:14px;">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Temperature</th>
                                            <th>1</th>
                                            <th>2</th>
                                            <th>3</th>
                                            <th>4</th>
                                            <th>5</th>
                                            <th>6</th>
                                            <th>6 Yes</th>
                                            <th>7</th>
                                            <th>Status</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(form, u) in forms" v-bind:key="u">
                                            <td>{{ form.created_at}}</td>
                                            <td>{{ form.temperature}}</td>
                                            <td>{{ form.one_question}}</td>
                                            <td>{{ form.two_question}}</td>
                                            <td>{{ form.three_question}}</td>
                                            <td>{{ form.four_question}}</td>
                                            <td>{{ form.five_question}}</td>
                                            <td>{{ form.six_question}}</td>
                                            <td>{{ form.six_yes_desc}}</td>
                                            <td>{{ form.seven_question}}</td>
                                            <td>{{ form.status}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12 mb-3">
                                <h4>Change Allowed / Not Allowed Status</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="ic_remark_status_yes" name="ic_remark_status" class="custom-control-input" value="Allowed : Overide" v-model="ic_remarks.status_remarks">
                                    <label class="custom-control-label" for="ic_remark_status_yes" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="ic_remark_status_no" name="ic_remark_status" class="custom-control-input" value="Not Allowed : Go Home" v-model="ic_remarks.status_remarks">
                                    <label class="custom-control-label" for="ic_remark_status_no">No</label>
                                </div>
                            </div>

                             <div class="col-md-12">
                                <div class="form-group">
                                    <h4>Remarks</h4> 
                                        <textarea  class="form-control" v-model="ic_remarks.remarks" placeholder="Remarks"></textarea>
                                    <br>
                                </div>
                                
                            </div>

                    </div>
                
                    <div class="modal-footer text-center">
                            <button id="edit_btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="updateICEmployee(ic_remarks)" style="width:150px;">Update</button>
                    </div>

                </div>

            </div>
        </div>

        <div class="modal fade" id="icreportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-employee" role="document" style="width:80%!important;">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center" id="addCompanyLabel">HEALTH DECLARATION FORM</h2> 
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>From</label>
                                    <input type="date" class="form-control"  v-model="from" placeholder="Input Search" />
                                    <span class="text-danger" v-if="errors.from"> {{ errors.from[0] }} </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>To</label>
                                    <input type="date" class="form-control"  v-model="to" placeholder="Input Search" />
                                    <span class="text-danger" v-if="errors.to"> {{ errors.to[0] }} </span>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div v-if="ic_export_employees.length > 0">
                                    <download-excel
                                        :data   = "ic_export_employees"
                                        :fields = "json_fields"
                                        class   = "btn btn-md btn-success ml-3 mr-3 mt-3 float-right"
                                        name    = "Export HDF Employee.xls"
                                    >
                                            Export to excel
                                    </download-excel>
                                </div>
                            
                                <button class="btn btn-md btn-primary mt-3 float-right" @click="fetchFilterICEmployee"> Apply Filter</button>
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
    import JsonExcel from 'vue-json-excel'
    export default {
        components: { 'downloadExcel': JsonExcel,loader },
        data(){
            return {
                keyword_ic : '',
                keyword : '',
                searchEmployees: [],
                employees: [],
                employee: [],
                ic_employee: [],
                errors: [],
                forms: [],
                loading : false,
                table_loading : false,
                table_loading_ic : false,
                ic_employees: [],

                remarks : [],
                ic_remarks : [],

                disableExport : true,
                export_employees : [],
                from : '',
                to : '',
                json_fields: {
                    'Date': 'date_time',
                    'Name': 'name',
                    'Dept/BU/Position/Location': 'dept_bu_position',
                    'Contact Number' : 'contact_number',
                    'Temperature' : 'temperature',
                    'One' : 'one_question',
                    'Two' : 'two_question',
                    'Three' : 'three_question',
                    'Four' : 'four_question',
                    'Five' : 'five_question',
                    'Six' : 'six_question',
                    'Six Yes Desc ' : 'six_yes_desc',
                    'Seven ' : 'seven_question',
                    'STATUS' : 'status'
                },
                ic_export_employees : [],
                attachment_file : ''
            }
        },
        created(){

        },
        methods:{
            attachmentHandleFileUpload(){
                var attachment_file = document.getElementById("attachment_file");
                this.attachment_file = attachment_file.files[0];
            },
            fetchFilterEmployee(){
                this.export_employees = [];

                let formData = new FormData();
                formData.append('from', this.from);
                formData.append('to', this.to);

                axios.post(`/fetch-apply-filter-hdf-employee`, 
                    formData
                )
                .then(response => {
                    this.export_employees = response.data;
                    this.disableExport = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            },
            fetchFilterICEmployee(){
                this.ic_export_employees = [];

                let formData = new FormData();
                formData.append('from', this.from);
                formData.append('to', this.from);

                axios.post(`/fetch-apply-filter-hdf-ic-employee`, 
                    formData
                )
                .then(response => {
                    this.ic_export_employees = response.data;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            },
            refreshDoorUsers(){
                axios.get('/user-access')
                .then(response => { 
                    if(response.data.length > 0){
                       alert('Door access is refreshed');
                    }
                    
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            refreshFaceusers(){
                axios.get('/face-user-access')
                .then(response => { 
                    if(response.data.length > 0){
                       alert('Face access is refreshed');
                    }
                    
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            viewForms(employee){
                this.employee = employee;
                this.forms = [];
                axios.post('/fetch-form-list', {
                    employee_id: employee.employee_id
                })
                .then(response => {
                    this.forms = response.data;                    
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            },
            viewICForms(employee){
                this.forms = [];
                this.ic_employee = employee;
                axios.post('/fetch-form-list-ic', {
                    employee_id: employee.employee_id
                })
                .then(response => {
                    this.forms = response.data;
                    
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            },
            fetchEmployees(){
                this.errors = []; 
                this.employees = [];
                this.table_loading = true; 
                axios.post('/fetch-filter-employee-health-overide', {
                    keyword: this.keyword
                })
                .then(response => {
                    this.employees = response.data;
                    this.table_loading = false; 
                    
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            },
            checkEmployee(employee){
                this.employee = employee;
            },
            enableDoorAccess(employee){
                this.loading = true;
                let formData = new FormData();
                formData.append('user_id', employee.user_id);
                formData.append('employee_id', employee.employee_id);
                formData.append('name', employee.name);

                axios.post(`/enable-door-access-overide`, 
                    formData
                )
                .then(response => {
                    var message = response.data;
                    console.log(message);
                    if(message == 'Overide'){
                        Swal.fire({
                            title: 'Success!',
                            text: 'Employee door access has been successfully enabled.',
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        });
                    }else{
                        Swal.fire({
                            title: 'Warning!',
                            text: 'Enable Door access cannot overide.',
                            icon: 'warning',
                            confirmButtonText: 'Okay'
                        });
                    }
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.loading = false;
                })
            },
            disableDoorAccess(employee){
                this.loading = true;
                let formData = new FormData();
                formData.append('user_id', employee.user_id);
                formData.append('employee_id', employee.employee_id);
                formData.append('name', employee.name);

                axios.post(`/disable-door-access-overide`, 
                    formData
                )
                .then(response => {
                    var message = response.data;
                    console.log(message);
                    if(message == 'Overide'){
                        Swal.fire({
                            title: 'Success!',
                            text: 'Employee door access has been successfully disabled.',
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        });
                    }else{
                        Swal.fire({
                            title: 'Warning!',
                            text: 'Disable Door access cannot overide.',
                            icon: 'warning',
                            confirmButtonText: 'Okay'
                        });
                    }
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.loading = false;
                })
            },
            enableFaceAccess(employee){
                this.loading = true;
                let formData = new FormData();
                formData.append('face_user_id', employee.face_user_id);
                formData.append('employee_id', employee.employee_id);
                formData.append('name', employee.name);

                axios.post(`/enable-face-access-overide`, 
                    formData
                )
                .then(response => {
                    var message = response.data;
                    console.log(message);
                    if(message == 'Overide'){
                        Swal.fire({
                            title: 'Success!',
                            text: 'Employee biometric access has been successfully enabled.',
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        });
                    }else{
                        Swal.fire({
                            title: 'Warning!',
                            text: 'Enable biometric access cannot overide.',
                            icon: 'warning',
                            confirmButtonText: 'Okay'
                        });
                    }
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.loading = false;
                })
            },
            disableFaceAccess(employee){
                this.loading = true;
                let formData = new FormData();
                formData.append('face_user_id', employee.face_user_id);
                formData.append('employee_id', employee.employee_id);
                formData.append('name', employee.name);

                axios.post(`/disable-face-access-overide`, 
                    formData
                )
                .then(response => {
                    var message = response.data;
                    console.log(message);
                    if(message == 'Overide'){
                        Swal.fire({
                            title: 'Success!',
                            text: 'Employee biometric access has been successfully disabled.',
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        });
                    }else{
                        Swal.fire({
                            title: 'Warning!',
                            text: 'Disable biometric access cannot overide.',
                            icon: 'warning',
                            confirmButtonText: 'Okay'
                        });
                    }
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.loading = false;
                })
            },
            saveCheckForm(){
            
                this.loading = true;

                let formData = new FormData();
                formData.append('user_id',  this.employee.user_id);
                    formData.append('face_user_id',  this.employee.face_user_id);
                
                axios.post(`/save-health-declaration-overide`, 
                    formData
                )
                .then(response => {
                    var message = response.data;
                    console.log(message);
                    if(message == 'Overide'){
                        Swal.fire({
                            title: 'Success!',
                            text: 'Employee has been successfully overide.',
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        });
                        $('#checkModal').modal('hide');
                    }else{
                        Swal.fire({
                            title: 'Warning!',
                            text: 'Cannot Overide',
                            icon: 'warning',
                            confirmButtonText: 'Okay'
                        });
                        $('#checkModal').modal('hide');   
                    }
                    
                    this.loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.loading = false;
                })
            },
            fetchICEmployees(){
                this.errors = []; 
                this.ic_employees = [];
                this.table_loading_ic = true; 
                axios.post('/fetch-filter-ic-employee-health-overide', {
                    keyword_ic: this.keyword_ic
                })
                .then(response => {
                    this.ic_employees = response.data;
                    this.table_loading_ic = false; 
                    
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.table_loading_ic = false; 
                })
            },   

            updateEmployee(remarks){
                let v = this;
                let formData = new FormData();
                formData.append('employee_id', v.employee.employee_id);
                formData.append('status', v.remarks.status_remarks ? v.remarks.status_remarks : "");
                formData.append('attachment_file', v.attachment_file ? v.attachment_file : "");
                formData.append('remarks', v.remarks.remarks ? v.remarks.remarks : "");

                axios.post(`/employee-update-status`, 
                    formData
                )
                .then(response => {
                    var employee = response.data;
                    this.employee = employee; 
                    
                    Swal.fire({
                        title: 'Success!',
                        text: 'Employee has been updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    });
                

                    v.remarks.status_remarks = "";
                    v.remarks.remarks = "";

                    v.fetchEmployees();
                    console.log(message);   
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })

            },
            updateICEmployee(remarks){
                let v = this;
                let formData = new FormData();
                formData.append('employee_id', v.ic_employee.employee_id);
                formData.append('status', v.ic_remarks.status_remarks ? v.ic_remarks.status_remarks : "");
                formData.append('remarks', v.ic_remarks.remarks ? v.ic_remarks.remarks : "");

                axios.post(`/ic-employee-update-status`, 
                    formData
                )
                .then(response => {
                    var message = response.data;
                     if(message == 'saved'){
                        Swal.fire({
                            title: 'Success!',
                            text: 'Employee has been updated successfully.',
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        });
                    }else{
                        Swal.fire({
                            title: 'Warning!',
                            text: 'Unable to update.',
                            icon: 'warning',
                            confirmButtonText: 'Okay'
                        });
                    }

                    v.ic_remarks.status_remarks = "";
                    v.ic_remarks.remarks = "";

                    v.fetchICEmployees();
                    console.log(message);   
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })

            }
        }
    }
</script>

<style lang="scss" scoped>

</style>