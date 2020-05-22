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
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="font-size:14px;">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Employee Name</th>
                                                <th>Status</th>
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
                                <div class="col-lg-4">
                                    <button type="button" class="btn btn-primary btn-md mt-4" @click="fetchICEmployees">Search</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="font-size:14px;">
                                        <thead>
                                            <tr>
                                                <th>Employee Name</th>
                                                <th>Department/BU/Position</th>
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
            }
        },
        created(){

        },
        methods:{
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
        }
    }
</script>

<style lang="scss" scoped>

</style>