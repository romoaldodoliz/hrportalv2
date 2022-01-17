<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-primary opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white text-uppercase">Pre - Test Wheat Cleaning, Tempering and Conditioning</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt--7 mb-6 mx-auto">
        <div class="row justify-content-center">
            <div class="col-xl-10 order-xl-1">
                <div class="card bg-secondary shadow"  v-if="user">
                    <div class="card-header bg-white border-0">
                        <div class="col-md-12 text-center">
                            <img src="/img/lfuggoc_logo.png" style="width:150px;height:auto;">
                        </div>
                    </div>
                    <div class="card-body">
                        <div>
                            NAME : <b>{{user.first_name + ' ' + user.last_name}}</b><br>
                            POSITION : {{position}}<br>
                            DEPARTMENT : {{department}}<br>
                            COMPANY : {{company}}<br>
                            LOCATION : {{location}}
                        </div>
                        <div class="row">
                            <!-- q1 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>1. This is a set of step-by-step instructions compiled by an organization to help workers carry out routine operations</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_1" class="custom-control-input" value="a" v-model="q1">
                                        <label class="custom-control-label" for="q1_1" >A.	SOP</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_2" class="custom-control-input" value="b" v-model="q1">
                                        <label class="custom-control-label" for="q1_2" >B.	FIFO</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_3" class="custom-control-input" value="c" v-model="q1">
                                        <label class="custom-control-label" for="q1_3" >C.	FEFO</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_4" class="custom-control-input" value="d" v-model="q1">
                                        <label class="custom-control-label" for="q1_4" >D.	CAR</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q1">{{errors.q1[0]}}</span>
                            </div>
                            <!-- q2 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>2.	What are the steps in Wheat receiving to storage?</h4>

                                1.	Inspection <br>
                                2.	Wheat Receiving <br>
                                3.	Analysis <br>
                                4.	Storage <br>
                                5.	Transfer <br>


                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_1" class="custom-control-input" value="a" v-model="q2">
                                        <label class="custom-control-label" for="q2_1" >A.	1,2,4,5,3</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_2" class="custom-control-input" value="b" v-model="q2">
                                        <label class="custom-control-label" for="q2_2" >B.	2, 1,3,5,4</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_3" class="custom-control-input" value="c" v-model="q2">
                                        <label class="custom-control-label" for="q2_3" >C.	3,5,1,2,4</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_4" class="custom-control-input" value="d" v-model="q2">
                                        <label class="custom-control-label" for="q2_4" >D.	1,2,3,4,5</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q2">{{errors.q2[0]}}</span>
                            </div>
                            <!-- q3 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>3.	What are the documents that the miller's needs to provide to ensure wheat transfer to mill is correct?</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q3_1" value="a" v-model="q3">
                                    <label class="custom-control-label" for="q3_1">
                                        <span class="text-muted">A.	Storage silo (FIFO)</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q3_2" value="b" v-model="q3">
                                    <label class="custom-control-label" for="q3_2">
                                        <span class="text-muted">B.	Wheat variety/vessel</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q3_3" value="c" v-model="q3">
                                    <label class="custom-control-label" for="q3_3">
                                        <span class="text-muted">C.	Quantity</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q3_4" value="d" v-model="q3">
                                    <label class="custom-control-label" for="q3_4">
                                        <span class="text-muted">D.	Transfer to mixing bins</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q3_5" value="e" v-model="q3">
                                    <label class="custom-control-label" for="q3_5">
                                        <span class="text-muted">E.	Additives Batching</span>
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q3">{{errors.q3[0]}}</span>
                            </div>
                            <!-- q4 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>4.	Process by which wheat is grounded into flour:</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_1" class="custom-control-input" value="a" v-model="q4">
                                        <label class="custom-control-label" for="q4_1" >A. Wheat milling process</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_2" class="custom-control-input" value="b" v-model="q4">
                                        <label class="custom-control-label" for="q4_2" >B. Return to mill process</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_3" class="custom-control-input" value="c" v-model="q4">
                                        <label class="custom-control-label" for="q4_3" >C. Wheat Cleaning, Tempering, & Conditioning process</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_4" class="custom-control-input" value="d" v-model="q4">
                                        <label class="custom-control-label" for="q4_4" >D. Warehousing</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q4">{{errors.q4[0]}}</span>
                            </div>
                            <!-- q5 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>5.	This process is the removal or separation of undesirable matter and impurities from wheat: </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_1" class="custom-control-input" value="a" v-model="q5">
                                        <label class="custom-control-label" for="q5_1" >A. Wheat cleaning process</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_2" class="custom-control-input" value="b" v-model="q5">
                                        <label class="custom-control-label" for="q5_2" >B. Wheat tempering process</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_3" class="custom-control-input" value="c" v-model="q5">
                                        <label class="custom-control-label" for="q5_3" >C. Wheat milling process</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_4" class="custom-control-input" value="d" v-model="q5">
                                        <label class="custom-control-label" for="q5_4" >D. Flour storing process</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q5">{{errors.q5[0]}}</span>
                            </div>
                            <!-- q6 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>6.	Give three (3) examples of impurities: </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q6_1" value="a" v-model="q6">
                                    <label class="custom-control-label" for="q6_1">
                                        <span class="text-muted">A.	Foreign organic materials</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q6_2" value="b" v-model="q6">
                                    <label class="custom-control-label" for="q6_2">
                                        <span class="text-muted">B.	Wheat defects</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q6_3" value="c" v-model="q6">
                                    <label class="custom-control-label" for="q6_3">
                                        <span class="text-muted">C.	Other cereals</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q6_4" value="d" v-model="q6">
                                    <label class="custom-control-label" for="q6_4">
                                        <span class="text-muted">D.	Stalks and Chaffs</span>
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q6">{{errors.q6[0]}}</span>
                            </div>
                            <!-- q7 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>7.	Give three (3) machines related to wheat cleaning process:</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q7_1" value="a" v-model="q7">
                                    <label class="custom-control-label" for="q7_1">
                                        <span class="text-muted">A.	Wheat separator</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q7_2" value="b" v-model="q7">
                                    <label class="custom-control-label" for="q7_2">
                                        <span class="text-muted">B.	Scourer</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q7_3" value="c" v-model="q7">
                                    <label class="custom-control-label" for="q7_3">
                                        <span class="text-muted">C.	magnetic separator</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q7_4" value="d" v-model="q7">
                                    <label class="custom-control-label" for="q7_4">
                                        <span class="text-muted">D.	Destoner</span>
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q7">{{errors.q7[0]}}</span>
                            </div>
                            <!-- q8 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>8.	What is the Cleaning SOP schedule for magnetic separator? </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q8_1" class="custom-control-input" value="a" v-model="q8">
                                        <label class="custom-control-label" for="q8_1" >A.	6-2 shift</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q8_2" class="custom-control-input" value="b" v-model="q8">
                                        <label class="custom-control-label" for="q8_2" >B.	2-10 shift</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q8_3" class="custom-control-input" value="c" v-model="q8">
                                        <label class="custom-control-label" for="q8_3" >C.	10-6 shift</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q8_4" class="custom-control-input" value="d" v-model="q8">
                                        <label class="custom-control-label" for="q8_4" >D.	8-5 shift</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q8">{{errors.q8[0]}}</span>
                            </div>
                            <!-- q9 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>9.	What is the SOP for wheat separator? </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q9_1" class="custom-control-input" value="a" v-model="q9">
                                        <label class="custom-control-label" for="q9_1" >A. Daily checking for other cereal grain contamination</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q9_2" class="custom-control-input" value="b" v-model="q9">
                                        <label class="custom-control-label" for="q9_2" >B. Checking of screens</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q9_3" class="custom-control-input" value="c" v-model="q9">
                                        <label class="custom-control-label" for="q9_3" >C. Checking of wheat</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q9_4" class="custom-control-input" value="d" v-model="q9">
                                        <label class="custom-control-label" for="q9_4" >D.	Checking of off-specs/overtails</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q9">{{errors.q9[0]}}</span>
                            </div>
                            <!-- q10 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>10.	Give three (3) benefits of wheat cleaning: </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q10_1" value="a" v-model="q10">
                                    <label class="custom-control-label" for="q10_1">
                                        <span class="text-muted">A.	Decrease microbial growth</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q10_2" value="b" v-model="q10">
                                    <label class="custom-control-label" for="q10_2">
                                        <span class="text-muted">B.	Increase grain shelf-life</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q10_3" value="c" v-model="q10">
                                    <label class="custom-control-label" for="q10_3">
                                        <span class="text-muted">C.	Improve sanitation and dust control</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q10_4" value="d" v-model="q10">
                                    <label class="custom-control-label" for="q10_4">
                                        <span class="text-muted">D.	Increase wheat moisture</span>
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q10">{{errors.q10[0]}}</span>
                            </div>
                            <!-- q11 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>11.	A machine use to blend of wheat in an accurate and constant manner, and for easy control </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q11_1" class="custom-control-input" value="a" v-model="q11">
                                        <label class="custom-control-label" for="q11_1" >A.	Volumetric measurer</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q11_2" class="custom-control-input" value="b" v-model="q11">
                                        <label class="custom-control-label" for="q11_2" >B. Flour blending</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q11_3" class="custom-control-input" value="c" v-model="q11">
                                        <label class="custom-control-label" for="q11_3" >C. Additives mixer</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q11_4" class="custom-control-input" value="d" v-model="q11">
                                        <label class="custom-control-label" for="q11_4" >D.	Airlock</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q11">{{errors.q11[0]}}</span>
                            </div>
                            <!-- q12 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>12.	A machine use to transfer wheat with height or bringing in up level  </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q12_1" class="custom-control-input" value="a" v-model="q12">
                                        <label class="custom-control-label" for="q12_1" >A.	Bucket elevator</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q12_2" class="custom-control-input" value="b" v-model="q12">
                                        <label class="custom-control-label" for="q12_2" >B.	 Screw conveyor</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q12_3" class="custom-control-input" value="c" v-model="q12">
                                        <label class="custom-control-label" for="q12_3" >C. Chain conveyor</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q12_4" class="custom-control-input" value="d" v-model="q12">
                                        <label class="custom-control-label" for="q12_4" >D.	Transport blower</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q12">{{errors.q12[0]}}</span>
                            </div>
                            <!-- q13 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>13.	What kind of material use for buckets in a bucket elevator? </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q13_1" class="custom-control-input" value="a" v-model="q13">
                                        <label class="custom-control-label" for="q13_1" >A.	Plastic</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q13_2" class="custom-control-input" value="b" v-model="q13">
                                        <label class="custom-control-label" for="q13_2" >B.	 Steel</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q13_3" class="custom-control-input" value="c" v-model="q13">
                                        <label class="custom-control-label" for="q13_3" >C. Engineering plastic</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q13_4" class="custom-control-input" value="d" v-model="q13">
                                        <label class="custom-control-label" for="q13_4" >D.	Wood</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q13">{{errors.q13[0]}}</span>
                            </div>
                            <!-- q14 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>14.	A type of separator that separates by length and consists of horizontal axis, inclined, rotating circular cylinder or shell:</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q14_1" class="custom-control-input" value="a" v-model="q14">
                                        <label class="custom-control-label" for="q14_1" >A.	Super triur / Disc separator</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q14_2" class="custom-control-input" value="b" v-model="q14">
                                        <label class="custom-control-label" for="q14_2" >B.	 Scourer</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q14_3" class="custom-control-input" value="c" v-model="q14">
                                        <label class="custom-control-label" for="q14_3" >C. Airlock</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q14_4" class="custom-control-input" value="d" v-model="q14">
                                        <label class="custom-control-label" for="q14_4" >D.	Color Sorter</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q14">{{errors.q14[0]}}</span>
                            </div>
                            <!-- q15 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>15.	What to check if there are unusual sound and vibrations in screw conveyor? </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q15_1" class="custom-control-input" value="a" v-model="q15">
                                        <label class="custom-control-label" for="q15_1" >A.	Check worn-out gasket hanger screw coupling</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q15_2" class="custom-control-input" value="b" v-model="q15">
                                        <label class="custom-control-label" for="q15_2" >B.	 Check motor</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q15_3" class="custom-control-input" value="c" v-model="q15">
                                        <label class="custom-control-label" for="q15_3" >C. Check lubrication and grease</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q15_4" class="custom-control-input" value="d" v-model="q15">
                                        <label class="custom-control-label" for="q15_4" >D.	Check pulley</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q15">{{errors.q15[0]}}</span>
                            </div>
                            <!-- q16 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>16.	What to do when there is a failure discharge in weigher? </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q16_1" class="custom-control-input" value="a" v-model="q16">
                                        <label class="custom-control-label" for="q16_1" >A.	Check compressed air supply and other pneumatic system</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q16_2" class="custom-control-input" value="b" v-model="q16">
                                        <label class="custom-control-label" for="q16_2" >B.	 Check discharge</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q16_3" class="custom-control-input" value="c" v-model="q16">
                                        <label class="custom-control-label" for="q16_3" >C. Check power</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q16_4" class="custom-control-input" value="d" v-model="q16">
                                        <label class="custom-control-label" for="q16_4" >D.	Check pressure gauge</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q16">{{errors.q16[0]}}</span>
                            </div>
                            <!-- q17 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>17.	In a destoner, what to do if there is a wheat coming out from over tails?</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q17_1" class="custom-control-input" value="a" v-model="q17">
                                        <label class="custom-control-label" for="q17_1" >A.	Clean lower deck screen</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q17_2" class="custom-control-input" value="b" v-model="q17">
                                        <label class="custom-control-label" for="q17_2" >B.	 Upper deck screen</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q17_3" class="custom-control-input" value="c" v-model="q17">
                                        <label class="custom-control-label" for="q17_3" >C. Middle deck screen</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q17_4" class="custom-control-input" value="d" v-model="q17">
                                        <label class="custom-control-label" for="q17_4" >D.	Filter</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q17">{{errors.q17[0]}}</span>
                            </div>
                            <!-- q18 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>18.	Give three (3) machines related to wheat screenings </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q18_1" value="a" v-model="q18">
                                    <label class="custom-control-label" for="q18_1">
                                        <span class="text-muted">A.	Centrifugal sifter</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q18_2" value="b" v-model="q18">
                                    <label class="custom-control-label" for="q18_2">
                                        <span class="text-muted">B.	hammer mill</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q18_3" value="c" v-model="q18">
                                    <label class="custom-control-label" for="q18_3">
                                        <span class="text-muted">C.	turbo sifter</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q18_4" value="d" v-model="q18">
                                    <label class="custom-control-label" for="q18_4">
                                        <span class="text-muted">D.	Weigher</span>
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q18">{{errors.q18[0]}}</span>
                            </div>
                            <!-- q19 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>19.	SOP for centrifugal sifter </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q19_1" class="custom-control-input" value="a" v-model="q19">
                                        <label class="custom-control-label" for="q19_1" >A.	Check every shift quality of ground stocks (fine screenings must free from larges stalks or other material  larger than 3mm size)</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q19_2" class="custom-control-input" value="b" v-model="q19">
                                        <label class="custom-control-label" for="q19_2" >B.	 Check stocks granulation</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q19_3" class="custom-control-input" value="c" v-model="q19">
                                        <label class="custom-control-label" for="q19_3" >C. Check fine stocks if free from contamination</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q19_4" class="custom-control-input" value="d" v-model="q19">
                                        <label class="custom-control-label" for="q19_4" >D.	Check ground stocks free from contamination </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q19">{{errors.q19[0]}}</span>
                            </div>
                            <!-- q20 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>20.	SOP for hammer mill </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q20_1" value="a" v-model="q20">
                                    <label class="custom-control-label" for="q20_1">
                                        <span class="text-muted">A.	Run the hammer mill every 6-2 shifts until the ground screening bin is empty</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q20_2" value="b" v-model="q20">
                                    <label class="custom-control-label" for="q20_2">
                                        <span class="text-muted">B.	Change rotation weekly</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q20_3" value="c" v-model="q20">
                                    <label class="custom-control-label" for="q20_3">
                                        <span class="text-muted">C.	Check beater</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q20_4" value="d" v-model="q20">
                                    <label class="custom-control-label" for="q20_4">
                                        <span class="text-muted">D.	Daily checking of motor</span>
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q20">{{errors.q20[0]}}</span>
                            </div>
                            <!-- q21 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>21.	A machine used to separate screenings of the cleaning house according to their grading</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q21_1" class="custom-control-input" value="a" v-model="q21">
                                        <label class="custom-control-label" for="q21_1" >A.	Centrifugal sifter</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q21_2" class="custom-control-input" value="b" v-model="q21">
                                        <label class="custom-control-label" for="q21_2" >B.	 Hammer mill</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q21_3" class="custom-control-input" value="c" v-model="q21">
                                        <label class="custom-control-label" for="q21_3" >C. Wheat separator</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q21_4" class="custom-control-input" value="d" v-model="q21">
                                        <label class="custom-control-label" for="q21_4" >D.	Color sorter</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q21">{{errors.q21[0]}}</span>
                            </div>
                            <!-- q22 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>22.	What to do when there is clogged stocks in hammer mill </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q22_1" value="a" v-model="q22">
                                    <label class="custom-control-label" for="q22_1">
                                        <span class="text-muted">A.	Clear clogged stocks</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q22_2" value="b" v-model="q22">
                                    <label class="custom-control-label" for="q22_2">
                                        <span class="text-muted">B.	Check and adjust opening of vibrating discharge</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q22_3" value="c" v-model="q22">
                                    <label class="custom-control-label" for="q22_3">
                                        <span class="text-muted">C.	Check and replaced worn-out beaters</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q22_4" value="d" v-model="q22">
                                    <label class="custom-control-label" for="q22_4">
                                        <span class="text-muted">D.	Clean the hammer mill</span>
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q22">{{errors.q22[0]}}</span>
                            </div>
                            <!-- q23 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>23.	Give three (3) importance of wheat tempering </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q23_1" value="a" v-model="q23">
                                    <label class="custom-control-label" for="q23_1">
                                        <span class="text-muted">A.	Toughen the bran</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q23_2" value="b" v-model="q23">
                                    <label class="custom-control-label" for="q23_2">
                                        <span class="text-muted">B.	Mellow the endosperm</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q23_3" value="c" v-model="q23">
                                    <label class="custom-control-label" for="q23_3">
                                        <span class="text-muted">C.	Condition the Endosperm</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q23_4" value="d" v-model="q23">
                                    <label class="custom-control-label" for="q23_4">
                                        <span class="text-muted">D.	Removing the other grains in the wheat</span>
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q23">{{errors.q23[0]}}</span>
                            </div>
                            <!-- q24 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>21.	24.	Standard tempering time:</h4>

                                <label for="">A. Hard Wheat</label>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q24_a_1" class="custom-control-input" value="a" v-model="q24_a">
                                        <label class="custom-control-label" for="q24_a_1" >A. 18-24 hours</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q24_a_2" class="custom-control-input" value="b" v-model="q24_a">
                                        <label class="custom-control-label" for="q24_a_2" >B. 22-25 hours</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q24_a_3" class="custom-control-input" value="c" v-model="q24_a">
                                        <label class="custom-control-label" for="q24_a_3" >C. 16-22 hours</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q24_a_4" class="custom-control-input" value="d" v-model="q24_a">
                                        <label class="custom-control-label" for="q24_a_4" >D. 18-20 hours</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2 mb-3">
                                    <span class="text-danger" v-if="errors.q24_a">{{errors.q24_a[0]}}</span>
                                </div>

                                <label for="">B. Soft Wheat </label>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q24_b_1" class="custom-control-input" value="a" v-model="q24_b">
                                        <label class="custom-control-label" for="q24_b_1" >A. 6-8 hours</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q24_b_2" class="custom-control-input" value="b" v-model="q24_b">
                                        <label class="custom-control-label" for="q24_b_2" >B. 7-9 hours</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q24_b_3" class="custom-control-input" value="c" v-model="q24_b">
                                        <label class="custom-control-label" for="q24_b_3" >C. 6-9 hours</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q24_b_4" class="custom-control-input" value="d" v-model="q24_b">
                                        <label class="custom-control-label" for="q24_b_4" >D. 6-10 hours</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2 mb-3">
                                    <span class="text-danger" v-if="errors.q24_b">{{errors.q24_b[0]}}</span>
                                </div>
                            </div>
                            <!-- q25 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>25.	SOP for auto dampener </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q25_1" value="a" v-model="q25">
                                    <label class="custom-control-label" for="q25_1">
                                        <span class="text-muted">A.	Always set in mode 1</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q25_2" value="b" v-model="q25">
                                    <label class="custom-control-label" for="q25_2">
                                        <span class="text-muted">B.	Calibrate initial moisture according In-process result</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q25_3" value="c" v-model="q25">
                                    <label class="custom-control-label" for="q25_3">
                                        <span class="text-muted">C.	Set in mode 2</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q25_4" value="d" v-model="q25">
                                    <label class="custom-control-label" for="q25_4">
                                        <span class="text-muted">D.	Set in mode 5</span>
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q25">{{errors.q25[0]}}</span>
                            </div>
                            <!-- q26 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>26.	Label the parts of water flow assembly: </h4>
                                <div class="row">
                                        <div class="col-md-3">
                                            <div class="col-12 mt-2">
                                                <label for="">A.</label>
                                                <select v-model="q26_a">
                                                    <option v-for="(item, u) in q26_options" v-bind:key="u" :value="item.value">{{item.option}}</option>
                                                </select>
                                                <br>
                                                <span class="text-danger" v-if="errors.q26_a">{{errors.q26_a[0]}}</span>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <label for="">B.</label>
                                                <select v-model="q26_b">
                                                    <option v-for="(item, u) in q26_options" v-bind:key="u" :value="item.value">{{item.option}}</option>
                                                </select>
                                                <br>
                                                <span class="text-danger" v-if="errors.q26_b">{{errors.q26_b[0]}}</span>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <label for="">C.</label>
                                                <select v-model="q26_c">
                                                    <option v-for="(item, u) in q26_options" v-bind:key="u" :value="item.value">{{item.option}}</option>
                                                </select>
                                                <br>
                                                <span class="text-danger" v-if="errors.q26_c">{{errors.q26_c[0]}}</span>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <label for="">D.</label>
                                                <select v-model="q26_d">
                                                    <option v-for="(item, u) in q26_options" v-bind:key="u" :value="item.value">{{item.option}}</option>
                                                </select>
                                                <br>
                                                <span class="text-danger" v-if="errors.q26_d">{{errors.q26_d[0]}}</span>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <label for="">E.</label>
                                                <select v-model="q26_e">
                                                    <option v-for="(item, u) in q26_options" v-bind:key="u" :value="item.value">{{item.option}}</option>
                                                </select>
                                                <br>
                                                <span class="text-danger" v-if="errors.q26_e">{{errors.q26_e[0]}}</span>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <label for="">F.</label>
                                                <select v-model="q26_f">
                                                    <option v-for="(item, u) in q26_options" v-bind:key="u" :value="item.value">{{item.option}}</option>
                                                </select>
                                                <br>
                                                <span class="text-danger" v-if="errors.q26_f">{{errors.q26_f[0]}}</span>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <label for="">G.</label>
                                                <select v-model="q26_g">
                                                    <option v-for="(item, u) in q26_options" v-bind:key="u" :value="item.value">{{item.option}}</option>
                                                </select>
                                                <br>
                                                <span class="text-danger" v-if="errors.q26_g">{{errors.q26_g[0]}}</span>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <label for="">H.</label>
                                                <select v-model="q26_h">
                                                    <option v-for="(item, u) in q26_options" v-bind:key="u" :value="item.value">{{item.option}}</option>
                                                </select>
                                                <br>
                                                <span class="text-danger" v-if="errors.q26_h">{{errors.q26_h[0]}}</span>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <label for="">I.&nbsp;</label>
                                                <select v-model="q26_i">
                                                    <option v-for="(item, u) in q26_options" v-bind:key="u" :value="item.value">{{item.option}}</option>
                                                </select>
                                                <br>
                                                <span class="text-danger" v-if="errors.q26_i">{{errors.q26_i[0]}}</span>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <label for="">J.</label>
                                                <select v-model="q26_j">
                                                    <option v-for="(item, u) in q26_options" v-bind:key="u" :value="item.value">{{item.option}}</option>
                                                </select>
                                                <br>
                                                <span class="text-danger" v-if="errors.q26_j">{{errors.q26_j[0]}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <img src="/hr_survey/q26.png" width="100%" alt="">
                                        </div>
                                </div>
                                
                                
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q26">{{errors.q26[0]}}</span>
                            </div>

                            <!-- q27 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>27.	Importance of a color sorter equipment in cleaning process: </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q27_1" class="custom-control-input" value="a" v-model="q27">
                                        <label class="custom-control-label" for="q27_1" >A.	Help removes color impurities in wheat</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q27_2" class="custom-control-input" value="b" v-model="q27">
                                        <label class="custom-control-label" for="q27_2" >B.	Help to kill microorganism</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q27_3" class="custom-control-input" value="c" v-model="q27">
                                        <label class="custom-control-label" for="q27_3" >C. Help to determine specks in wheat</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q27_4" class="custom-control-input" value="d" v-model="q27">
                                        <label class="custom-control-label" for="q27_4" >D.	Help to increase wheat moisture</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q27">{{errors.q27[0]}}</span>
                            </div>

                            <!-- q28 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>28.	Importance of a moisture control/auto-dampener equipment in tempering process  </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q28_1" class="custom-control-input" value="a" v-model="q28">
                                        <label class="custom-control-label" for="q28_1" >A.	Consistent grain mixing for optimum water distribution</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q28_2" class="custom-control-input" value="b" v-model="q28">
                                        <label class="custom-control-label" for="q28_2" >B.	Mix wheat and water</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q28_3" class="custom-control-input" value="c" v-model="q28">
                                        <label class="custom-control-label" for="q28_3" >C. Check wheat moisture</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q28_4" class="custom-control-input" value="d" v-model="q28">
                                        <label class="custom-control-label" for="q28_4" >D.	Check wheat flow rate</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q28">{{errors.q28[0]}}</span>
                            </div>

                            <!-- q29 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>29.	Cleaning machines that uses rubbing actions for the removal of dirt, truss epidermis and mold adhering to the wheat:</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q29_1" class="custom-control-input" value="a" v-model="q29">
                                        <label class="custom-control-label" for="q29_1" >A.	Scourer</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q29_2" class="custom-control-input" value="b" v-model="q29">
                                        <label class="custom-control-label" for="q29_2" >B.	Destoner</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q29_3" class="custom-control-input" value="c" v-model="q29">
                                        <label class="custom-control-label" for="q29_3" >C. Color sorter</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q29_4" class="custom-control-input" value="d" v-model="q29">
                                        <label class="custom-control-label" for="q29_4" >D.	Recycling aspirator</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q29">{{errors.q29[0]}}</span>
                            </div>

                            <!-- q30 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>30.	Remove color impurities in wheat </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q30_1" class="custom-control-input" value="a" v-model="q30">
                                        <label class="custom-control-label" for="q30_1" >A.	Color sorter</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q30_2" class="custom-control-input" value="b" v-model="q30">
                                        <label class="custom-control-label" for="q30_2" >B.	Wheat separator</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q30_3" class="custom-control-input" value="c" v-model="q30">
                                        <label class="custom-control-label" for="q30_3" >C. Scourer</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q30_4" class="custom-control-input" value="d" v-model="q30">
                                        <label class="custom-control-label" for="q30_4" >D.	Super triur</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q30">{{errors.q30[0]}}</span>
                            </div>

                            <!-- q31 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>31.	Compute the water addition given the ff data:   </h4>

                                <span>A. Final Moisture (FM) – 16.0%</span> <br>
                                <span>B. Initial Moisture (IM) – 12.0%</span> <br>
                                <span>C. Wheat Flowrate – 17,000 Kg/Hr</span> <br>

                                <div class="col-3 mt-2">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control text-outline-primary" style="width:50%!important;" v-model="q31" placeholder="Input Answer" aria-label="" aria-describedby="button-addon2" @keyup.enter="getClientPaymentDetails">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-default disabled" type="button" id="button-addon2">Li/Hr  </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q30">{{errors.q30[0]}}</span>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button id="save-btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="savePreTestWheatCleaningTemperingAndConditioning" style="width:150px;">Save</button>
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
                q3 : [],
                q4 : '',
                q5 : '',
                q6 : [],
                q7 : [],
                q8 : '',
                q9 : '',
                q10 : [],
                q11 : '',
                q12 : '',
                q13 : '',
                q14 : '',
                q15 : '',
                q16 : '',
                q17 : '',
                q18 : [],
                q19 : '',
                q20 : [],
                q21 : '',
                q22 : [],
                q23 : [],
                q24_a : '',
                q24_b : '',
                q25 : [],
                q26_a : '',
                q26_b : '',
                q26_c : '',
                q26_d : '',
                q26_e : '',
                q26_f : '',
                q26_g : '',
                q26_h : '',
                q26_i : '',
                q26_j : '',
                q27 : '',
                q28 : '',
                q29 : '',
                q30 : '',
                q31 : '',
                errors : [],
                saveDisable : false,

                q26_options : [
                    {'value':'a','option':'Automatic Pneumatic Valve'},
                    {'value':'c','option':'Valve'},
                    {'value':'e','option':'Digital display water addition'},
                    {'value':'f','option':'Manual Regulator'},
                    {'value':'i','option':'Electronic water flow regulator'},
                    {'value':'j','option':'Selector Valve'},
                    {'value':'b','option':'Filter'},
                    {'value':'d','option':'Water Hose connect to water pump'},
                    {'value':'g','option':'Valve connected to water filtration'},
                    {'value':'h','option':'Graduated Cylinder'},
                ]
            }
        },
        created () {
            this.getUser();
        },
        methods: {
            savePreTestWheatCleaningTemperingAndConditioning(){
                let v = this;
                v.saveDisable = true;
                var r = confirm("Are you sure you want to save this pre-test?");
                if (r == true) {
                    if(v.user){
                        axios.post(`/save-pre-test-wheat-cleaning-tempering-and-conditioning`, {
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
                            q3: v.q3.length > 0 ? JSON.stringify(v.q3) : "",
                            q4: v.q4 ? v.q4 : "",
                            q5: v.q5 ? v.q5 : "",
                            q6: v.q6.length > 0 ? JSON.stringify(v.q6) : "",
                            q7: v.q7.length > 0 ? JSON.stringify(v.q7) : "",
                            q8: v.q8 ? v.q8 : "",
                            q9: v.q9 ? v.q9 : "",
                            q10: v.q10.length > 0 ? JSON.stringify(v.q10) : "",
                            q11: v.q11 ? v.q11 : "",
                            q12: v.q12 ? v.q12 : "",
                            q13: v.q13 ? v.q13 : "",
                            q14: v.q14 ? v.q14 : "",
                            q15: v.q15 ? v.q15 : "",
                            q16: v.q16 ? v.q16 : "",
                            q17: v.q17 ? v.q17 : "",
                            q18: v.q18.length > 0 ? JSON.stringify(v.q18) : "",
                            q19: v.q19 ? v.q19 : "",
                            q20: v.q20.length > 0 ? JSON.stringify(v.q20) : "",
                            q21: v.q21 ? v.q21 : "",
                            q22: v.q22.length > 0 ? JSON.stringify(v.q22)  : "",
                            q23: v.q23.length > 0 ? JSON.stringify(v.q23) : "",
                            q24_a: v.q24_a ? v.q24_a : "",
                            q24_b: v.q24_b ? v.q24_b : "",
                            q25: v.q25.length > 0 ? JSON.stringify(v.q25) : "",
                            q26_a: v.q26_a ? v.q26_a : "",
                            q26_b: v.q26_b ? v.q26_b : "",
                            q26_c: v.q26_c ? v.q26_c : "",
                            q26_d: v.q26_d ? v.q26_d : "",
                            q26_e: v.q26_e ? v.q26_e : "",
                            q26_f: v.q26_f ? v.q26_f : "",
                            q26_g: v.q26_g ? v.q26_g : "",
                            q26_h: v.q26_h ? v.q26_h : "",
                            q26_i: v.q26_i ? v.q26_i : "",
                            q26_j: v.q26_j ? v.q26_j : "",
                            q27: v.q27 ? v.q27 : "",
                            q28: v.q28 ? v.q28 : "",
                            q29: v.q29 ? v.q29 : "",
                            q30: v.q30 ? v.q30 : "",
                            q31: v.q31 ? v.q31 : "",
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
                axios.get('/get-user-pre-test-wheat-cleaning-tempering-and-conditioning')
                .then(response => { 
                    this.user = response.data;
                    this.position = response.data.position;
                    this.department = response.data.departments[0].name;
                    this.company = response.data.companies[0].name;
                    this.location = response.data.locations[0].name;
                })
                .catch(error => { 
                    console.log(error);
                    this.errors = error.response.data.error;
                   
                });
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>