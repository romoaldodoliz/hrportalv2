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
                                        <h3 class="mb-0">Employee Transfer Reports</h3>
                                        <small class="text-muted">List of all employee transfer</small>
                                    </div> 
                                </div>

                                <div class="row align-items-center">
                                   
                                    <div class="col-xl-3 mb-2 mt-3 float-left">
                                        <input type="text" class="form-control" placeholder="Search by Employee" autocomplete="off" v-model="keywords" id="name">
                                    </div>
                                    <div class="col-xl-12">
                                        <download-excel
                                            v-if="employee_transfers.length > 0"
                                            :data   = "employee_transfers"
                                            :fields = "json_fields"
                                            class   = "btn btn-sm btn-default float-right mb-2"
                                            name    = "Employee Transfer.xls">
                                                Export to excel ({{employee_transfers.length}})
                                        </download-excel>
                                    </div>
                                </div>

                                 <div class="table-responsive" style="min-height: 300px!important;">
                                    <!-- employees table -->
                                    <table class="table align-items-center table-flush mb-5">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Employee Name</th>
                                                <th scope="col">From ID Number</th>
                                                <th scope="col">From Date Hired</th>
                                                <th scope="col">From Position</th>
                                                <th scope="col">From Department</th>
                                                <th scope="col">From Company</th>
                                                <th scope="col">From Location</th>
                                                <th scope="col">To ID Number</th>
                                                <th scope="col">To Date Hired</th>
                                                <th scope="col">To Position</th>
                                                <th scope="col">To Department</th>
                                                <th scope="col">To Company</th>
                                                <th scope="col">To Location</th>
                                                <th scope="col">Created Date</th>
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
                                            <tr v-for="(transfer, u) in filteredQueues" v-bind:key="u">
                                                <td>
                                                    {{transfer.employee.first_name + ' ' + transfer.employee.last_name}} <br>
                                                </td>
                                                <td>{{transfer.previous_id_number}}</td>
                                                <td>{{transfer.previous_date_hired}}</td>
                                                <td>{{transfer.previous_position}}</td>
                                                <td>{{transfer.previous_department ? transfer.previous_department.name : ""}}</td>
                                                <td>{{transfer.previous_company ? transfer.previous_company.name : ""}}</td>
                                                <td>{{transfer.previous_location ? transfer.previous_location.name : ""}}</td>

                                                <td>{{transfer.new_id_number}}</td>
                                                <td>{{transfer.new_position}}</td>
                                                <td>{{transfer.new_date_hired}}</td>
                                                <td>{{transfer.new_department ? transfer.new_department.name : ""}}</td>
                                                <td>{{transfer.new_company ? transfer.new_company.name : ""}}</td>
                                                <td>{{transfer.new_location ? transfer.new_location.name : ""}}</td>
                                                <td>{{transfer.created_at }}</td>
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
                                        <span class="mr-2">Filtered Employee Transfer(s) : {{ filteredQueues.length }} </span><br>
                                        <span class="mr-2">Total Employee Transfer(s) : {{ employee_transfers.length }}</span>
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
                employee_transfers: [],
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
                            if(value){
                                return value.employee.first_name + ' ' + value.employee.last_name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'FROM ID NUMBER': 'previous_id_number',
                    'FROM DATE HIRED': 'previous_date_hired',
                    'FROM POSITION': 'previous_position',
                    'FROM DEPARTMENT': {
                        callback: (value) => {
                            if(value){
                                return value.previous_department.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'FROM COMPANY':  {
                        callback: (value) => {
                            if(value){
                                return value.previous_company.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'FROM LOCATION': {
                        callback: (value) => {
                            if(value){
                                return value.previous_location.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'TO ID NUMBER': 'new_id_number',
                    'TO DATE HIRED': 'new_date_hired',
                    'TO POSITION': 'new_position',
                    'TO DEPARTMENT': {
                        callback: (value) => {
                            if(value){
                                return value.new_department.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'TO COMPANY':  {
                        callback: (value) => {
                            if(value){
                                return value.new_company.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'TO LOCATION': {
                        callback: (value) => {
                            if(value){
                                return value.new_location.name;
                            }else{
                                return '';
                            }
                        }
                    },
                    'CREATED AT' : 'created_at'
                }
            }
        },
        created () {
            this.getEmployeeTransfers();
        },
        methods: {
            getEmployeeTransfers(){
                let v = this;
                v.table_loading = true;
                axios.get(`/employee-transfer-reports-data`)
                .then(response => {
                    v.employee_transfers = response.data.employee_transfers;
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
                return Object.values(self.employee_transfers).filter(transfer => {
                    let full_name = transfer.employee.first_name + " " + transfer.employee.last_name;
                    return transfer.employee.first_name.toLowerCase().includes(this.keywords.toLowerCase()) || transfer.employee.last_name.toLowerCase().includes(this.keywords.toLowerCase()) || full_name.toLowerCase().includes(this.keywords.toLowerCase())
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