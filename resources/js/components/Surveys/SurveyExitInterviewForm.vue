<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-success opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white text-uppercase">SURVEY : EXIT INTERVIEW FORM</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt--7 mb-6 mx-auto">
        <div class="row justify-content-center">
            <div class="col-xl-10 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="col-md-12 text-center">
                            <img src="/img/lfuggoc_logo.png" style="width:150px;height:auto;">
                        </div>
                    </div>
                    <div class="card-body">
                        <div v-if="user">
                            NAME : {{user.first_name + ' ' + user.last_name}}<br>
                            POSITION : {{position}}<br>
                            DEPARTMENT : {{department}}<br>
                            COMPANY : {{company}}<br>
                            LOCATION : {{location}}<br>
                            No. of months / years worked with the company : {{ getTenure(user.date_hired) }} <br>
                            Age : {{ getAge(user.birthdate) }}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered mt-5">
                                <tr>
                                    <td>
                                        Primary reason for leaving:
                                    </td>
                                    <td>
                                        <div class="custom-control custom-radio custom-control-inline ml-4">
                                            <input type="radio" id="a_1" name="a_1" class="custom-control-input" value="Work (abroad)" v-model="form.reason_for_leaving">
                                            <label class="custom-control-label" for="a_1" >Work (abroad)</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="a_2" name="a_1" class="custom-control-input" value="Work (other co.)" v-model="form.reason_for_leaving">
                                            <label class="custom-control-label" for="a_2">Work (other co.)</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="a_3" name="a_1" class="custom-control-input" value="Personal /Family Matter" v-model="form.reason_for_leaving"> 
                                            <label class="custom-control-label" for="a_3">Personal /Family Matter</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="a_4" name="a_1" class="custom-control-input" value="Migration/Relocation" v-model="form.reason_for_leaving">
                                            <label class="custom-control-label" for="a_4">Migration/Relocation </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="a_5" name="a_1" class="custom-control-input" value="Others" v-model="form.reason_for_leaving">
                                            <label class="custom-control-label" for="a_5">Others </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        If primary reason is others, please indicate reason:   
                                    </td>
                                    <td width="80%">
                                        <textarea name="" id="" cols="30" rows="5" class="form-control" placeholder="Input Here" v-model="form.reason_for_leaving_others"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        If primary reason is work, please indicate main consideration:            
                                    </td>
                                    <td width="80%">
                                        <div class="custom-control custom-radio custom-control-inline ml-4">
                                            <input type="radio" id="b_1" name="b_1" class="custom-control-input" value="Salary" v-model="form.reason_is_work">
                                            <label class="custom-control-label" for="b_1" >Salary</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="b_2" name="b_1" class="custom-control-input" value="Career dev't. " v-model="form.reason_is_work">
                                            <label class="custom-control-label" for="b_2">Career dev't. </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="b_3" name="b_1" class="custom-control-input" value="Personal /Family Matter" v-model="form.reason_is_work">
                                            <label class="custom-control-label" for="b_3">Personal /Family Matter</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="b_4" name="b_1" class="custom-control-input" value="Location" v-model="form.reason_is_work">
                                            <label class="custom-control-label" for="b_4">Location</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="b_5" name="b_1" class="custom-control-input" value="Others" v-model="form.reason_is_work">
                                            <label class="custom-control-label" for="b_5">Others </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%">
                                        If primary reason is others, please indicate reason:           
                                    </td>
                                    <td width="80%">
                                        <textarea name="" id="" cols="30" rows="5" class="form-control" placeholder="Input Here" v-model="form.reason_is_work_others"></textarea>
                                    </td>
                                </tr>
                            </table>

                            <table class="table table-bordered mt-5">
                                <thead>
                                    <tr class="text-center" style="background-color:#00713A;color:white">
                                        <th width="20%">STATEMENTS</th>
                                        <th width="60%">RATING</th>
                                        <th width="20%">COMMENTS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- R1 -->
                                    <tr style="background-color:#CAD1D7">
                                        <td><strong>My immediate superior regularly gives me feedback and coaching and does the following:</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <!-- R1 A ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>a.  Meets team regularly</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r1_a_1" name="r1_a" class="custom-control-input" value="1" v-model="form.r1_a">
                                                <label class="custom-control-label" for="r1_a_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r1_a_2" name="r1_a" class="custom-control-input" value="2" v-model="form.r1_a">
                                                <label class="custom-control-label" for="r1_a_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r1_a_3" name="r1_a" class="custom-control-input" value="3" v-model="form.r1_a">
                                                <label class="custom-control-label" for="r1_a_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r1_a_4" name="r1_a" class="custom-control-input" value="4" v-model="form.r1_a">
                                                <label class="custom-control-label" for="r1_a_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r1_a_comments"></textarea>
                                        </td>
                                    </tr>
                                    <!-- R1 B  ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>b. Discusses performance evaluation through one-on-one discussion and feedback</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r1_b_1" name="r1_b" class="custom-control-input" value="1" v-model="form.r1_b">
                                                <label class="custom-control-label" for="r1_b_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r1_b_2" name="r1_b" class="custom-control-input" value="2" v-model="form.r1_b">
                                                <label class="custom-control-label" for="r1_b_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r1_b_3" name="r1_b" class="custom-control-input" value="3" v-model="form.r1_b">
                                                <label class="custom-control-label" for="r1_b_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r1_b_4" name="r1_b" class="custom-control-input" value="4" v-model="form.r1_b">
                                                <label class="custom-control-label" for="r1_b_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r1_b_comments"></textarea>
                                        </td>
                                    </tr>
                                    <!-- R1 C  ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>c. Helps employee develop a passion for work</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r1_c_1" name="r1_c" class="custom-control-input" value="1" v-model="form.r1_c">
                                                <label class="custom-control-label" for="r1_c_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r1_c_2" name="r1_c" class="custom-control-input" value="2" v-model="form.r1_c">
                                                <label class="custom-control-label" for="r1_c_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r1_c_3" name="r1_c" class="custom-control-input" value="3" v-model="form.r1_c">
                                                <label class="custom-control-label" for="r1_c_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r1_c_4" name="r1_c" class="custom-control-input" value="4" v-model="form.r1_c">
                                                <label class="custom-control-label" for="r1_c_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r1_c_comments"></textarea>
                                        </td>
                                    </tr>
                                    <!-- R1 D  ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>d. Communicates reasons for changes and decisions</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r1_d_1" name="r1_d" class="custom-control-input" value="1" v-model="form.r1_d">
                                                <label class="custom-control-label" for="r1_d_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r1_d_2" name="r1_d" class="custom-control-input" value="2" v-model="form.r1_d">
                                                <label class="custom-control-label" for="r1_d_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r1_d_3" name="r1_d" class="custom-control-input" value="3" v-model="form.r1_d">
                                                <label class="custom-control-label" for="r1_d_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r1_d_4" name="r1_d" class="custom-control-input" value="4" v-model="form.r1_d">
                                                <label class="custom-control-label" for="r1_d_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r1_d_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R2 -->
                                    <tr style="background-color:#CAD1D7">
                                        <td><strong>My team works well together and promotes a harmonious and positive working <br> environment for me which can be observed through the following:</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <!-- R2 A ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>a. Staff are treated with dignity and respect</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r2_a_1" name="r2_a" class="custom-control-input" value="1" v-model="form.r2_a">
                                                <label class="custom-control-label" for="r2_a_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r2_a_2" name="r2_a" class="custom-control-input" value="2" v-model="form.r2_a">
                                                <label class="custom-control-label" for="r2_a_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r2_a_3" name="r2_a" class="custom-control-input" value="3" v-model="form.r2_a">
                                                <label class="custom-control-label" for="r2_a_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r2_a_4" name="r2_a" class="custom-control-input" value="4" v-model="form.r2_a">
                                                <label class="custom-control-label" for="r2_a_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r2_a_comments"></textarea>
                                        </td>
                                    </tr>
                                    <!-- R2 B ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>b. Conflicts and issues among the team are being addressed.</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r2_b_1" name="r2_b" class="custom-control-input" value="1" v-model="form.r2_b">
                                                <label class="custom-control-label" for="r2_b_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r2_b_2" name="r2_b" class="custom-control-input" value="2" v-model="form.r2_b">
                                                <label class="custom-control-label" for="r2_b_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r2_b_3" name="r2_b" class="custom-control-input" value="3" v-model="form.r2_b">
                                                <label class="custom-control-label" for="r2_b_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r2_b_4" name="r2_b" class="custom-control-input" value="4" v-model="form.r2_b">
                                                <label class="custom-control-label" for="r2_b_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r2_b_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R2 C ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>c. Each team member has trust and constant collaboration.</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r2_c_1" name="r2_c" class="custom-control-input" value="1" v-model="form.r2_c">
                                                <label class="custom-control-label" for="r2_c_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r2_c_2" name="r2_c" class="custom-control-input" value="2" v-model="form.r2_c">
                                                <label class="custom-control-label" for="r2_c_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r2_c_3" name="r2_c" class="custom-control-input" value="3" v-model="form.r2_c">
                                                <label class="custom-control-label" for="r2_c_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r2_c_4" name="r2_c" class="custom-control-input" value="4" v-model="form.r2_c">
                                                <label class="custom-control-label" for="r2_c_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r2_c_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R3 -->
                                    <tr style="background-color:#CAD1D7">
                                        <td><strong>My job responsibilities and performance expectations are clear.</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <!-- R3 A ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>a. Description of the job is accurate</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r3_a_1" name="r3_a" class="custom-control-input" value="1" v-model="form.r3_a">
                                                <label class="custom-control-label" for="r3_a_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r3_a_2" name="r3_a" class="custom-control-input" value="2" v-model="form.r3_a">
                                                <label class="custom-control-label" for="r3_a_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r3_a_3" name="r3_a" class="custom-control-input" value="3" v-model="form.r3_a">
                                                <label class="custom-control-label" for="r3_a_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r3_a_4" name="r3_a" class="custom-control-input" value="4" v-model="form.r3_a">
                                                <label class="custom-control-label" for="r3_a_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r3_a_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R3 B ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>b. Goals and targets of the role were made clear throughout my employment</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r3_b_1" name="r3_b" class="custom-control-input" value="1" v-model="form.r3_b">
                                                <label class="custom-control-label" for="r3_b_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r3_b_2" name="r3_b" class="custom-control-input" value="2" v-model="form.r3_b">
                                                <label class="custom-control-label" for="r3_b_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r3_b_3" name="r3_b" class="custom-control-input" value="3" v-model="form.r3_b">
                                                <label class="custom-control-label" for="r3_b_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r3_b_4" name="r3_b" class="custom-control-input" value="4" v-model="form.r3_b">
                                                <label class="custom-control-label" for="r3_b_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r3_b_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R3 C ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>c. I received appropriate support to do my job</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r3_c_1" name="r3_c" class="custom-control-input" value="1" v-model="form.r3_c">
                                                <label class="custom-control-label" for="r3_c_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r3_c_2" name="r3_c" class="custom-control-input" value="2" v-model="form.r3_c">
                                                <label class="custom-control-label" for="r3_c_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r3_c_3" name="r3_c" class="custom-control-input" value="3" v-model="form.r3_c">
                                                <label class="custom-control-label" for="r3_c_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r3_c_4" name="r3_c" class="custom-control-input" value="4" v-model="form.r3_c">
                                                <label class="custom-control-label" for="r3_c_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r3_c_comments"></textarea>
                                        </td>
                                    </tr>
                                    
                                    <!-- R4 -->
                                    <tr style="background-color:#CAD1D7">
                                        <td><strong>The company provides opportunities for career growth and advancement.</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <!-- R4 A ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>a. Career path was identified and defined for my role</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r4_a_1" name="r4_a" class="custom-control-input" value="1" v-model="form.r4_a">
                                                <label class="custom-control-label" for="r4_a_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r4_a_2" name="r4_a" class="custom-control-input" value="2" v-model="form.r4_a">
                                                <label class="custom-control-label" for="r4_a_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r4_a_3" name="r4_a" class="custom-control-input" value="3" v-model="form.r4_a">
                                                <label class="custom-control-label" for="r4_a_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r4_a_4" name="r4_a" class="custom-control-input" value="4" v-model="form.r4_a">
                                                <label class="custom-control-label" for="r4_a_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r4_a_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R4 B ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>b. My skills and willingness to contibute was recognized</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r4_b_1" name="r4_b" class="custom-control-input" value="1" v-model="form.r4_b">
                                                <label class="custom-control-label" for="r4_b_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r4_b_2" name="r4_b" class="custom-control-input" value="2" v-model="form.r4_b">
                                                <label class="custom-control-label" for="r4_b_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r4_b_3" name="r4_b" class="custom-control-input" value="3" v-model="form.r4_b">
                                                <label class="custom-control-label" for="r4_b_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r4_b_4" name="r4_b" class="custom-control-input" value="4" v-model="form.r4_b">
                                                <label class="custom-control-label" for="r4_b_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r4_b_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R4 C ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>c. I see opportunies for development and promotion within the organization</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r4_c_1" name="r4_c" class="custom-control-input" value="1" v-model="form.r4_c">
                                                <label class="custom-control-label" for="r4_c_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r4_c_2" name="r4_c" class="custom-control-input" value="2" v-model="form.r4_c">
                                                <label class="custom-control-label" for="r4_c_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r4_c_3" name="r4_c" class="custom-control-input" value="3" v-model="form.r4_c">
                                                <label class="custom-control-label" for="r4_c_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r4_c_4" name="r4_c" class="custom-control-input" value="4" v-model="form.r4_c">
                                                <label class="custom-control-label" for="r4_c_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r4_c_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R5 -->
                                    <tr style="background-color:#CAD1D7">
                                        <td><strong>The company provides trainings and other opportunities for learning and development.</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <!-- R5 A ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>a. I received adequate training that enable me to do my job</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r5_a_1" name="r5_a" class="custom-control-input" value="1" v-model="form.r5_a">
                                                <label class="custom-control-label" for="r5_a_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r5_a_2" name="r5_a" class="custom-control-input" value="2" v-model="form.r5_a">
                                                <label class="custom-control-label" for="r5_a_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r5_a_3" name="r5_a" class="custom-control-input" value="3" v-model="form.r5_a">
                                                <label class="custom-control-label" for="r5_a_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r5_a_4" name="r5_a" class="custom-control-input" value="4" v-model="form.r5_a">
                                                <label class="custom-control-label" for="r5_a_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r5_a_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R5 B ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>b. I see further training opportunities  that the company is offering </td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r5_b_1" name="r5_b" class="custom-control-input" value="1" v-model="form.r5_b">
                                                <label class="custom-control-label" for="r5_b_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r5_b_2" name="r5_b" class="custom-control-input" value="2" v-model="form.r5_b">
                                                <label class="custom-control-label" for="r5_b_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r5_b_3" name="r5_b" class="custom-control-input" value="3" v-model="form.r5_b">
                                                <label class="custom-control-label" for="r5_b_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r5_b_4" name="r5_b" class="custom-control-input" value="4" v-model="form.r5_b">
                                                <label class="custom-control-label" for="r5_b_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r5_b_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R6 -->
                                    <tr style="background-color:#CAD1D7">
                                        <td><strong>The company's systems and processes are clear and efficient.</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <!-- R6 A ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>a. HR policies and processes</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r6_a_1" name="r6_a" class="custom-control-input" value="1" v-model="form.r6_a">
                                                <label class="custom-control-label" for="r6_a_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r6_a_2" name="r6_a" class="custom-control-input" value="2" v-model="form.r6_a">
                                                <label class="custom-control-label" for="r6_a_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r6_a_3" name="r6_a" class="custom-control-input" value="3" v-model="form.r6_a">
                                                <label class="custom-control-label" for="r6_a_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r6_a_4" name="r6_a" class="custom-control-input" value="4" v-model="form.r6_a">
                                                <label class="custom-control-label" for="r6_a_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r6_a_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R6 B ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>b. Finance policies and processes</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r6_b_1" name="r6_b" class="custom-control-input" value="1" v-model="form.r6_b">
                                                <label class="custom-control-label" for="r6_b_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r6_b_2" name="r6_b" class="custom-control-input" value="2" v-model="form.r6_b">
                                                <label class="custom-control-label" for="r6_b_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r6_b_3" name="r6_b" class="custom-control-input" value="3" v-model="form.r6_b">
                                                <label class="custom-control-label" for="r6_b_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r6_b_4" name="r6_b" class="custom-control-input" value="4" v-model="form.r6_b">
                                                <label class="custom-control-label" for="r6_b_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r6_b_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R6 C ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>c. Own department policies and processes</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r6_c_1" name="r6_c" class="custom-control-input" value="1" v-model="form.r6_c">
                                                <label class="custom-control-label" for="r6_c_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r6_c_2" name="r6_c" class="custom-control-input" value="2" v-model="form.r6_c"> 
                                                <label class="custom-control-label" for="r6_c_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r6_c_3" name="r6_c" class="custom-control-input" value="3" v-model="form.r6_c">
                                                <label class="custom-control-label" for="r6_c_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r6_c_4" name="r6_c" class="custom-control-input" value="4" v-model="form.r6_c">
                                                <label class="custom-control-label" for="r6_c_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r6_c_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R6 D ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>d. Other department's policies and processes</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r6_d_1" name="r6_d" class="custom-control-input" value="1" v-model="form.r6_d">
                                                <label class="custom-control-label" for="r6_d_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r6_d_2" name="r6_d" class="custom-control-input" value="2" v-model="form.r6_d">
                                                <label class="custom-control-label" for="r6_d_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r6_d_3" name="r6_d" class="custom-control-input" value="3" v-model="form.r6_d">
                                                <label class="custom-control-label" for="r6_d_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r6_d_4" name="r6_d" class="custom-control-input" value="4" v-model="form.r6_d">
                                                <label class="custom-control-label" for="r6_d_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r6_d_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R7 -->
                                    <tr style="background-color:#CAD1D7">
                                        <td><strong>The company provides a competitive compensation and benefits package.</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <!-- R7 A ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>a. Salaries & Wages</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r7_a_1" name="r7_a" class="custom-control-input" value="1" v-model="form.r7_a">
                                                <label class="custom-control-label" for="r7_a_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r7_a_2" name="r7_a" class="custom-control-input" value="2" v-model="form.r7_a">
                                                <label class="custom-control-label" for="r7_a_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r7_a_3" name="r7_a" class="custom-control-input" value="3" v-model="form.r7_a">
                                                <label class="custom-control-label" for="r7_a_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r7_a_4" name="r7_a" class="custom-control-input" value="4" v-model="form.r7_a">
                                                <label class="custom-control-label" for="r7_a_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r7_a_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R7 B ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>b. Health Insurance</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r7_b_1" name="r7_b" class="custom-control-input" value="1" v-model="form.r7_b">
                                                <label class="custom-control-label" for="r7_b_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r7_b_2" name="r7_b" class="custom-control-input" value="2" v-model="form.r7_b">
                                                <label class="custom-control-label" for="r7_b_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r7_b_3" name="r7_b" class="custom-control-input" value="3" v-model="form.r7_b">
                                                <label class="custom-control-label" for="r7_b_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r7_b_4" name="r7_b" class="custom-control-input" value="4" v-model="form.r7_b">
                                                <label class="custom-control-label" for="r7_b_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r7_b_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R7 C ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>c. Rest Days, Paid Vacation & Sick Leave</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r7_c_1" name="r7_c" class="custom-control-input" value="1" v-model="form.r7_c">
                                                <label class="custom-control-label" for="r7_c_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r7_c_2" name="r7_c" class="custom-control-input" value="2" v-model="form.r7_c">
                                                <label class="custom-control-label" for="r7_c_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r7_c_3" name="r7_c" class="custom-control-input" value="3" v-model="form.r7_c">
                                                <label class="custom-control-label" for="r7_c_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r7_c_4" name="r7_c" class="custom-control-input" value="4" v-model="form.r7_c">
                                                <label class="custom-control-label" for="r7_c_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r7_c_comments"></textarea>
                                        </td>
                                    </tr>
                                    <!-- R7 D ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>d. Other benefits and bonuses</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r7_d_1" name="r7_d" class="custom-control-input" value="1" v-model="form.r7_d">
                                                <label class="custom-control-label" for="r7_d_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r7_d_2" name="r7_d" class="custom-control-input" value="2" v-model="form.r7_d">
                                                <label class="custom-control-label" for="r7_d_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r7_d_3" name="r7_d" class="custom-control-input" value="3" v-model="form.r7_d">
                                                <label class="custom-control-label" for="r7_d_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r7_d_4" name="r7_d" class="custom-control-input" value="4" v-model="form.r7_d">
                                                <label class="custom-control-label" for="r7_d_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r7_d_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R8 -->
                                    <tr style="background-color:#CAD1D7">
                                        <td><strong>The company's culture is generally positive.</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <!-- R8 A ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>a. The company supports, respects and values my effort</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r8_a_1" name="r8_a" class="custom-control-input" value="1" v-model="form.r8_a">
                                                <label class="custom-control-label" for="r8_a_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r8_a_2" name="r8_a" class="custom-control-input" value="2" v-model="form.r8_a">
                                                <label class="custom-control-label" for="r8_a_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r8_a_3" name="r8_a" class="custom-control-input" value="3" v-model="form.r8_a">
                                                <label class="custom-control-label" for="r8_a_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r8_a_4" name="r8_a" class="custom-control-input" value="4" v-model="form.r8_a">
                                                <label class="custom-control-label" for="r8_a_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r8_a_comments"></textarea>
                                        </td>
                                    </tr>
                                    <!-- R8 B ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>b.  People are approachable and easy to work with.</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r8_b_1" name="r8_b" class="custom-control-input" value="1" v-model="form.r8_b">
                                                <label class="custom-control-label" for="r8_b_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r8_b_2" name="r8_b" class="custom-control-input" value="2" v-model="form.r8_b">
                                                <label class="custom-control-label" for="r8_b_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r8_b_3" name="r8_b" class="custom-control-input" value="3" v-model="form.r8_b">
                                                <label class="custom-control-label" for="r8_b_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r8_b_4" name="r8_b" class="custom-control-input" value="4" v-model="form.r8_b">
                                                <label class="custom-control-label" for="r8_b_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r8_b_comments"></textarea>
                                        </td>
                                    </tr>
                                    <!-- R8 C ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>c.  Generally has a good working environment.</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r8_c_1" name="r8_c" class="custom-control-input" value="1" v-model="form.r8_c">
                                                <label class="custom-control-label" for="r8_c_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r8_c_2" name="r8_c" class="custom-control-input" value="2" v-model="form.r8_c">
                                                <label class="custom-control-label" for="r8_c_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r8_c_3" name="r8_c" class="custom-control-input" value="3" v-model="form.r8_c">
                                                <label class="custom-control-label" for="r8_c_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r8_c_4" name="r8_c" class="custom-control-input" value="4" v-model="form.r8_c">
                                                <label class="custom-control-label" for="r8_c_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r8_c_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R9 -->
                                    <tr style="background-color:#CAD1D7">
                                        <td><strong>The company's leaders display good leadership qualities and expertise in their field.</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <!-- R9 A ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>a. I'am comfortable talking to my manager</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r9_a_1" name="r9_a" class="custom-control-input" value="1" v-model="form.r9_a">
                                                <label class="custom-control-label" for="r9_a_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r9_a_2" name="r9_a" class="custom-control-input" value="2" v-model="form.r9_a">
                                                <label class="custom-control-label" for="r9_a_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r9_a_3" name="r9_a" class="custom-control-input" value="3" v-model="form.r9_a">
                                                <label class="custom-control-label" for="r9_a_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r9_a_4" name="r9_a" class="custom-control-input" value="4" v-model="form.r9_a">
                                                <label class="custom-control-label" for="r9_a_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r9_a_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R9 B ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>b. My immediate superior delegated work and responsibility consistent with my ability</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r9_b_1" name="r9_b" class="custom-control-input" value="1" v-model="form.r9_b">
                                                <label class="custom-control-label" for="r9_b_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r9_b_2" name="r9_b" class="custom-control-input" value="2" v-model="form.r9_b">
                                                <label class="custom-control-label" for="r9_b_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r9_b_3" name="r9_b" class="custom-control-input" value="3" v-model="form.r9_b">
                                                <label class="custom-control-label" for="r9_b_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r9_b_4" name="r9_b" class="custom-control-input" value="4" v-model="form.r9_b">
                                                <label class="custom-control-label" for="r9_b_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r9_b_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R9 C ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>c. Leaders have a good understanding of the business.</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r9_c_1" name="r9_c" class="custom-control-input" value="1" v-model="form.r9_c">
                                                <label class="custom-control-label" for="r9_c_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r9_c_2" name="r9_c" class="custom-control-input" value="2" v-model="form.r9_c">
                                                <label class="custom-control-label" for="r9_c_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r9_c_3" name="r9_c" class="custom-control-input" value="3" v-model="form.r9_c">
                                                <label class="custom-control-label" for="r9_c_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r9_c_4" name="r9_c" class="custom-control-input" value="4" v-model="form.r9_c">
                                                <label class="custom-control-label" for="r9_c_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r9_c_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R9 D ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>d. My immediate superior shows our core values of Integrity, Loyalty and Excellence</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r9_d_1" name="r9_d" class="custom-control-input" value="1" v-model="form.r9_d">
                                                <label class="custom-control-label" for="r9_d_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r9_d_2" name="r9_d" class="custom-control-input" value="2" v-model="form.r9_d">
                                                <label class="custom-control-label" for="r9_d_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r9_d_3" name="r9_d" class="custom-control-input" value="3" v-model="form.r9_d">
                                                <label class="custom-control-label" for="r9_d_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r9_d_4" name="r9_d" class="custom-control-input" value="4" v-model="form.r9_d">
                                                <label class="custom-control-label" for="r9_d_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r9_d_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R10 -->
                                    <tr style="background-color:#CAD1D7">
                                        <td><strong>I am aware of and appreciate the company's engagement programs.</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <!-- R10 A ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>a. I regularly receive communications of new policies, guidelines and other company announcements.</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r10_a_1" name="r10_a" class="custom-control-input" value="1" v-model="form.r10_a">
                                                <label class="custom-control-label" for="r10_a_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r10_a_2" name="r10_a" class="custom-control-input" value="2" v-model="form.r10_a">
                                                <label class="custom-control-label" for="r10_a_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r10_a_3" name="r10_a" class="custom-control-input" value="3" v-model="form.r10_a">
                                                <label class="custom-control-label" for="r10_a_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r10_a_4" name="r10_a" class="custom-control-input" value="4" v-model="form.r10_a">
                                                <label class="custom-control-label" for="r10_a_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r10_a_comments"></textarea>
                                        </td>
                                    </tr>
                                    <!-- R10 B ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>b. Employee activities are interesting and always keep employees excited.</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r10_b_1" name="r10_b" class="custom-control-input" value="1" v-model="form.r10_b">
                                                <label class="custom-control-label" for="r10_b_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r10_b_2" name="r10_b" class="custom-control-input" value="2" v-model="form.r10_b">
                                                <label class="custom-control-label" for="r10_b_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r10_b_3" name="r10_b" class="custom-control-input" value="3" v-model="form.r10_b">
                                                <label class="custom-control-label" for="r10_b_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r10_b_4" name="r10_b" class="custom-control-input" value="4" v-model="form.r10_b">
                                                <label class="custom-control-label" for="r10_b_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r10_b_comments"></textarea>
                                        </td>
                                    </tr>
                                    <!-- R10 C ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>c. I am aware of what is happening in the organization.</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r10_c_1" name="r10_c" class="custom-control-input" value="1" v-model="form.r10_c">
                                                <label class="custom-control-label" for="r10_c_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r10_c_2" name="r10_c" class="custom-control-input" value="2" v-model="form.r10_c">
                                                <label class="custom-control-label" for="r10_c_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r10_c_3" name="r10_c" class="custom-control-input" value="3" v-model="form.r10_c">
                                                <label class="custom-control-label" for="r10_c_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r10_c_4" name="r10_c" class="custom-control-input" value="4" v-model="form.r10_c">
                                                <label class="custom-control-label" for="r10_c_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r10_c_comments"></textarea>
                                        </td>
                                    </tr>

                                    <!-- R11 -->
                                    <tr style="background-color:#CAD1D7">
                                        <td><strong>I would recommend this company to others exploring career opportunities.</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <!-- R11 A ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>a. I would recommend this company to others.</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r11_a_1" name="r11_a" class="custom-control-input" value="1" v-model="form.r11_a">
                                                <label class="custom-control-label" for="r11_a_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r11_a_2" name="r11_a" class="custom-control-input" value="2" v-model="form.r11_a">
                                                <label class="custom-control-label" for="r11_a_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r11_a_3" name="r11_a" class="custom-control-input" value="3" v-model="form.r11_a">
                                                <label class="custom-control-label" for="r11_a_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r11_a_4" name="r11_a" class="custom-control-input" value="4" v-model="form.r11_a">
                                                <label class="custom-control-label" for="r11_a_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r11_a_comments"></textarea>
                                        </td>
                                    </tr>
                                    <!-- R11 B ------------------------------------------------------------------------------------------------------------------>
                                    <tr>
                                        <td>b. I would consider returning to this company if there is an opportunity in the future.</td>
                                        <td>
                                            <div class="custom-control custom-radio custom-control-inline ml-4">
                                                <input type="radio" id="r11_b_1" name="r11_b" class="custom-control-input" value="1" v-model="form.r11_b">
                                                <label class="custom-control-label" for="r11_b_1" >1 Strongly Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r11_b_2" name="r11_b" class="custom-control-input" value="2" v-model="form.r11_b">
                                                <label class="custom-control-label" for="r11_b_2">2 Disagree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r11_b_3" name="r11_b" class="custom-control-input" value="3" v-model="form.r11_b">
                                                <label class="custom-control-label" for="r11_b_3">3 Agree</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="r11_b_4" name="r11_b" class="custom-control-input" value="4" v-model="form.r11_b">
                                                <label class="custom-control-label" for="r11_b_4">4 Strongly Agree</label>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Comments" v-model="form.r11_b_comments"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <textarea name="" id="" cols="30" rows="3" class="form-control" placeholder="Other suggestions for improvement:" v-model="form.other_suggestions_for_improvement"></textarea>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>


                        </div>
                    </div>

                    <div class="card-footer bg-white border-0">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button id="save-btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="saveSurveyExitInterview" style="width:150px;">Save</button>
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
    import Swal from 'sweetalert2'
    export default {
        data() {
           return {
                user: [],
                errors: [],
                position: '',
                department: '',
                company: '',
                location: '',
                reason_for_leaving_type : '',
                reason_for_leaving_remarks : '',
                reason_is_work : '',
                reason_is_work_remarks : '',
                form : []
            }
        },
        created () {
            this.getUser();
        },
        methods: {
            saveSurveyExitInterview(){
                let v = this;
                Swal.fire({
                        title: 'Are you sure you want to save this?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Save'
                        }).then((result) => {
                        if (result.value) {
                            let formData = new FormData();
                            if(v.user){
                                formData.append('user_id', v.user.user_id);
                                formData.append('name', v.user.first_name + ' ' + v.user.last_name);
                                formData.append('position', v.position);
                                formData.append('department', v.department);
                                formData.append('company', v.company);
                                formData.append('location', v.location);
                                formData.append('no_of_months_worked', v.getTenure(v.user.date_hired));
                                formData.append('age', v.getTenure(v.user.birthdate));

                                formData.append('reason_for_leaving', v.form.reason_for_leaving ? v.form.reason_for_leaving : '');
                                formData.append('reason_for_leaving_others', v.form.reason_for_leaving_others ? v.form.reason_for_leaving_others : '');
                                formData.append('reason_is_work', v.form.reason_is_work ? v.form.reason_is_work : '');
                                formData.append('reason_is_work_others', v.form.reason_is_work_others ? v.form.reason_is_work_others : '');
                                    
                                // R1
                                formData.append('r1_a', v.form.r1_a ? v.form.r1_a : '');
                                formData.append('r1_a_comments', v.form.r1_a_comments ? v.form.r1_a_comments : '');
                                formData.append('r1_b', v.form.r1_b ? v.form.r1_b : '');
                                formData.append('r1_b_comments', v.form.r1_b_comments ? v.form.r1_b_comments : '');
                                formData.append('r1_c', v.form.r1_c ? v.form.r1_c : '');
                                formData.append('r1_c_comments', v.form.r1_c_comments ? v.form.r1_c_comments : '');
                                formData.append('r1_d', v.form.r1_d ? v.form.r1_d : '');
                                formData.append('r1_d_comments', v.form.r1_d_comments ? v.form.r1_d_comments : '');

                                // R2
                                formData.append('r2_a', v.form.r2_a ? v.form.r2_a : '');
                                formData.append('r2_a_comments', v.form.r2_a_comments ? v.form.r2_a_comments : '');
                                formData.append('r2_b', v.form.r2_b ? v.form.r2_b : '');
                                formData.append('r2_b_comments', v.form.r2_b_comments ? v.form.r2_b_comments : '');
                                formData.append('r2_c', v.form.r2_c ? v.form.r2_c : '');
                                formData.append('r2_c_comments', v.form.r2_c_comments ? v.form.r2_c_comments : '');

                                // R3
                                formData.append('r3_a', v.form.r3_a ? v.form.r3_a : '');
                                formData.append('r3_a_comments', v.form.r3_a_comments ? v.form.r3_a_comments : '');
                                formData.append('r3_b', v.form.r3_b ? v.form.r3_b : '');
                                formData.append('r3_b_comments', v.form.r3_b_comments ? v.form.r3_b_comments : '');
                                formData.append('r3_c', v.form.r3_c ? v.form.r3_c : '');
                                formData.append('r3_c_comments', v.form.r3_c_comments ? v.form.r3_c_comments : '');

                                // R4
                                formData.append('r4_a', v.form.r4_a ? v.form.r4_a : '');
                                formData.append('r4_a_comments', v.form.r4_a_comments ? v.form.r4_a_comments : '');
                                formData.append('r4_b', v.form.r4_b ? v.form.r4_b : '');
                                formData.append('r4_b_comments', v.form.r4_b_comments ? v.form.r4_b_comments : '');
                                formData.append('r4_c', v.form.r4_c ? v.form.r4_c : '');
                                formData.append('r4_c_comments', v.form.r4_c_comments ? v.form.r4_c_comments : '');

                                // R5
                                formData.append('r5_a', v.form.r5_a ? v.form.r5_a : '');
                                formData.append('r5_a_comments', v.form.r5_a_comments ? v.form.r5_a_comments : '');
                                formData.append('r5_b', v.form.r5_b ? v.form.r5_b : '');
                                formData.append('r5_b_comments', v.form.r5_b_comments ? v.form.r5_b_comments : '');

                                // R6
                                formData.append('r6_a', v.form.r6_a ? v.form.r6_a : '');
                                formData.append('r6_a_comments', v.form.r6_a_comments ? v.form.r6_a_comments : '');
                                formData.append('r6_b', v.form.r6_b ? v.form.r6_b : '');
                                formData.append('r6_b_comments', v.form.r6_b_comments ? v.form.r6_b_comments : '');
                                formData.append('r6_c', v.form.r6_c ? v.form.r6_c : '');
                                formData.append('r6_c_comments', v.form.r6_c_comments ? v.form.r6_c_comments : '');
                                formData.append('r6_d', v.form.r6_d ? v.form.r6_d : '');
                                formData.append('r6_d_comments', v.form.r6_d_comments ? v.form.r6_d_comments : '');

                                // R7
                                formData.append('r7_a', v.form.r7_a ? v.form.r7_a : '');
                                formData.append('r7_a_comments', v.form.r7_a_comments ? v.form.r7_a_comments : '');
                                formData.append('r7_b', v.form.r7_b ? v.form.r7_b : '');
                                formData.append('r7_b_comments', v.form.r7_b_comments ? v.form.r7_b_comments : '');
                                formData.append('r7_c', v.form.r7_c ? v.form.r7_c : '');
                                formData.append('r7_c_comments', v.form.r7_c_comments ? v.form.r7_c_comments : '');
                                formData.append('r7_d', v.form.r7_d ? v.form.r7_d : '');
                                formData.append('r7_d_comments', v.form.r7_d_comments ? v.form.r7_d_comments : '');

                                // R8
                                formData.append('r8_a', v.form.r8_a ? v.form.r8_a : '');
                                formData.append('r8_a_comments', v.form.r8_a_comments ? v.form.r8_a_comments : '');
                                formData.append('r8_b', v.form.r8_b ? v.form.r8_b : '');
                                formData.append('r8_b_comments', v.form.r8_b_comments ? v.form.r8_b_comments : '');
                                formData.append('r8_c', v.form.r8_c ? v.form.r8_c : '');
                                formData.append('r8_c_comments', v.form.r8_c_comments ? v.form.r8_c_comments : '');

                                // R9
                                formData.append('r9_a', v.form.r9_a ? v.form.r9_a : '');
                                formData.append('r9_a_comments', v.form.r9_a_comments ? v.form.r9_a_comments : '');
                                formData.append('r9_b', v.form.r9_b ? v.form.r9_b : '');
                                formData.append('r9_b_comments', v.form.r9_b_comments ? v.form.r9_b_comments : '');
                                formData.append('r9_c', v.form.r9_c ? v.form.r9_c : '');
                                formData.append('r9_c_comments', v.form.r9_c_comments ? v.form.r9_c_comments : '');
                                formData.append('r9_d', v.form.r9_d ? v.form.r9_d : '');
                                formData.append('r9_d_comments', v.form.r9_d_comments ? v.form.r9_d_comments : '');

                                // R10
                                formData.append('r10_a', v.form.r10_a ? v.form.r10_a : '');
                                formData.append('r10_a_comments', v.form.r10_a_comments ? v.form.r10_a_comments : '');
                                formData.append('r10_b', v.form.r10_b ? v.form.r10_b : '');
                                formData.append('r10_b_comments', v.form.r10_b_comments ? v.form.r10_b_comments : '');
                                formData.append('r10_c', v.form.r10_c ? v.form.r10_c : '');
                                formData.append('r10_c_comments', v.form.r10_c_comments ? v.form.r10_c_comments : '');

                                // R11
                                formData.append('r11_a', v.form.r11_a ? v.form.r11_a : '');
                                formData.append('r11_a_comments', v.form.r11_a_comments ? v.form.r11_a_comments : '');
                                formData.append('r11_b', v.form.r11_b ? v.form.r11_b : '');
                                formData.append('r11_b_comments', v.form.r11_b_comments ? v.form.r11_b_comments : '');

                                formData.append('other_suggestions_for_improvement', v.form.other_suggestions_for_improvement ? v.form.other_suggestions_for_improvement : '');
      
                                axios.post(`/save-survey-exit-interview-form`, 
                                    formData,
                                    {
                                        headers: {
                                            'Content-Type': 'multipart/form-data'
                                        }
                                    }  
                                )
                                .then(response => {
                                    if(response.data == 'saved'){
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Successfully saved. Thank you',
                                            icon: 'success',
                                            confirmButtonText: 'Okay'
                                        })
                                        // .then(okay => {
                                        //     if (okay) {
                                        //         window.location.href = "http://10.96.4.70/login";
                                        //     }
                                        // });
                                    }
                                })
                                .catch(error => {
                                
                                })
                            }
                        }
                });
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
            getAge(dateString) {
                var today = new Date();
                var birthDate = new Date(dateString);
                var age = today.getFullYear() - birthDate.getFullYear();
                var m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                return age;
            },
            getUser(){
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                const user_id = urlParams.get('user_id');

                axios.get('/get-user-survey?user_id=' + user_id)
                .then(response => { 
                    this.user = response.data;
                    this.position = response.data.position;
                    this.department = response.data.departments[0].name;
                    this.company = response.data.companies[0].name;
                    this.location = response.data.locations[0].name;
                    if(this.user){
                        this.getSurvey();
                    }
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                });
            },
            getSurvey(){
                let v = this;
                axios.get('/get-user-survey-exit-interview-form?user_id=' + v.user.user_id)
                .then(response => { 
                    if(response.data){
                        this.form = response.data;
                    }
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                });
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>