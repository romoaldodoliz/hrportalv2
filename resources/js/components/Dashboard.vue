<template>
   <div>
  
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
          <!-- Mask -->
        <span class="mask bg-gradient-success opacity-7"></span>

        <div class="container-fluid">
            <div class="header-body">
               <div class="row">
                <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                    <div class="row">
                        <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total Active</h5>
                        <span class="h2 font-weight-bold mb-0">{{ employees }}</span>
                        </div>
                        <div class="col-auto">
                        <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                            <i class="fas fa-users"></i>
                        </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-success mr-2"></span>
                        <span class="text-nowrap">Last updated: Today</span>
                    </p>
                    </div>
                </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                    <div class="row">
                        <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">New users</h5>
                        <span class="h2 font-weight-bold mb-0">{{ new_employees.length }}</span>
                        </div>
                        <div class="col-auto">
                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-danger mr-2"></span>
                        <span class="text-nowrap">Last updated: Today</span>
                    </p>
                    </div>
                </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                    <div class="row">
                        <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total Inactive</h5>
                        <span class="h2 font-weight-bold mb-0">{{ total_inactives }}</span>
                        </div>
                        <div class="col-auto">
                        <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                            <i class="fas fa-user-minus"></i>
                        </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-warning mr-2"></span>
                        <span class="text-nowrap">Last updated: Today</span>
                    </p>
                    </div>
                </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                    <div class="row">
                        <div class="col">
                        <h5 class="card-title text-uppercase text-muted mb-0">Total Verified Employee</h5>
                        <span class="h2 font-weight-bold mb-0">{{ total_updates }}</span>
                        </div>
                        <div class="col-auto">
                        <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                        <span class="text-success mr-2"></span>
                        <span class="text-nowrap">Last updated: Today</span>
                    </p>
                    </div>
                </div>
                </div>

                
            </div>
            </div>
        </div>

    </div>
       
        
        

        <div class="container-fluid mt--7">
        <div class="row ">
        <div class="col-xl-6 mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Employee Approval Request(s)</h3>
                </div>
                <div class="col text-right">
                  <a href="/employee_approval_requests" class="btn btn-sm btn-primary">See all</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Date</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody>
                    <tr v-for="(employee_request,index) in filteredQueues" v-bind:key="index">
                        <td>{{ employee_request.employee.first_name }} {{ employee_request.employee.last_name }}</td>
                        <td>{{ employee_request.created_at }}</td>
                        <td>{{ employee_request.status }}</td>
                    </tr>
                </tbody>
              </table>
            </div>

            <div class="row mb-3 mt-3 ml-1" v-if="filteredQueues.length ">
                <div class="col-6">
                    <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(requestcurrentPage - 1)"> Previous </button>
                        <span class="text-dark">Page {{ requestcurrentPage + 1 }} of {{ totalPages }}</span>
                    <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(requestcurrentPage + 1)"> Next </button>
                </div>
                <div class="col-6 text-right">
                    <span class="mr-2">Filtered employee request(s) : {{ filteredQueues.length }} </span><br>
                    <span class="mr-2">Total employee request(s) : {{ employee_approval_requests.length }}</span>
                </div>
            </div>

          </div>
        </div>

        <div class="col-xl-6">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">New Employees</h3>
                </div>
                <div class="col text-right">
                  <!-- <a href="#!" class="btn btn-sm btn-primary">See all</a> -->
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">ID Number</th>
                    <th scope="col">Name</th>
                    <th scope="col">Company</th>
                    <th scope="col">Department</th>
                  </tr>
                </thead>
                <tbody>
                    <tr v-for="(new_employee,index) in newfilteredQueues" v-bind:key="index">
                        <td>{{ new_employee.employee_number }}</td>
                        <td>{{ new_employee.first_name }} {{ new_employee.last_name }}</td>
                        <td>{{ new_employee.companies[0].name }}</td>
                        <td>{{ new_employee.departments[0].name }}</td>
                    </tr>
                </tbody>
              </table>
            </div>

            <div class="row mb-3 mt-3 ml-1" v-if="newfilteredQueues.length ">
                <div class="col-6">
                    <button :disabled="!newshowPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="newsetPage(newcurrentPage - 1)"> Previous </button>
                        <span class="text-dark">Page {{ newcurrentPage + 1 }} of {{ newtotalPages }}</span>
                    <button :disabled="!newshowNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="newsetPage(newcurrentPage + 1)"> Next </button>
                </div>
                <div class="col-6 text-right">
                    <span class="mr-2">Filtered New Employee(s) : {{ newfilteredQueues.length }} </span><br>
                    <span class="mr-2">Total New Employee(s) : {{ new_employees.length }}</span>
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
        data(){
            return {
                employees: 0,
                new_employees: [],
                total_inactives: 0,
                total_updates: 0,
                employee_approval_requests : [],
                errors: [],
                requestcurrentPage: 0,
                requestitemsPerPage: 10,
                requestkeywords: "",
                newcurrentPage: 0,
                newitemsPerPage: 10,
                newkeywords: "",
            }
        },
        created(){
            this.fetchEmployees();
            this.fetchTotalInactives();
            this.fetchNewEmployees();
            this.fetchUpdateEmployees();
            this.fetchEmployeeApprovalRequests();
        },
        methods:{
           fetchEmployees(){
                axios.get('/employees-index-count')
                .then(response => { 
                    this.employees = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
           fetchTotalInactives(){
                axios.get('/employees-inactive-count')
                .then(response => { 
                    this.total_inactives = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
           fetchNewEmployees(){
                axios.get('/employees-new-count')
                .then(response => { 
                    this.new_employees = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
           fetchUpdateEmployees(){
                axios.get('/employees-update-count')
                .then(response => { 
                    this.total_updates = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchEmployeeApprovalRequests(){
                axios.get('/employee-approval-requests-pending-all')
                .then(response => { 
                    this.employee_approval_requests = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },

            setPage(pageNumber) {
                this.requestcurrentPage = pageNumber;
            },

            resetStartRow() {
                this.requestcurrentPage = 0;
            },

            showPreviousLink() {
                return this.requestcurrentPage == 0 ? false : true;
            },

            showNextLink() {
                return this.requestcurrentPage == (this.totalPages - 1) ? false : true;
            },

            newsetPage(pageNumber) {
                this.newcurrentPage = pageNumber;
            },

            newresetStartRow() {
                this.newcurrentPage = 0;
            },

            newshowPreviousLink() {
                return this.newcurrentPage == 0 ? false : true;
            },

            newshowNextLink() {
                return this.newcurrentPage == (this.newtotalPages - 1) ? false : true;
            } 

        },
        computed:{
            filteredemployeerequests(){
                let self = this;
                return Object.values(self.employee_approval_requests).filter(employee_request => {
                    let full_name = employee_request.employee.first_name + " " + employee_request.employee.last_name;
                    return employee_request.status.toLowerCase().includes(this.requestkeywords.toLowerCase()) || employee_request.employee.first_name.toLowerCase().includes(this.requestkeywords.toLowerCase()) || employee_request.employee.last_name.toLowerCase().includes(this.requestkeywords.toLowerCase()) || full_name.toLowerCase().includes(this.requestkeywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(Object.values(this.employee_approval_requests).length / this.requestitemsPerPage)
            },
            filteredQueues() {
                var index = this.requestcurrentPage * this.requestitemsPerPage;
                var queues_array = this.filteredemployeerequests.slice(index, index + this.requestitemsPerPage);

                if(this.requestcurrentPage >= this.totalPages) {
                    this.requestcurrentPage = this.totalPages - 1
                }

                if(this.requestcurrentPage == -1) {
                    this.requestcurrentPage = 0;
                }

                return queues_array;
            },
            filterednewemployees(){
                let self = this;
                return Object.values(self.new_employees).filter(new_employee => {
                    let full_name = new_employee.first_name + " " + new_employee.last_name;
                    return full_name.toLowerCase().includes(this.newkeywords.toLowerCase()) 
                });
            },
            newtotalPages() {
                return Math.ceil(Object.values(this.new_employees).length / this.newitemsPerPage)
            },
            newfilteredQueues() {
                var index = this.newcurrentPage * this.newitemsPerPage;
                var queues_array = this.filterednewemployees.slice(index, index + this.newitemsPerPage);

                if(this.newcurrentPage >= this.newtotalPages) {
                    this.newcurrentPage = this.newtotalPages - 1
                }

                if(this.newcurrentPage == -1) {
                    this.newcurrentPage = 0;
                }

                return queues_array;
            },
        }
    }
</script>