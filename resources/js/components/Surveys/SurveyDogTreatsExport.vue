<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-primary opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white text-uppercase"> SURVEY : NOVEMBER 2021 VALUES QUESTIONNAIRE</h1>
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
                                    :data   = "allSurveyDogTreats"
                                    :fields = "json_fields"
                                    class   = "btn btn-md btn-default mt-3 ml-3 mr-3"
                                    name    = "SURVEY : DOG TREATS.xls">
                                        Export to excel ({{allSurveyDogTreats.length}})
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
                allSurveyDogTreats: [],
                json_fields: {
                    // 'Employee Number' : 'employee_number',
                    'Name' : 'name',
                    'Cluster' : 'cluster',
                    'Company' : 'company',
                    'Business Unit' : 'business_unit',
                    'Department' : 'department',
                    'Position' : 'position',
                    'Region' : 'region',
                    'Location' : 'location',
                    // 'Hired Date' : 'hired_date',
                    'Date Answered' : 'date_answered',
                    'Time Answered' : 'time_answered',
                    'Q1' : 'q1',
                    'Q2' : 'q2'
                }
            }
        },
        created () {
            this.getSurvey();
        },
        methods: {
            getSurvey() {
                axios.get('/all-survey-dog-treats')
                .then(response => { 
                    this.allSurveyDogTreats = response.data;
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