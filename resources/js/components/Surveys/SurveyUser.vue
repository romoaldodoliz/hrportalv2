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
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="col-md-12 text-center">
                            <img src="/img/lfuggoc_logo.png" style="width:150px;height:auto;">
                        </div>
                        <hr>
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0 text-uppercase">List of Users</h3>
                           </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-xl-4 mb-2 mt-3 float-right">
                                <input type="text" name="keyword" class="form-control" placeholder="Search" autocomplete="off" v-model="keywords" id="keyword">
                            </div> 
                            <div class="col-md-12">
                                    <download-excel
                                    :data   = "users"
                                    :fields = "json_fields"
                                    class   = "btn btn-sm btn-default mt-3 ml-3 mr-3 float-right"
                                    name    = "Learning and Development.xls">
                                        Export to excel
                                </download-excel>
                            </div>
                        </div> 
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <!-- Users table -->
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Date</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Company</th>
                                        <th scope="col">Department</th>
                                        <th scope="col">Core Skills</th>
                                        <th scope="col">CROSS-FUNCTIONAL training</th>
                                        <th scope="col">COMMUNICATION SKILLS TRAINING</th>
                                        <th scope="col">CUSTOMER SERVICE TRAINING</th>
                                        <th scope="col">INFORMATION TECHNOLOGY SOFTWARE SKILLS</th>
                                        <th scope="col">LEADERSHIP training</th>
                                        <th scope="col">Functional Competency</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(user, i) in filteredQueues" :key="i">
                                        <td>{{user.created_at }}</td>
                                        <td>{{user.user_name}}</td>
                                        <td>{{user.user_company}}</td>
                                        <td>{{user.user_department}}</td>
                                        <td>
                                            <ul >
                                                <li v-for="item in user.core_skills">
                                                    {{item}}
                                                </li>
                                            </ul>
                                        </td>
                                        <td>
                                            <ul >
                                                <li v-for="item in user.cc_trainings">
                                                    {{item}}
                                                </li>
                                            </ul>
                                        </td>
                                        <td>
                                            <ul >
                                                <li v-for="item in user.cc_communication_skills">
                                                    {{item}}
                                                </li>
                                                <li v-if="user.cc_communication_skills_other">{{user.cc_communication_skills_other}}</li>
                                            </ul>
                                        </td>
                                        <td>
                                            <ul >
                                                <li v-for="item in user.cc_customer_service">
                                                    {{item}}
                                                </li>
                                                <li v-if="user.cc_customer_service_other">{{user.cc_customer_service_other}}</li>
                                            </ul>
                                        </td>
                                        <td>
                                            <ul >
                                                <li v-for="item in user.cc_it_software_skills">
                                                    {{item}}
                                                </li>
                                                <li v-if="user.cc_it_software_skills_other">{{user.cc_it_software_skills_other}}</li>
                                            </ul>
                                        </td>
                                        <td>
                                            <ul >
                                                <li v-for="item in user.leadership_trainings">
                                                    {{item}}
                                                </li>
                                            </ul>
                                        </td>
                                        <td>
                                            <ul>
                                                <li v-if="user.fc_answer_1">{{user.fc_answer_1}}</li>
                                                <li v-if="user.fc_answer_2">{{user.fc_answer_2}}</li>
                                                <li v-if="user.fc_answer_3">{{user.fc_answer_3}}</li>
                                                <li v-if="user.fc_answer_4">{{user.fc_answer_4}}</li>
                                                <li v-if="user.fc_answer_5">{{user.fc_answer_5}}</li>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mb-3 mt-3 ml-1" v-if="filteredQueues.length ">
                                <div class="col-6">
                                    <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                                        <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                                    <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                                </div>
                                <div class="col-6 text-right">
                                    <span class="mr-2">Filtered User(s) : {{ filteredQueues.length }} </span><br>
                                    <span class="mr-2">Total User(s) : {{ users.length }}</span>
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
import JsonExcel from 'vue-json-excel'
export default {
    components: {
        'downloadExcel': JsonExcel
    },
    data() {
        return {
            keywords: "",
            survey: [],
            users: [],
            currentPage: 0,
            itemsPerPage: 50,
            json_fields: {
                'DATE' : 'created_at',
                'NAME' : 'user_name',
                'COMPANY' : 'user_company',
                'DEPARTMENT' : 'user_department',
                'CORE SKILLS' : {
                    callback: (value) => {
                        let core_skills = '';
                        if(value.core_skills){
                            value.core_skills.forEach((value, index) => {
                                if(index == 0){
                                    core_skills = value;
                                }else{
                                    core_skills = core_skills + ',' + value;
                                }
                            });
                        }
                        return core_skills;
                    }
                },
                'CROSS-FUNCTIONAL TRAINING' : {
                    callback: (value) => {
                        let cc_trainings = '';
                        if(value.cc_trainings){
                            value.cc_trainings.forEach((value, index) => {
                                if(index == 0){
                                    cc_trainings = value;
                                }else{
                                    cc_trainings = cc_trainings + ',' + value;
                                }
                            });
                        }
                        return cc_trainings;
                    }
                },
                'COMMUNICATION SKILLS TRAINING' : {
                    callback: (value) => {
                        let cc_communication_skills = '';
                        if(value.cc_communication_skills){
                            value.cc_communication_skills.forEach((value, index) => {
                                if(index == 0){
                                    cc_communication_skills = value;
                                }else{
                                    cc_communication_skills = cc_communication_skills + ',' + value;
                                }
                            });
                        }
                        if(value.cc_communication_skills_other){
                             cc_communication_skills = cc_communication_skills + ',' + value.cc_communication_skills_other;
                        }

                        return cc_communication_skills;
                    }
                },
                'CUSTOMER SERVICE TRAINING' : {
                    callback: (value) => {
                        let cc_customer_service = '';
                        if(value.cc_customer_service){
                            value.cc_customer_service.forEach((value, index) => {
                                if(index == 0){
                                    cc_customer_service = value;
                                }else{
                                    cc_customer_service = cc_customer_service + ',' + value;
                                }
                            });
                        }
                        if(value.cc_customer_service_other){
                             cc_customer_service = cc_customer_service + ',' + value.cc_customer_service_other;
                        }

                        return cc_customer_service;
                    }
                },
                'INFORMATION TECHNOLOGY SOFTWARE SKILLS' : {
                    callback: (value) => {
                        let cc_it_software_skills = '';
                        if(value.cc_it_software_skills){
                            value.cc_it_software_skills.forEach((value, index) => {
                                if(index == 0){
                                    cc_it_software_skills = value;
                                }else{
                                    cc_it_software_skills = cc_it_software_skills + ',' + value;
                                }
                            });
                        }
                        if(value.cc_it_software_skills_other){
                             cc_customer_service = cc_it_software_skills + ',' + value.cc_it_software_skills_other;
                        }

                        return cc_it_software_skills;
                    }
                },
                'LEADERSHIP TRAINING' : {
                    callback: (value) => {
                        let leadership_trainings = '';
                        if(value.leadership_trainings){
                            value.leadership_trainings.forEach((value, index) => {
                                if(index == 0){
                                    leadership_trainings = value;
                                }else{
                                    leadership_trainings = leadership_trainings + ',' + value;
                                }
                            });
                        }
                        return leadership_trainings;
                    }
                },
                'FUNCTIONAL COMPETENCY ANSWER 1' : 'fc_answer_1',
                'FUNCTIONAL COMPETENCY ANSWER 2' : 'fc_answer_2',
                'FUNCTIONAL COMPETENCY ANSWER 3' : 'fc_answer_3',
                'FUNCTIONAL COMPETENCY ANSWER 4' : 'fc_answer_4',
                'FUNCTIONAL COMPETENCY ANSWER 5' : 'fc_answer_5',
                
            }
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
                this.getAlluser();
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            });
        },
        getAlluser() {
            this.users = [];
            axios.get('/all-user-survey?survey_id=' + this.survey.id)
            .then(response => { 
                this.users = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            })     
        },
        setPage(pageNumber) {
            this.currentPage = pageNumber;
        },

        resetStartRow() {
            this.currentPage = 0;
        },

        showPreviousLink() {
            return this.currentPage == 0 ? false : true;
        },

        showNextLink() {
            return this.currentPage == (this.totalPages - 1) ? false : true;
        }  
    },
    computed:{
        filteredUsers(){
            let self = this;
            return Object.values(self.users).filter(user => {
                return user.user_name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(Object.values(this.filteredUsers).length / this.itemsPerPage)
        },
        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredUsers.slice(index, index + this.itemsPerPage);

            if(this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            }

            if(this.currentPage == -1) {
                this.currentPage = 0;
            }

            return queues_array;
        },
    }
}
</script>

<style lang="scss" scoped>

</style>