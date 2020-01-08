<template>
<div>
    <loader v-if="loading"></loader>
    <div class="header bg-gradient-success pb-8 pt-5 pt-md-8 container-list"></div>
        <div class="container-fluid mt--9">
            <div class="header-body">
                <div class="row">
                    <div class="col-xl-12 col-lg-6">
                         <div class="card shadow">
                                <div class="card-header border-0">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="mb-0">Activities</h3>
                                            <small class="text-muted">List of all user logs</small>
                                        </div> 
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-xl-4 mb-2 mt-3 float-right">
                                            <input type="text" name="keyword" class="form-control" placeholder="Search" autocomplete="off" v-model="keywords" id="keyword">
                                        </div> 
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <!-- Users table -->
                                    <table class="table align-items-center table-flush">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Event</th>
                                                <th scope="col">User Name</th>
                                                <th scope="col">Model</th>
                                                <th scope="col">ID</th>
                                                <th scope="col">IP Address</th>
                                                <th scope="col">Log Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <tr v-for="(activities, u) in filteredQueues" v-bind:key="u">
                                                <td>{{ activities.event }}</td>
                                                <td>{{ activities.user.name }}</td>
                                                <td>{{ activities.auditable_type }}</td> 
                                                <td>{{ activities.auditable_id }}</td>  
                                                <td>{{ activities.ip_address }}</td>  
                                                <td>{{ activities.created_at }}</td>  
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mb-3 mt-3 ml-1" v-if="filteredQueues.length ">
                                    <div class="col-6">
                                        <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                                            <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                                        <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                                    </div>
                                    <div class="col-6 text-right">
                                        <span class="mr-2">Filtered Activities : {{ filteredQueues.length }} </span><br>
                                        <span class="mr-2">Total Activities : {{ activities.length }}</span>
                                    </div>
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
    import loader from './Loader'
    import Swal from 'sweetalert2'
    export default {
         components: {
            loader
        },
        data(){
            return {
                activities: [],
                errors: [],
                currentPage: 0,
                itemsPerPage: 50,
                keywords: "",
                loading: false,
            }
        },
        created(){
            this.fetchActivities();
        },
        methods:{
            fetchUsername(user_id){
                axios.get('/activities-username/' + user_id)
                .then(response => { 
                    return response.data.name;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            fetchActivities(){
                axios.get('/activities-all')
                .then(response => {
                    this.activities = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            setPage(pageNumber) {
                this.currentPage = pageNumber;
            },
            resetStartRow() {
                this.currentPage = 0;
            },

            showPreviousLink() {
                return this.currentPage == 0 ? false : true;
            },

            showNextLink() {
                return this.currentPage == (this.totalPages - 1) ? false : true;
            }  
        },
        computed:{
            filteredActivities(){
                let self = this;
                return Object.values(self.activities).filter(activity => {
                    return activity.event.toLowerCase().includes(this.keywords.toLowerCase())
                });
            },
            totalPages() {
                return Math.ceil(Object.values(this.activities).length / this.itemsPerPage)
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredActivities.slice(index, index + this.itemsPerPage);

                if(this.currentPage >= this.totalPages) {
                    this.currentPage = this.totalPages - 1
                }

                if(this.currentPage == -1) {
                    this.currentPage = 0;
                }

                return queues_array;
            },
        }
    }
</script>