<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-success opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white text-uppercase">SURVEY : EXIT INTERVIEW FORM REPORTS</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt--7 mb-6 mx-auto">
        <div class="row justify-content-center">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="col-md-12 text-center">
                            <img src="/img/lfuggoc_logo.png" style="width:150px;height:auto;">
                        </div>
                        <hr>
                        <div class="row align-items-center">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">From</label>
                                    <input type="date" class="form-control" v-model="from">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">To</label>
                                    <input type="date" class="form-control" v-model="to">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-md btn-primary ml-3" @click="applyFilter">Apply Filter</button>
                                <download-excel
                                    :data   = "allSurveys"
                                    :fields = "json_fields"
                                    class   = "btn btn-md btn-default"
                                    name    = "SURVEY EXIT INTERVIEW FORM REPORTS.xls">
                                        Export to excel ({{allSurveys.length}})
                                </download-excel>
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
    import JsonExcel from 'vue-json-excel'
    export default {
        components: {
            'downloadExcel': JsonExcel
        },
        data() {
            return {
                allSurveys: [],
                from: '',
                to: '',
                json_fields: {
                    'Name' : 'name',
                    'Position' : 'position',
                    'department' : 'department',
                    'Company' : 'company',
                    'Location' : 'location',
                    'No of Months Worked' : 'no_of_months_worked',
                    'Age' : 'age',
                    'Primary reason for leaving' : 'reason_for_leaving',
                    'If primary reason is others, please indicate reason' : 'reason_for_leaving_others',
                    'If primary reason is work, please indicate main consideration' : 'reason_is_work',
                    'If primary reason is others, please indicate reason' : 'reason_is_work_others',
                    
                    //R1
                    'My immediate superior regularly gives me feedback and coaching and does the following:' : {
                        callback: (value) => {
                            return '';
                        }
                    },
                    'a. Meets team regularly' : 'r1_a',
                    'a. comments r1' : 'r1_a_comments',
                    'b. Discusses performance evaluation through one-on-one discussion and feedback' : 'r1_b',
                    'b. comments r1' : 'r1_b_comments',
                    'c. Helps employee develop a passion for work' : 'r1_c',
                    'c. comments r1' : 'r1_c_comments',
                    'd. Communicates reasons for changes and decisions' : 'r1_d',
                    'd. comments r1' : 'r1_d_comments',
                    //R2
                    'My team works well together and promotes a harmonious and positive working environment for me which can be observed through the following:' : {
                        callback: (value) => {
                            return '';
                        }
                    },
                    'a. Staff are treated with dignity and respect' : 'r2_a',
                    'a. comments r2' : 'r2_a_comments',
                    'b. Conflicts and issues among the team are being addressed.' : 'r2_b',
                    'b. comments r2' : 'r2_b_comments',
                    'c. Each team member has trust and constant collaboration.' : 'r2_c',
                    'c. comments r2' : 'r2_c_comments',
                    //R3
                    'My job responsibilities and performance expectations are clear.' : {
                        callback: (value) => {
                            return '';
                        }
                    },
                    'a. Description of the job is accurate' : 'r3_a',
                    'a. comments r3' : 'r3_a_comments',
                    'b. Goals and targets of the role were made clear throughout my employment' : 'r3_b',
                    'b. comments r3' : 'r3_b_comments',
                    'c. I received appropriate support to do my job' : 'r3_c',
                    'c. comments r3' : 'r3_c_comments',
                    //R4
                    'The company provides opportunities for career growth and advancement.' : {
                        callback: (value) => {
                            return '';
                        }
                    },
                    'a. Career path was identified and defined for my role' : 'r4_a',
                    'a. comments r4' : 'r4_a_comments',
                    'b. My skills and willingness to contibute was recognized' : 'r4_b',
                    'b. comments r4' : 'r4_b_comments',
                    'c. I see opportunies for development and promotion within the organization' : 'r4_c',
                    'c. comments r4' : 'r4_c_comments',
                    //R5
                    'The company provides trainings and other opportunities for learning and development.' : {
                        callback: (value) => {
                            return '';
                        }
                    },
                    'a. I received adequate training that enable me to do my job' : 'r5_a',
                    'a. comments r5' : 'r5_a_comments',
                    'b. I see further training opportunities  that the company is offering ' : 'r5_b',
                    'b. comments r5' : 'r5_b_comments',
                    //R6
                    'The companys systems and processes are clear and efficient.' : {
                        callback: (value) => {
                            return '';
                        }
                    },
                    'a. HR policies and processes' : 'r6_a',
                    'a. comments r6' : 'r6_a_comments',
                    'b. Finance policies and processes' : 'r6_b',
                    'b. comments r6' : 'r6_b_comments',
                    'c. Own department policies and processes' : 'r6_c',
                    'c. comments r6' : 'r6_c_comments',
                    'd. Other departments policies and processes' : 'r6_d',
                    'd. comments r6' : 'r6_d_comments',
                    //R7
                    'The company provides a competitive compensation and benefits package.' : {
                        callback: (value) => {
                            return '';
                        }
                    },
                    'a. Salaries & Wages' : 'r7_a',
                    'a. comments r7' : 'r7_a_comments',
                    'b. Health Insurance' : 'r7_b',
                    'b. comments r7' : 'r7_b_comments',
                    'c. Rest Days, Paid Vacation & Sick Leave' : 'r7_c',
                    'c. comments r7' : 'r7_c_comments',
                    'd. Other benefits and bonuses' : 'r7_d',
                    'd. comments r7' : 'r7_d_comments',
                    //R8
                    'The companys culture is generally positive.' : {
                        callback: (value) => {
                            return '';
                        }
                    },
                    'a. The company supports, respects and values my effort' : 'r8_a',
                    'a. comments r8' : 'r8_a_comments',
                    'b. People are approachable and easy to work with.' : 'r8_b',
                    'b. comments r8' : 'r8_b_comments',
                    'c. Generally has a good working environment.' : 'r8_c',
                    'c. comments r8' : 'r8_c_comments',
                    //R9
                    'The companys leaders display good leadership qualities and expertise in their field.' : {
                        callback: (value) => {
                            return '';
                        }
                    },
                    "a. I'am comfortable talking to my manager" : 'r9_a',
                    "a. comments r9" : 'r9_a_comments',
                    'b. My immediate superior delegated work and responsibility consistent with my ability' : 'r9_b',
                    'b. comments r9' : 'r9_b_comments',
                    'c. Leaders have a good understanding of the business.' : 'r9_c',
                    'c. comments r9' : 'r9_c_comments',
                    'd. My immediate superior shows our core values of Integrity, Loyalty and Excellence' : 'r9_d',
                    'd. comments r9' : 'r9_d_comments',
                    //R10
                    "I am aware of and appreciate the company's engagement programs." : {
                        callback: (value) => {
                            return '';
                        }
                    },
                    "a. I regularly receive communications of new policies, guidelines and other company announcements." : 'r10_a',
                    "a. comments r10" : 'r10_a_comments',
                    'b. Employee activities are interesting and always keep employees excited.' : 'r10_b',
                    'b. comments r10' : 'r10_b_comments',
                    'c. I am aware of what is happening in the organization.' : 'r10_c',
                    'c. comments r10' : 'r10_c_comments',
                    //R11
                    "I would recommend this company to others exploring career opportunities." : {
                        callback: (value) => {
                            return '';
                        }
                    },
                    "a. I would recommend this company to others." : 'r11_a',
                    "a. comments r11" : 'r11_a_comments',
                    'b. I would consider returning to this company if there is an opportunity in the future.' : 'r11_b',
                    'b. comments r11' : 'r11_b_comments',
                    "Other suggestions for improvement:" : 'other_suggestions_for_improvement'
                }
            }
        },
        methods: {
            applyFilter() {
                let v = this;
                v.allSurveys = [];
                axios.get('/all-survey-exit-interview?from='+v.from+'&to='+v.to)
                .then(response => { 
                    v.allSurveys = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                });
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>