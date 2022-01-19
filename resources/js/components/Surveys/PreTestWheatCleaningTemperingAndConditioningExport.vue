<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-primary opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white text-uppercase"> Pre - Test Wheat Cleaning, Tempering and Conditioning</h1>
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
                            <div class="col-12">
                               <download-excel
                                    :data   = "allPreTestWheatCleaningTemperingAndConditioning"
                                    :fields = "json_fields"
                                    class   = "btn btn-md btn-default mt-3 ml-3 mr-3"
                                    name    = "Pre - Test Wheat Cleaning, Tempering and Conditioning.xls">
                                        Export to excel ({{allPreTestWheatCleaningTemperingAndConditioning.length}})
                                </download-excel>
                           </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-xl-4 mb-2 mt-3 float-right">
                                <div class="form-group">
                                    <input type="text" name="keyword" class="form-control" placeholder="Search" autocomplete="off" v-model="keywords" id="keyword">
                                </div> 
                            </div> 
                            <div class="col-xl-4 mb-2 mt-3 float-right">
                                <div class="form-group">
                                    <select name="select" v-model="is_allowed" class="form-control">
                                        <option value="">Filter Allowed Employees</option>
                                        <option value="1">Allowed</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <!-- Users table -->
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        
                                        <th scope="col">Is allowed</th>
                                        <th scope="col">ID</th>
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
                                                <input v-if="user.pre_test_wheat_user" type="checkbox" style="width:20px;height:20px;"  :id="user.id" :value="user.pre_test_wheat_user ? user.pre_test_wheat_user.status : 0" v-model="user.pre_test_wheat_user.status"  true-value="1" false-value="0" @click="changeUserStatus(user,$event)">
                                                <input v-else type="checkbox" style="width:20px;height:20px;"  :id="user.id" :value="user.pre_test_wheat_user ? user.pre_test_wheat_user.status : 0"  true-value="1" false-value="0" @click="changeUserStatus(user,$event)">
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td>{{user.user.id}}</td>
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
                allPreTestWheatCleaningTemperingAndConditioning: [],
                json_fields: {
                    'Employee Number' : 'employee_number',
                    'Name' : 'name',
                    'Cluster' : 'cluster',
                    'Company' : 'company',
                    'Business Unit' : 'business_unit',
                    'Department' : 'department',
                    'Position' : 'position',
                    'Region' : 'region',
                    'Location' : 'location',
                    'Hired Date' : 'hired_date',
                    'Date Answered' : 'date_answered',
                    'Time Answered' : 'time_answered',
                    'Q1' : 'q1',
                    'Q2' : 'q2',
                    'Q3' : 'q3',
                    'Q4' : 'q4',
                    'Q5' : 'q5',
                    'Q6' : 'q6',
                    'Q7' : 'q7',
                    'Q8' : 'q8',
                    'Q9' : 'q9',
                    'Q10' : 'q10',
                    'Q11' : 'q11',
                    'Q12' : 'q12',
                    'Q13' : 'q13',
                    'Q14' : 'q14',
                    'Q15' : 'q15',
                    'Q16' : 'q16',
                    'Q17' : 'q17',
                    'Q18' : 'q18',
                    'Q19' : 'q19',
                    'Q20' : 'q20',
                    'Q21' : 'q21',
                    'Q22' : 'q22',
                    'Q23' : 'q23',
                    'Q24_a' : 'q24_a',
                    'Q24_b' : 'q24_b',
                    'Q25' : 'q25',
                    'Q26_a' : 'q26_a',
                    'Q26_b' : 'q26_b',
                    'Q26_c' : 'q26_c',
                    'Q26_d' : 'q26_d',
                    'Q26_e' : 'q26_e',
                    'Q26_f' : 'q26_f',
                    'Q26_g' : 'q26_g',
                    'Q26_h' : 'q26_h',
                    'Q26_i' : 'q26_i',
                    'Q26_j' : 'q26_j',
                    'Q27' : 'q27',
                    'Q28' : 'q28',
                    'Q29' : 'q29',
                    'Q30' : 'q30',
                    'Q31' : 'q31',
                },
                users: [],
                user: [],
                currentPage: 0,
                itemsPerPage: 10,
                keywords: "",
                is_allowed: "",
            }
        },
        created () {
            this.getPreTestWheatCleaningTemperingAndConditioning();
            this.getPreTestWheatCleaningTemperingAndConditioningUser();
        },
        methods: {
            changeUserStatus: function(user,event) {
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

                axios.post('/change-pre-test-wheat-cleaning-tempering-and-conditioning-user-status',{
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

            getPreTestWheatCleaningTemperingAndConditioning() {
                axios.get('/all-pre-test-wheat-cleaning-tempering-and-conditioning')
                .then(response => { 
                    this.allPreTestWheatCleaningTemperingAndConditioning = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                });
            },
            getPreTestWheatCleaningTemperingAndConditioningUser() {
                axios.get('/get-pre-test-wheat-cleaning-tempering-and-conditioning-users')
                .then(response => { 
                    this.users = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                });
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
                    if(user.user){
                        if(self.is_allowed){
                            if(user.pre_test_wheat_user){
                                return user.pre_test_wheat_user.status.toLowerCase().includes(this.is_allowed.toLowerCase())
                            }
                        }else{
                            return user.user.name.toLowerCase().includes(this.keywords.toLowerCase()) || user.user.email.toLowerCase().includes(this.keywords.toLowerCase())
                        }
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