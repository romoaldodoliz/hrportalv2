<template>
<div>
    <div class="header bg-gradient-success pb-8 pt-5 pt-md-8 container-list"></div>
        <div class="container-fluid mt--9">
            <div class="header-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card shadow">
                            <div class="card-header border-0">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="mb-0">Employee Npa Requests</h3>
                                        <small class="text-muted">List of all employee npa requests</small>
                                    </div> 
                                </div>

                                <div class="row align-items-center">
                                   
                                    <div class="col-xl-3 mb-2 mt-3 float-left">
                                        <input type="text" class="form-control" placeholder="Search by Employee" autocomplete="off" v-model="keywords" id="name">
                                    </div>
                                    <div class="col-xl-12">
                                        <download-excel
                                            v-if="employee_npa_requests.length > 0"
                                            :data   = "employee_npa_requests"
                                            :fields = "json_fields"
                                            class   = "btn btn-sm btn-default float-right mb-2"
                                            name    = "Employee NPA Requests.xls">
                                                Export to excel ({{employee_npa_requests.length}})
                                        </download-excel>
                                    </div>
                                </div>

                                 <div class="table-responsive" style="min-height: 300px!important;">
                                    <!-- employees table -->
                                    <table class="table align-items-center table-flush mb-5">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Employee Name</th>
                                                <th scope="col">Ctrl No.</th>
                                                <th scope="col">Subject</th>
                                                <th scope="col">From Type of Movement</th>
                                                <th scope="col">From Company</th>
                                                <th scope="col">From Location</th>
                                                <th scope="col">From Position Title</th>
                                                <th scope="col">From Department</th>
                                                <th scope="col">From Immediate Superior</th>
                                                <th scope="col">Effectivity Date</th>
                                                <th scope="col">To Type of Movement</th>
                                                <th scope="col">To Company</th>
                                                <th scope="col">To Location</th>
                                                <th scope="col">To Position Title</th>
                                                <th scope="col">To Department</th>
                                                <th scope="col">To Immediate Superior</th>



                                                <th scope="col">Recommended Status</th>
                                                <th scope="col">Approved By Status</th>
                                                <th scope="col">BU Head Status</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Date</th>
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
                                            <tr v-for="(npa, u) in filteredQueues" v-bind:key="u">
                                                <td>
                                                    {{npa.employee.first_name + ' ' + npa.employee.last_name}} <br>
                                                </td>
                                                <td>{{npa.ctrl_no}}</td>
                                                <td>{{npa.subject}}</td>
                                                <!-- <td>{{npa.from_type_of_movement}}</td> -->
                                                <td>{{npa.from_company ? npa.from_company.name : ""}}</td>
                                                <td>{{npa.from_location ? npa.from_location.name : ""}}</td>
                                                <td>{{npa.from_position_title}}</td>
                                                <td>{{npa.from_department ? npa.from_department.name : ""}}</td>
                                                <td>{{npa.from_immediate_manager ? npa.from_immediate_manager.first_name + ' ' + npa.from_immediate_manager.last_name : ""}}</td>
                                                <td>{{ npa.effectivity_date}}</td>
                                                <!-- <td>{{npa.to_type_of_movement}}</td> -->
                                                <td>{{npa.to_company ? npa.to_company.name : ""}}</td>
                                                <td>{{npa.to_location ? npa.to_location.name : ""}}</td>
                                                <td>{{npa.to_position_title}}</td>
                                                <td>{{npa.to_department ? npa.to_department.name : ""}}</td>
                                                <td>{{npa.to_immediate_manager ? npa.to_immediate_manager.first_name + ' ' + npa.to_immediate_manager.last_name : ""}}</td>
                                                
                                                <td>{{npa.recommended_by_status }}</td>
                                                <td>{{npa.approved_by_status }}</td>
                                                <td>{{npa.bu_head_status }}</td>
                                                <td>{{npa.status }}</td>

                                                <td>{{npa.created_at }}</td>
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
                                        <span class="mr-2">Filtered Employee NPA Request(s) : {{ filteredQueues.length }} </span><br>
                                        <span class="mr-2">Total Employee NPA Request(s) : {{ employee_npa_requests.length }}</span>
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
                total_count: [],
                employee_npa_requests: [],
                companies: [],
                departments: [],
                locations: [],
                errors: [],
                currentPage: 0,
                itemsPerPage: 10,
                keywords: "",
                company: "",
                department: "",
                location: "",
                table_loading : false,
                json_fields: {
                    'EMPLOYEE NAME': {
                        callback: (value) => {
                            if(value.employee){
                                return value.employee.first_name + ' ' + value.employee.last_name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'CTRL NO.' : 'ctrl_no',
                    'SUBJECT' : 'subject',
                    'FROM TYPE OF MOVEMENT': 'from_type_of_movement',
                    'FROM COMPANY': {
                        callback: (value) => {
                            if(value.from_company){
                                return value.from_company.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'FROM LOCATION': {
                        callback: (value) => {
                            if(value.from_location){
                                return value.from_location.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'FROM POSITION TITLE': {
                        callback: (value) => {
                            if(value){
                                return value.from_position_title;
                            }else{
                                return '';
                            }
                        }
                    },
                    'FROM DEPARTMENT': {
                        callback: (value) => {
                            if(value.from_department){
                                return value.from_department.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'FROM IMMEDIATE SUPERIOR': {
                        callback: (value) => {
                            if(value.from_immediate_manager){
                                return value.from_immediate_manager.first_name + ' ' + value.from_immediate_manager.last_name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'EFFECTIVITY DATE': 'effectivity_date',
                    'TO COMPANY': {
                        callback: (value) => {
                            if(value.to_company){
                                return value.to_company.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'TO LOCATION': {
                        callback: (value) => {
                            if(value.to_location){
                                return value.to_location.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'TO POSITION TITLE': {
                        callback: (value) => {
                            if(value){
                                return value.to_position_title;
                            }else{
                                return '';
                            }
                        }
                    },
                    'TO DEPARTMENT': {
                        callback: (value) => {
                            if(value.to_department){
                                return value.to_department.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'TO IMMEDIATE SUPERIOR': {
                        callback: (value) => {
                            if(value.to_immediate_manager){
                                return value.to_immediate_manager.first_name + ' ' + value.to_immediate_manager.last_name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'RECOMMENDED STATUS' : 'recommended_by_status',
                    'APPROVED BY STATUS' : 'approved_by_status',
                    'BU HEAD STATUS' : 'bu_head_status',
                    'STATUS' : 'status',
                    'DATE' : 'created_at'
                }
            }
        },
        created () {
            this.getEmployeeNpaRequests();
        },
        methods: {
            getEmployeeNpaRequests(){
                let v = this;
                v.table_loading = true;
                axios.get(`/employee-npa-reports-data`)
                .then(response => {
                    v.employee_npa_requests = response.data.employee_npa_requests;
                    v.table_loading = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
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
            filteredemployees(){
                let self = this;
                return Object.values(self.employee_npa_requests).filter(npa => {
                    if(npa.employee){
                        let full_name = npa.employee.first_name + " " + npa.employee.last_name;
                        return npa.employee.first_name.toLowerCase().includes(this.keywords.toLowerCase()) || npa.employee.last_name.toLowerCase().includes(this.keywords.toLowerCase()) || full_name.toLowerCase().includes(this.keywords.toLowerCase())
                    }
                });
            },
            totalPages() {
                return Math.ceil(Object.values(this.filteredemployees).length / this.itemsPerPage)
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredemployees.slice(index, index + this.itemsPerPage);

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