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
                                        <h3 class="mb-0">EMPLOYEE ID's</h3>
                                        <small class="text-muted">List of all employees</small>
                                    </div> 
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-xl-12 mb-2 mt-3 float-right">
                                        <div class="col-xl-6 mb-2 mt-3 float-left">
                                            <input type="text" name="employee_ids" class="form-control" placeholder="Search by Name" autocomplete="off" v-model="keywords" id="employee_ids">
                                        </div> 
                                    </div> 
                                    <div class="col-xl-12 mb-2 mt-3 float-right">
                                        <h4>Filter by: </h4>
                                        <div class="col-xl-3 mb-2 float-left">
                                            <select class="form-control" v-model="company" id="company">
                                                <option value="">Choose Company</option>
                                                <option v-for="(company,v) in companies" v-bind:key="v" :value="company.id"> {{ company.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3 mb-2 float-left">
                                            <select class="form-control" v-model="department" id="department">
                                                <option value="">Choose Deparment</option>
                                                <option v-for="(department,v) in departments" v-bind:key="v" :value="department.id"> {{ department.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3 mb-2 float-left">
                                            <select class="form-control" v-model="location" id="location">
                                                <option value="">Choose Location</option>
                                                <option v-for="(location,v) in locations" v-bind:key="v" :value="location.id"> {{ location.name }}</option>
                                            </select>
                                        </div> 
                                        <div class="col-xl-3 mb-2 float-left">
                                                <select class="form-control" v-model="employee_status" id="employee_status">
                                                    <option value="">Choose Status</option>
                                                    <option value="ACTIVE">Active</option>
                                                    <option value="INACTIVE">Inactive</option>
                                                </select>
                                            </div> 
                                        <div class="col-xl-12">
                                            <button class="btn btn-sm btn-primary mt-3 float-right" @click="fetchFilterEmployee"> Apply Filter</button>
                                        </div> 
                                    </div> 
                                </div>
                            </div>

                            <div class="table-responsive">
                                <!-- employees table -->
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Employee Number</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Company</th>
                                            <th scope="col">Department</th>
                                            <th scope="col">Location</th>
                                            <th scope="col">ID Status</th>
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
                                        <tr v-for="(employee_id,index) in filteredQueues" v-bind:key="index">
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#printModal" style="cursor: pointer" @click="previewPrintId(employee_id)"><i class="fas fa-print"></i>Print Preview</a>
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#scanModal" style="cursor: pointer" @click="scanRFID(employee_id)"><i class="fas fa-id-card"></i>Scan RFID</a>
                                                    </div>
                                                </div>
                                                <!-- <button class="btn btn-sm btn-primary " data-target="#printModal" style="cursor: pointer" @click="previewPrintId(employee_id)"><i class="fas fa-print"></i> Print Preview</button> -->
                                            </td>
                                            <td>{{ employee_id.id_number }}</td>
                                            <td>{{ employee_id.first_name }} {{ employee_id.last_name }}</td>
                                            <td>{{ employee_id.companies[0] ? employee_id.companies[0].name : '' }}</td>
                                            <td>{{ employee_id.departments[0] ? employee_id.departments[0].name : '' }}</td>
                                            <td>{{ employee_id.locations[0] ? employee_id.locations[0].name : '' }}</td>
                                            <td v-if="employee_id.print_id_logs.length > 0">{{ employee_id.print_id_logs.length }} No. of Print(s)</td>
                                            <td v-else>Not yet</td>
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
                                    <span class="mr-2">Total employee(s) : {{ employee_ids.length }}</span>
                                </div>
                            </div>

                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-sm modal-employee" role="document" style="width:80%!important;">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center"> Employee ID Preview</h2> 
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12 text-center">
                            <iframe id="id-frame" :src="employee_id_src" frameborder="0" height="700px" width="100%"></iframe>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div v-if="employee_id.verification">
                           <button v-if="employee_id.verification.verification == '1'" class="btn btn-primary btn-md" @click="printEmployeeId"> Print </button>
                        </div>
                        <div v-else class="text-center">
                            <span class="badge badge-danger"><strong>Warning!</strong> This employee is not yet verified. Do you want to <a href="#" @click="printEmployeeId"><strong class="text-primary">print</strong></a> it anyway?</span> 
                        </div>
                        

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="scanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-sm modal-employee" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center"> SCAN RFID</h2>
                        
                    </div>
                    <div class="modal-body">
                       <h2 class="text-center">{{scan_rfid.first_name + " " + scan_rfid.last_name + " | " + scan_rfid.id_number }}</h2>
                       <h4 class="text-center text-default">{{scan_rfid.rfid_26 + " | " + scan_rfid.rfid_64 + " | " + scan_rfid.door_id_number }}</h4>

                       <div class="row">
                           <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">RFID 26 BIT</label>
                                    <input type="text" class="form-control" v-model="rfid_number.rfid_26">
                                    <span class="text-danger" v-if="errors.rfid_26">{{ errors.rfid_26[0] }}</span> 
                                </div>
                            </div>
                           <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">RFID 64 BIT</label>
                                    <input type="text" class="form-control" v-model="rfid_number.rfid_64">
                                    <span class="text-danger" v-if="errors.rfid_64">{{ errors.rfid_64[0] }}</span> 
                                </div>
                            </div>
                           <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">DOOR ID NUMBER</label>
                                    <input type="text" class="form-control" v-model="rfid_number.decimal_number">
                                    <span class="text-danger" v-if="errors.decimal_number">{{ errors.decimal_number[0] }}</span> 
                                </div>
                            </div>
                       </div>

                       <div class="row">
                           <div class="table-responsive">
                               <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr>
                                             <th scope="col">Card Bits</th>
                                            <th scope="col">Card Code</th>
                                            <th scope="col">Date/Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(rfid,index) in scanned_rfid_logs" v-bind:key="index">
                                            <td>{{ rfid.CardBits }}</td>
                                            <td>{{ rfid.CardCode }}</td>
                                            <td>{{ rfid.LocalTime }}</td>  
                                        </tr>
                                    </tbody>
                                </table>
                           </div>
                            <div v-if="error_tap">
                                <p class="text-warning">RFID is not defined. Please tap again.</p>
                            </div>
                            <div v-else>
                                <p class="text-success">RFID is defined. Click Get Scan.</p>
                            </div>
                           
                       </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" :disabled="error_tap" @click="fetchRfidNumber()">Get Scan</button>
                        <button class="btn btn-primary" @click="saveRFID(rfid_number)">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import print from 'print-js'
export default {
    data(){
         return {
            errors: [],
            currentPage: 0,
            itemsPerPage: 25,
            keywords: "",
            employee_ids: [],
            employee_id: [],
            companies : [],
            company : '',
            locations : [],
            location : '',
            departments : [],
            department : '',
            employee_id_src : '',
            employee_status : '',
            table_loading : true,
            scanned_rfid_logs : [],
            scan_rfid : [],
            rfid_number : [],
            scan_rfid_data : [],
            timer : '',
            error_tap : true
         }
    },
    created(){
        this.fetchEmployees();
        this.fetchCompanies();
        this.fetchDepartments();
        this.fetchLocations();
        this.fetchRFIDScans();
    },
    methods:{
        fetchRFIDScans(){
            axios.get('/scan-rfids')
            .then(response => { 
                this.scanned_rfid_logs = response.data;
                if(this.scanned_rfid_logs){
                     if(this.scanned_rfid_logs[0].CardBits == '64' && this.scanned_rfid_logs[1].CardBits == '64'){
                        this.error_tap = true;
                    }
                    else if(this.scanned_rfid_logs[0].CardBits == '26' && this.scanned_rfid_logs[1].CardBits == '26'){
                        this.error_tap = true;
                    }
                    else if(this.scanned_rfid_logs[0].CardBits == '26' && this.scanned_rfid_logs[1].CardBits == '64'){
                        this.error_tap = true;
                    }
                    else if(this.scanned_rfid_logs[0].LocalTime < this.scanned_rfid_logs[1].LocalTime){
                        this.error_tap = true;
                    }
                    else if(this.scanned_rfid_logs[0].LocalTime > this.scanned_rfid_logs[1].LocalTime){
                        this.error_tap = true;
                    }
                    else{
                        this.error_tap = false;
                    }
                }else{
                    this.error_tap = false;
                }
               
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            })
        },
        saveRFID(rfid_number){
            let v = this;
            this.formFilterData = new FormData();
            this.formFilterData.append('id',this.scan_rfid.id);
            this.formFilterData.append('rfid_26',this.rfid_number.rfid_26);
            this.formFilterData.append('rfid_64',this.rfid_number.rfid_64);
            this.formFilterData.append('door_id_number',this.rfid_number.decimal_number);

            axios.post('/save-rfid', this.formFilterData)
            .then(response => {
                this.scan_rfid.rfid_26 = response.data.rfid_26;
                this.scan_rfid.rfid_64 = response.data.rfid_64;
                this.fetchEmployees();
                alert(this.scan_rfid.first_name + ' ' + this.scan_rfid.last_name  + ' Rfid Number Successfully saved.');
            })
            .catch(error => {
                this.errors = error.response.data.errors;
               alert('Error: Please try again.');
            })
        },
        fetchRfidNumber(){
            axios.get('/rfid_number')
            .then(response => { 
                this.rfid_number = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            })
        },
        scanRFID(employee_id){
            this.timer = setInterval(this.fetchRFIDScans, 3000)
            this.scan_rfid = employee_id;
            this.rfid_number = [];
        },
        previewPrintId(employee_id){
            this.employee_id_src = '';
            var num = Math.random();
            this.employee_id_src = 'employee_print_id/'+ employee_id.id +'#toolbar=0&navpanes=0&scrollbar=0' + '?var=' + num;
            this.employee_id = employee_id;
        },
        printEmployeeId(){
            this.errors = [];
            this.formFilterData = new FormData();
            this.table_loading = false;

            var index = this.employee_ids.findIndex(item => item.id == this.employee_id.id);

            this.formFilterData.append('employee_id',this.employee_id.id);
            axios.post('/print-id-logs', this.formFilterData)
            .then(response => {
                this.employee_ids.splice(index,1,response.data);
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })

            printJS({printable: this.employee_id_src , type:'pdf', showModal:true});
        },
        fetchFilterEmployee(){
            this.table_loading = true;
            this.employee_ids = [];
            this.formFilterData = new FormData();
        
            this.formFilterData.append('company',this.company);
            this.formFilterData.append('department',this.department);
            this.formFilterData.append('location',this.location);
            this.formFilterData.append('employee_status',this.employee_status);
           
            this.formFilterData.append('_method', 'POST');

            axios.post('/filter-employee-id', this.formFilterData)
            .then(response => {
                this.employee_ids =  response.data;
                this.errors = [];
                this.table_loading = false;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
                this.table_loading = false;
            })
        },
        fetchEmployees(){
            this.table_loading = true;
            axios.get('/employee-ids')
            .then(response => { 
                this.employee_ids = response.data;
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
    computed:{
        filteredemployeeids(){
            let self = this;
            return Object.values(self.employee_ids).filter(employee_id => {
                let full_name = employee_id.first_name + " " + employee_id.last_name;
                return  employee_id.first_name.toLowerCase().includes(this.keywords.toLowerCase()) || employee_id.last_name.toLowerCase().includes(this.keywords.toLowerCase()) || full_name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(Object.values(this.filteredemployeeids).length / this.itemsPerPage)
        },
        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredemployeeids.slice(index, index + this.itemsPerPage);

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
