<template>
<div>
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
        <!-- Mask -->
        <span class="mask bg-gradient-primary opacity-7"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white text-uppercase"><i class="fas fa-holly-berry text-green"></i> SURVEY : DECEMBER 2020 VALUES QUESTIONNAIRE</h1>
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
                                    :data   = "allSurveyCulture"
                                    :fields = "json_fields"
                                    class   = "btn btn-md btn-default mt-3 ml-3 mr-3"
                                    name    = "SURVEY : DECEMBER 2020 VALUES QUESTIONNAIRE.xls">
                                        Export to excel ({{allSurveyCulture.length}})
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
                allSurveyCulture: [],
                json_fields: {
                    'Name' : 'name',
                    'Position' : 'position',
                    'Department' : 'department',
                    'Company' : 'company',
                    'Location' : 'location',
                    'q1' : 'q1',
                    'q2' : 'q2',
                    'q3' : 'q3',
                    'q4' : 'q4',
                    'q5' : 'q5',
                    'q6' : 'q6',
                    'date' : 'created_at',
                }
            }
        },
        created () {
            this.getSurveyCulter();
        },
        methods: {
            getSurveyCulter() {
                axios.get('/all-survey-culture')
                .then(response => { 
                    this.allSurveyCulture = response.data;
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