<template>
<div>
    <div class="header bg-gradient-success pb-8 pt-5 pt-md-8 container-list"></div>
    <div class="container-fluid mt--9">
        <div class="header-body mb-5">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h2 class="col-12 modal-title" id="addCompanyLabel">Offboarding | Resignation Letter's</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="keyword" placeholder="Search Employee">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive" style="min-height: 300px!important;">
                                        <!-- employees table -->
                                        <table class="table align-items-center table-bordered mb-5">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th></th>
                                                    <th scope="col" >Employee Name</th>
                                                    <th scope="col" class="text-center">Type</th>
                                                    <th scope="col" class="text-center">Last Day</th>
                                                    <th scope="col" class="text-center">Date Uploaded</th>
                                                    <th scope="col" class="text-center">Letter</th>
                                                    <th scope="col" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="table_loading">
                                                    <td colspan="15">
                                                        <content-placeholders>
                                                            <content-placeholders-text :lines="3" />
                                                        </content-placeholders>
                                                        <h4>Loading Records.. Please wait a moment... </h4>
                                                    </td>
                                                </tr>
                                                <tr v-for="(item, u) in filteredQueues" v-bind:key="u">
                                                    <td>
                                                        <div class="row justify-content-center mb-2"><img :src="'http://10.96.4.126:8668/storage/id_image/employee_image/'+item.employee.id+'.png?v=' + Math.random()" class="rounded-circle" style="width: 50px; height: 50px; border: 2px dotted;"></div>
                                                    </td>
                                                    <td>
                                                        <img src="" alt="">
                                                        <small><strong> {{ getFullName(item.employee) }}</strong></small> <br>
                                                        <small>{{item.employee.position}}</small><br>
                                                        <small>{{item.employee.companies[0].name}} </small><br>
                                                        <small>{{item.employee.departments[0].name}} </small> <br>
                                                        <small>{{item.employee.locations[0].name}}</small>

                                                    </td>
                                                    <td align="center">
                                                       {{item.type}}
                                                    </td>
                                                    <td align="center">
                                                       {{item.last_day}}
                                                    </td>
                                                    <td align="center">
                                                       {{item.created_at}}
                                                    </td>
                                                    <td align="center">
                                                       <a v-if="item.letter" :href="item.letter.upload_pdf_url" class="btn btn-sm btn-primary" target="_blank"> Letter </a>
                                                       <small v-else class="text-danger">Empty</small>
                                                    </td>
                                                    <td align="center">
                                                        <div v-if="item.cancel == '1'">
                                                            <button class="btn btn-sm danger mt-2" disabled :title="'Cancelled On : ' + item.cancel_date + ' by: ' + getFullName(item.cancelled_by)">Cancelled</button>
                                                            <button v-if="item.clearance" class="btn btn-sm btn-info mt-2" @click="viewClearance(item)">View Clearance</button>
                                                        </div>
                                                        <div v-else>
                                                            <button class="btn btn-sm btn-danger mt-2" @click="cancelUploadPdf(item)">Cancel</button>
                                                            <button v-if="item.clearance" class="btn btn-sm btn-info mt-2" @click="viewClearance(item)">View Clearance</button>
                                                        </div>
                                                       
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
                                            <span class="mr-2">Filtered : {{ filteredQueues.length }} </span><br>
                                            <span class="mr-2">Total: {{ uploaded_pdfs.length }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="clearanceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-employee" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Clearance - {{ upload_pdf ? getFullName(upload_pdf.employee) : "" }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table align-items-center table-bordered mb-5">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Department</th>
                                    <th scope="col">Signatory</th>
                                    <th scope="col">Accountabilities</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Date Verified</th>
                                    <th scope="col">Action</th>
                                </tr>
                                
                            </thead>
                            <tbody>
                                <tr v-for="(item, u) in clearance_signatories" v-bind:key="u">
                                    <td>
                                        {{item.department ?  item.department.name : "DEPARTMENT/DIVISION HEAD"}}
                                    </td>
                                    <td>
                                        <strong>{{item.user ?  item.user.name : ""}}</strong>
                                    </td>
                                    <td>
                                        {{item.accountabilities ?  item.accountabilities : ""}} <br>
                                        {{item.remarks ? "Remarks: "+ item.remarks : ""}}
                                    </td>
                                    <td>
                                        {{item.amount ?  item.amount : ""}}
                                    </td>
                                    <td>
                                        {{item.date_verified}}
                                    </td>
                                    <td>
                                        <button v-if="item.status == 1" class="btn btn-sm btn-danger" @click="resetClearance(item)">Reset</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
        data() {
            return {
                keyword: '',
                table_loading : false,
                upload_pdf : '',
                uploaded_pdfs : [],
                errors : [],
                currentPage: 0,
                itemsPerPage: 10,
                clearance_signatories : [],
            }
        },
        created () {
            this.getUploadedPDF();
        },
        methods: {
            resetClearance(clearance_signatory){
                let v = this;
                if(clearance_signatory){
                    Swal.fire({
                        title: 'Are you sure you want to reset this signatory?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Reset',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        let formData = new FormData();
                        formData.append('id', clearance_signatory.id);
                        formData.append('upload_pdf_id', v.upload_pdf.id);
                        axios.post(`/reset-clearance-signatory`, formData)
                        .then(response => {
                            if(response.data.status == "saved"){
                                alert('Successfully reset');
                                var index = this.clearance_signatories.findIndex(item => item.id == clearance_signatory.id);
                                this.clearance_signatories.splice(index,1,response.data.clearance_signatory_data);
                            }
                        })
                    })
                }
            },
            viewClearance(upload_pdf){
                if(upload_pdf.clearance.signatories.length > 0){
                    this.upload_pdf = upload_pdf; 
                    this.clearance_signatories = upload_pdf.clearance.signatories;
                    $('#clearanceModal').modal('show');
                }else{
                    alert('No Signatories Found!');
                }
            },
            cancelUploadPdf(upload_pdf){
                let v = this;
                this.upload_pdf = upload_pdf;
                Swal.fire({
                    title: 'Are you sure you want to cancel this uploaded PDF?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Cancel',
                    cancelButtonText: 'No'
                    }).then((result) => {
                    if (result.value) {
                        let formData = new FormData();
                        formData.append('id', v.upload_pdf.id);
                        axios.post(`/cancel-upload-pdf`, formData)
                        .then(response => {
                            if(response.data.status == "saved"){
                                alert('Successfully Saved');
                                var index = this.uploaded_pdfs.findIndex(item => item.id == upload_pdf.id);
                                this.uploaded_pdfs.splice(index,1,response.data.upload_pdf);
                            }
                        })
                    }
                })
            },
            getFullName(employee){
                var first_name = employee.first_name;
                var last_name = employee.last_name;
                var middle_initial = employee.middle_initial && employee.middle_initial != '-' ? employee.middle_initial + '.' : "";
                var name_suffix = employee.name_suffix && employee.name_suffix != '-' ? employee.name_suffix : "";
                return first_name + ' ' + middle_initial + ' ' + last_name + ' ' + name_suffix ;
            },
            getUploadedPDF() {
                let v = this;
                v.uploaded_pdfs = [];
                v.table_loading = true;
                axios.get(`/uploaded-pdf-data`)
                .then(response => {
                    v.uploaded_pdfs = response.data;
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
            }, 
        },
        computed:{
            filteredUploadPdf(){
                let self = this;
                return Object.values(self.uploaded_pdfs).filter(item => {
                    if(item.employee){
                            let full_name = item.employee.first_name + " " + item.employee.last_name;
                            return item.employee.first_name.toLowerCase().includes(this.keyword.toLowerCase()) || item.employee.last_name.toLowerCase().includes(this.keyword.toLowerCase()) || full_name.toLowerCase().includes(this.keyword.toLowerCase())
                        }
                    });
            },
            totalPages() {
                return Math.ceil(Object.values(this.filteredUploadPdf).length / this.itemsPerPage)
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredUploadPdf.slice(index, index + this.itemsPerPage);

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
    @media (min-width: 992px){
        .modal-lg {
            max-width: 700px!important;
        }
        .modal-employee {
            max-width: 1200px!important;
        }
    }
</style>