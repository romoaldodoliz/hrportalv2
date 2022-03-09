<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-primary opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white text-uppercase">SURVEY : For Dog Treats</h1>
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

                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <h4 class="text-center">Good day! Before we start our work, we would like to ask for a few seconds of your time to answer this quick survey regarding your own preference for brand name and packaging design </h4>
                                <hr>
                            </div>
                            <!-- q1 -->
                            <div class="col-md-12">
                                <h4>1.	Which of the following proposed brand name (for dog treats) appeals to you most</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_1" class="custom-control-input" value="a" v-model="q1">
                                        <label class="custom-control-label" for="q1_1" >a.	Tasty Paws</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_2" class="custom-control-input" value="b" v-model="q1">
                                        <label class="custom-control-label" for="q1_2" >b.	Goody YumYum</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_3" class="custom-control-input" value="c" v-model="q1">
                                        <label class="custom-control-label" for="q1_3" >c.	Feedo Bites</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_4" class="custom-control-input" value="d" v-model="q1">
                                        <label class="custom-control-label" for="q1_4" >d.	Trick o’ Treats</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_5" class="custom-control-input" value="e" v-model="q1">
                                        <label class="custom-control-label" for="q1_5" >e.	Bravo Perro</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_6" class="custom-control-input" value="f" v-model="q1">
                                        <label class="custom-control-label" for="q1_6" >f.	Doggo Delight</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_7" class="custom-control-input" value="g" v-model="q1">
                                        <label class="custom-control-label" for="q1_7" >g.	Oh My Dog!</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_8" class="custom-control-input" value="h" v-model="q1">
                                        <label class="custom-control-label" for="q1_8" >h.	Magnifico</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q1">{{errors.q1[0]}}</span>
                            </div>
                            <!-- q2 -->
                            <div class="col-md-12">
                                <h4>2.	Which of the following packaging design appeals to you most (kindly disregard the brand name)</h4>
                                <center>
                                    <img src="/hr_survey/dog_treats_q2.png" alt="" class="mt-2">
                                </center>
                                
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_1" class="custom-control-input" value="a" v-model="q2">
                                        <label class="custom-control-label" for="q2_1" >a. 1</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_2" class="custom-control-input" value="b" v-model="q2">
                                        <label class="custom-control-label" for="q2_2" >b. 2</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_3" class="custom-control-input" value="c" v-model="q2">
                                        <label class="custom-control-label" for="q2_3" >c. 3</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_4" class="custom-control-input" value="d" v-model="q2">
                                        <label class="custom-control-label" for="q2_4" >d. 4</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_5" class="custom-control-input" value="e" v-model="q2">
                                        <label class="custom-control-label" for="q2_5" >e. 5</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_6" class="custom-control-input" value="f" v-model="q2">
                                        <label class="custom-control-label" for="q2_6" >f. 6</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q2">{{errors.q2[0]}}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="card-footer bg-white border-0" v-if="user">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button id="save-btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" :disabled="saveDisable" @click="saveSurveyDec2021CqaCulture" style="width:150px;">Save</button>
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
                q2 : '',
                errors : [],
                saveDisable : false
            }
        },
        created () {
            this.getUser();
        },
        methods: {
            saveSurveyDec2021CqaCulture(){
                let v = this;
                v.saveDisable = true;
                var r = confirm("Are you sure you want to save this survey?");
                if (r == true) {
                    if(v.user){
                        axios.post(`/save-survey-dog-treats`, {
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
                            q2: v.q2 ? v.q2 : "",
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
                            alert('Check you form and then try again. Thank you.');
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
                axios.get('/get-user-dog-treats')
                .then(response => { 
                    this.user = response.data;
                    this.position = response.data.position;
                    this.department = response.data.departments[0].name;
                    this.company = response.data.companies[0].name;
                    this.location = response.data.locations[0].name;

                    // var modal = document.getElementById("languageModal");
                    // modal.style.display = "block";
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