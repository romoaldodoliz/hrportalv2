<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-primary opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white text-uppercase">SURVEY : Iloilo Plant Billboard</h1>
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
                    <div class="card-body" v-if="user">
                        <div>
                            NAME : <b>{{user.first_name + ' ' + user.last_name}}</b><br>
                            POSITION : {{position}}<br>
                            DEPARTMENT : {{department}}<br>
                            COMPANY : {{company}}<br>
                            LOCATION : {{location}}
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td align="center">
                                                <img src="/hr_survey/IloiloPlantBillboard/Design1.jpg" alt="" class="mt-2" width="100%" height="auto">
                                                <br>
                                                <strong>Design 1</strong> 
                                            </td>
                                            <td align="center">
                                                <img src="/hr_survey/IloiloPlantBillboard/Design2.jpg" alt="" class="mt-2" width="100%" height="auto">
                                                <br>
                                                <strong>Design 2</strong> 
                                            </td>
                                            <td align="center">
                                                <img src="/hr_survey/IloiloPlantBillboard/Design3.jpg" alt="" class="mt-2" width="100%" height="auto">
                                                <br>
                                                <strong>Design 3</strong> 
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- q1 -->
                            <div class="col-md-12">
                                <h4>Which art card design do you like the most for Iloilo Plant Billboard?</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_1" class="custom-control-input" value="a" v-model="q1">
                                        <label class="custom-control-label" for="q1_1" >a.	Design 1</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_2" class="custom-control-input" value="b" v-model="q1">
                                        <label class="custom-control-label" for="q1_2" >b.	Design 2</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_3" class="custom-control-input" value="c" v-model="q1">
                                        <label class="custom-control-label" for="q1_3" >c.	Design 3</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q1">{{errors.q1[0]}}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="card-footer bg-white border-0" v-if="user">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button id="save-btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" :disabled="saveDisable" @click="saveSurvey" style="width:150px;">Save</button>
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
    export default {
        data() {
            return {
                user: '',
                position: '',
                department: '',
                company: '',
                location: '',
                q1 : '',
                errors : [],
                saveDisable : false
            }
        },
        created () {
            this.getUser();
        },
        methods: {
            saveSurvey(){
                let v = this;
                v.saveDisable = true;
                var r = confirm("Are you sure you want to save this survey?");
                if (r == true) {
                    if(v.user){
                        axios.post(`/save-survey-iloilo-plant-billboard`, {
                            user_id: v.user.user_id,
                            employee_number: v.user.id_number,
                            name: v.user.first_name + ' ' + v.user.last_name,
                            business_unit: v.user.cluster,
                            cluster: v.user.new_cluster,
                            company: v.company,
                            department: v.department,
                            location: v.location,
                            position: v.position,
                            region: v.user.area ? v.user.area : "",
                            hired_date : v.user.date_hired,
                            q1: v.q1 ? v.q1 : "",
                            _method: 'POST'
                        })
                        .then(response => {
                            if(response.data == "saved"){
                                alert('Successfully saved. Thank you.');
                                window.location.href = "http://10.96.4.70/login";
                            }else{
                                alert('Please try again. Thank you.');
                                location.reload();
                            }
                        })
                        .catch(error => {
                            alert('Check your form and then try again. Thank you.');
                            this.errors = error.response.data.errors;
                            v.saveDisable = false;
                        });
                    }else{
                        window.location.href = "http://10.96.4.70/login";
                    }
                }else{
                    v.saveDisable = false;
                }
            },
            getUser(){
                axios.get('/get-user-iloilo-plant-billboard')
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
        },
    }
</script>

<style lang="scss" scoped>

</style>