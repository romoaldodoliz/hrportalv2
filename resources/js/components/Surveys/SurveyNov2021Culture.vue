<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-primary opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white text-uppercase">SURVEY : NOVEMBER 2021 VALUES QUESTIONNAIRE</h1>
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
                            <!-- Q1 -->
                            <div class="col-md-12">
                                <hr>
                                <h4 v-if="language == 'English'">1.	What are the Core Values of La Filipina Uygongco Group of Companies?</h4>
                                <h4 v-else>1.	Anu- ano ang Core Values ng La Filipina Uygongco Group of Companies?</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_1" class="custom-control-input" value="a" v-model="q1">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q1_1" >A.	Integrity, Loyalty, Excellence</label>
                                        <label  v-else class="custom-control-label" for="q1_1" >A.	Integrity, Loyalty, Excellence</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_2" class="custom-control-input" value="b" v-model="q1">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q1_2" >B.	Honesty, Excellence, Innovation, Diligence, Empathy, Agility, Loyalty</label>
                                        <label  v-else class="custom-control-label" for="q1_2" >B.	Honesty, Excellence, Innovation, Diligence, Empathy, Agility, Loyalty</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_3" class="custom-control-input" value="c" v-model="q1">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q1_3" >C.	Excellence, Loyalty, intelligence</label>
                                        <label  v-else class="custom-control-label" for="q1_3" >C.	Excellence, Loyalty, intelligence</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_4" class="custom-control-input" value="d" v-model="q1">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q1_4" >D.	Integrity, Loyalty, Happiness</label>
                                        <label  v-else class="custom-control-label" for="q1_4" >D.	Integrity, Loyalty, Happiness</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <span class="text-danger" v-if="errors.q1">{{errors.q1[0]}}</span>
                            </div>
                            <!-- Q2 -->
                            <div class="col-md-12">
                                <hr>
                                <h4 v-if="language == 'English'">2.	All of the following can be attributed to the value of Excellence except:</h4>
                                <h4 v-else>2.	Lahat ng mga sumusunod ay patungkol sa value ng Excellence maliban sa: </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_1" class="custom-control-input" value="a" v-model="q2">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q2_1" >A.	Ensuring work we do for internal and external clients are done with speed, quality and cost efficiency </label>
                                        <label  v-else class="custom-control-label" for="q2_1" >A.	Sinisigurado na ang trabaho na ating ginagawa para sa ating mga kliyente sa loob at labas ng kumpanya ay mabilis, de-kalidad at sa tamang halaga</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_2" class="custom-control-input" value="b" v-model="q2">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q2_2" >B.	Honoring commitment to clients</label>
                                        <label  v-else class="custom-control-label" for="q2_2" >B.	Pagtupad sa mga pangako sa mga kliyente</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_3" class="custom-control-input" value="c" v-model="q2">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q2_3" >C.	Relentlessly striving to exceed expectations at work</label>
                                        <label  v-else class="custom-control-label" for="q2_3" >C.	Walang tigil sa pagsubok na malampasan pa ang mga inaasahan sa atin sa trabaho</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_4" class="custom-control-input" value="d" v-model="q2">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q2_4" >D.	Continuous learning and improvement</label>
                                        <label  v-else class="custom-control-label" for="q2_4" >D.	Tuloy-tuloy na pag-aaral at pagdagdag ng kaalaman </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_5" class="custom-control-input" value="e" v-model="q2">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q2_5" >E.	Not being satisfied with “good enough” results</label>
                                        <label  v-else class="custom-control-label" for="q2_5" >E.	Hindi pwede ang “pwede na”.</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <span class="text-danger" v-if="errors.q2">{{errors.q2[0]}}</span>
                            </div>

                            <!-- Q3 -->
                            <div class="col-md-12">
                                <hr>
                                <h4 v-if="language == 'English'">3.	Following / liking the social media pages of our company and products (Facebook, Instagram, YouTube) and sharing posts is one way to show what particular company Core Value?</h4>
                                <h4 v-else>3.	Ang pag-like, follow at subscribe sa mga social media pages’ ng ating kumpanya at mga produkto (Facebook, Instagram, YouTube) at pag-share ng mga posts dito ay isang paraan upang mapakita ng pagsunod sa anong Core Value?  </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q3_1" class="custom-control-input" value="a" v-model="q3">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q3_1" >A.	Integrity</label>
                                        <label  v-else class="custom-control-label" for="q3_1" >A.	Integrity</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q3_2" class="custom-control-input" value="b" v-model="q3">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q3_2" >B.	Loyalty</label>
                                        <label  v-else class="custom-control-label" for="q3_2" >B.	Loyalty</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q3_3" class="custom-control-input" value="c" v-model="q3">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q3_3" >C.	Excellence</label>
                                        <label  v-else class="custom-control-label" for="q3_3" >C.	Excellence</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <span class="text-danger" v-if="errors.q3">{{errors.q3[0]}}</span>
                            </div>

                            <!-- L1 -->
                            <div class="col-md-12">
                                <h4 v-if="language == 'English'">Do you like, follow and subscribe to the social media pages (Facebook, Instagram or YouTube) of La Filipina Uy Gongco Group of Companies La Filipina and Philippine Foremost Milling Corporation?</h4>
                                <h4 v-else>Nag-like, follow o subscribe ka ba sa mga social media pages (Facebook, Instagram, YouTube) and social media pages’ ng of La Filipina Uy Gongco Group of Companies La Filipina and Philippine Foremost Milling Corporation?</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="l1_1" class="custom-control-input" value="a" v-model="l1">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="l1_1" >A.	Yes</label>
                                        <label  v-else class="custom-control-label" for="l1_1" >A.	Oo</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="l1_2" class="custom-control-input" value="b" v-model="l1">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="l1_2" >B.	No</label>
                                        <label  v-else class="custom-control-label" for="l1_2" >B.	Hindi</label>
                                    </div>
                                </div>
                            </div>
                            <!-- L3 -->
                            <div class="col-md-12">
                                <h4 v-if="language == 'English'">Do you share the content of the social media pages of the company to your social media contacts to help promote our company and products?</h4>
                                <h4 v-else>Nag she-share ka ba ng mga posts sa social media ng kumpanya sa iyong mga contact o kaibigan para maipagmalaki an gating kumpanya at mga produkto?</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="l2_1" class="custom-control-input" value="a" v-model="l2">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="l2_1" >A.	Yes</label>
                                        <label  v-else class="custom-control-label" for="l2_1" >A.	Oo</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="l2_2" class="custom-control-input" value="b" v-model="l2">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="l2_2" >B.	No</label>
                                        <label  v-else class="custom-control-label" for="l2_2" >B.	Hindi</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Q4 -->
                            <div class="col-md-12">
                                <hr>
                                <h4 v-if="language == 'English'">4.	You were asked by a co-worker who has been on Mandatory Leave to file leaves on his behalf in the ESS since he does not have any access to the system and Payroll’s deadline is that same day. The right thing to do is:</h4>
                                <h4 v-else>4.	Ikaw ay pinakiusapan ng iyong katrabaho na naka Mandatory Leave para mag-file ng kanyang leave sa ESS dahil hindi niya ito ma access mula sa bahay at yung araw na rin na iyon ang deadline ng Payroll. An tamang gawin ay: </h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_1" class="custom-control-input" value="a" v-model="q4">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q4_1" >A.	Log-into your co-worker’s ESS on his behalf since his lack of access to the system in an emergency situation will justify this action.</label>
                                        <label  v-else class="custom-control-label" for="q4_1" >A.	Mag-log in sa ESS ng katrabaho at i-file ang kanyang leave dahil ang kawalan nya ng access habang emergency ang sitwasyon ay makatwiran. </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_2" class="custom-control-input" value="b" v-model="q4">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q4_2" >B.	Do not accommodate co-worker’s request and ask the Department Manager to access his ESS account on his behalf instead.</label>
                                        <label  v-else class="custom-control-label" for="q4_2" >B.	Huwag sundin ang pakiusap ng katrabaho at sa halip ay sabihan ang Manager ng Departamento na siya ang gumawa nito.</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_3" class="custom-control-input" value="c" v-model="q4">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q4_3" >C.	Log-into your co-worker’s account but inform your Department Manager after doing so.</label>
                                        <label  v-else class="custom-control-label" for="q4_3" >C.	Mag-log-in sa ESS ng katrabaho at sabihin sa Manager ng Department matapos gawin ito. </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_4" class="custom-control-input" value="d" v-model="q4">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q4_4" >D.	Do not accommodate co-worker’s request and ask him to coordinate immediately with HR for proper guidance.</label>
                                        <label  v-else class="custom-control-label" for="q4_4" >D.	Huwag sundin ang pakiusap ng katrabaho at sabihin na makipag-usap agad sa HR para mabigyan siya ng tamang pag-gabay.</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <span class="text-danger" v-if="errors.q4">{{errors.q4[0]}}</span>
                            </div>

                            <!-- Q5 -->
                            <div class="col-md-12">
                                <hr>
                                <h4 v-if="language == 'English'">5.	You noticed that the process of another section of your department is somewhat inefficient and you think that the process can still be improved to boost productivity. What is the best thing to do?</h4>
                                <h4 v-else>5.	Napansin mo na ang proseso ng iba seksyon ng iyong departamento ay medyo hindi mabisa at mayroon kang nasa isip upang mapabuti ito at sa palagay mo mas mapapalakas din nito ang kanilang pagiging produktibo. Ano ang maaaring maging pinakamahusay na bagay na dapat gawin?</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_1" class="custom-control-input" value="a" v-model="q5">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q5_1" >A.	Observe the other section and wait for them to make improvements</label>
                                        <label  v-else class="custom-control-label" for="q5_1" >A.	Obserbahan ang iba pang seksyon kung maiisip din nila ang proseso na iniisip mo </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_2" class="custom-control-input" value="b" v-model="q5">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q5_2" >B.	Politely suggest it to the section manager and help them to implement the process to achieve optimal process</label>
                                        <label  v-else class="custom-control-label" for="q5_2" >B.	Magalang na imungkahi ito sa tagapamahala ng seksyon at tulungan silang ipatupad ang proseso upang makamit ang pinakamainam na proseso</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_3" class="custom-control-input" value="c" v-model="q5">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q5_3" >C.	Think that it is not under your department so you don’t have to put any concern on it</label>
                                        <label  v-else class="custom-control-label" for="q5_3" >C.	Isipin na wala ito sa ilalim ng iyong seksyon kaya hindi mo na kailangang isipin pa</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_4" class="custom-control-input" value="d" v-model="q5">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q5_4" >D.	Tell your other colleagues that the other section is wasting time on doing something inefficient</label>
                                        <label  v-else class="custom-control-label" for="q5_4" >D.	Sabihin sa iyong iba pang mga kasamahan na ang ibang seksyon ay nag-aaksaya ng oras sa paggawa ng isang bagay na hindi mabisa</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <span class="text-danger" v-if="errors.q5">{{errors.q5[0]}}</span>
                            </div>

                            <!-- Q6 -->
                            <div class="col-md-12">
                                <hr>
                                <h4 v-if="language == 'English'">6.	You have traveled yesterday to the emergency room of a hospital to bring a relative who suffered a stroke. Since you are feeling just fine, there is no need to declare this in the Health Declaration Form.  </h4>
                                <h4 v-else>6.	Ikaw ay galing sa emergency room ng isang ospital kahapon para dalhin ang isang kapamilya na na-stroke. Dahil wala ka namang nararamdaman, hindi na kailangan ito ideklara sa Health Declaration Form.</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q6_1" class="custom-control-input" value="a" v-model="q6">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q6_1" >A.	True</label>
                                        <label  v-else class="custom-control-label" for="q6_1" >A.	Tama</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q6_2" class="custom-control-input" value="b" v-model="q6">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q6_2" >B.	False</label>
                                        <label  v-else class="custom-control-label" for="q6_2" >B.	Mali</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <span class="text-danger" v-if="errors.q6">{{errors.q6[0]}}</span>
                            </div>

                            <!-- Q7 -->
                            <div class="col-md-12">
                                <hr>
                                <h4 v-if="language == 'English'">7.	An example of Loyalty is personally using and promoting our excellent company products.</h4>
                                <h4 v-else>7.	Isang halimbawa ng “Loyalty” o katapatan ay personal na pag gamit at pag kukwento sa iba tungkol sa mga mahuhusay na produkto ng ating kumpanya.</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q7_1" class="custom-control-input" value="a" v-model="q7">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q7_1" >A.	True</label>
                                        <label  v-else class="custom-control-label" for="q7_1" >A.	Tama</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q7_2" class="custom-control-input" value="b" v-model="q7">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q7_2" >B.	False</label>
                                        <label  v-else class="custom-control-label" for="q7_2" >B.	Mali</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <span class="text-danger" v-if="errors.q7">{{errors.q7[0]}}</span>
                            </div>

                            <!-- Q8 -->
                            <div class="col-md-12">
                                <hr>
                                <h4 v-if="language == 'English'">8.	Integrity is doing the right thing when the boss is watching</h4>
                                <h4 v-else>8.	Ang “Integrity” o Katapatan ay pag-gawa ng tama kung nakikita lamang ng ating pinuno.</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q8_1" class="custom-control-input" value="a" v-model="q8">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q8_1" >A.	Correct. We should do the right thing when authority figures see us.</label>
                                        <label  v-else class="custom-control-label" for="q8_1" >A.	Tama. Gawin natin ang tama pag may nakakakitang mga pinuno</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q8_2" class="custom-control-input" value="b" v-model="q8">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q8_2" >B.	Wrong. We should do the right thing even if no one is watching</label>
                                        <label  v-else class="custom-control-label" for="q8_2" >B.	Mali. Gawin natin ang tama kahit walang nakatingin</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <span class="text-danger" v-if="errors.q8">{{errors.q8[0]}}</span>
                            </div>

                            <!-- Q9 -->
                            <div class="col-md-12">
                                <hr>
                                <h4 v-if="language == 'English'">9.	An example of Excellence is constantly learning new things to apply in the job.</h4>
                                <h4 v-else>9.	Isang halimbawa ng Kahusayan o “Excellence” ay ang tuloy tuloy na pagaaral upang magamit sa trabaho.</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q9_1" class="custom-control-input" value="a" v-model="q9">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q9_1" >A.	True. Excellence is also striving to develop one’s knowledge and skills</label>
                                        <label  v-else class="custom-control-label" for="q9_1" >A.	Tama. Ang Kahusayan o “Excellence” ay pagpapalawig ng kaalaman at kakayahan ng isang tao.</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q9_2" class="custom-control-input" value="b" v-model="q9">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q9_2" >B.	False. Learning new things and applying in the job is an example of Loyalty.</label>
                                        <label  v-else class="custom-control-label" for="q9_2" >B.	Mali. Ang pag aaral ng mga bagong bagay para magamit sa trabaho ay isang halimbawa ng Katapatan o “Loyalty”</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <span class="text-danger" v-if="errors.q9">{{errors.q9[0]}}</span>
                            </div>

                            <!-- Q10 -->
                            <div class="col-md-12">
                                <hr>
                                <h4 v-if="language == 'English'">10.	Which of the following statements is incorrect:</h4>
                                <h4 v-else>10.	Alin sa mga sumusunod ang mali:</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q10_1" class="custom-control-input" value="a" v-model="q10">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q10_1" >A.	Values are the guiding principles of a company.</label>
                                        <label  v-else class="custom-control-label" for="q10_1" >A.	Ang mga “Values” ay ang mga prinsipyong gumagabay sa isang kumpanya.</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q10_2" class="custom-control-input" value="b" v-model="q10">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q10_2" >B.	Values are not as important as achieving business results and targets</label>
                                        <label  v-else class="custom-control-label" for="q10_2" >B.	Ang “Values” ay hindi kasing importante ng pagkakaroon ng resulta sa ating negosyo at pagkamit ng mga “target”.</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q10_3" class="custom-control-input" value="c" v-model="q10">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q10_3" >C.	Values should be practiced by leaders of the company and staff alike</label>
                                        <label  v-else class="custom-control-label" for="q10_3" >C.	Ang “Values” ay dapat ipakita ng mga pinuno ng kumpanya at mga “staff”.</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q10_4" class="custom-control-input" value="d" v-model="q10">
                                        <label  v-if="language == 'English'" class="custom-control-label" for="q10_4" >D.	A company with the right values and culture can overcome challenges easier.</label>
                                        <label  v-else class="custom-control-label" for="q10_4" >D.	Ang isang kumpanya na may tamang “Values” aymas kayang mapagtagumpayan ang mga pagsubok na haharapin nito. </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <span class="text-danger" v-if="errors.q10">{{errors.q10[0]}}</span>
                            </div>
                        
                        </div>
                    </div>

                    
                     <!-- Footer -->
                    <div class="card-footer bg-white border-0" v-if="user">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button id="save-btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" :disabled="saveDisable" @click="saveSurveyNov2021Culture" style="width:150px;">Save</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="languageModal" class="language-modal">
        <!-- Modal content -->
        <div class="language-content">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Select Language</label>
                    <select v-model="language" class="form-control">
                        <option value="English">English</option>
                        <option value="Filipino">Filipino</option>
                    </select>
                </div>
                <button class="btn btn-md btn-success" @click="setLanguage(language)">Set</button>
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
                l1 : '',
                l2 : '',
                errors : [],
                language : 'English',
                saveDisable : false
            }
        },
        created () {
            this.getUser();
        },
        methods: {
            setLanguage(language){
                if(language){
                    this.language = language;
                    var modal = document.getElementById("languageModal");
                    modal.style.display = "none";
                }else{
                    alert('Warning : Select Language');
                }
            },
            getUser(){
                axios.get('/get-user-survey-nov-2021-culture')
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
            saveSurveyNov2021Culture() {
                let v = this;
                v.saveDisable = true;
                var r = confirm("Are you sure you want to save this survey?");
                if (r == true) {
                    if(v.user){
                        axios.post(`/save-survey-nov-2021-culture`, {
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
                            l1: v.l1 ? v.l1 : "",
                            l2: v.l2 ? v.l2 : "",
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
            }
        },
    }
</script>

<style lang="scss" scoped>
    .language-modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content/Box */
    .language-content {
        background-color: #fefefe;
        margin: 5% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 30%; /* Could be more or less, depending on screen size */
        border: 0 solid rgba(0,0,0,.2);
        border-radius: .4375rem;
    }

    .error {
        font-weight: bold;
        color: red;
    }

</style>