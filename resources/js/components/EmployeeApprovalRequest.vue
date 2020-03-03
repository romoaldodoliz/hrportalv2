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
                                            <h3 class="mb-0">ALL EMPLOYEE REQUESTS</h3>
                                            <small class="text-muted">List of all employee requests</small>
                                        </div> 
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-xl-4 mb-2 mt-3 float-right">
                                            <input type="text" name="employee_approval_requests" class="form-control" placeholder="Search" autocomplete="off" v-model="keywords" id="employee_approval_requests">
                                        </div> 
                                    </div>


                                    <div class="table-responsive">
                                        <!-- employees table -->
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Company</th>
                                                    <th scope="col">Department</th>
                                                    <th scope="col">Location</th>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(employee_request,index) in filteredQueues" v-bind:key="index">
                                                    <td>{{ employee_request.employee.first_name }} {{ employee_request.employee.last_name }}</td>
                                                    <td>{{ employee_request.employee.companies ? employee_request.employee.companies[0].name : ""}}</td>
                                                    <td>{{ employee_request.employee.departments ? employee_request.employee.departments[0].name : ""}}</td>
                                                    <td>{{ employee_request.employee.locations ? employee_request.employee.locations[0].name  : "" }}</td>
                                                    <td>{{ employee_request.created_at }}</td>
                                                    <td> <button type="button" class="btn btn-sm" v-bind:class="statusClassObject(employee_request.status)" data-toggle="modal" data-target="#viewEmployeeRequestModal" style="cursor: pointer" @click="copyObjectEmployeeRequest(employee_request)"> {{ employee_request.status }} </button></td>
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
                                            <span class="mr-2">Total employee(s) : {{ employee_approval_requests.length }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            

            <!-- Employee Request View -->
            <div class="modal fade" id="viewEmployeeRequestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
                <div class="modal-dialog modal-dialog-centered modal-md modal-employee-request" role="document">
                    <div class="modal-content">
                        <div>
                            <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div> 
                        <div class="modal-header">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <h3 class="mb-0">View Employee Request - {{ employee_request.employee ? employee_request.employee.first_name + ' ' + employee_request.employee.last_name : ''}}</h3>
                                    <small class="text-muted">Date Modified: {{ employee_request.created_at }}</small>
                                </div> 
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                
                                        <th width="20%;">*Field</th>
                                        <th><span class="label label-warning">Old</span></th>
                                        <th><span class="label label-success">New</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td align="left"> NICK NAME</td>
                                        <td align="left"> {{ employee_request_original.nick_name }}</td>
                                        <td align="left"> <i v-if="employee_request_original.nick_name != employee_request_approval.nick_name" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.nick_name }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> LAST NAME</td>
                                        <td align="left"> {{ employee_request_original.last_name }}</td>
                                        <td align="left"> <i v-if="employee_request_original.last_name != employee_request_approval.last_name" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> MIDDLE NAME</td>
                                        <td align="left"> {{ employee_request_original.middle_name }}</td>
                                        <td align="left"> <i v-if="employee_request_original.middle_name != employee_request_approval.middle_name" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.middle_name }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> MIDDLE INITIAL</td>
                                        <td align="left"> {{ employee_request_original.middle_initial }}</td>
                                        <td align="left"> <i v-if="employee_request_original.middle_initial != employee_request_approval.middle_initial" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.middle_initial }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> MARITAL STATUS</td>
                                        <td align="left"> {{ employee_request_original.marital_status }}</td>
                                        <td align="left"> <i v-if="employee_request_original.marital_status != employee_request_approval.marital_status" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.marital_status }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> MARITAL STATUS ATTACHMENT</td>
                                        <td align="left"> 
                                            <a v-if="employee_request_original.marital_status_attachment" :href="'storage/marital_attachments/temps/'+employee_request_original.marital_status_attachment" target="_blank">{{ employee_request_original.marital_status_attachment }}</a>
                                        </td>
                                        <td align="left"> 
                                            <i v-if="employee_request_original.marital_status_attachment != employee_request_approval.marital_status_attachment" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i>

                                            <a v-if="employee_request_approval.marital_status_attachment" :href="'storage/marital_attachments/temps/'+employee_request_approval.marital_status_attachment" target="_blank">{{ employee_request_approval.marital_status_attachment }}</a>
                                            </td>
                                    </tr>
                                    <tr>
                                        <td align="left"> GENDER</td>
                                        <td align="left"> {{ employee_request_original.gender }}</td>
                                        <td align="left"> <i v-if="employee_request_original.gender != employee_request_approval.gender" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.gender }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> CURRENT ADDRESS</td>
                                        <td align="left"  width="100px;"> {{ employee_request_original.current_address }}</td>
                                        <td align="left" width="40%"> <i v-if="employee_request_original.current_address != employee_request_approval.current_address" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.current_address }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> PERMANENT ADDRESS</td>
                                        <td align="left"> {{ employee_request_original.permanent_address }}</td>
                                        <td align="left"> <i v-if="employee_request_original.permanent_address != employee_request_approval.permanent_address" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.permanent_address }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> LANDLINE</td>
                                        <td align="left"> {{ employee_request_original.phone_number }}</td>
                                        <td align="left"> <i v-if="employee_request_original.phone_number != employee_request_approval.phone_number" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.phone_number }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> MOBILE NUMBER</td>
                                        <td align="left"> {{ employee_request_original.mobile_number }}</td>
                                        <td align="left"> <i v-if="employee_request_original.mobile_number != employee_request_approval.mobile_number" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.mobile_number }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> CONTACT PERSON</td>
                                        <td align="left"> {{ employee_request_original.contact_person }}</td>
                                        <td align="left"> <i v-if="employee_request_original.contact_person != employee_request_approval.contact_person" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.contact_person }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> CONTACT RELATION</td>
                                        <td align="left"> {{ employee_request_original.contact_relation }}</td>
                                        <td align="left"> <i v-if="employee_request_original.contact_relation != employee_request_approval.contact_relation" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.contact_relation }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> CONTACT NUMBER</td>
                                        <td align="left"> {{ employee_request_original.contact_number }}</td>
                                        <td align="left"> <i v-if="employee_request_original.contact_number != employee_request_approval.contact_number" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.contact_number }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> SSS</td>
                                        <td align="left"> {{ employee_request_original.sss_number }}</td>
                                        <td align="left"> <i v-if="employee_request_original.sss_number != employee_request_approval.sss_number" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.sss_number }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> HDMF</td>
                                        <td align="left"> {{ employee_request_original.hdmf }}</td>
                                        <td align="left"> <i v-if="employee_request_original.hdmf != employee_request_approval.hdmf" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.hdmf }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> Philhealth</td>
                                        <td align="left"> {{ employee_request_original.phil_number }}</td>
                                        <td align="left"> <i v-if="employee_request_original.phil_number != employee_request_approval.phil_number" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.phil_number }}</td>
                                    </tr>
                                    <tr>
                                        <td align="left"> TIN</td>
                                        <td align="left"> {{ employee_request_original.tax_number }}</td>
                                        <td align="left"> <i v-if="employee_request_original.tax_number != employee_request_approval.tax_number" class="fa fa-exclamation-circle" style="color:#F3BB45" title="Changed"></i> {{ employee_request_approval.tax_number }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>

                            <div class="table-responsive mt-3">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="5"><h4 class="text-success">NEW/MODIFIED - HMO DEPENDENTS</h4> </th>
                                    </tr>
                                    <tr>
                                        <th width="20%;">#</th>
                                        <th width="20%;">Name</th>
                                        <th width="20%;">Gender</th>
                                        <th width="20%;">Date of Birth</th>
                                        <th width="20%;">Relationship</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(request_approval_dependent, index) in employee_request_approval.dependents" v-bind:key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ request_approval_dependent.dependent_name }}</td>
                                        <td>{{ request_approval_dependent.dependent_gender }}</td>
                                        <td>{{ request_approval_dependent.bdate }}</td>
                                        <td>{{ request_approval_dependent.relation }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>

                            <div class="table-responsive mt-3">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="5"><h4 class="text-danger">DELETED - HMO DEPENDENTS</h4> </th>
                                        </tr>
                                        <tr>
                                            <th width="20%;">#</th>
                                            <th width="20%;">Name</th>
                                            <th width="20%;">Gender</th>
                                            <th width="20%;">Date of Birth</th>
                                            <th width="20%;">Relationship</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(request_approval_deleted_dependent, index) in employee_request_approval.deleted_dependents" v-bind:key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ request_approval_deleted_dependent.dependent_name }}</td>
                                            <td>{{ request_approval_deleted_dependent.dependent_gender }}</td>
                                            <td>{{ request_approval_deleted_dependent.bdate }}</td>
                                            <td>{{ request_approval_deleted_dependent.relation }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                             <div class="table-responsive mt-3" v-if="employee_request_approval.dependent_attachments">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="5"><h4 class="text-success">NEW - HMO DEPENDENTS ATTACHMENT</h4> </th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>FILE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(request_approval_dependent_attachment, index) in employee_request_approval.dependent_attachments" v-bind:key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ request_approval_dependent_attachment }}</td>
                                        <td class="text-center"> <a target="_blank" :href="'storage/dependents_attachments/temps/'+request_approval_dependent_attachment"><span class="btn btn-info btn-sm mt-2"> View </span></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive mt-3" v-if="employee_request_approval.deleted_dependent_attachments">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="5"><h4 class="text-danger">DELETED - HMO DEPENDENTS ATTACHMENT</h4> </th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>FILE</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(request_approval_deleted_dependent_attachment, index) in employee_request_approval.deleted_dependent_attachments" v-bind:key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ request_approval_deleted_dependent_attachment.file }}</td>
                                        <td class="text-center"> <a target="_blank" :href="'storage/dependents_attachments/'+request_approval_deleted_dependent_attachment.file"><span class="btn btn-info btn-sm mt-2"> View </span></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                            

                            <div class="row mt-5" v-if="employee_request_approval.remarks">
                                <div class="col-md-12">
                                
                                    <div data-notify="container" class="alert alert-primary alert-with-icon"><i class="fas fa-sticky-note"></i> <strong>Notes/Remarks:</strong>
                                    <br>
                                    <span data-notify="message">
                                        &nbsp;&nbsp;&nbsp;&nbsp;{{employee_request_approval.remarks}}
                                    </span>
                                    </div>
                                </div>
                            </div>
                        
                        </div>    
                        <div class="modal-footer">
                            <div class="col-md-12 text-center">
                                <button v-if="employee_request.status=='Pending' || employee_request.status=='Disapproved'" type="button" class="btn btn-md btn-outline-success" style="cursor: pointer" @click="employeeRequestApprove"> Approve</button>
                                <button v-if="employee_request.status=='Pending'" type="button" class="btn btn-md btn-outline-danger" style="cursor: pointer" @click="employeeRequestDisapprove"> Disapprove</button>
                            </div>    
                        </div>    
                    </div>    
                </div>    
            </div>


    </div>

        

    
</template>

<script>

import Swal from 'sweetalert2'

export default {
    data(){
         return {
            errors: [],
            currentPage: 0,
            itemsPerPage: 25,
            keywords: "",
            employee_approval_requests: [],
            employee_request: [],
            employee_request_original: [],
            employee_request_approval: [],
         }
    },
    created(){
        this.fetchEmployeeApprovalRequests();
    },
    methods:{
        employeeRequestApprove(){
            let v = this;
            Swal.fire({
                title: 'Are you sure you want to approve this request?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2dce89',
                cancelButtonColor: '#fb6340',
                confirmButtonText: 'Yes, Approve'
                }).then((result) => {
                
                    let formData = new FormData();
                    formData.append('status', 'Approved');
                    formData.append('_method', 'PATCH');
                    if (result.value) {
                        var index = v.employee_approval_requests.findIndex(item => item.id == v.employee_request.id);   
                        axios.post(`/employee_approval/` + v.employee_request.id, 
                        formData
                        )
                        .then(response => {
                            v.employee_approval_requests.splice(index,1,response.data);
                            v.employee_request = response.data;

                            Swal.fire({
                                title: 'Success!',
                                text: 'Employee Updated Successfully',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            })

                        })
                        .catch(error => {
                            v.loading = false;
                            v.errors = error.response.data.errors;
                        
                            
                            Swal.fire({
                                title: 'Warning!',
                                text: 'Unable to Update Employee. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'Okay'
                            })
                        })
                }

            })
            
        },
        employeeRequestDisapprove(){
            let v = this;
            Swal.fire({
                title: 'Are you sure you want to disapprove this request?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f5365c',
                cancelButtonColor: '#fb6340 ',
                confirmButtonText: 'Yes, Disapprove'
                }).then((result) => {
                    let formData = new FormData();
                    formData.append('status', 'Disapproved');
                    formData.append('_method', 'PATCH');
                    if (result.value) {
                        var index = v.employee_approval_requests.findIndex(item => item.id == v.employee_request.id);   
                            axios.post(`/employee_approval/` + v.employee_request.id, 
                            formData
                        )
                        .then(response => {
                            v.employee_request = response.data;
                            v.employee_approval_requests.splice(index,1,response.data);
                            Swal.fire({
                                title: 'Disapproved!',
                                text: 'Employee update has been disapproved.',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            })
                        })
                        .catch(error => {
                            this.loading = false;
                            this.errors = error.response.data.errors;
                            Swal.fire({
                                title: 'Warning!',
                                text: 'Unable to Update Employee. Check Entries and then try again.',
                                icon: 'error',
                                confirmButtonText: 'Okay'
                            })
                        })
                }
            })
        },
        copyObjectEmployeeRequest(employee_request){
            this.employee_request = employee_request;
            //Original Data
            var original_employee_data = JSON.parse(employee_request.original_employee_data);
            this.employee_request_original = original_employee_data;
            this.employee_request_original.dependents = original_employee_data.dependents;
            
            //Approval Data
            var employee_data = JSON.parse(employee_request.employee_data);
            this.employee_request_approval = employee_data;
            this.employee_request_approval.dependents = JSON.parse(employee_data.dependents);
            this.employee_request_approval.deleted_dependents = JSON.parse(employee_data.deleted_dependents);
            this.employee_request_approval.deleted_dependent_attachments = JSON.parse(employee_data.deleted_dependent_attachments);
        },



        fetchEmployeeApprovalRequests(){
            axios.get('/employee-approval-requests-all')
            .then(response => { 
                this.employee_approval_requests = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            })
        },
        statusClassObject(status) {
            if(status == 'Pending'){
                return 'btn-outline-primary';
            }else if(status == 'Approved'){
                return 'btn-outline-success';
            }else if(status == 'Disapproved'){
                return 'btn-outline-warning';
            }else{
                return 'btn-outline-default';
            }
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
        filteredemployeerequests(){
            let self = this;
            return Object.values(self.employee_approval_requests).filter(employee_request => {
                let full_name = employee_request.employee.first_name + " " + employee_request.employee.last_name;
                return employee_request.employee.first_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee_request.employee.last_name.toLowerCase().includes(this.keywords.toLowerCase()) || full_name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(Object.values(this.employee_approval_requests).length / this.itemsPerPage)
        },
        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredemployeerequests.slice(index, index + this.itemsPerPage);

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