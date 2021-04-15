<template>
    <div>
        <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
            <!-- Mask -->
            <span class="mask bg-gradient-info opacity-7"></span>
            <!-- Header container -->
            <div class="container-fluid d-flex align-items-center">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <h1 class="display-2 text-white text-uppercase">SURVEY : LEGAL QUESTIONNAIRE (USERS)</h1>
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

                            <div class="row align-items-center">
                                <div class="col-xl-4 mb-2 mt-3 float-right">
                                    <div class="form-group">
                                        <input type="text" name="keyword" class="form-control" placeholder="Search" autocomplete="off" v-model="keywords" id="keyword">
                                    </div> 
                                </div> 
                                <div class="col-xl-4 mb-2 mt-3 float-right">
                                    <div class="form-group">
                                        <select name="select" v-model="is_allowed" class="form-control">
                                            <option value="">Is Allowed</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <span class="text-danger" v-if="errors.role">{{ errors.role[0] }}</span>
                                    </div>
                                </div>

                                <div class="col-12">
                                <download-excel
                                        :data   = "allSurveyLegal"
                                        :fields = "json_fields"
                                        class   = "btn btn-md btn-default mt-3 ml-3 mr-3 float-right"
                                        name    = "SURVEY : LEGAL QUESTIONNAIRE.xls">
                                            Export to excel ({{allSurveyLegal.length}})
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
                                               
                                                <th scope="col">Is allowed</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Position</th>
                                                <th scope="col">Company</th>
                                                <th scope="col">Department</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(user, i) in filteredQueues" :key="i">
                                                 <td>
                                                    <label class="container">
                                                        <input v-if="user.survey_legal_user" type="checkbox" style="width:20px;height:20px;"  :id="user.id" :value="user.survey_legal_user ? user.survey_legal_user.status : 0" v-model="user.survey_legal_user.status"  true-value="1" false-value="0" @click="changeLegalUserStatus(user,$event)">
                                                        <input v-else type="checkbox" style="width:20px;height:20px;"  :id="user.id" :value="user.survey_legal_user ? user.survey_legal_user.status : 0"  true-value="1" false-value="0" @click="changeLegalUserStatus(user,$event)">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </td>
                                                <td>{{user.user.name}}</td>
                                                <td>{{user.user.email}}</td>
                                                <td>{{user.position}}</td>
                                                <td>{{user.companies[0].name}}</td>
                                                <td>{{user.departments[0].name}}</td>
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
                                    <span class="mr-2">Filtered User(s) : {{ filteredUsers.length }} </span><br>
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
                allSurveyLegal: [],
                errors: [],
                users: [],
                user: [],
                currentPage: 0,
                itemsPerPage: 10,
                keywords: "",
                is_allowed: "",
                json_fields: {
                    'Name' : 'name',
                    'Position' : 'position',
                    'Department' : 'department',
                    'Company' : 'company',
                    'q1' : 'q1',
                    'q2' : 'q2',
                    'q3' : 'q3',
                    'date' : 'created_at',
                }
            }
        },
        created () {
            this.getSurveyLegalUser();
            this.getSurveyLegal();
        },
        methods: {
            getSurveyLegal(){
                let v = this;

                axios.get('/get-survey-legal-questionnaire')
                .then(response => { 
                    this.allSurveyLegal = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })

            },
            changeLegalUserStatus: function(user,event) {
                let v = this;
                let check = event.target.checked;
                let user_status;
                let user_status_desc;
                if(check == true){
                    user_status = 1;
                    user_status_desc = 'Yes';
                }else{
                    user_status = 0;
                    user_status_desc = 'No';
                }
                
                var index = this.users.findIndex(item => item.id == user.id);

                axios.post('/change-legal-user-status',{
                    user_id: user.user.id,
                    status: user_status,
                })
                .then(response => {
                    this.users.splice(index,1,response.data);
                })
                .catch(error => { 
                    this.errors = error.response.data.errors;
                })
            },
            getSurveyLegalUser() {
                let v = this;
                axios.get('/get-survey-legal-questionnaire-users')
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

                    if(self.is_allowed){
                        if(user.survey_legal_user){
                            return user.survey_legal_user.status.toLowerCase().includes(this.is_allowed.toLowerCase())
                        }
                    }else{
                        return user.user.name.toLowerCase().includes(this.keywords.toLowerCase()) || user.user.email.toLowerCase().includes(this.keywords.toLowerCase())
                    }
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