<template>
    <div>
        <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
            <!-- Mask -->
            <span class="mask bg-gradient-info opacity-7"></span>
            <!-- Header container -->
            <div class="container-fluid d-flex align-items-center">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <h1 class="display-2 text-white text-uppercase">SURVEY : LEGAL QUESTIONNAIRE</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt--7 mb-6 mx-auto">
            <div class="row justify-content-center">
                <div class="col-xl-10 order-xl-1">
                    <div class="card bg-secondary shadow">
                        <div class="card-header bg-white border-0">
                            <div class="col-md-12 text-center">
                                <img src="/img/lfuggoc_logo.png" style="width:150px;height:auto;">
                            </div>
                        </div>
                        <div class="card-body">
                            <div v-if="user">
                                NAME : {{user.first_name + ' ' + user.last_name}}<br>
                                POSITION : {{position}}<br>
                                DEPARTMENT : {{department}}<br>
                                COMPANY : {{company}}<br>
                                LOCATION : {{location}}
                            </div>
                        
                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                    <h4>1.	How would you rate the quality of the services provided by the Legal Department to you or your Department/Business Unit during this quarter? Kindly use the following rating scale:</h4>
                                    <table class="table align-items-center table-bordered text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <td><h4>1</h4></td>
                                                <td><h4>2</h4></td>
                                                <td><h4>3</h4></td>
                                                <td><h4>4</h4></td>
                                                <td><h4>5</h4></td>
                                              
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><h4>Unsatisfactory</h4> </td>
                                                <td><h4>Needs Improvement</h4> </td>
                                                <td><h4>Meets Expectations</h4> </td>
                                                <td><h4>Exceeds Expectations</h4> </td>
                                                <td><h4>Exceptional</h4> </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="col-xl-2 mb-2 mt-3">
                                        <input type="text" class="form-control" placeholder="Your Answer" v-model="q1" @keypress="isNumber($event)" maxlength="1">
                                    </div> 
                                </div>
                                <div class="col-12 mt-2">
                                    <span class="text-danger" v-if="errors.q1">{{errors.q1[0]}}</span>
                                </div>
                                <div class="col-md-12 mt-5">
                                    <h4>2. Is there any service that we provided you or your Department/BU, which you think is outstanding and deserves to be singled out?  Kindly explain.</h4>
                                    <textarea name="" id="" cols="30" rows="10" class="form-control mb-2" v-model="q2">Input here..</textarea>
                                </div>
                                <div class="col-12 mt-2">
                                    <span class="text-danger" v-if="errors.q2">{{errors.q2[0]}}</span>
                                </div>
                                <div class="col-md-12 mt-5">
                                    <h4>3. Kindly submit your suggestions on how we can improve our services.</h4>
                                    <textarea name="" id="" cols="30" rows="10" class="form-control mb-2" v-model="q3">Input here..</textarea>
                                </div>
                                <div class="col-12 mt-2">
                                    <span class="text-danger" v-if="errors.q3">{{errors.q3[0]}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button id="save-btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="saveSurveyLegal" style="width:150px;">Save</button>
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
    import Swal from 'sweetalert2'
    export default {
        data() {
            return {
                user: [],
                errors: [],
                position: '',
                department: '',
                company: '',
                location: '',
                q1: '',
                q2: '',
                q3: '',
            }
        },
        created () {
            this.getUser();
        },
        methods: {
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 53)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            getUser(){
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                const user_id = urlParams.get('user_id');

                axios.get('/get-user-survey?user_id=' + user_id)
                .then(response => { 
                    this.user = response.data;
                    this.position = response.data.position;
                    this.department = response.data.departments[0].name;
                    this.company = response.data.companies[0].name;
                    this.location = response.data.locations[0].name;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                });
            },
            saveSurveyLegal(){
                let v = this;
               Swal.fire({
                        title: 'Preview',
                        icon: false,
                        width : '60%',
                        html : '<div class="row text-left">'+
                               ' <div class="col-md-12">'+
                                   ' <hr>'+
                                    '<h4>1.	How would you rate the quality of the services provided by the Legal Department to you or your Department/Business Unit during this quarter? Kindly use the following rating scale:</h4>'+
                                    '<table class="table align-items-center table-bordered text-center">'+
                                        '<thead class="thead-light">'+
                                            '<tr>'+
                                                '<td><h4>1</h4></td>'+
                                                '<td><h4>2</h4></td>'+
                                                '<td><h4>3</h4></td>'+
                                                '<td><h4>4</h4></td>'+
                                                '<td><h4>5</h4></td>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                           '<tr>'+
                                               '<td><h4>Unsatisfactory</h4> </td>'+
                                                '<td><h4>Needs Improvement</h4> </td>'+
                                                '<td><h4>Meets Expectations</h4> </td>'+
                                                '<td><h4>Exceeds Expectations</h4> </td>'+
                                                '<td><h4>Exceptional</h4> </td>'+
                                            '</tr>'+
                                        '</tbody>'+
                                    '</table>'+
                                    '<div class="col-xl-2 mb-2 mt-3">'+
                                        '<input type="text" class="form-control " placeholder="Your Answer"  value="'+v.q1+'" disabled> '+
                                    '</div> '+
                                '</div>'+
                                
                                '<div class="col-md-12 mt-5">'+
                                    '<h4>2. Is there any service that we provided you or your Department/BU, which you think is outstanding and deserves to be singled out?  Kindly explain.</h4>'+
                                    '<textarea name="" id="" cols="30" rows="5" class="form-control mb-2" disabled>'+v.q2+'</textarea>'+
                                '</div>'+
                               
                                '<div class="col-md-12 mt-5">'+
                                    '<h4>3. Kindly submit your suggestions on how we can improve our services.</h4>'+
                                    '<textarea name="" id="" cols="30" rows="5" class="form-control mb-2" disabled>'+v.q3+'</textarea>'+
                                '</div>'+
                                '<span class="ml-2 mt-5 text-danger">*Please review your answers. You can no longer change you answers once you click on "Yes, Save"</span>'+
                            '</div>',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Save'
                        }).then((result) => {
                        if (result.value) {
                            if(v.user){
                                axios.post(`/save-survey-legal-questionnaire`, {
                                    user_id: v.user.user_id,
                                    name: v.user.first_name + ' ' + v.user.last_name,
                                    position: v.position,
                                    department: v.department,
                                    company: v.company,
                                    location: v.location,
                                    q1: v.q1,
                                    q2: v.q2,
                                    q3: v.q3,
                                    _method: 'POST'
                                })
                                .then(response => {
                                    if(response.data == "saved"){
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Successfully saved. Thank you',
                                            icon: 'success',
                                            confirmButtonText: 'Okay'
                                        })
                                        window.location.href = "http://10.96.4.70/login";
                                    }else{
                                        alert('Please try again. Thank you.');
                                        location.reload();
                                    }
                                })
                                .catch(error => {
                                    alert('Check you form and then try again. Thank you.');
                                    this.errors = error.response.data.errors;
                                });
                            }
                        }
                })
                
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>