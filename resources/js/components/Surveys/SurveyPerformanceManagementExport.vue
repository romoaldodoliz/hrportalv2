<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-primary opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white text-uppercase"> SURVEY : Performance Management</h1>
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
                                    :data   = "allSurveyPerformanceManagement"
                                    :fields = "json_fields"
                                    class   = "btn btn-md btn-default mt-3 ml-3 mr-3"
                                    name    = "SURVEY : PERFORMANCE MANAGEMENT.xls">
                                        Export to excel ({{allSurveyPerformanceManagement.length}})
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
                allSurveyPerformanceManagement: [],
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
                    'T1' : 't1',
                    'T2' : 't2',
                    'T3' : 't3',
                    'T4' : 't4',
                    'T5' : 't5',
                    'P1' : 'p1',
                    'P2' : 'p2',
                    'P3' : 'p3',
                    'P4' : 'p4',
                    'P5' : 'p5',
                    'WHAT DO YOU APPRECIATE ABOUT THE PERFORMANCE MANAGEMENT PROCESS AND SYSTEM IN LA FIL?' : 'c1',
                    'HOW CAN WE IMPROVE THE PERFORMANCE MANAGEMENT PROCESS AND SYSTEM IN LA FIL?' : 'c2',
                }
            }
        },
        created () {
            this.getSurvey();
        },
        methods: {
            getSurvey() {
                axios.get('/all-survey-performance-management')
                .then(response => { 
                    this.allSurveyPerformanceManagement = response.data;
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