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
                                        <h3 class="mb-0">Employee Dependent Reports</h3>
                                        <small class="text-muted">List of all employee dependents</small>
                                    </div> 
                                </div>

                                <div class="row align-items-center">
                                   
                                    <div class="col-xl-3 mb-2 mt-3 float-left">
                                        <input type="text" class="form-control" placeholder="Search by Employee" autocomplete="off" v-model="keywords" id="name">
                                    </div>

                                    <div class="col-xl-12">
                                        <download-excel
                                            v-if="employee_dependents.length > 0"
                                            :data   = "employee_dependents"
                                            :fields = "json_fields"
                                            class   = "btn btn-sm btn-default float-right mb-2"
                                            name    = "Employee Dependents.xls">
                                                Export to excel ({{employee_dependents.length}})
                                        </download-excel>
                                    </div>
                                </div>

                                <div class="table-responsive" style="min-height: 300px!important;">
                                    <!-- employees table -->
                                    <table class="table align-items-center table-flush mb-5">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Employee Name</th>
                                                <th scope="col">Dependent</th>
                                                <th scope="col">Relation</th>
                                                <th scope="col">Birthdate</th>
                                                <th scope="col">Gender</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">HMO Enrollment</th>
                                                <th scope="col">Civil Status</th>
                                                <th scope="col">MBL</th>
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
                                             <tr v-for="(employee, u) in filteredQueues" v-bind:key="u">
                                                <td>
                                                    {{employee.first_name + ' ' + employee.last_name}} <br>
                                                    <small>{{employee.position}}</small> <br>
                                                    <small>{{employee.departments}}</small> <br>
                                                    <small>{{employee.locations}}</small> <br>
                                                    <small>{{employee.companies}}</small>
                                                </td>
                                                <td>{{employee.dependent_name}}</td>
                                                <td>{{employee.dependent_relation}}</td>
                                                <td>{{employee.dependent_bdate}}</td>
                                                <td>{{employee.dependent_gender}}</td>
                                                <td>{{employee.dependent_status}}</td>
                                                <td>{{employee.hmo_enrollment}}</td>
                                                <td>{{employee.civil_status}}</td>
                                                <td>{{employee.mbl}}</td>
                                                <td>{{employee.dependent_created_date}}</td>
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
                                        <span class="mr-2">Total employee(s) : {{ total_count }}</span>
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
            employee_dependents: [],
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
                'Employee Name': {
                    callback: (value) => {
                        if(value){
                            return value.first_name + ' ' + value.last_name;
                        }else{
                            return '';
                        }
                    }
                },
                'Position': 'position',
                'Department': 'departments',
                'Company': 'companies',
                'Dependent Name': 'dependent_name',
                'Relation': 'dependent_relation',
                'Birthdate': 'dependent_bdate',
                'Gender': 'dependent_gender',
                'Status': 'dependent_status',
                'Civil Status': 'civil_status',
                'HMO Enrollment': 'hmo_enrollment',
                'MBL': 'mbl',
                'Created Date': 'dependent_created_date',
            }
        }
    },
    created () {
        this.getEmployeeDependents();
        this.fetchCompanies();
        this.fetchDepartments();
        this.fetchLocations();
    },
    methods: {
        getEmployeeDependents(){
            let v = this;
            v.table_loading = true;
            axios.get(`/employee-dependents-reports-data`)
            .then(response => {
                v.employee_dependents = response.data.employee_dependents;
                v.total_count = response.data.total_count;
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
            return Object.values(self.employee_dependents).filter(employee => {
                let full_name = employee.first_name + " " + employee.last_name;
                return employee.first_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee.last_name.toLowerCase().includes(this.keywords.toLowerCase()) || full_name.toLowerCase().includes(this.keywords.toLowerCase())
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