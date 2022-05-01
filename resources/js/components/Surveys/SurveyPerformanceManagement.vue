<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-primary opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white text-uppercase">SURVEY : PERFORMANCE MANAGEMENT</h1>
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
                                    Instruction: Please rate the following statements according to your Performance Management System and Process experience.
                                </h4>
                                <hr>
                            </div>
                           <div class="col-md-12">
                               <div class="table-responsive">
                                   <table class="table table-bordered">
                                       <thead>
                                           <tr>
                                               <th class="text-center">TOOL – PERFORMANCE APPRAISAL SYSTEM</th>
                                               <th class="text-center">
                                                   1 <br>
                                                   Strongly Disagree    
                                               </th>
                                               <th class="text-center">
                                                   2 <br>
                                                   Disagree
                                               </th>
                                               <th class="text-center">
                                                   3 <br>
                                                   Agree
                                               </th>
                                               <th class="text-center">
                                                   4 <br>
                                                   Strongly Agree
                                               </th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                           <tr>
                                               <td style="text-align: left; width: 200px !important;">1.	The system is simple and easy to use.</td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="1" id="t1_a" v-model="t1">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="2" id="t1_b" v-model="t1">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="3" id="t1_c" v-model="t1">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="4" id="t1_d" v-model="t1">
                                               </td>
                                           </tr>
                                           <tr>
                                               <td style="text-align: left; width: 200px !important;">2.    The Key Performance Indicators (KPI) truly reflect my duties and responsibilities as an employee.</td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="1" id="t2_a" v-model="t2">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="2" id="t2_b" v-model="t2">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="3" id="t2_c" v-model="t2">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="4" id="t2_d" v-model="t2">
                                               </td>
                                           </tr>
                                           <tr>
                                               <td style="text-align: left; width: 200px !important;">3.    The KPIs are realistic and achievable</td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="1" id="t3_a" v-model="t3">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="2" id="t3_b" v-model="t3">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="3" id="t3_c" v-model="t3">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="4" id="t3_d" v-model="t3">
                                               </td>
                                           </tr>
                                           <tr>
                                               <td style="text-align: left; width: 200px !important;">4.	The portion of the values assessment is easy to understand.</td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="1" id="t4_a" v-model="t4">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="2" id="t4_b" v-model="t4">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="3" id="t4_c" v-model="t4">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="4" id="t4_d" v-model="t4">
                                               </td>
                                           </tr>
                                           <tr>
                                               <td style="text-align: left; width: 200px !important;">5.	The assessment of the values truly reflects the company’s expectations of my behavior as an employee.</td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="1" id="t5_a" v-model="t5">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="2" id="t5_b" v-model="t5">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="3" id="t5_c" v-model="t5">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="4" id="t5_d" v-model="t5">
                                               </td>
                                           </tr>
                                       </tbody>
                                       <thead>
                                           <tr>
                                               <th class="text-center">PROCESS – PERFORMANCE APPRAISAL PROCESS</th>
                                               <th class="text-center">
                                                   1 <br>
                                                   Strongly Disagree    
                                               </th>
                                               <th class="text-center">
                                                   2 <br>
                                                   Disagree
                                               </th>
                                               <th class="text-center">
                                                   3 <br>
                                                   Agree
                                               </th>
                                               <th class="text-center">
                                                   4 <br>
                                                   Strongly Agree
                                               </th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                           <tr>
                                               <td style="text-align: left; width: 200px !important;">1.	The system is simple and easy to use.</td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="1" id="p1_a" v-model="p1">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="2" id="p1_b" v-model="p1">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="3" id="p1_c" v-model="p1">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="4" id="p1_d" v-model="p1">
                                               </td>
                                           </tr>
                                           <tr>
                                               <td style="text-align: left; width: 200px !important;">2.    The Key Performance Indicators (KPI) truly reflect my duties and responsibilities as an employee.</td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="1" id="p2_a" v-model="p2">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="2" id="p2_b" v-model="p2">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="3" id="p2_c" v-model="p2">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="4" id="p2_d" v-model="p2">
                                               </td>
                                           </tr>
                                           <tr>
                                               <td style="text-align: left; width: 200px !important;">3.    The KPIs are realistic and achievable</td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="1" id="p3_a" v-model="p3">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="2" id="p3_b" v-model="p3">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="3" id="p3_c" v-model="p3">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="4" id="p3_d" v-model="p3">
                                               </td>
                                           </tr>
                                           <tr>
                                               <td style="text-align: left; width: 200px !important;">4.	The portion of the values assessment is easy to understand.</td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="1" id="p4_a" v-model="p4">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="2" id="p4_b" v-model="p4">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="3" id="p4_c" v-model="p4">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="4" id="p4_d" v-model="p4">
                                               </td>
                                           </tr>
                                           <tr>
                                               <td style="text-align: left; width: 200px !important;">5.	The assessment of the values truly reflects the company’s expectations of my behavior as an employee.</td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="1" id="p5_a" v-model="p5">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="2" id="p5_b" v-model="p5">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="3" id="p5_c" v-model="p5">
                                               </td>
                                               <td style="text-align: center;">
                                                   <input type="radio" value="4" id="p5_d" v-model="p5">
                                               </td>
                                           </tr>
                                       
                                           <tr>
                                               <td colspan="5">
                                                   <h4>WHAT DO YOU APPRECIATE ABOUT THE PERFORMANCE MANAGEMENT PROCESS AND SYSTEM IN LA FIL?</h4>
                                                   <textarea class="form-control" v-model="c1" cols="30" rows="5" width="100%" placeholder="Input here..."></textarea>
                                               </td>
                                           </tr>
                                           <tr>
                                               <td colspan="5">
                                                   <h4>HOW CAN WE IMPROVE THE PERFORMANCE MANAGEMENT PROCESS AND SYSTEM IN LA FIL?</h4>
                                                    <textarea class="form-control" v-model="c2" cols="30" rows="5" width="100%" placeholder="Input here..."></textarea>
                                               </td>
                                           </tr>
                                       </tbody>
                                   </table>
                                   <i>Note: Your answer to this survey will be treated with utmost confidentiality.</i>
                               </div>
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
                t1 : '',
                t2 : '',
                t3 : '',
                t4 : '',
                t5 : '',
                p1 : '',
                p2 : '',
                p3 : '',
                p4 : '',
                p5 : '',
                c1 : '',
                c2 : '',
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
                        axios.post(`/save-survey-performance-management`, {
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
                            t1: v.t1 ? v.t1 : "",
                            t2: v.t2 ? v.t2 : "",
                            t3: v.t3 ? v.t3 : "",
                            t4: v.t4 ? v.t4 : "",
                            t5: v.t5 ? v.t5 : "",
                            p1: v.p1 ? v.p1 : "",
                            p2: v.p2 ? v.p2 : "",
                            p3: v.p3 ? v.p3 : "",
                            p4: v.p4 ? v.p4 : "",
                            p5: v.p5 ? v.p5 : "",
                            c1: v.c1 ? v.c1 : "",
                            c2: v.c2 ? v.c2 : "",
                            _method: 'POST'
                        })
                        .then(response => {
                            if(response.data == "saved"){
                                alert('Successfully saved. Thank you.');
                                window.location.href = "http://10.96.4.70/login";
                            }else{
                                alert('Please try again. Thank you.');
                                // location.reload();
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