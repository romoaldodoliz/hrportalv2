<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-primary opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white text-uppercase">{{ survey ? survey.survey_description : '' }}</h1>
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
                        <hr>
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0 text-uppercase">Take Survey</h3>
                           </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="role">Name*</label> 
                                        <input type="text"  class="form-control" v-model="user_name" @input="user_name=$event.target.value.toUpperCase()">
                                        <span class="text-danger" v-if="errors.user_name">{{ errors.user_name[0] }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="role">Company*</label> 
                                        <input type="text"  class="form-control" v-model="user_company" @input="user_company=$event.target.value.toUpperCase()">
                                        <span class="text-danger" v-if="errors.user_company">{{ errors.user_company[0] }}</span>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="role">Department*</label> 
                                        <input type="text"  class="form-control" v-model="user_department" @input="user_department=$event.target.value.toUpperCase()">
                                        <span class="text-danger" v-if="errors.user_department">{{ errors.user_department[0] }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">

                                <!-- Core Competency -->
                                <div class="col-md-12">
                                    <h4>Choose the knowledge/Skills you and/or your staff need to reach your business goals for 2021</h4>
                                    <small>Based on the La Fil University Competency Framework, identify the training program that will support your 2021 Business Objectives.</small>
                                    <hr>
                                    <h3 class="text-primary">Core Competency</h3>
                                    <small>Knowledge & Skills with direct relation to strategic organization capabilities. Attributes in which our organization seeks to have a competitive advantage.</small>
                                </div>

                                <div class="col-md-12">
                                    <hr>
                                    <h4>What type(s) of CORE SKILLS would benefit you and/or your BU/Dept?</h4>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="1" value="Value Session (Understanding LFUG GOC Core Values)" v-model="core_skills">
                                        <label class="custom-control-label" for="1">
                                            <span class="text-muted">Value Session (Understanding LFUG GOC Core Values)</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12  mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="2" value="Code of Conduct Training" v-model="core_skills">
                                        <label class="custom-control-label" for="2">
                                            <span class="text-muted">Code of Conduct Training</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12  mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="3" value="Health & Safety Training" v-model="core_skills">
                                        <label class="custom-control-label" for="3">
                                            <span class="text-muted">Health & Safety Training</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12  mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="4" value="ISO & GMP Training" v-model="core_skills">
                                        <label class="custom-control-label" for="4">
                                            <span class="text-muted">ISO & GMP Training</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12  mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="5" value="5S / House Keeping" v-model="core_skills">
                                        <label class="custom-control-label" for="5">
                                            <span class="text-muted">5S / House Keeping</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12  mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="6" value="Operational Excellence Training (Lean 6 Sigma, TQM, Design Thinking etc.)" v-model="core_skills">
                                        <label class="custom-control-label" for="6">
                                            <span class="text-muted">Operational Excellence Training (Lean 6 Sigma, TQM, Design Thinking etc.)</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <span class="text-danger" v-if="errors.core_skills">{{errors.core_skills[0]}}</span>
                                    </div>
                                </div>
                                
                                <!-- Cross-Functional Competency -->
                                <div class="col-md-12">
                                    <hr>
                                    <h3 class="text-primary">Cross-Functional Competency</h3>
                                    <small>This knowledge and skills are common job requirements across other functions & departments. Some benefits of these programs are increase employee proficiency, promotion of teamwork & reduction of time & cost, etc.</small>
                                </div>

                                <div class="col-md-12">
                                    <hr>
                                    <h4>What type(s) of CROSS-FUNCTIONAL training would benefit you and/or your BU/Dept?</h4>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="2_1" value="Problem Solving / Decision Making" v-model="cc_trainings">
                                        <label class="custom-control-label" for="2_1">
                                            <span class="text-muted">Problem Solving / Decision Making</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="2_2" value="Project Management" v-model="cc_trainings">
                                        <label class="custom-control-label" for="2_2">
                                            <span class="text-muted">Project Management</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="2_3" value="Collaboration Skills (breaking the Silo Mentality)" v-model="cc_trainings">
                                        <label class="custom-control-label" for="2_3">
                                            <span class="text-muted">Collaboration Skills (breaking the Silo Mentality)</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="2_4" value="Building Strategy to Outcome" v-model="cc_trainings">
                                        <label class="custom-control-label" for="2_4">
                                            <span class="text-muted">Building Strategy to Outcome</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="2_5" value="Developing Emotional Maturity" v-model="cc_trainings">
                                        <label class="custom-control-label" for="2_5">
                                            <span class="text-muted">Developing Emotional Maturity</span>
                                        </label>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-2">
                                        <span class="text-danger" v-if="errors.cc_trainings">{{errors.cc_trainings[0]}}</span>
                                    </div>

                                    <h5 class="mt-3">COMMUNICATION SKILLS TRAINING</h5>

                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="3_1" value="Business Writing" v-model="cc_communication_skills">
                                        <label class="custom-control-label" for="3_1">
                                            <span class="text-muted">Business Writing</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="3_2" value="Presentation Skills" v-model="cc_communication_skills">
                                        <label class="custom-control-label" for="3_2">
                                            <span class="text-muted">Presentation Skills</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="3_3" value="Grammar and Proofreading" v-model="cc_communication_skills">
                                        <label class="custom-control-label" for="3_3">
                                            <span class="text-muted">Grammar and Proofreading</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="3_4" value="E-mail Protocol / Etiqutte" v-model="cc_communication_skills">
                                        <label class="custom-control-label" for="3_4">
                                            <span class="text-muted">E-mail Protocol / Etiqutte</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="3_5" value="Interpersonal Communication Skills" v-model="cc_communication_skills">
                                        <label class="custom-control-label" for="3_5">
                                            <span class="text-muted">Interpersonal Communication Skills</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="3_6" value="Other" v-model="cc_communication_skills">
                                            <label class="custom-control-label" for="3_6">
                                                <span class="text-muted">Other</span>
                                            </label>
                                        </div>
                                        
                                        <div v-for="k in cc_communication_skills">
                                            <input type="text"  class="form-control mt-2 col-6" v-if="k=='Other'" v-model="cc_communication_skills_other" placeholder="Other Specify...">
                                        </div>
                                        
                                    </div>

                                    <div class="col-12 mt-2">
                                        <span class="text-danger" v-if="errors.cc_communication_skills">{{errors.cc_communication_skills[0]}}</span>
                                    </div>

                                    <h5 class="mt-3">CUSTOMER SERVICE TRAINING</h5>

                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="4_1" value="Building a Customer Service Mindset" v-model="cc_customer_service">
                                        <label class="custom-control-label" for="4_1">
                                            <span class="text-muted">Building a Customer Service Mindset</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="4_2" value="How to deal with Difficult People" v-model="cc_customer_service">
                                        <label class="custom-control-label" for="4_2">
                                            <span class="text-muted">How to deal with Difficult People</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="4_3" value="Service Recovery" v-model="cc_customer_service">
                                        <label class="custom-control-label" for="4_3">
                                            <span class="text-muted">Service Recovery</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="4_4" value="Other" v-model="cc_customer_service">
                                            <label class="custom-control-label" for="4_4">
                                                <span class="text-muted">Other</span>
                                            </label>
                                        </div>

                                        <div v-for="k in cc_customer_service">
                                            <input type="text"  class="form-control mt-2 col-6" v-if="k=='Other'" v-model="cc_customer_service_other" placeholder="Other Specify...">
                                        </div>

                                    </div>

                                    <div class="col-12 mt-2">
                                        <span class="text-danger" v-if="errors.cc_customer_service">{{errors.cc_customer_service[0]}}</span>
                                    </div>

                                    <h5 class="mt-3">INFORMATION TECHNOLOGY SOFTWARE SKILLS</h5>

                                     <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="5_1" value="MS Excel" v-model="cc_it_software_skills">
                                        <label class="custom-control-label" for="5_1">
                                            <span class="text-muted">MS Excel</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="5_2" value="MS Word" v-model="cc_it_software_skills">
                                        <label class="custom-control-label" for="5_2">
                                            <span class="text-muted">MS Word</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="5_3" value="MS Powerpoint" v-model="cc_it_software_skills">
                                        <label class="custom-control-label" for="5_3">
                                            <span class="text-muted">MS Powerpoint</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="5_4" value="Basic Computer Skills" v-model="cc_it_software_skills">
                                        <label class="custom-control-label" for="5_4">
                                            <span class="text-muted">Basic Computer Skills</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="5_5" value="Use of Virtual Tools (Webex Meetings, WFH Tools, Etc)" v-model="cc_it_software_skills">
                                        <label class="custom-control-label" for="5_5">
                                            <span class="text-muted">Use of Virtual Tools (Webex Meetings, WFH Tools, Etc)</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="5_6" value="SAP" v-model="cc_it_software_skills">
                                        <label class="custom-control-label" for="5_6">
                                            <span class="text-muted">SAP</span>
                                        </label>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="5_7" value="Other" v-model="cc_it_software_skills">
                                            <label class="custom-control-label" for="5_7">
                                                <span class="text-muted">Other</span>
                                            </label>
                                        </div>

                                        <div v-for="k in cc_it_software_skills">
                                            <input type="text"  class="form-control mt-2 col-6" v-if="k=='Other'" v-model="cc_it_software_skills_other" placeholder="Other Specify...">
                                        </div>

                                    </div>

                                    <div class="col-12 mt-2">
                                        <span class="text-danger" v-if="errors.cc_it_software_skills">{{errors.cc_it_software_skills[0]}}</span>
                                    </div>

                                </div>


                                <!-- Leadership Competency -->
                                <div class="col-md-12 mt-2">
                                    <hr>
                                    <h3 class="text-primary">Leadership Competency</h3>
                                    <small>Skills and attributes that make Manager or Supervisor effective leaders in our organization</small>
                                </div>

                                <div class="col-md-12">
                                    <hr>
                                    <h4>What type(s) of LEADERSHIP training would benefit you and/or your BU/Dept?</h4>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="6_1" value="Performance Management (Objective Setting, Coaching, Employee Descipline)" v-model="leadership_trainings">
                                        <label class="custom-control-label" for="6_1">
                                            <span class="text-muted">Performance Management (Objective Setting, Coaching, Employee Descipline)</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="6_3" value="Talent Management (Understanding Competencies, Conducting Talent Reviews, Dev't & Succession Planning, Retaining Key & Critical Talents)" v-model="leadership_trainings">
                                        <label class="custom-control-label" for="6_3">
                                            <span class="text-muted">Talent Management (Understanding Competencies, Conducting Talent Reviews, Dev't & Succession Planning, Retaining Key & Critical Talents)</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="6_4" value="Rewards Management (Tangible & Intangible Rewards, Fixed & Variable Pays, 3Ps of Compensation)" v-model="leadership_trainings">
                                        <label class="custom-control-label" for="6_4">
                                            <span class="text-muted">Rewards Management (Tangible & Intangible Rewards, Fixed & Variable Pays, 3Ps of Compensation)</span>
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="6_5" value="Financial Management (Profit & Loss, Planning & Forecasting, Financial Reporting & Analysis)" v-model="leadership_trainings">
                                        <label class="custom-control-label" for="6_5">
                                            <span class="text-muted">Financial Management (Profit & Loss, Planning & Forecasting, Financial Reporting & Analysis)</span>
                                        </label>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-2">
                                        <span class="text-danger" v-if="errors.leadership_trainings">{{errors.leadership_trainings[0]}}</span>
                                    </div>

                                </div>


                                

                                <!-- Functional Competency -->
                                <div class="col-md-12 mt-2">
                                    <hr>
                                    <h3 class="text-primary">Functional Competency</h3>
                                    <small>This relates to the knowledge, skills, and behavior required for the successful accomplishment of a specific job or task.</small>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                   <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="role">What knowledge/skills gaps exist within your team (ex. Millers - Standard Operating Procedures
                                                , IT Specialists - new trends in software development)</label> 
                                            <input type="text"  class="form-control" v-model="fc_answer_1" placeholder="Your answer...">
                                            <span class="text-danger" v-if="errors.fc_answer_1">{{errors.fc_answer_1[0]}}</span>
                                        </div>
                                    </div>
                                   <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="role">Is there a particular knowledge or skills your Business Unit needs to succeed in the "new normal"?
                                                (Ex. Leading Virtual Teams)</label> 
                                            <input type="text"  class="form-control" v-model="fc_answer_2" placeholder="Your answer...">
                                            <span class="text-danger" v-if="errors.fc_answer_2">{{errors.fc_answer_2[0]}}</span>
                                        </div>
                                    </div>
                                   <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="role">Who needs training the most? (Please specifiy job title.)</label> 
                                            <input type="text"  class="form-control" v-model="fc_answer_3" placeholder="Your answer...">
                                            <span class="text-danger" v-if="errors.fc_answer_3">{{errors.fc_answer_3[0]}}</span>
                                        </div>
                                    </div>
                                   <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="role">What is the time frame for learning this particular skill? (Do you have an expected timeline for
                                                developing the new capabilities?)</label> 
                                            <input type="text"  class="form-control" v-model="fc_answer_4" placeholder="Your answer...">
                                            <span class="text-danger" v-if="errors.fc_answer_4">{{errors.fc_answer_4[0]}}</span>
                                        </div>
                                    </div>
                                   <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="role">What should be the outcome of this intervention? What change do you want to see? What does "good"
                                                 look like to you?</label> 
                                            <input type="text"  class="form-control" v-model="fc_answer_5" placeholder="Your answer...">
                                            <span class="text-danger" v-if="errors.fc_answer_4">{{ errors.fc_answer_4[0] }}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                     <!-- Footer -->
                    <div class="card-footer bg-white border-0">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button id="save-btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="saveSurvey" style="width:150px;">Save</button>
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
            user_name : '',
            user_company : '',
            user_department : '',
            survey:[],
            core_skills : [],
            cc_trainings : [],
            cc_communication_skills : [],
            cc_communication_skills_other : '',
            cc_customer_service : [],
            cc_customer_service_other : '',
            cc_it_software_skills : [],
            cc_it_software_skills_other : '',
            leadership_trainings : [],
            fc_answer_1 : '',
            fc_answer_2 : '',
            fc_answer_3 : '',
            fc_answer_4 : '',
            fc_answer_5 : '',
            errors:[]
        }
    },
    created () {
        this.getSurvey();
    },
    methods: {
        getSurvey() {
            axios.get('/get-survey')
            .then(response => { 
                this.survey = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            });
        },
        saveSurvey(){
            let v = this;
            axios.post(`/save-user-survey`, {
                survey_id: v.survey.id,
                user_name: v.user_name,
                user_company: v.user_company,
                user_department: v.user_department,
                core_skills: v.core_skills.length > 0 ? JSON.stringify(v.core_skills) : "",
                cc_trainings: v.cc_trainings.length > 0 ? JSON.stringify(v.cc_trainings) : "",
                cc_communication_skills: v.cc_communication_skills.length > 0 ? JSON.stringify(v.cc_communication_skills) : "",
                cc_communication_skills_other: v.cc_communication_skills_other ? v.cc_communication_skills_other : "",
                cc_customer_service: v.cc_customer_service.length > 0 ? JSON.stringify(v.cc_customer_service) : "",
                cc_customer_service_other: v.cc_customer_service_other ? v.cc_customer_service_other : "",
                cc_it_software_skills: v.cc_it_software_skills.length > 0 ? JSON.stringify(v.cc_it_software_skills) : "",
                cc_it_software_skills_other: v.cc_it_software_skills_other ? v.cc_it_software_skills_other : "",
                leadership_trainings: v.leadership_trainings.length > 0 ? JSON.stringify(v.leadership_trainings) : "",
                fc_answer_1: v.fc_answer_1 ? v.fc_answer_1 : "",
                fc_answer_2: v.fc_answer_2 ? v.fc_answer_2 : "",
                fc_answer_3: v.fc_answer_3 ? v.fc_answer_3 : "",
                fc_answer_4: v.fc_answer_4 ? v.fc_answer_4 : "",
                fc_answer_5: v.fc_answer_5 ? v.fc_answer_5 : "",
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
            });
        }
    },
}
</script>
