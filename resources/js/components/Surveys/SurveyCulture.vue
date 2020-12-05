<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-primary opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white text-uppercase"><i class="fas fa-holly-berry text-green"></i> DECEMBER 2020 VALUES QUESTIONNAIRE</h1>
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
                            <!-- Q1 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>1.	What are the characteristics of a person with integrity? (choose 4 possible correct answers)</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q1_1" value="a" v-model="q1">
                                    <label class="custom-control-label" for="q1_1">
                                        <span class="text-muted">a.	Encourages colleagues to work together to achieve the objectives of the business</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q1_2" value="b" v-model="q1">
                                    <label class="custom-control-label" for="q1_2">
                                        <span class="text-muted">b.	Abides by the Code of Conduct at all times</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q1_3" value="c" v-model="q1">
                                    <label class="custom-control-label" for="q1_3">
                                        <span class="text-muted">c.	Takes ownership / accountability for his actions and for those of one's group, team, or department. </span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q1_4" value="d" v-model="q1">
                                    <label class="custom-control-label" for="q1_4">
                                        <span class="text-muted">d.	Acts as the ambassador of the organization during business interface, always putting in mind the best interest of the organization</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q1_5" value="e" v-model="q1">
                                    <label class="custom-control-label" for="q1_5">
                                        <span class="text-muted">e.	Guards the company resources and does not use it for personal gain</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q1_6" value="f" v-model="q1">
                                    <label class="custom-control-label" for="q1_6">
                                        <span class="text-muted">f.	Has a growth mindset</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q1_7" value="g" v-model="q1">
                                    <label class="custom-control-label" for="q1_7">
                                        <span class="text-muted">g.	Ensures that we are compliant to all regulatory and statutory requirements, laws and policies </span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <span class="text-danger" v-if="errors.q1">{{errors.q1[0]}}</span>
                                </div>
                            </div>
                            <!-- Q2 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>2.	You are responsible for the petty cash fund of your department, your superior told you to disburse 2,000 pesos from the petty cash fund to help your colleague with her medical expenses. Your superior told you that she will go to the ATM later to return the money. What will you do?</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_1" class="custom-control-input" value="a" v-model="q2">
                                        <label class="custom-control-label" for="q2_1">a.	Use your money to help your colleague and collect the money from your boss later.</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_2" class="custom-control-input" value="b" v-model="q2">
                                        <label class="custom-control-label" for="q2_2">b.	Disburse the money from the petty cash fund because your colleague needs the money.</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q2_3" class="custom-control-input" value="c" v-model="q2">
                                        <label class="custom-control-label" for="q2_3">c.	Tell your boss that you are not allowed to use the petty cash fund for personal use, and suggest other ways to help your colleague.</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <span class="text-danger" v-if="errors.q2">{{errors.q2[0]}}</span>
                                </div>
                            </div>
                            <!-- Q3 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>3. You have a good working relationship with your customer. There were times that you were praising and joking with him about how good his garden looks like, that you are interested in buying vegetables from him. One time your customer gave you 2 boxes full of freshly picked produce as a token of appreciation for assisting him with his concerns with the delivery order. What is the most appropriate response in this situation?</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q3_1" class="custom-control-input" value="a" v-model="q3">
                                        <label class="custom-control-label" for="q3_1">a.	Thank the customer for the gift and tell him that it is not necessary to give one.</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q3_2" class="custom-control-input" value="b" v-model="q3">
                                        <label class="custom-control-label" for="q3_2">b.	Politely refuse the gift and tell the customer that we have a "no-gift" policy. You will also email HR or Internal Audit regarding the incident.</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q3_3" class="custom-control-input" value="c" v-model="q3">
                                        <label class="custom-control-label" for="q3_3">c.	Accept the gift and share it with your colleagues.</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <span class="text-danger" v-if="errors.q3">{{errors.q3[0]}}</span>
                                </div>
                            </div>
                            <!-- Q4 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>4.	Amigo Segurado launched a new variant of Meat Loaf, you tried the product and it was delicious, the price is affordable too. How can you show your support for our very own brand? (choose the best answer)</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_1" class="custom-control-input" value="a" v-model="q4">
                                        <label class="custom-control-label" for="q4_1">a.	Take a picture of the Amigo Segurado Meat Loaf that you cooked and share the delicious experience on your Facebook page.</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_2" class="custom-control-input" value="b" v-model="q4">
                                        <label class="custom-control-label" for="q4_2">b.	During your zoom chat with your friends you mentioned to them that Amigo has a new product and that they should try it.</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_3" class="custom-control-input" value="c" v-model="q4">
                                        <label class="custom-control-label" for="q4_3">c.	When you were doing your grocery you saw the New Amigo Segurado Meat Loaf and you purchase the product instead of going for other brands</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q4_4" class="custom-control-input" value="d" v-model="q4">
                                        <label class="custom-control-label" for="q4_4">d.	All of the above</label>
                                    </div>
                                </div>

                                <div class="col-12 mt-2">
                                    <span class="text-danger" v-if="errors.q4">{{errors.q4[0]}}</span>
                                </div>

                            </div>
                            <!-- Q5 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>5. You are preparing a report that your superior will present in one of his meetings. You were only given a few hours to finish the report. You notice that there are still a lot of insights that you can input in the report and you can also put some graphs and visuals to make the report easy to understand, but your superior is already asking you to submit the report in an hour. You have no more time to make all these changes. What will you do in this situation? (choose the best answer)</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_1" class="custom-control-input" value="a" v-model="q5">
                                        <label class="custom-control-label" for="q5_1">a.	Input all your ideas even if that means you will be late in submitting the report.</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_2" class="custom-control-input" value="b" v-model="q5">
                                        <label class="custom-control-label" for="q5_2">b.	Make the most of the time given to you to finish the report, maximizing your time to include as many improvements that you can. After you completed the work, you discuss to your superior other possible improvements that you can make to enhance the value of the presentation.</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_3" class="custom-control-input" value="c" v-model="q5">
                                        <label class="custom-control-label" for="q5_3">c.	Submit the report so that you can meet the deadline</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-radio mb-3">
                                        <input type="radio" id="q5_4" class="custom-control-input" value="d" v-model="q5">
                                        <label class="custom-control-label" for="q5_4">d.	Complain to your boss, that you're not given ample time to finish the report, you could have enhanced the presentation given enough time.</label>
                                    </div>
                                </div>

                                <div class="col-12 mt-2">
                                    <span class="text-danger" v-if="errors.q5">{{errors.q5[0]}}</span>
                                </div>

                            </div>
                            <!-- Q6 -->
                            <div class="col-md-12">
                                <hr>
                                <h4>6.	What are the 3 Core Values of La Filipina Uygongco Group of Companies?</h4>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q6_1" value="a" v-model="q6">
                                    <label class="custom-control-label" for="q6_1">
                                        <span class="text-muted">a.	Honesty</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q6_2" value="b" v-model="q6">
                                    <label class="custom-control-label" for="q6_2">
                                        <span class="text-muted">b.	Stewardship</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q6_3" value="c" v-model="q6">
                                    <label class="custom-control-label" for="q6_3">
                                        <span class="text-muted">c.	Integrity</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q6_4" value="d" v-model="q6">
                                    <label class="custom-control-label" for="q6_4">
                                        <span class="text-muted">d.	Innovation</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q6_5" value="e" v-model="q6">
                                    <label class="custom-control-label" for="q6_5">
                                        <span class="text-muted">e.	Empathy</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q6_6" value="f" v-model="q6">
                                    <label class="custom-control-label" for="q6_6">
                                        <span class="text-muted">f.	Loyalty</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q6_8" value="g" v-model="q6">
                                    <label class="custom-control-label" for="q6_8">
                                        <span class="text-muted">g.	Agility</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q6_9" value="h" v-model="q6">
                                    <label class="custom-control-label" for="q6_9">
                                        <span class="text-muted">h.	Diligence</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q6_10" value="i" v-model="q6">
                                    <label class="custom-control-label" for="q6_10">
                                        <span class="text-muted">i.	Excellence</span>
                                    </label>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="q6_11" value="j" v-model="q6">
                                    <label class="custom-control-label" for="q6_11">
                                        <span class="text-muted">j.	Hard-work</span>
                                    </label>
                                    </div>
                                </div>

                                <div class="col-12 mt-2">
                                    <span class="text-danger" v-if="errors.q6">{{errors.q6[0]}}</span>
                                </div>

                            </div>

                        </div>
                    </div>

                     <!-- Footer -->
                    <div class="card-footer bg-white border-0">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button id="save-btn" type="button" class="btn btn-success btn-round btn-fill btn-lg" @click="saveSurveyCulture" style="width:150px;">Save</button>
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
            user: [],
            position: '',
            department: '',
            company: '',
            location: '',
            q1 : [],
            q2 : '',
            q3 : '',
            q4 : '',
            q5 : '',
            q6 : [],
            errors : []
        }
    },
    created () {
        this.getUser();
    },
    methods: {
        getUser(){
            axios.get('/get-user-survey-culture')
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
        saveSurveyCulture() {
            let v = this;
            var r = confirm("Are you sure you want to save this survey?");
            if (r == true) {
                if(v.user){
                    axios.post(`/save-survey-culture`, {
                        user_id: v.user.user_id,
                        name: v.user.first_name + ' ' + v.user.last_name,
                        position: v.position,
                        department: v.department,
                        company: v.company,
                        location: v.location,
                        q1: v.q1.length > 0 ? JSON.stringify(v.q1) : "",
                        q2: v.q2,
                        q3: v.q3,
                        q4: v.q4,
                        q5: v.q5,
                        q6: v.q6.length > 0 ? JSON.stringify(v.q6) : "",
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
                    });
                }else{
                    window.location.href = "http://10.96.4.70/login";
                }
            }
        }
    },
}
</script>
