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
                        <h1 class="display-2 text-white text-uppercase">HEALTH DECLARATION FORM</h1>
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
                                                <th>Department/BU/Position</th>
                                                <th>Contact Number</th>
                                                <th>Check</th>
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
                                                <td><button type="button" class="btn btn-primary btn-sm" style="font-size:14px;" @click="checkEmployee(employee)" data-toggle="modal" data-target="#checkModal" >Check</button></td>
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
                            <div class="col-md-12">
                                <h3>Name: {{employee.last_name + ', '+  employee.first_name}}</h3>
                                <h3>Dept./Position: {{ employee.departments ? employee.departments[0].name : "" }} / {{ employee.companies ? employee.companies[0].name : "" }} / {{ employee.position }} </h3>
                                <h3>Contact Number: {{ employee.mobile_number}}</h3>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h4>Temperature</h4> 
                                    <input type="text" class="form-control" v-model="form.temperature">
                                    <br>
                                    <span class="text-danger" v-if="errors.temperature">{{ errors.temperature[0] }}</span>
                                </div>
                                
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>1. Did you visit a hospital, clinic or medical health facility in the past 14 days?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="yes1" name="one_question" class="custom-control-input" value="Yes" v-model="form.one_question">
                                    <label class="custom-control-label" for="yes1" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no1" name="one_question" class="custom-control-input" value="No" v-model="form.one_question">
                                    <label class="custom-control-label" for="no1">No</label>
                                </div>
                                <br>
                                <span class="text-danger" v-if="errors.one_question">{{ errors.one_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>2. In the last 14 days, did you have any of the following: fever, colds, cough, sore throat, aches and\ pains, nasal congestion, runny nose, diarrhea or difficulty in breathing?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="yes2" name="two_question" class="custom-control-input" value="Yes" v-model="form.two_question">
                                    <label class="custom-control-label" for="yes2" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no2" name="two_question" class="custom-control-input" value="No" v-model="form.two_question">
                                    <label class="custom-control-label" for="no2">No</label>
                                </div>
                                <br>
                                <span class="text-danger" v-if="errors.two_question">{{ errors.two_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>3. Do you have any of the following illnes:: Asthma, TB, Diabetes, Hypertension, Cardio Vascular Disease, Obesity or other that can compromise your immunity?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="yes3" name="three_question" class="custom-control-input" value="Yes" v-model="form.three_question">
                                    <label class="custom-control-label" for="yes3" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no3" name="three_question" class="custom-control-input" value="No" v-model="form.three_question">
                                    <label class="custom-control-label" for="no3">No</label>
                                </div>
                                <br>
                                 <span class="text-danger" v-if="errors.three_question">{{ errors.three_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>4. Any members of your household experience any of the symptoms in #2?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="yes4" name="four_question" class="custom-control-input" value="Yes" v-model="form.four_question">
                                    <label class="custom-control-label" for="yes4" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no4" name="four_question" class="custom-control-input" value="No" v-model="form.four_question">
                                    <label class="custom-control-label" for="no4">No</label>
                                </div>
                                <br>    
                                 <span class="text-danger" v-if="errors.four_question">{{ errors.four_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>5. Have you worked together or stayed in the same close environment of a confirmed COVID19 Case?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="yes5" name="five_question" class="custom-control-input" value="Yes" v-model="form.five_question">
                                    <label class="custom-control-label" for="yes5" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no5" name="five_question" class="custom-control-input" value="No" v-model="form.five_question">
                                    <label class="custom-control-label" for="no5">No</label>
                                </div>
                                <br>
                                <span class="text-danger" v-if="errors.five_question">{{ errors.five_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>6. Have you had any contacts with anyone with fever, colds, cough, sore throat, aches and pains, nasal congestion, runny nose, diarrhea or difficulty in breathing in the past 14 days?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="yes6" name="six_question" class="custom-control-input" value="Yes" v-model="form.six_question">
                                    <label class="custom-control-label" for="yes6" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no6" name="six_question" class="custom-control-input" value="No" v-model="form.six_question">
                                    <label class="custom-control-label" for="no6">No</label>
                                </div>
                                <br>
                                <span class="text-danger" v-if="errors.six_question">{{ errors.six_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>7. Have you travelled to any area in NCR aside from your home?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="yes7" name="seven_question" class="custom-control-input" value="Yes" v-model="form.seven_question">
                                    <label class="custom-control-label" for="yes7" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no7" name="seven_question" class="custom-control-input" value="No" v-model="form.seven_question">
                                    <label class="custom-control-label" for="no7">No</label>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <h4>If yes, where?</h4> 
                                        <input type="text" class="form-control" v-model="form.seven_yes_desc">
                                    </div>
                                </div>

                                <br>
                                <span class="text-danger" v-if="errors.seven_question">{{ errors.seven_question[0] }}</span>
                                
                            </div>

                            <div class="col-md-12 mb-3">
                                <h4>8. Was there any confirmed cases of COVID19 within your barangay?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="yes8" name="eight_question" class="custom-control-input" value="Yes" v-model="form.eight_question">
                                    <label class="custom-control-label" for="yes8" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no8" name="eight_question" class="custom-control-input" value="No" v-model="form.eight_question">
                                    <label class="custom-control-label" for="no8">No</label>
                                </div>
                                <br>
                                <span class="text-danger" v-if="errors.eight_question">{{ errors.eight_question[0] }}</span>

                                <hr>
                            </div>

                           
                        </div>             
                    </div>

                    <div class="modal-footer text-center">
                        <button id="edit_btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="saveCheckForm(form)" style="width:150px;">Save</button>
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
                form: [],
                loading : false,
                table_loading : false,
            }
        },
        created(){

        },
        methods:{
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
                if(employee.name){
                    this.employee = employee;
                }else{
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Your name is not similar to Door/Face ID Access. Please contact the administrator for the assistance. Thank you.',
                        icon: 'warning',
                        confirmButtonText: 'Okay'
                    });
                }
            },
            clearForm(){
                this.employee = [];
                this.form = [];
                this.form.temperature = "";
                this.form.one_question = "";
                this.form.two_question = "";
                this.form.three_question = "";
                this.form.four_question = "";
                this.form.five_question = "";
                this.form.six_question = "";
                this.form.seven_question = "";
                this.form.seven_yes_desc = "";
                this.form.eight_question = "";
            },
            saveCheckForm(form){
                this.loading = true;
                if(this.employee.name){
                   

                    let formData = new FormData();
                    formData.append('employee_id',  this.employee.id);
                    formData.append('name',  this.employee.name);
                    formData.append('user_id',  this.employee.user_id);
                    formData.append('face_user_id',  this.employee.face_user_id);
                    var dept =  this.employee.departments ? this.employee.departments[0].name : "";
                    var company =  this.employee.companies ? this.employee.companies[0].name : "";
                    formData.append('dept_bu_position', dept  + '/' + company + '/' + this.employee.position);
                    formData.append('contact_number',  this.employee.mobile_number);
                    formData.append('temperature', form.temperature ? form.temperature  : "");
                    formData.append('one_question', form.one_question ? form.one_question : "");
                    formData.append('two_question', form.two_question ? form.two_question : "");
                    formData.append('three_question', form.three_question ? form.three_question : "");
                    formData.append('four_question', form.four_question ? form.four_question : "");
                    formData.append('five_question', form.five_question ? form.five_question : "");
                    formData.append('six_question', form.six_question ? form.six_question : "");
                    formData.append('seven_question', form.seven_question ? form.seven_question : "");
                    formData.append('seven_yes_desc', form.seven_yes_desc ? form.seven_yes_desc : "");
                    formData.append('eight_question', form.eight_question ? form.eight_question : "");
                    
                    axios.post(`/save-health-declaration`, 
                        formData
                    )
                    .then(response => {
                        var message = response.data;
                        if(message == 'saved'){
                            Swal.fire({
                                title: 'Success!',
                                text: 'Successfully Checked.',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            });

                            $('#checkModal').modal('hide');

                            this.clearForm();
                            this.loading = false;
                        }
                        else if(message == 'not_allowed'){
                            Swal.fire({
                                title: 'Warning!',
                                text: 'You are not allowed to pass. Your access has been temporarily disabled.',
                                icon: 'warning',
                                confirmButtonText: 'Okay'
                            });
                            $('#checkModal').modal('hide');

                            this.clearForm();
                            this.loading = false;
                        
                        }
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;

                        Swal.fire({
                            title: 'Warning!',
                            text: 'Warning!',
                            icon: 'warning',
                            confirmButtonText: 'Okay'
                        });

                        this.loading = false;
                    })
                }else{
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Your name is not similar to Door/Face ID Access. Please contact the administrator for the assistance. Thank you.',
                        icon: 'warning',
                        confirmButtonText: 'Okay'
                    });

                    this.loading = false;
                }
            }   
        }
    }
</script>

<style lang="scss" scoped>

</style>