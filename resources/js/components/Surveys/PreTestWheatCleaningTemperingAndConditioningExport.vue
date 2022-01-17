<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-primary opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white text-uppercase"> Pre - Test Wheat Cleaning, Tempering and Conditioning</h1>
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
                            <div class="col-12">
                               <download-excel
                                    :data   = "allPreTestWheatCleaningTemperingAndConditioning"
                                    :fields = "json_fields"
                                    class   = "btn btn-md btn-default mt-3 ml-3 mr-3"
                                    name    = "Pre - Test Wheat Cleaning, Tempering and Conditioning.xls">
                                        Export to excel ({{allPreTestWheatCleaningTemperingAndConditioning.length}})
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
                allPreTestWheatCleaningTemperingAndConditioning: [],
                json_fields: {
                    'Employee Number' : 'employee_number',
                    'Name' : 'name',
                    'Cluster' : 'cluster',
                    'Company' : 'company',
                    'Business Unit' : 'business_unit',
                    'Department' : 'department',
                    'Position' : 'position',
                    'Region' : 'region',
                    'Location' : 'location',
                    'Hired Date' : 'hired_date',
                    'Date Answered' : 'date_answered',
                    'Time Answered' : 'time_answered',
                    'Q1' : 'q1',
                    'Q2' : 'q2',
                    'Q3' : 'q3',
                    'Q4' : 'q4',
                    'Q5' : 'q5',
                    'Q6' : 'q6',
                    'Q7' : 'q7',
                    'Q8' : 'q8',
                    'Q9' : 'q9',
                    'Q10' : 'q10',
                    'Q11' : 'q11',
                    'Q12' : 'q12',
                    'Q13' : 'q13',
                    'Q14' : 'q14',
                    'Q15' : 'q15',
                    'Q16' : 'q16',
                    'Q17' : 'q17',
                    'Q18' : 'q18',
                    'Q19' : 'q19',
                    'Q20' : 'q20',
                    'Q21' : 'q21',
                    'Q22' : 'q22',
                    'Q23' : 'q23',
                    'Q24_a' : 'q24_a',
                    'Q24_b' : 'q24_b',
                    'Q25' : 'q25',
                    'Q26_a' : 'q26_a',
                    'Q26_b' : 'q26_b',
                    'Q26_c' : 'q26_c',
                    'Q26_d' : 'q26_d',
                    'Q26_e' : 'q26_e',
                    'Q26_f' : 'q26_f',
                    'Q26_g' : 'q26_g',
                    'Q26_h' : 'q26_h',
                    'Q26_i' : 'q26_i',
                    'Q26_j' : 'q26_j',
                    'Q27' : 'q27',
                    'Q28' : 'q28',
                    'Q29' : 'q29',
                    'Q30' : 'q30',
                    'Q31' : 'q31',
                }
            }
        },
        created () {
            this.getPreTestWheatCleaningTemperingAndConditioning();
        },
        methods: {
            getPreTestWheatCleaningTemperingAndConditioning() {
                axios.get('/all-pre-test-wheat-cleaning-tempering-and-conditioning')
                .then(response => { 
                    this.allPreTestWheatCleaningTemperingAndConditioning = response.data;
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