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
                                    <h3 class="mb-0">EMPLOYEE RFID</h3>
                                    <small class="text-muted">Biometric Registration</small>
                                </div> 
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="role">Search Employee from HR Portal</label> 
                                        <input type="text"  class="form-control" v-model="searchEmployee"  v-on:keyup.enter="fetchEmployees" placeholder="Search Last Name / First Name">
                                    </div>
                                </div>
                                <div class="col-lg-4 mt-2">
                                    <button type="button" class="btn btn-primary btn-md mt-4" @click="fetchEmployees">Search</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <h4 class="ml-3" v-if="searchEmployeeResults">Search results for : {{ searchEmployeeResults }}</h4>

                                    <div v-if="employeeResults.length > 0">
                                        <small><i>HR Portal Employees</i></small>
                                        <table class="table align-items-center table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">ID Number</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Biometric ID Number</th>
                                                    <th scope="col">Add Card</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="loading">
                                                    <td colspan="15">
                                                        <content-placeholders>
                                                            <content-placeholders-text :lines="2" />
                                                        </content-placeholders>
                                                        <h4>Loading Employee Records.. Please wait a moment... </h4>
                                                    </td>
                                                </tr>
                                                <tr v-for="(employee,index) in employeeResults" v-bind:key="index">
                                                    <td>
                                                        {{employee.id_number}}
                                                    </td>
                                                    <td>
                                                        {{employee.first_name + ' ' + employee.last_name}}
                                                    </td>
                                                    <td>
                                                        {{employee.door_id_number}}
                                                    </td>
                                                    <td>
                                                        <button v-if="employee.rfid_biometric_log" class="btn btn-sm btn-outline-default" data-toggle="modal" data-target="#addBiometricCardModal" style="cursor: pointer" @click="selectedEmployeeAddCard(employee)">Already Saved</button>
                                                        <button v-else class="btn btn-sm btn-success" data-toggle="modal" data-target="#addBiometricCardModal" style="cursor: pointer" @click="selectedEmployeeAddCard(employee)">Add Biometric Card</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                                
                                <div class="col-lg-6">
                                    <h4 class="ml-3" v-if="searchEmployeeResults">Search results for : {{ searchEmployeeResults }}</h4>

                                    <div v-if="employeeResults.length > 0">
                                        <small><i>Biometric Data</i></small>
                                        <table class="table align-items-center table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">User ID</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">View Cards</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(user,index) in filteredBiometricQueues" v-bind:key="index">
                                                    <td>
                                                        {{user.user_id}}
                                                    </td>
                                                    <td>
                                                        {{user.name}}
                                                    </td>
                                                    <td>
                                                       <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#viewUserModal" style="cursor: pointer" @click="viewBiometricCards(user)">View Cards</button>
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
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-sm modal-employee" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center"> View user Card</h2>
                        
                    </div>
                    <div class="modal-body">
                       <h4>Cards</h4>
                       <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Card ID</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Weigand Format</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(card,index) in userCards" v-bind:key="index">
                                    <td>
                                        {{card.id}}
                                    </td>
                                    <td>
                                        {{card.card_id}}
                                    </td>
                                    <td>
                                        {{card.status}}
                                    </td>
                                    <td>
                                        {{card.wiegand_format.name}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
    </div>

    <!-- Add Biometric Card  -->
    <div class="modal fade" id="addBiometricCardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center"> Add Biometric Card</h2>
                        
                    </div>
                    <div class="modal-body">
                        Selected Employee: {{selectedEmployee.first_name + ' ' + selectedEmployee.last_name}}<br>
                        Biometric ID Number: {{selectedEmployee.door_id_number }}<br><br>

                        <span>Select Biometric User</span>
                        <multiselect
                                v-model="form_biometric_user_id"
                                :options="filteredBiometricQueues"
                                :multiple="false"
                                track-by="id"
                                :custom-label="customLabelEmployee"
                                placeholder="Select User Biometric"
                                id="selected_employee"
                        >
                        </multiselect>

                        
                        <div v-if="biometricDevices.length > 0" style="width: 100%; height: 200px; overflow-y: scroll;" class="mt-3">
                            <h4>Biometric Devices</h4>
                            <div v-for="(device,index) in biometricDevices" v-bind:key="index">
                            
                                <label class="container mt-2" style="font-size:14px;">
                                    <small>{{device.name}}</small>
                                    <input type="checkbox" :id="device.id" :value="device.id" v-model="selectedDevices">
                                    <span class="checkmark"></span>
                                </label>
                                    
                            </div>
                        </div>

                        <div v-if="biometricDoorDevices.length > 0" style="width: 100%; height: 200px; overflow-y: scroll;" class="mt-3">
                            <h4>Doors</h4>
                            <div v-for="(door,index) in biometricDoorDevices" v-bind:key="index">
                            
                                <label class="container mt-2" style="font-size:14px;">
                                    <small>{{door.name}}</small>
                                    <input type="checkbox" :id="door.id" :value="door.id" v-model="selectedDoorDevices">
                                    <span class="checkmark"></span>
                                </label>
                                    
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-md btn-primary" :disabled="saveDisable" @click="saveBiometricCard">Save card</button>
                    </div>
                </div>
            </div>
    </div>

</div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<script>
    import Multiselect from 'vue-multiselect';
    import Swal from 'sweetalert2';

    export default {
        components: {
           Multiselect
        },   
        data() {
            return {
                searchEmployee: '',
                searchEmployeeResults: '',
                employeeResults: [],
                errors: [],
                loading: false,

                selectedEmployee : [],

                //Biometric Data
                biometricUserResults : [],
                currentPage: 0,
                itemsPerPage: 5,

                selectedViewUser : [],
                userCards : [],

                //Addcard
                form_biometric_user_id:'',
                selectedDevices : [],
                selectedDoorDevices : [],

                //Biometric Devices
                biometricDevices : [],

                //Biometric Door Devices
                biometricDoorDevices : [],

                saveDisable: false,
            }
        },
        created () {
            this.getBiometricDevices();
            this.getDoorBiometricDevices();
        },
        methods: {
            getDoorBiometricDevices(){
                axios.get('/get-rfid-door-devices')
                .then(response => {
                    if(response.data.status_code == "SUCCESSFUL"){
                        this.biometricDoorDevices = response.data.records;
                    }
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            },
            getBiometricDevices(){
                axios.get('/get-rfid-devices')
                .then(response => {
                    if(response.data.status_code == "SUCCESSFUL"){
                        this.biometricDevices = response.data.records;
                    }
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            },
            fetchEmployees() {
                this.errors = []; 
                this.employeeResults = [];
                this.loading = true; 
                axios.get('/employee-rfid-results?search='+this.searchEmployee)
                .then(response => {
                    this.employeeResults = response.data;
                    this.searchEmployeeResults = this.searchEmployee;
                    this.loading = false;  

                    this.fetchBiometricUsers();
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.loading = false; 
                })
            },
            fetchBiometricUsers(){
                axios.get('/biometric-user-results?search='+this.searchEmployee)
                .then(response => {
                    this.biometricUserResults = response.data;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            },
            viewBiometricCards(user){
                this.userCards = [];
                axios.get('/get-rfid-user-card?user_id='+user.user_id)
                .then(response => {
                    this.selectedViewUser = user;
                    this.userCards = response.data ? response.data.card_list : [];
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            },
            customLabelEmployee (employee) {
                if(employee){
                    return `${ employee.user_id }` + ' - '+ `${ employee.name }`;
                }else{
                   return '';
                }
            },
            selectedEmployeeAddCard(employee){
                this.selectedEmployee = employee;
                this.form_biometric_user_id = '';
            },
            saveBiometricCard(){
                let v = this;
                this.formFilterData = new FormData();
                this.formFilterData.append('employee_id',this.selectedEmployee.id);
                this.formFilterData.append('id_card',this.selectedEmployee.door_id_number);
                this.formFilterData.append('biometric_user_id',this.form_biometric_user_id.user_id ? this.form_biometric_user_id.user_id : "");
                this.formFilterData.append('selected_devices',this.selectedDevices ? this.selectedDevices : "");
                this.formFilterData.append('selected_door_devices',this.selectedDoorDevices ? this.selectedDoorDevices : "");

                this.saveDisable = true;

                axios.post('/save-biometric-user', this.formFilterData)
                .then(response => {
                    this.saveDisable = false;
                    if(response.data == "saved"){
                        Swal.fire({
                            title: 'Success!',
                            text: 'Biometric ID card has been successfully saved.',
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        }).then(okay => {
                            if (okay) {
                                $('#addBiometricCardModal').modal('hide');
                                this.selectedEmployee = '';
                                this.form_biometric_user_id = '';
                                this.fetchEmployees();
                                
                            }
                        });
                    }
                    else if(response.data == "response_error"){
                        Swal.fire({
                            title: 'Warning!',
                            text: 'Response Error. Please contact the administrator.',
                            icon: 'warning',
                            confirmButtonText: 'Okay'
                        })
                    }
                    else{
                        Swal.fire({
                            title: 'Warning!',
                            text: 'Please try again.',
                            icon: 'warning',
                            confirmButtonText: 'Okay'
                        })
                    }
                    
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    alert('Error: Please try again.');
                })
            }
        },
        computed:{
            filteredBiometricUser(){
                let self = this;
                return Object.values(self.biometricUserResults).filter(user => {
                    return  user.name.toLowerCase().includes(this.searchEmployee.toLowerCase())
                });
            },
            filteredBiometricQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredBiometricUser.slice(index, index + this.itemsPerPage);
                return queues_array;
            }
        }
    }
</script>

