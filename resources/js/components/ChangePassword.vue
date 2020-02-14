<template>
    <div>
            <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 300px; background-image: url(/img/bg.jpg); background-size: cover; background-position: center bottom;">
                <!-- Mask -->
                <span class="mask bg-gradient-success opacity-7"></span>
                <!-- Header container -->
                <div class="container-fluid d-flex align-items-center">
                    <div class="row">
                        <div class="col-lg-9 col-md-9">
                            <h1 class="display-2 text-white text-uppercase">Hello, {{ employee.first_name }}</h1>
                            <p class="text-white mt-0 mb-5"> This is your profile page. You can see the progress you've made with your account and manage your personal information.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt--7">
                <div class="row">
                    <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                        <div class="card card-profile shadow">
                            <div class="row justify-content-center">
                                <div class="col-lg-3 order-lg-2">
                                    <div class="card-profile-image">
                                    <a href="#">
                                        <img :src="profile_image" @error="profileImageLoadError()" class="rounded-circle">
                                    </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                                <div class="d-flex justify-content-right">
                                    <a href="#" class="btn btn-sm btn-info float-right">{{ employee.status}}</a>
                                </div>
                            </div>

                            <div class="card-body pt-0 pt-md-4">
                                <div class="row">
                                    <div class="col-md-12 text-center mt-3">
                                        <h3>{{ employee.first_name + " " +  employee.last_name}}<span class="font-weight-light"></span>
                                        </h3>
                                            <div class="h5 font-weight-300">
                                                {{ employee.position}}
                                            </div>
                                            <div class="h5 mt-4">
                                            {{ employee.departments ? employee.departments[0].name : ""}}
                                            </div>
                                            <div>
                                            {{ employee.companies ? employee.companies[0].name : ""}}
                                            <div class="h5 font-weight-300">
                                                <i class="ni location_pin"></i>{{ employee.division}}
                                            </div>
                                            </div>
                                            <hr class="my-4">
                                            <div class="row justify-content-center mb-2 mt-2">
                                                <img :src="signature_image" @error="signatureImageLoadError()" style="width:250px;height:auto;border-radius:6px;border:1px solid #8898aa;">
                                                <div class="col-md-12 h5 font-weight-300">
                                                    Signature   
                                                </div>
                                            </div>
                                        
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-8 order-xl-1">
                        <div class="card bg-secondary">
                            <div class="card-header bg-white border-0">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h3 class="mb-0 text-uppercase">Change Password</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body bg-white mb-5">
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="role">New password*</label> 
                                            <input type="password"  class="form-control" v-model="user.new_password">
                                            <span class="text-danger" v-if="errors.new_password">{{ errors.new_password[0] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="role">Confirm password*</label> 
                                            <input type="password" class="form-control" v-model="user.new_password_confirmation">
                                            <span class="text-danger" v-if="errors.new_password_confirmation">{{ errors.new_password_confirmation[0] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                   <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="changePassword(user)" >Save</button>
                                </div>
                            </div>
                        </div>

                     
                    </div>
                </div>
            </div>
    </div>
</template>
<script>
    import Swal from 'sweetalert2'
    export default {
        data(){
            return {
                employee: [],
                user: [],
                errors: [],
                profile_image:'',
                signature_image:'',
            }
        },
        created(){
            this.fetchEmployee();
        },
        methods:{
            profileImageLoadError(){
                this.profile_image = 'storage/default.png';
            },
            signatureImageLoadError(){
                this.signature_image = 'storage/image_not_available.png';
            },
            resetForm(){
                this.errors = [];
                this.user = [];
            },
            changePassword(user){
                axios.post('/change-password', {
                    user_id: this.employee.user_id,
                    new_password: user.new_password,
                    new_password_confirmation: user.new_password_confirmation
                })
                .then(response => {
                    $('#changePasswordModal').modal('hide');
                    this.resetForm();
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your Password has been Updated Successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    console.log(this.errors);
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Unable to save user. Please try again.',
                        icon: 'warning',
                        confirmButtonText: 'Okay'
                    })
                })
            },
            fetchEmployee(){
                axios.get('/user-profile')
                .then(response => { 
                    this.employee = response.data;
                    var num = Math.random();
                    this.profile_image = 'storage/id_image/employee_image/' + this.employee.id + '.png?v='+num;
                    this.signature_image = 'storage/id_image/employee_signature/' + this.employee.id + '.png?v='+num;
                })
                .catch(error => { 
                    this.errors = error;
                })
            },
        }
    }
</script>