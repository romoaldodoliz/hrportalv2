<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-primary opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white text-uppercase">SURVEY : QUESTIONNAIRE</h1>
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
                    <div class="card-body" v-if="user">
                        <div>
                            NAME : <b>{{user.first_name + ' ' + user.last_name}}</b><br>
                            POSITION : {{position}}<br>
                            DEPARTMENT : {{department}}<br>
                            COMPANY : {{company}}<br>
                            LOCATION : {{location}}
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <h5>INSTRUCTIONs:  Answer each question truthfully. Read the questions and choose the letter that corresponds to your answer.</h5>
                                <hr>
                            </div>
                            <!-- q1 -->
                            <div class="col-md-12">
                                <h4>1.	I know what our company’s food safety policy is and our core values</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_1" class="custom-control-input" value="a" v-model="q1">
                                        <label class="custom-control-label" for="q1_1" >Strongly Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_2" class="custom-control-input" value="b" v-model="q1">
                                        <label class="custom-control-label" for="q1_2" >Agree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_3" class="custom-control-input" value="c" v-model="q1">
                                        <label class="custom-control-label" for="q1_3" >Either Agree or Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_4" class="custom-control-input" value="d" v-model="q1">
                                        <label class="custom-control-label" for="q1_4" >Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_5" class="custom-control-input" value="e" v-model="q1">
                                        <label class="custom-control-label" for="q1_5" >Strongly Agree</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q1">{{errors.q1[0]}}</span>
                            </div>
                            <!-- q2 -->
                            <div class="col-md-12">
                                <h4>2.	We receive important trainings that are relevant to our food handling `tasks –schedule is consistent and followed</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_1" class="custom-control-input" value="a" v-model="q2">
                                        <label class="custom-control-label" for="q2_1" >Never</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_2" class="custom-control-input" value="b" v-model="q2">
                                        <label class="custom-control-label" for="q2_2" >Rarely</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_3" class="custom-control-input" value="c" v-model="q2">
                                        <label class="custom-control-label" for="q2_3" >Sometime</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_4" class="custom-control-input" value="d" v-model="q2">
                                        <label class="custom-control-label" for="q2_4" >Most of the Time</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_5" class="custom-control-input" value="e" v-model="q2">
                                        <label class="custom-control-label" for="q2_5" >Every Time</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q2">{{errors.q2[0]}}</span>
                            </div>
                            <!-- q3 -->
                            <div class="col-md-12">
                                <h4>3.	When there is change in our food safety programs, we update all the documents and immediately have training</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q3_1" class="custom-control-input" value="a" v-model="q3">
                                        <label class="custom-control-label" for="q3_1" >Never</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q3_2" class="custom-control-input" value="b" v-model="q3">
                                        <label class="custom-control-label" for="q3_2" >Rarely</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q3_3" class="custom-control-input" value="c" v-model="q3">
                                        <label class="custom-control-label" for="q3_3" >Sometime</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q3_4" class="custom-control-input" value="d" v-model="q3">
                                        <label class="custom-control-label" for="q3_4" >Most of the Time</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q3_5" class="custom-control-input" value="e" v-model="q3">
                                        <label class="custom-control-label" for="q3_5" >Every Time</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q3">{{errors.q3[0]}}</span>
                            </div>
                            <!-- q4 -->
                            <div class="col-md-12">
                                <h4>4.	I take responsibility to make action or I immediately report my observations to my superior about any food safety issue</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_1" class="custom-control-input" value="a" v-model="q4">
                                        <label class="custom-control-label" for="q4_1" >Strongly Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_2" class="custom-control-input" value="b" v-model="q4">
                                        <label class="custom-control-label" for="q4_2" >Agree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_3" class="custom-control-input" value="c" v-model="q4">
                                        <label class="custom-control-label" for="q4_3" >Either Agree or Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_4" class="custom-control-input" value="d" v-model="q4">
                                        <label class="custom-control-label" for="q4_4" >Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_5" class="custom-control-input" value="e" v-model="q4">
                                        <label class="custom-control-label" for="q4_5" >Strongly Agree</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q4">{{errors.q4[0]}}</span>
                            </div>
                            <!-- q5 -->
                            <div class="col-md-12">
                                <h4>5.	We follow the training program to inform, remind and update our operators, technicians, about the CCPs, OPRPs, PRPs in their task and workplace</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_1" class="custom-control-input" value="a" v-model="q5">
                                        <label class="custom-control-label" for="q5_1" >Never</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_2" class="custom-control-input" value="b" v-model="q5">
                                        <label class="custom-control-label" for="q5_2" >Rarely</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_3" class="custom-control-input" value="c" v-model="q5">
                                        <label class="custom-control-label" for="q5_3" >Sometime</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_4" class="custom-control-input" value="d" v-model="q5">
                                        <label class="custom-control-label" for="q5_4" >Most of the Time</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_5" class="custom-control-input" value="e" v-model="q5">
                                        <label class="custom-control-label" for="q5_5" >Every Time</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q5">{{errors.q5[0]}}</span>
                            </div>
                            <!-- q6 -->
                            <div class="col-md-12">
                                <h4>6.	We have food safety-related key performanceindicators/objectives as individual and as a team</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q6_1" class="custom-control-input" value="a" v-model="q6">
                                        <label class="custom-control-label" for="q6_1" >Strongly Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q6_2" class="custom-control-input" value="b" v-model="q6">
                                        <label class="custom-control-label" for="q6_2" >Agree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q6_3" class="custom-control-input" value="c" v-model="q6">
                                        <label class="custom-control-label" for="q6_3" >Either Agree or Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q6_4" class="custom-control-input" value="d" v-model="q6">
                                        <label class="custom-control-label" for="q6_4" >Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q6_5" class="custom-control-input" value="e" v-model="q6">
                                        <label class="custom-control-label" for="q6_5" >Strongly Agree</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q6">{{errors.q6[0]}}</span>
                            </div>
                            <!-- q7 -->
                            <div class="col-md-12">
                                <h4>7.	Important information about food safety topics and issues are relayed to us by our superiors</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q7_1" class="custom-control-input" value="a" v-model="q7">
                                        <label class="custom-control-label" for="q7_1" >Strongly Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q7_2" class="custom-control-input" value="b" v-model="q7">
                                        <label class="custom-control-label" for="q7_2" >Agree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q7_3" class="custom-control-input" value="c" v-model="q7">
                                        <label class="custom-control-label" for="q7_3" >Either Agree or Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q7_4" class="custom-control-input" value="d" v-model="q7">
                                        <label class="custom-control-label" for="q7_4" >Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q7_5" class="custom-control-input" value="e" v-model="q7">
                                        <label class="custom-control-label" for="q7_5" >Strongly Agree</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q7">{{errors.q7[0]}}</span>
                            </div>
                            <!-- q8 -->
                            <div class="col-md-12">
                                <h4>8.	We are quick to adapt to changes when there are improvements in our food safety system</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q8_1" class="custom-control-input" value="a" v-model="q8">
                                        <label class="custom-control-label" for="q8_1" >Strongly Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q8_2" class="custom-control-input" value="b" v-model="q8">
                                        <label class="custom-control-label" for="q8_2" >Agree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q8_3" class="custom-control-input" value="c" v-model="q8">
                                        <label class="custom-control-label" for="q8_3" >Either Agree or Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q8_4" class="custom-control-input" value="d" v-model="q8">
                                        <label class="custom-control-label" for="q8_4" >Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q8_5" class="custom-control-input" value="e" v-model="q8">
                                        <label class="custom-control-label" for="q8_5" >Strongly Agree</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q8">{{errors.q8[0]}}</span>
                            </div>
                            <!-- q9 -->
                            <div class="col-md-12">
                                <h4>9.	We consistently perform the best food safety practices and this is reflected in our scores, ratings, and observation by external parties</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q9_1" class="custom-control-input" value="a" v-model="q9">
                                        <label class="custom-control-label" for="q9_1" >Strongly Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q9_2" class="custom-control-input" value="b" v-model="q9">
                                        <label class="custom-control-label" for="q9_2" >Agree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q9_3" class="custom-control-input" value="c" v-model="q9">
                                        <label class="custom-control-label" for="q9_3" >Either Agree or Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q9_4" class="custom-control-input" value="d" v-model="q9">
                                        <label class="custom-control-label" for="q9_4" >Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q9_5" class="custom-control-input" value="e" v-model="q9">
                                        <label class="custom-control-label" for="q9_5" >Strongly Agree</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q9">{{errors.q9[0]}}</span>
                            </div>
                            <!-- q10 -->
                            <div class="col-md-12">
                                <h4>10.	I do believe my family can consume, without question, the food products manufactured in our plant</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q10_1" class="custom-control-input" value="a" v-model="q10">
                                        <label class="custom-control-label" for="q10_1" >Strongly Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q10_2" class="custom-control-input" value="b" v-model="q10">
                                        <label class="custom-control-label" for="q10_2" >Agree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q10_3" class="custom-control-input" value="c" v-model="q10">
                                        <label class="custom-control-label" for="q10_3" >Either Agree or Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q10_4" class="custom-control-input" value="d" v-model="q10">
                                        <label class="custom-control-label" for="q10_4" >Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q10_5" class="custom-control-input" value="e" v-model="q10">
                                        <label class="custom-control-label" for="q10_5" >Strongly Agree</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q10">{{errors.q10[0]}}</span>
                            </div>
                            <!-- q11 -->
                            <div class="col-md-12">
                                <h4>11.	The food safety performance score is communicated to us and we are aware of the current status</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q11_1" class="custom-control-input" value="a" v-model="q11">
                                        <label class="custom-control-label" for="q11_1" >Never</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q11_2" class="custom-control-input" value="b" v-model="q11">
                                        <label class="custom-control-label" for="q11_2" >Rarely</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q11_3" class="custom-control-input" value="c" v-model="q11">
                                        <label class="custom-control-label" for="q11_3" >Sometime</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q11_4" class="custom-control-input" value="d" v-model="q11">
                                        <label class="custom-control-label" for="q11_4" >Most of the Time</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q11_5" class="custom-control-input" value="e" v-model="q11">
                                        <label class="custom-control-label" for="q11_5" >Every Time</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q11">{{errors.q11[0]}}</span>
                            </div>
                            <!-- q12 -->
                            <div class="col-md-12">
                                <h4>12.	A strong program is implemented to give reward when we do above the expected or sanction if we do poorly</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q12_1" class="custom-control-input" value="a" v-model="q12">
                                        <label class="custom-control-label" for="q12_1" >Strongly Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q12_2" class="custom-control-input" value="b" v-model="q12">
                                        <label class="custom-control-label" for="q12_2" >Agree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q12_3" class="custom-control-input" value="c" v-model="q12">
                                        <label class="custom-control-label" for="q12_3" >Either Agree or Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q12_4" class="custom-control-input" value="d" v-model="q12">
                                        <label class="custom-control-label" for="q12_4" >Disagree</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q12_5" class="custom-control-input" value="e" v-model="q12">
                                        <label class="custom-control-label" for="q12_5" >Strongly Agree</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q12">{{errors.q12[0]}}</span>
                            </div>
                            <!-- q13 -->
                            <div class="col-md-12">
                                <h4>13.	When we encounter an issue or problem, we respond very quick and with the correct actions in place 100% of the time</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q13_1" class="custom-control-input" value="a" v-model="q13">
                                        <label class="custom-control-label" for="q13_1" >Never</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q13_2" class="custom-control-input" value="b" v-model="q13">
                                        <label class="custom-control-label" for="q13_2" >Rarely</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q13_3" class="custom-control-input" value="c" v-model="q13">
                                        <label class="custom-control-label" for="q13_3" >Sometime</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q13_4" class="custom-control-input" value="d" v-model="q13">
                                        <label class="custom-control-label" for="q13_4" >Most of the Time</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q13_5" class="custom-control-input" value="e" v-model="q13">
                                        <label class="custom-control-label" for="q13_5" >Every Time</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q13">{{errors.q13[0]}}</span>
                            </div>
                            <!-- q14 -->
                            <div class="col-md-12">
                                <h4>14.	We involve the employees, contractors, operators when revising or updating our procedures or policies.</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q14_1" class="custom-control-input" value="a" v-model="q14">
                                        <label class="custom-control-label" for="q14_1" >Never</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q14_2" class="custom-control-input" value="b" v-model="q14">
                                        <label class="custom-control-label" for="q14_2" >Rarely</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q14_3" class="custom-control-input" value="c" v-model="q14">
                                        <label class="custom-control-label" for="q14_3" >Sometime</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q14_4" class="custom-control-input" value="d" v-model="q14">
                                        <label class="custom-control-label" for="q14_4" >Most of the Time</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q14_5" class="custom-control-input" value="e" v-model="q14">
                                        <label class="custom-control-label" for="q14_5" >Every Time</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q14">{{errors.q14[0]}}</span>
                            </div>
                            <!-- q15 -->
                            <div class="col-md-12">
                                <h4>15.	We review near-misses, audit reports, or customer complaints to identify long-term improvements on our food safety system</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q15_1" class="custom-control-input" value="a" v-model="q15">
                                        <label class="custom-control-label" for="q15_1" >Never</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q15_2" class="custom-control-input" value="b" v-model="q15">
                                        <label class="custom-control-label" for="q15_2" >Rarely</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q15_3" class="custom-control-input" value="c" v-model="q15">
                                        <label class="custom-control-label" for="q15_3" >Sometime</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q15_4" class="custom-control-input" value="d" v-model="q15">
                                        <label class="custom-control-label" for="q15_4" >Most of the Time</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q15_5" class="custom-control-input" value="e" v-model="q15">
                                        <label class="custom-control-label" for="q15_5" >Every Time</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q15">{{errors.q15[0]}}</span>
                            </div>
                        </div>

                    </div>
                    <!-- Footer -->
                    <div class="card-footer bg-white border-0" v-if="user">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button id="save-btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" :disabled="saveDisable" @click="saveSurveyDec2021CqaCulture" style="width:150px;">Save</button>
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
    export default {
        data() {
            return {
                user: '',
                position: '',
                department: '',
                company: '',
                location: '',
                q1 : '',
                q2 : '',
                q3 : '',
                q4 : '',
                q5 : '',
                q6 : '',
                q7 : '',
                q8 : '',
                q9 : '',
                q10 : '',
                q11 : '',
                q12 : '',
                q13 : '',
                q14 : '',
                q15 : '',
                errors : [],
                saveDisable : false
            }
        },
        created () {
            this.getUser();
        },
        methods: {
            saveSurveyDec2021CqaCulture(){
                let v = this;
                v.saveDisable = true;
                var r = confirm("Are you sure you want to save this survey?");
                if (r == true) {
                    if(v.user){
                        axios.post(`/save-survey-dec-2021-cqa-culture`, {
                            user_id: v.user.user_id,
                            employee_number: v.user.id_number,
                            name: v.user.first_name + ' ' + v.user.last_name,
                            business_unit: v.user.cluster,
                            cluster: v.user.new_cluster,
                            company: v.company,
                            department: v.department,
                            location: v.location,
                            position: v.position,
                            region: v.user.area ? v.user.area : "",
                            hired_date : v.user.date_hired,
                            q1: v.q1 ? v.q1 : "",
                            q2: v.q2 ? v.q2 : "",
                            q3: v.q3 ? v.q3 : "",
                            q4: v.q4 ? v.q4 : "",
                            q5: v.q5 ? v.q5 : "",
                            q6: v.q6 ? v.q6 : "",
                            q7: v.q7 ? v.q7 : "",
                            q8: v.q8 ? v.q8 : "",
                            q9: v.q9 ? v.q9 : "",
                            q10: v.q10 ? v.q10 : "",
                            q11: v.q11 ? v.q11 : "",
                            q12: v.q12 ? v.q12 : "",
                            q13: v.q13 ? v.q13 : "",
                            q14: v.q14 ? v.q14 : "",
                            q15: v.q15 ? v.q15 : "",
                            _method: 'POST'
                        })
                        .then(response => {
                            if(response.data == "saved"){
                                alert('Successfully saved. Thank you.');
                                window.location.href = "http://10.96.4.70/login";
                            }else{
                                alert('Please try again. Thank you.');
                                location.reload();
                            }
                        })
                        .catch(error => {
                            alert('Check you form and then try again. Thank you.');
                            this.errors = error.response.data.errors;
                            v.saveDisable = false;
                        });
                    }else{
                        window.location.href = "http://10.96.4.70/login";
                    }
                }else{
                    v.saveDisable = false;
                }
            },
            getUser(){
                axios.get('/get-user-survey-dec-2021-cqa-culture')
                .then(response => { 
                    this.user = response.data;
                    this.position = response.data.position;
                    this.department = response.data.departments[0].name;
                    this.company = response.data.companies[0].name;
                    this.location = response.data.locations[0].name;

                    var modal = document.getElementById("languageModal");
                    modal.style.display = "block";
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                   
                });
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>