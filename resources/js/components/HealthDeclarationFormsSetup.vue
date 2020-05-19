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
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="role">Search Employee</label> 
                                        <input type="text"  class="form-control" v-model="keyword" placeholder="Search Last Name / First Name">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <button type="button" class="btn btn-primary btn-md mt-4" @click="fetchEmployees">Search</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="font-size:14px;">
                                        <thead>
                                            <tr>
                                                <th>Employee Name</th>
                                                <th>Department/Position</th>
                                                <th>Contact Number</th>
                                                <th>Card Access Blocked</th>
                                                <th>Overide Access</th>
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
                                                <td>{{ employee.last_name + ', ' + employee.first_name  }}</td>
                                                <td>{{ employee.departments[0] ? employee.departments[0].name : "" }} / {{ employee.position }}</td>
                                                <td>{{ employee.mobile_number}}</td>
                                                 <td>{{ employee.card_access_blocked }}</td>
                                                <td><button type="button" class="btn btn-primary btn-sm" style="font-size:14px;" @click="checkEmployee(employee)" data-toggle="modal" data-target="#checkModal" >Overide Access</button></td>
                                                <td><button type="button" class="btn btn-primary btn-sm" style="font-size:14px;" @click="viewForms(employee)" data-toggle="modal" data-target="#viewFormsModal" >View Forms</button></td>
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
                        <button  type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="saveCheckForm(form)" style="width:150px;">YES</button>
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
                                            <th>7</th>
                                            <th>8</th>
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
                                            <td>{{ form.seven_question}}</td>
                                            <td>{{ form.eight_question}}</td>
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
                keyword : '',
                searchEmployees: [],
                employees: [],
                employee: [],
                errors: [],
                forms: [],
                loading : false,
                table_loading : false,
            }
        },
        created(){

        },
        methods:{
            viewForms(employee){
                this.forms = [];
                axios.post('/fetch-form-list', {
                    employee_id: employee.id
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
                axios.post('/fetch-filter-employee-health', {
                    keyword: this.keyword
                })
                .then(response => {
                    this.employees = response.data;
                    this.table_loading = false; 
                    
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.table_loading = false; 
                })
            },
            checkEmployee(employee){
                this.employee = employee;
            },
            saveCheckForm(form){
                if(this.employee.card_access_blocked == true){

                    this.loading = true;

                    let formData = new FormData();
                    formData.append('employee_id',  this.employee.id);
                    
                    axios.post(`/save-health-declaration-overide`, 
                        formData
                    )
                    .then(response => {
                        var message = response.data;
                        Swal.fire({
                                title: 'Success!',
                                text: 'Employee has been successfully overide.',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            });
                        $('#checkModal').modal('hide');
                        this.loading = false;
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                        this.loading = false;
                    })

                }else{
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Cannot access overide.',
                        icon: 'warning',
                        confirmButtonText: 'Okay'
                    });

                    $('#checkModal').modal('hide'); 
                }
            }   
        }
    }
</script>

<style lang="scss" scoped>

</style>