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
                                                <td class="text-center"><button type="button" :disabled="employee.already_check" class="btn btn-primary btn-sm" style="font-size:14px;" @click="checkEmployee(employee)" data-toggle="modal" data-target="#checkModal" >Check</button></td>
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
                                    <input type="number" class="form-control" v-model="form.temperature">
                                    <br>
                                    <span class="text-danger" v-if="errors.temperature">{{ errors.temperature[0] }}</span>
                                </div>
                                
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>1. Did you visit a hospital, clinic or medical health facility in the past 14 days?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="yes1" name="one_question" class="custom-control-input" value="Yes" v-model="form.one_question" @click="validateTemperature">
                                    <label class="custom-control-label" for="yes1" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no1" name="one_question" class="custom-control-input" value="No" v-model="form.one_question" @click="validateTemperature">
                                    <label class="custom-control-label" for="no1">No</label>
                                </div>
                                <br>
                                <span class="text-danger" v-if="errors.one_question">{{ errors.one_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>2. In the last 14 days, did you have any of the following: fever, colds, cough, sore throat, aches and\ pains, nasal congestion, runny nose, diarrhea or difficulty in breathing?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="yes2" name="two_question" class="custom-control-input" value="Yes" v-model="form.two_question" @click="validateTemperature">
                                    <label class="custom-control-label" for="yes2" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no2" name="two_question" class="custom-control-input" value="No" v-model="form.two_question" @click="validateTemperature">
                                    <label class="custom-control-label" for="no2">No</label>
                                </div>
                                <br>
                                <span class="text-danger" v-if="errors.two_question">{{ errors.two_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>3. In the last 14 days, did you have contact with anyone with symptoms indicated in item #2?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="yes3" name="three_question" class="custom-control-input" value="Yes" v-model="form.three_question" @click="validateTemperature">
                                    <label class="custom-control-label" for="yes3" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no3" name="three_question" class="custom-control-input" value="No" v-model="form.three_question" @click="validateTemperature">
                                    <label class="custom-control-label" for="no3">No</label>
                                </div>
                                <br>
                                 <span class="text-danger" v-if="errors.three_question">{{ errors.three_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>4. Have you worked together or stayed in the same close environment of a confirmed COVID19 Case?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="yes4" name="four_question" class="custom-control-input" value="Yes" v-model="form.four_question" @click="validateTemperature">
                                    <label class="custom-control-label" for="yes4" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no4" name="four_question" class="custom-control-input" value="No" v-model="form.four_question" @click="validateTemperature">
                                    <label class="custom-control-label" for="no4">No</label>
                                </div>
                                <br>    
                                 <span class="text-danger" v-if="errors.four_question">{{ errors.four_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>5. Was there any confirmed cases of Covid19 within your block?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="yes5" name="five_question" class="custom-control-input" value="Yes" v-model="form.five_question" @click="validateTemperature">
                                    <label class="custom-control-label" for="yes5" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no5" name="five_question" class="custom-control-input" value="No" v-model="form.five_question" @click="validateTemperature">
                                    <label class="custom-control-label" for="no5">No</label>
                                </div>
                                <br>
                                <span class="text-danger" v-if="errors.five_question">{{ errors.five_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>6. Have you travelled to any area in your city aside from your home?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="yes6" name="six_question" class="custom-control-input" value="Yes" v-model="form.six_question" @click="validateTemperature">
                                    <label class="custom-control-label" for="yes6" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no6" name="six_question" class="custom-control-input" value="No" v-model="form.six_question" @click="validateTemperature">
                                    <label class="custom-control-label" for="no6">No</label>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <div class="form-group">
                                        <h4>If yes, where?</h4> 
                                        <input type="text" class="form-control" v-model="form.six_yes_desc" @click="validateTemperature" @input="form.six_yes_desc=$event.target.value.toUpperCase()">
                                    </div>
                                </div>

                                <br>
                                <span class="text-danger" v-if="errors.six_question">{{ errors.six_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>7. Do you have any of the following illness: Asthma, TB, Diabetes, Hypertension, Cardio Vascular Disease, Obesity or other that can compromise your immunity?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="yes7" name="seven_question" class="custom-control-input" value="Yes" v-model="form.seven_question" @click="validateTemperature">
                                    <label class="custom-control-label" for="yes7" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="no7" name="seven_question" class="custom-control-input" value="No" v-model="form.seven_question" @click="validateTemperature">
                                    <label class="custom-control-label" for="no7">No</label>
                                </div>
                                <br>
                                <span class="text-danger" v-if="errors.seven_question">{{ errors.seven_question[0] }}</span>
                                
                            </div>
                        </div>             
                    </div>

                    <div class="modal-footer text-center">
                        <button id="edit_btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="saveCheckForm(form)" style="width:150px;">Check</button>
                    </div>

                </div>
            </div>
        </div>


        <!-- IC EMPLOYEE -->

        <!-- <div class="container-fluid">
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
                                                <th>Agency</th>
                                                <th>Check</th>
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
                                                <td>{{ ic_employee.agency_name }}</td>
                                                <td><button type="button" :disabled="ic_employee.already_check" class="btn btn-primary btn-sm" style="font-size:14px;" @click="checkICEmployee(ic_employee)" data-toggle="modal" data-target="#iccheckModal" >Check</button></td>
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


        <div class="modal fade" id="iccheckModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
                                <h3>Name: {{ic_employee.name}}</h3>
                                <h3>Agency: {{ ic_employee.agency_name }}</h3>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h4>Temperature</h4> 
                                    <input type="number" class="form-control" v-model="form_ic.temperature">
                                    <br>
                                    <span class="text-danger" v-if="errors.temperature">{{ errors.temperature[0] }}</span>
                                </div>
                                
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>1. Did you visit a hospital, clinic or medical health facility in the past 14 days?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="ic_yes1" name="ic_one_question" class="custom-control-input" value="Yes" v-model="form_ic.one_question" @click="validateICTemperature">
                                    <label class="custom-control-label" for="ic_yes1" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="ic_no1" name="ic_one_question" class="custom-control-input" value="No" v-model="form_ic.one_question" @click="validateICTemperature">
                                    <label class="custom-control-label" for="ic_no1">No</label>
                                </div>
                                <br>
                                <span class="text-danger" v-if="errors.one_question">{{ errors.one_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>2. In the last 14 days, did you have any of the following: fever, colds, cough, sore throat, aches and\ pains, nasal congestion, runny nose, diarrhea or difficulty in breathing?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="ic_yes2" name="ic_two_question" class="custom-control-input" value="Yes" v-model="form_ic.two_question" @click="validateICTemperature">
                                    <label class="custom-control-label" for="ic_yes2" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="ic_no2" name="ic_two_question" class="custom-control-input" value="No" v-model="form_ic.two_question" @click="validateICTemperature">
                                    <label class="custom-control-label" for="ic_no2">No</label>
                                </div>
                                <br>
                                <span class="text-danger" v-if="errors.two_question">{{ errors.two_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>3. In the last 14 days, did you have contact with anyone with symptoms indicated in item #2?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="ic_yes3" name="ic_three_question" class="custom-control-input" value="Yes" v-model="form_ic.three_question" @click="validateICTemperature">
                                    <label class="custom-control-label" for="ic_yes3" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="ic_no3" name="ic_three_question" class="custom-control-input" value="No" v-model="form_ic.three_question" @click="validateICTemperature">
                                    <label class="custom-control-label" for="ic_no3">No</label>
                                </div>
                                <br>
                                 <span class="text-danger" v-if="errors.three_question">{{ errors.three_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>4. Have you worked together or stayed in the same close environment of a confirmed COVID19 Case?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="ic_yes4" name="ic_four_question" class="custom-control-input" value="Yes" v-model="form_ic.four_question" @click="validateICTemperature">
                                    <label class="custom-control-label" for="ic_yes4" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="ic_no4" name="ic_four_question" class="custom-control-input" value="No" v-model="form_ic.four_question" @click="validateICTemperature">
                                    <label class="custom-control-label" for="ic_no4">No</label>
                                </div>
                                <br>    
                                 <span class="text-danger" v-if="errors.four_question">{{ errors.four_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>5. Was there any confirmed cases of Covid19 within your block?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="ic_yes5" name="ic_five_question" class="custom-control-input" value="Yes" v-model="form_ic.five_question" @click="validateICTemperature">
                                    <label class="custom-control-label" for="ic_yes5" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="ic_no5" name="ic_five_question" class="custom-control-input" value="No" v-model="form_ic.five_question" @click="validateICTemperature">
                                    <label class="custom-control-label" for="ic_no5">No</label>
                                </div>
                                <br>
                                <span class="text-danger" v-if="errors.five_question">{{ errors.five_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>6. Have you travelled to any area in your city aside from your home?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="ic_yes6" name="ic_six_question" class="custom-control-input" value="Yes" v-model="form_ic.six_question" @click="validateICTemperature">
                                    <label class="custom-control-label" for="ic_yes6" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="ic_no6" name="ic_six_question" class="custom-control-input" value="No" v-model="form_ic.six_question" @click="validateICTemperature">
                                    <label class="custom-control-label" for="ic_no6">No</label>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <div class="form-group">
                                        <h4>If yes, where?</h4> 
                                        <input type="text" class="form-control" v-model="form_ic.six_yes_desc" @input="form_ic.six_yes_desc=$event.target.value.toUpperCase()">
                                    </div>
                                </div>

                                <br>
                                <span class="text-danger" v-if="errors.six_question">{{ errors.six_question[0] }}</span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h4>7. Do you have any of the following illness: Asthma, TB, Diabetes, Hypertension, Cardio Vascular Disease, Obesity or other that can compromise your immunity?</h4>
                                <div class="custom-control custom-radio custom-control-inline ml-4">
                                    <input type="radio" id="ic_yes7" name="ic_seven_question" class="custom-control-input" value="Yes" v-model="form_ic.seven_question" @click="validateICTemperature">
                                    <label class="custom-control-label" for="ic_yes7" >Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="ic_no7" name="ic_seven_question" class="custom-control-input" value="No" v-model="form_ic.seven_question" @click="validateICTemperature">
                                    <label class="custom-control-label" for="ic_no7">No</label>
                                </div>
                                <br>
                                <span class="text-danger" v-if="errors.seven_question">{{ errors.seven_question[0] }}</span>
                                
                            </div>
                        </div>             
                    </div>

                    <div class="modal-footer text-center">
                        <button id="edit_btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="saveICCheckForm(form_ic)" style="width:150px;">Check</button>
                    </div>

                </div>
            </div>
        </div> -->


        <div class="modal fade" id="lockScreenModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2 class="col-12 modal-title text-center" id="addCompanyLabel">Unlock Screen</h2>
                        <small class="col-12 text-center mb-2">Automatically lock screen if employee is not allowed to pass.</small> 
                        <div class="row" style="border:2px solid;border-radius:10px;">
                            
                            <div class="col-md-12 mt-3" >
                                <i class="fas fa-lock mt-2 mb-2 text-danger" style="font-size:5em;"></i>
                                <br>
                                <h4>Warning Message</h4>
                              
                                <div v-if="lockScreen.lock_data">
                                    <strong >{{ lockScreen.lock_data.name}}</strong>
                                    <br>
                                    <span style="font-size:0.7em;">{{ lockScreen.lock_data.dept_bu_position}}</span>
                                    <br>
                                        <span style="font-size:0.7em;">{{ lockScreen.lock_data.contact_number}}</span>
                                    <br>
                                    <span class="text-danger">{{ lockScreen.lock_data.status}} to Pass</span>
                                </div>    
                            </div>
                            <div class="col-md-12 mt-3 mb-3 text-center">
                                <h4 class="mb-2">Please ask the guard to assist you at the holding area.</h4>
                                <div class="form-group">
                                    <input v-model="lock_password" id="passwordHDF" type="password" class="form-control text-center" placeholder="Password" autocomplete="off" style="font-size:1.5em;">
                                    <span class="text-danger" v-if="errors.lock_password">{{ errors.lock_password[0] }}</span>
                                </div>
                                
                                <button class="btn btn-md btn-outline-primary mt-3" @click="unlockScreen"><i class="fas fa-key"></i> Unlock</button>
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
    export default {
        data(){
            return {
                keyword_ic : '',
                keyword : '',
                searchICEmployees: [],
                ic_employees: [],
                employees: [],
                ic_employee: [],
                employee: [],
                errors: [],
                form: [],
                form_ic: [],
                ic_form: [],
                loading : false,
                table_loading : false,
                table_loading_ic : false,
                lockScreen : [],
                lock_password : ""
            }
        },
        created(){
            this.getLockScreen();
        },
        methods:{
            unlockScreen(){
                axios.post('/unlock-screen', {
                    lock_password: this.lock_password ? this.lock_password : ""
                })
                .then(response => {
                    if(response.data == 'unlock'){
                        location.reload();
                    }else{
                         Swal.fire({
                            title: 'Invalid Password!',
                            text: '',
                            icon: 'warning',
                            confirmButtonText: 'Okay'
                        }).then(okay => {
                            if (okay) {
                                location.reload();
                            }
                        });
                    }
                    
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            },
            getLockScreen(){
                axios.get('/get-session-autolock-screen')
                .then(response => { 
                    this.lockScreen = response.data;
                    if(this.lockScreen){
                        if(this.lockScreen.status == 'lock'){
                            $('#lockScreenModal').modal('show');
                        }else{
                            $('#lockScreenModal').modal('hide');
                        }
                    }else{
                        $('#lockScreenModal').modal('hide');
                    }
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            forceUppercase(e, o, prop) {
                const start = e.target.selectionStart;
                e.target.value = e.target.value.toUpperCase();
                this.$set(o, prop, e.target.value);
                e.target.setSelectionRange(start, start);
            },
            validateTemperature(){
                let v = this;
                if(v.form.temperature){
                    if(v.form.temperature > 37.5){
                        this.playSound('/sound/alarm.mp3');
                        Swal.fire({
                            title: 'Warning!',
                            text: 'You have a high temperature. Please seek assistance before you proceed. Thank you.',
                            icon: 'warning',
                            confirmButtonText: 'Okay'
                        });
                        v.clearForm();
                        $('#checkModal').modal('hide');    
                    }
                }else{
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Please fill up temperature.',
                        icon: 'warning',
                        confirmButtonText: 'Okay'
                    });
                }
            },
            validateICTemperature(){
                let v = this;
                if(v.form_ic.temperature){
                    if(v.form_ic.temperature > 37.5){
                        this.playSound('/sound/alarm.mp3');
                        Swal.fire({
                            title: 'Warning!',
                            text: 'You have a high temperature. Please seek assistance before you proceed. Thank you.',
                            icon: 'warning',
                            confirmButtonText: 'Okay'
                        });
                        v.clearICForm();
                        $('#iccheckModal').modal('hide');    
                    }
                }else{
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Please fill up temperature.',
                        icon: 'warning',
                        confirmButtonText: 'Okay'
                    });
                }
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
            fetchICEmployees(){
                this.errors = []; 
                this.ic_employees = [];
                this.table_loading_ic = true; 
                axios.post('/ic-employees', {
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
            checkEmployee(employee){
                if(employee.face_user_id){
                    this.employee = employee;
                }else{
                    this.employee = employee;
                    // Swal.fire({
                    //     title: 'Warning!',
                    //     text: 'Your name is not similar to Biometric Access. Please contact the administrator for the assistance. Thank you.',
                    //     icon: 'warning',
                    //     confirmButtonText: 'Okay'
                    // });
                }

            },
            checkICEmployee(ic_employee){
                this.ic_employee = ic_employee;
            },
            clearForm(){
                this.keyword = '';
                this.employees = [];
                this.employee = [];
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
                this.form = [];

                
            },
            clearICForm(){
                this.ic_employee = [];
                this.form_ic.temperature = "";
                this.form_ic.one_question = "";
                this.form_ic.two_question = "";
                this.form_ic.three_question = "";
                this.form_ic.four_question = "";
                this.form_ic.five_question = "";
                this.form_ic.six_question = "";
                this.form_ic.six_yes_desc = "";
                this.form_ic.seven_question = "";
                this.form_ic = [];
            },
            saveCheckForm(form){
                this.loading = true;
                
                let formData = new FormData();
                formData.append('employee_id',  this.employee.id);
                formData.append('name',  this.employee.first_name + ' ' + this.employee.last_name);
                formData.append('user_id',  this.employee.user_id);
                formData.append('face_user_id',  this.employee.face_user_id);
                var dept =  this.employee.departments ? this.employee.departments[0].name : "";
                var company =  this.employee.companies ? this.employee.companies[0].name : "";
                var location =  this.employee.locations ? this.employee.locations[0].name : "";
                formData.append('dept_bu_position', dept  + '/' + company + '/' + this.employee.position + '/' + location);
                formData.append('contact_number',  this.employee.mobile_number);
                formData.append('temperature', form.temperature ? form.temperature  : "");
                formData.append('one_question', form.one_question ? form.one_question : "");
                formData.append('two_question', form.two_question ? form.two_question : "");
                formData.append('three_question', form.three_question ? form.three_question : "");
                formData.append('four_question', form.four_question ? form.four_question : "");
                formData.append('five_question', form.five_question ? form.five_question : "");
                formData.append('six_question', form.six_question ? form.six_question : "");
                formData.append('six_yes_desc', form.six_yes_desc ? form.six_yes_desc : "");
                formData.append('seven_question', form.seven_question ? form.seven_question : "");
                
                axios.post(`/save-health-declaration`, 
                    formData
                )
                .then(response => {
                    var message = response.data;
                    if(message == 'saved'){
                        this.playSound('/sound/success.mp3');

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
                        $('#checkModal').modal('hide');
                        this.playSound('/sound/alarm.mp3');
                        Swal.fire({
                            title: 'Warning!',
                            text: 'You are not allowed to pass. Please seek assistance.',
                            icon: 'warning',
                            confirmButtonText: 'Okay'
                        });
                        
                        this.getLockScreen();

                        this.clearForm();
                        this.loading = false;
                    
                    }else{
                        Swal.fire({
                            title: 'Error!',
                            text: 'Unable to saved.',
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
                        text: 'Some error occured. Please try again!',
                        icon: 'warning',
                        confirmButtonText: 'Okay'
                    });

                    this.loading = false;
                })
            },
            saveICCheckForm(form_ic){
                this.loading = true;
                
                let formData = new FormData();
                formData.append('employee_id',  this.ic_employee.usruid);
                formData.append('name',  this.ic_employee.name);
                formData.append('dept_bu_position', this.ic_employee.agency_name);
                formData.append('temperature', form_ic.temperature ? form_ic.temperature  : "");
                formData.append('one_question', form_ic.one_question ? form_ic.one_question : "");
                formData.append('two_question', form_ic.two_question ? form_ic.two_question : "");
                formData.append('three_question', form_ic.three_question ? form_ic.three_question : "");
                formData.append('four_question', form_ic.four_question ? form_ic.four_question : "");
                formData.append('five_question', form_ic.five_question ? form_ic.five_question : "");
                formData.append('six_question', form_ic.six_question ? form_ic.six_question : "");
                formData.append('six_yes_desc', form_ic.six_yes_desc ? form_ic.six_yes_desc : "");
                formData.append('seven_question', form_ic.seven_question ? form_ic.seven_question : "");
                
                axios.post(`/save-health-declaration-ic`, 
                    formData
                )
                .then(response => {
                    var message = response.data;
                    if(message == 'saved'){
                        this.playSound('/sound/success.mp3');
                        Swal.fire({
                            title: 'Success!',
                            text: 'Successfully Checked.',
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        });

                        $('#iccheckModal').modal('hide');

                        this.clearICForm();
                        this.loading = false;
                    }
                    else if(message == 'not_allowed'){

                        this.playSound('/sound/alarm.mp3');

                        Swal.fire({
                            title: 'Warning!',
                            text: 'You are not allowed to pass. Please seek assistance.',
                            icon: 'warning',
                            confirmButtonText: 'Okay'
                        });
                        $('#checkModal').modal('hide');

                        this.clearForm();
                        this.loading = false;
                    
                    }else{
                            Swal.fire({
                            title: 'Error!',
                            text: 'Unable to saved.',
                            icon: 'warning',
                            confirmButtonText: 'Okay'
                        });
                        $('#iccheckModal').modal('hide');

                        this.clearICForm();
                        this.loading = false;
                    }
                })
                .catch(error => {
                    this.errors = error.response.data.errors;

                    Swal.fire({
                        title: 'Warning!',
                        text: 'Some error occured. Please try again!',
                        icon: 'warning',
                        confirmButtonText: 'Okay'
                    });

                    this.loading = false;
                })
            },
            playSound (sound) {
                if(sound) {
                    var audio = new Audio(sound);
                    audio.play();
                }
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>