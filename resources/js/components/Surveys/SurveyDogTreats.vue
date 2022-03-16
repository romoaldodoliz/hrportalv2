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
                                <h4 class="text-center">
                                    Survey Time!!! Good day! We would like to ask for a few seconds of your time to answer this quick survey regarding your preference for brand name and packaging design. PFMC will launch it’s very first dog treats and as part of LFUG-GOC, your feedback is valuable to us. Dog treats are dietary supplements similar to human snack food. They are usually dry and in biscuit form. Treats are given to dogs for training as reward for their good behavior. 
                                </h4>
                                <hr>
                            </div>
                            <!-- q1 -->
                            <div class="col-md-12">
                                <h4>1.	Which of the following proposed brand names appeal to you most?</h4>
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
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q1_9" class="custom-control-input" value="i" v-model="q1">
                                        <label class="custom-control-label" for="q1_9" >i.	None of the above</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q1">{{errors.q1[0]}}</span>
                            </div>
                            <!-- q2 -->
                            <div class="col-md-12">
                                <h4>2.	Which of the following packaging design elements appeal to you most? (choose only 1 per category)</h4>
                                <center>
                                    <img src="/hr_survey/dog_treats_q2.png" alt="" class="mt-2" width="100%" height="auto">
                                </center>
                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td align="center"><strong>Category</strong></td>
                                            <td align="center"><strong>1</strong></td>
                                            <td align="center"><strong>2</strong></td>
                                            <td align="center"><strong>3</strong></td>
                                            <td align="center"><strong>4</strong></td>
                                            <td align="center"><strong>5</strong></td>
                                            <td align="center"><strong>6</strong></td>
                                        </tr>
                                        <tr>
                                            <td align="center"><strong>Brand Name font</strong></td>
                                            <td align="center">
                                                <input type="radio" value="1" id="q2_a_1" v-model="q2_a">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="2" id="q2_a_2" v-model="q2_a">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="3" id="q2_a_3" v-model="q2_a">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="4" id="q2_a_4" v-model="q2_a">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="5" id="q2_a_5" v-model="q2_a">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="6" id="q2_a_6" v-model="q2_a">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center"><strong>Color combination</strong></td>
                                            <td align="center">
                                                <input type="radio" value="1" id="q2_b_1" v-model="q2_b">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="2" id="q2_b_2" v-model="q2_b">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="3" id="q2_b_3" v-model="q2_b">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="4" id="q2_b_4" v-model="q2_b">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="5" id="q2_b_5" v-model="q2_b">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="6" id="q2_b_6" v-model="q2_b">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center"><strong>Design execution</strong></td>
                                            <td align="center">
                                                <input type="radio" value="1" id="q2_c_1" v-model="q2_c">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="2" id="q2_c_2" v-model="q2_c">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="3" id="q2_c_3" v-model="q2_c">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="4" id="q2_c_4" v-model="q2_c">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="5" id="q2_c_5" v-model="q2_c">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="6" id="q2_c_6" v-model="q2_c">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center"><strong>Overall design</strong></td>
                                            <td align="center">
                                                <input type="radio" value="1" id="q2_d_1" v-model="q2_d">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="2" id="q2_d_2" v-model="q2_d">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="3" id="q2_d_3" v-model="q2_d">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="4" id="q2_d_4" v-model="q2_d">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="5" id="q2_d_5" v-model="q2_d">
                                            </td>
                                            <td align="center">
                                                <input type="radio" value="6" id="q2_d_6" v-model="q2_d">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                
                            </div>
                            <div class="col-12 mt-2 mb-3">
                                <span class="text-danger" v-if="errors.q2_a">{{errors.q2_a[0]}}</span>
                                <span class="text-danger" v-else-if="errors.q2_b">{{errors.q2_b[0]}}</span>
                                <span class="text-danger" v-else-if="errors.q2_c">{{errors.q2_c[0]}}</span>
                                <span class="text-danger" v-else-if="errors.q2_d">{{errors.q2_d[0]}}</span>
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
                q2_a : '',
                q2_b : '',
                q2_c : '',
                q2_d : '',
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
                            q2_a: v.q2_a ? v.q2_a  : "",
                            q2_b: v.q2_b ? v.q2_b : "",
                            q2_c: v.q2_c ? v.q2_c : "",
                            q2_d: v.q2_d ? v.q2_d : "",
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