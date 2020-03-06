<template>
    <div>
        <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
          <!-- Mask -->
            <span class="mask bg-gradient-success opacity-7"></span>
        </div>


        <div class="container-fluid mt--9">
            <div class="header-body">
                <div class="row">
                    <div class="col-xl-12">
                         <div class="card shadow">
                                <div class="card-header border-0">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="mb-0">ALL VERIFIED EMPLOYEES</h3>
                                            <small class="text-muted">List of verified employees</small>
                                        </div> 
                                    </div>
                        
                                    <div class="row align-items-center mb-3">
                                        <div class="col-xl-12 mb-2 mt-3 float-right">
                                            <div class="col-xl-6 mb-2 mt-3 float-left">
                                                <input type="text" name="employee_ids" class="form-control" placeholder="Search by Name" autocomplete="off" v-model="keywords" id="employee_approval_requests">
                                            </div> 
                                        </div> 
                                        
                                        <div class="col-xl-12 float-right text-right">
                                            <download-excel
                                                :data   = "verified_employees"
                                                :fields = "json_fields"
                                                class   = "btn btn-sm btn-default"
                                                name    = "Employee Request and Verification Report.xls">
                                                    Export to excel
                                            </download-excel>
                                        </div> 
                                    
                                    </div>


                                    <div class="table-responsive">
                                        <!-- employees table -->
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">ID Number</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Company</th>
                                                    <th scope="col">Department</th>
                                                    <th scope="col">Location</th>
                                                    <th scope="col">Update Requests</th>
                                                    <th scope="col">Accepted by HR</th>
                                                    <th scope="col">Verified by Employee</th>
                                                    <th scope="col">Unverified</th>
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
                                                <tr v-for="(verified_employee,index) in filteredQueues" v-bind:key="index">
                                                    <td>{{ verified_employee.id_number }}</td>
                                                    <td>{{ verified_employee.name }}</td>
                                                    <td>{{ verified_employee.company }}</td>
                                                    <td>{{ verified_employee.department }}</td>
                                                    <td>{{ verified_employee.location }}</td>
                                                    <td align="center">{{ verified_employee.employee_approval_requests }}</td>
                                                    <td align="center">{{ verified_employee.accepted_by_hr }}</td> 
                                                    <td align="center">
                                                        <span v-if="verified_employee.verified_by_employee == 'Yes'"> <i class="fas fa-check text-success"></i></span>
                                                        <span v-else> </span>
                                                    </td>

                                                    <td align="center">
                                                        <span v-if="verified_employee.unverified == 'Yes'"> <i class="fas fa-check text-danger"></i></span>
                                                        <span v-else> </span>
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
                                            <span class="mr-2">Filtered employee(s) : {{ filteredQueues.length }} </span><br>
                                            <span class="mr-2">Total employee(s) : {{ verified_employees.length }}</span>
                                        </div>
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
        components: { 'downloadExcel': JsonExcel }, 
        data() {
           return {
               errors: [],
                currentPage: 0,
                itemsPerPage: 25,
                keywords: "",
                verified_employees: [],
                companies : [],
                company : '',
                locations : [],
                location : '',
                departments : [],
                department : '',
                table_loading : false,
                json_fields: {
                    'ID NUMBER': 'id_number',
                    'NAME': 'name',
                    'COMPANY': 'company',
                    'DEPARTMENT': 'department',
                    'LOCATION': 'location',
                    'UPDATE REQUESTS': 'employee_approval_requests',
                    'ACCEPTED BY HR': 'accepted_by_hr',
                    'VERIFIED BY EMPLOYEE': 'verified_by_employee',
                    'UNVERIFIED': 'unverified',
                },
                totalUpdateRequests : 0,
                totalAcceptedbyHR : 0,
                totalVerifiedByEmployee : 0,
                totalUnverified : 0,
           }
       }, 
       created () {
            this.fetchVerifiedEmpoyees();
            this.fetchCompanies();
            this.fetchDepartments();
            this.fetchLocations();
       },
       methods: {
           fetchVerifiedEmpoyees() {
               this.table_loading = true;
               this.verified_employees = [];
                axios.get('/download-data-request-verification')
                .then(response => { 
                    this.verified_employees = response.data;
                    this.table_loading = false;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
           },
           fetchCompanies(){
                axios.get('/companies-all')
                .then(response => { 
                    this.companies = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchLocations(){
                axios.get('/locations-all')
                .then(response => { 
                    this.locations = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchDepartments(){
                axios.get('/departments-all')
                .then(response => { 
                    this.departments = response.data;
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
       computed: {
           filteredverifiedemployees(){
                let self = this;
                return Object.values(self.verified_employees).filter(verified_employee => {
                    return  verified_employee.name.toLowerCase().includes(this.keywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(Object.values(this.verified_employees).length / this.itemsPerPage)
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredverifiedemployees.slice(index, index + this.itemsPerPage);

                if(this.currentPage >= this.totalPages) {
                    this.currentPage = this.totalPages - 1
                }

                if(this.currentPage == -1) {
                    this.currentPage = 0;
                }

                return queues_array;
            },
       },
    }
</script>