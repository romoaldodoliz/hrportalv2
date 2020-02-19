<template>
<div>
    <loader v-if="loading"></loader>
    <div class="header bg-gradient-success pb-8 pt-5 pt-md-8 container-list"></div>
    <div class="container-fluid mt--9">
        <div class="header-body">
            <div class="row">
                <div class="col-xl-12 col-lg-6">
                    <div class="nav-wrapper">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-sm-2 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="fas fa-building mr-2"></i>COMPANIES</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-2 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>DEPARTMENTS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-2 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false"><i class="fas fa-map-marked-alt mr-2"></i>LOCATIONS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-2 mb-md-0" id="tabs-icons-text-4-tab" data-toggle="tab" href="#tabs-icons-text-4" role="tab" aria-controls="tabs-icons-text-4" aria-selected="false"><i class="fas fa-map-marker-alt mr-2"></i>ADDRESSES</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-2 mb-md-0" id="tabs-icons-text-5-tab" data-toggle="tab" href="#tabs-icons-text-5" role="tab" aria-controls="tabs-icons-text-5" aria-selected="false"><i class="fas fa-address-card mr-2"></i>HEADS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-2 mb-md-0" id="tabs-icons-text-6-tab" data-toggle="tab" href="#tabs-icons-text-6" role="tab" aria-controls="tabs-icons-text-6" aria-selected="false"><i class="fas fa-layer-group mr-2"></i>LEVELS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-2 mb-md-0" id="tabs-icons-text-7-tab" data-toggle="tab" href="#tabs-icons-text-7" role="tab" aria-controls="tabs-icons-text-7" aria-selected="false"><i class="fas fa-id-card-alt mr-2"></i> <span style="font-size:12px;">MARITALS STATUS</span> </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <!-- COMPANIES -->
                                <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                    <h4> COMPANIES</h4>
                                    <div class="col text-right">
                                        <a href="javascript.void(0)" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addCompanyModal" @click="companyresetForm()">Add Company</a>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-xl-4 mb-2 mt-3 float-right">
                                            <input type="text" name="company" class="form-control" placeholder="Search" autocomplete="off" v-model="company_keywords" id="company_name">
                                        </div>  
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th scope="col"></th>
                                                    <th scope="col">Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(company, u) in companyfilteredQueues" v-bind:key="u">
                                                    <td width="5%">
                                                        <div class="dropdown">
                                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#editCompanyModal" style="cursor: pointer" @click="companycopyObject(company)">Edit</a>
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteCompanyModal" style="cursor: pointer" @click="deleteCompany(company)">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="avatar avatar-sm rounded-circle"><img id="image_profile" alt="Image placeholder" :src="'/storage/id_image/company/' + company.id + '.png'"></span></td>
                                                    <td>{{ company.name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row mb-3 mt-3 ml-1" v-if="companyfilteredQueues.length ">
                                        <div class="col-6">
                                            <button :disabled="!companieshowPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="companiessetPage(companycurrentPage - 1)"> Previous </button>
                                                <span class="text-dark">Page {{ companycurrentPage + 1 }} of {{ companytotalPages }}</span>
                                            <button :disabled="!companieshowNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="companiessetPage(companycurrentPage + 1)"> Next </button>
                                        </div>
                                        <div class="col-6 text-right">
                                            <span class="mr-2">Filtered Companies : {{ companyfilteredQueues.length }} </span><br>
                                            <span class="mr-2">Total Companies : {{ companies.length }}</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- DEPARTMENTS -->
                                <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    DEPARTMENTS
                                    <div class="col text-right">
                                        <a href="javascript.void(0)" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addDepartmentModal" @click="departmentresetForm()">Add Department</a>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-xl-4 mb-2 mt-3 float-right">
                                            <input type="text" name="department" class="form-control" placeholder="Search" autocomplete="off" v-model="department_keywords" id="department_name">
                                        </div>  
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th scope="col">Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(department, u) in departmentfilteredQueues" v-bind:key="u">
                                                    <td width="5%">
                                                        <div class="dropdown">
                                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#editDepartmentModal" style="cursor: pointer" @click="departmentcopyObject(department)">Edit</a>
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteDepartmentModal" style="cursor: pointer" @click="deleteDepartment(department)">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ department.name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row mb-3 mt-3 ml-1" v-if="departmentfilteredQueues.length ">
                                        <div class="col-6">
                                            <button :disabled="!departmentsshowPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="departmentssetPage(departmentcurrentPage - 1)"> Previous </button>
                                                <span class="text-dark">Page {{ departmentcurrentPage + 1 }} of {{ departmenttotalPages }}</span>
                                            <button :disabled="!departmentsshowNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="departmentssetPage(departmentcurrentPage + 1)"> Next </button>
                                        </div>
                                        <div class="col-6 text-right">
                                            <span class="mr-2">Filtered Departments : {{ departmentfilteredQueues.length }} </span><br>
                                            <span class="mr-2">Total Departments : {{ departments.length }}</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- LOCATIONS -->
                                <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                                    LOCATIONS
                                    <div class="col text-right">
                                        <a href="javascript.void(0)" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addLocationModal" @click="locationresetForm()">Add Location</a>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-xl-4 mb-2 mt-3 float-right">
                                            <input type="text" name="location" class="form-control" placeholder="Search" autocomplete="off" v-model="location_keywords" id="location_name">
                                        </div>  
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th scope="col">Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(location, u) in locationfilteredQueues" v-bind:key="u">
                                                    <td width="5%">
                                                        <div class="dropdown">
                                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#editLocationModal" style="cursor: pointer" @click="locationcopyObject(location)">Edit</a>
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteLocationModal" style="cursor: pointer" @click="deleteLocation(location)">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ location.name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row mb-3 mt-3 ml-1" v-if="locationfilteredQueues.length ">
                                        <div class="col-6">
                                            <button :disabled="!locationsshowPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="locationssetPage(locationcurrentPage - 1)"> Previous </button>
                                                <span class="text-dark">Page {{ locationcurrentPage + 1 }} of {{ locationtotalPages }}</span>
                                            <button :disabled="!locationsshowNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="locationssetPage(locationcurrentPage + 1)"> Next </button>
                                        </div>
                                        <div class="col-6 text-right">
                                            <span class="mr-2">Filtered Locations : {{ locationfilteredQueues.length }} </span><br>
                                            <span class="mr-2">Total Locations : {{ locations.length }}</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- ADDRESSES -->
                                <div class="tab-pane fade" id="tabs-icons-text-4" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab">
                                    ADDRESSES
                                    <div class="col text-right">
                                        <a href="javascript.void(0)" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addAddressModal" @click="addressresetForm()">Add Address</a>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-xl-4 mb-2 mt-3 float-right">
                                            <input type="text" name="address" class="form-control" placeholder="Search" autocomplete="off" v-model="address_keywords" id="address_name">
                                        </div>  
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th scope="col">Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(address, u) in addressfilteredQueues" v-bind:key="u">
                                                    <td width="5%">
                                                        <div class="dropdown">
                                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#editAddressModal" style="cursor: pointer" @click="addresscopyObject(address)">Edit</a>
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteAddressModal" style="cursor: pointer" @click="deleteAddress(address)">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ address.name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row mb-3 mt-3 ml-1" v-if="addressfilteredQueues.length ">
                                        <div class="col-6">
                                            <button :disabled="!addressesshowPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="addressessetPage(addresscurrentPage - 1)"> Previous </button>
                                                <span class="text-dark">Page {{ addresscurrentPage + 1 }} of {{ addresstotalPages }}</span>
                                            <button :disabled="!addressesshowNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="addressessetPage(addresscurrentPage + 1)"> Next </button>
                                        </div>
                                        <div class="col-6 text-right">
                                            <span class="mr-2">Filtered Addresses : {{ addressfilteredQueues.length }} </span><br>
                                            <span class="mr-2">Total Addresses : {{ addresses.length }}</span>
                                        </div>
                                    </div>


                                </div>
                                <!-- HEAD POSITIONS -->
                                <div class="tab-pane fade" id="tabs-icons-text-5" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab">
                                    HEAD POSITIONS
                                    <div class="col text-right">
                                        <a href="javascript.void(0)" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addHeadModal" @click="headresetForm()">Add Head Position</a>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-xl-4 mb-2 mt-3 float-right">
                                            <input type="text" name="head" class="form-control" placeholder="Search" autocomplete="off" v-model="head_keywords" id="head_name">
                                        </div>  
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th scope="col">Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(head, u) in headfilteredQueues" v-bind:key="u">
                                                    <td width="5%">
                                                        <div class="dropdown">
                                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#editHeadModal" style="cursor: pointer" @click="headcopyObject(head)">Edit</a>
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteHeadModal" style="cursor: pointer" @click="deleteHead(head)">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ head.name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row mb-3 mt-3 ml-1" v-if="headfilteredQueues.length ">
                                        <div class="col-6">
                                            <button :disabled="!headsshowPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="headssetPage(headcurrentPage - 1)"> Previous </button>
                                                <span class="text-dark">Page {{ headcurrentPage + 1 }} of {{ headtotalPages }}</span>
                                            <button :disabled="!headsshowNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="headssetPage(headcurrentPage + 1)"> Next </button>
                                        </div>
                                        <div class="col-6 text-right">
                                            <span class="mr-2">Filtered Heads : {{ headfilteredQueues.length }} </span><br>
                                            <span class="mr-2">Total Heads : {{ heads.length }}</span>
                                        </div>
                                    </div>

                                </div>
                                <!-- LEVELS -->
                                <div class="tab-pane fade" id="tabs-icons-text-6" role="tabpanel" aria-labelledby="tabs-icons-text-6-tab">
                                    LEVELS
                                    <div class="col text-right">
                                        <a href="javascript.void(0)" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addLevelModal" @click="levelresetForm()">Add Level</a>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-xl-4 mb-2 mt-3 float-right">
                                            <input type="text" name="level" class="form-control" placeholder="Search" autocomplete="off" v-model="level_keywords" id="level_name">
                                        </div>  
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th scope="col">Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(level, u) in levelfilteredQueues" v-bind:key="u">
                                                    <td width="5%">
                                                        <div class="dropdown">
                                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#editLevelModal" style="cursor: pointer" @click="levelcopyObject(level)">Edit</a>
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteLevelModal" style="cursor: pointer" @click="deleteLevel(level)">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ level.name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row mb-3 mt-3 ml-1" v-if="levelfilteredQueues.length ">
                                        <div class="col-6">
                                            <button :disabled="!levelsshowPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="levelssetPage(levelcurrentPage - 1)"> Previous </button>
                                                <span class="text-dark">Page {{ levelcurrentPage + 1 }} of {{ leveltotalPages }}</span>
                                            <button :disabled="!levelsshowNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="levelssetPage(levelcurrentPage + 1)"> Next </button>
                                        </div>
                                        <div class="col-6 text-right">
                                            <span class="mr-2">Filtered Levels : {{ levelfilteredQueues.length }} </span><br>
                                            <span class="mr-2">Total Levels : {{ levels.length }}</span>
                                        </div>
                                    </div>

                                </div>

                                  <!-- MARITAL STATUS -->
                                <div class="tab-pane fade" id="tabs-icons-text-7" role="tabpanel" aria-labelledby="tabs-icons-text-7-tab">
                                    MARITAL STATUS
                                    <div class="col text-right">
                                        <a href="javascript.void(0)" class="btn btn-sm btn-success" data-toggle="modal" data-target="#addMaritalModal" @click="maritalresetForm()">Add Marital Status</a>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-xl-4 mb-2 mt-3 float-right">
                                            <input type="text" name="marital" class="form-control" placeholder="Search" autocomplete="off" v-model="marital_keywords" id="marital_name">
                                        </div>  
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th scope="col">Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(marital, u) in maritalfilteredQueues" v-bind:key="u">
                                                    <td width="5%">
                                                        <div class="dropdown">
                                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#editMaritalModal" style="cursor: pointer" @click="maritalcopyObject(marital)">Edit</a>
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#deleteMaritalModal" style="cursor: pointer" @click="deleteMarital(marital)">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ marital.name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row mb-3 mt-3 ml-1" v-if="maritalfilteredQueues.length ">
                                        <div class="col-6">
                                            <button :disabled="!maritalsshowPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="maritalssetPage(maritalcurrentPage - 1)"> Previous </button>
                                                <span class="text-dark">Page {{ maritalcurrentPage + 1 }} of {{ maritaltotalPages }}</span>
                                            <button :disabled="!maritalsshowNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="maritalssetPage(maritalcurrentPage + 1)"> Next </button>
                                        </div>
                                        <div class="col-6 text-right">
                                            <span class="mr-2">Filtered Marital Status : {{ maritalfilteredQueues.length }} </span><br>
                                            <span class="mr-2">Total Marital Status : {{ maritals.length }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Company Modal -->
    <div class="modal fade" id="editCompanyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center" id="addCompanyLabel">Edit Company</h2>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" v-if="company_updated">
                        <strong>Success!</strong> Company succesfully updated
                    </div>
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Name*</label> 
                                <input type="text"  class="form-control" v-model="company_copied.name"  >
                                <span class="text-danger" v-if="companyerrors.name">{{ companyerrors.name[0] }}</span>
                            </div>
                        </div>
                        

                        <div class="col-md-12">
                            <h4>Company Divisions</h4>
                            <button type="button" class="btn btn-success btn-sm mb-2" style="float: right;" @click="fetchDivisions()"><i class="fas fa-redo" title="Refresh HMO Dependents"></i></button>
                            <button type="button" class="btn btn-primary btn-sm mb-2" style="float: right;" @click="addDivision()">Add Row</button>
                            <div class="table-responsive">
                                <table class="table table-hover" id="tab_company_division">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                Name
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(row,index) in company_divisions" v-bind:key="index">
                                            <td>
                                                <input type="text" class="form-control" v-model="row.name">     
                                            </td>
                                            <td width="5%">
                                                <button type="button" class="btn btn-danger btn-sm mt-2" style="float:right;" v-if="row.id" @click="removeDivision(index,row.id)">Remove</button>
                                                <button type="button" v-else class="btn btn-success btn-sm mt-2" style="float:right;" @click="removeDivision(index)">Remove</button>  
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>           
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Company Image</label> 
                                <input type="file" accept="image/*" id="company_image_file" class="form-control" ref="file" v-on:change="companyHandleFileUpload()"/>
                                <span class="text-danger" v-if="companyerrors.company_image">{{ companyerrors.company_image[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row justify-content-center mb-2">
                                <img :src="company_image" @error="companyImageLoadError()" style="width:250px;height:auto;border-radius:6px;border:2px dotted;">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button id="company_edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateCompany(company_copied)">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Company Modal -->
    <div class="modal fade" id="addCompanyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center" id="addCompanyLabel">Add Company</h2>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" v-if="company_added">
                        <strong>Success!</strong> Company succesfully added
                    </div>
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Name*</label> 
                                <input type="text"  class="form-control" v-model="company.name"  >
                                <span class="text-danger" v-if="companyerrors.name">{{ companyerrors.name[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="company_edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="storeCompany(company)">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Department Modal -->
    <div class="modal fade" id="editDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center" id="addDepartmentLabel">Edit Department</h2>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" v-if="department_updated">
                        <strong>Success!</strong> Department succesfully updated
                    </div>
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Name*</label> 
                                <input type="text"  class="form-control" v-model="department_copied.name"  >
                                <span class="text-danger" v-if="departmenterrors.name">{{ departmenterrors.name[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Color</label> 
                                <select class="form-control" v-model="department_copied.color" id="department-color">
                                    <option v-for="(department_color,b) in colors" v-bind:key="b" :value="department_color"> {{ department_color }}</option>
                                </select>
                                <span class="text-danger" v-if="departmenterrors.color">{{ departmenterrors.color[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="department_edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateDepartment(department_copied)">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Department Modal -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center" id="addDepartmentLabel">Add Department</h2>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" v-if="department_added">
                        <strong>Success!</strong> Department succesfully added
                    </div>
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Name*</label> 
                                <input type="text"  class="form-control" v-model="department.name"  >
                                <span class="text-danger" v-if="departmenterrors.name">{{ departmenterrors.name[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Color</label> 

                                <select class="form-control" v-model="department.color" id="department-color">
                                    <option v-for="(department_color,b) in colors" v-bind:key="b" :value="department_color"> {{ department_color }}</option>
                                </select>
                                
                                <span class="text-danger" v-if="departmenterrors.color">{{ departmenterrors.color[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="department_edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="storeDepartment(department)">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Location Modal -->
    <div class="modal fade" id="editLocationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center" id="addLocationLabel">Edit Location</h2>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" v-if="location_updated">
                        <strong>Success!</strong> Location succesfully updated
                    </div>
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Name*</label> 
                                <input type="text"  class="form-control" v-model="location_copied.name"  >
                                <span class="text-danger" v-if="locationerrors.name">{{ locationerrors.name[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Address</label> 
                                <select class="form-control" v-model="location_copied.address_id" id="based_truck-edit">
                                    <option v-for="(location_address,b) in addresses" v-bind:key="b" :value="location_address.id"> {{ location_address.name }}</option>
                                </select>
                                <span class="text-danger" v-if="locationerrors.address_id">{{ locationerrors.address_id[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="location_edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateLocation(location_copied)">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Location Modal -->
    <div class="modal fade" id="addLocationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center" id="addLocationLabel">Add Location</h2>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" v-if="location_added">
                        <strong>Success!</strong> Location succesfully added
                    </div>
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Name*</label> 
                                <input type="text"  class="form-control" v-model="location.name"  >
                                <span class="text-danger" v-if="locationerrors.name">{{ locationerrors.name[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Address</label> 
                                <select class="form-control" v-model="location.address_id" id="based_truck-edit">
                                    <option v-for="(location_address,b) in addresses" v-bind:key="b" :value="location_address.id"> {{ location_address.name }}</option>
                                </select>
                                <span class="text-danger" v-if="locationerrors.address_id">{{ locationerrors.address_id[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="location_edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="storeLocation(location)">Save</button>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Edit Address Modal -->
    <div class="modal fade" id="editAddressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center" id="addAddressLabel">Edit Address</h2>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" v-if="address_updated">
                        <strong>Success!</strong> Address succesfully updated
                    </div>
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Name*</label> 
                                <textarea  class="form-control" v-model="address_copied.name"  ></textarea>
                                <span class="text-danger" v-if="addresserrors.name">{{ addresserrors.name[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="address_edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateAddress(address_copied)">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Address Modal -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center" id="addAddressLabel">Add Address</h2>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" v-if="address_added">
                        <strong>Success!</strong> Address succesfully added
                    </div>
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Name*</label> 
                                <textarea class="form-control" v-model="address.name"  ></textarea >
                                <span class="text-danger" v-if="addresserrors.name">{{ addresserrors.name[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="address_edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="storeAddress(address)">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Head Position Modal -->
    <div class="modal fade" id="editHeadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center" id="addHeadLabel">Edit Head</h2>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" v-if="head_updated">
                        <strong>Success!</strong> Head Position succesfully updated
                    </div>
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Name*</label> 
                                <input type="text"  class="form-control" v-model="head_copied.name"  >
                                <span class="text-danger" v-if="headerrors.name">{{ headerrors.name[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="head_edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateHead(head_copied)">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Head Modal -->
    <div class="modal fade" id="addHeadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center" id="addHeadLabel">Add Head</h2>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" v-if="head_added">
                        <strong>Success!</strong> Head succesfully added
                    </div>
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Name*</label> 
                                <input type="text" class="form-control" v-model="head.name"  >
                                <span class="text-danger" v-if="headerrors.name">{{ headerrors.name[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="head_edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="storeHead(head)">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Level Modal -->
    <div class="modal fade" id="editLevelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center" id="addLevelLabel">Edit Level</h2>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" v-if="level_updated">
                        <strong>Success!</strong> Level succesfully updated
                    </div>
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Name*</label> 
                                <input type="text"  class="form-control" v-model="level_copied.name"  >
                                <span class="text-danger" v-if="levelerrors.name">{{ levelerrors.name[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="level_edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateLevel(level_copied)">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Level Modal -->
    <div class="modal fade" id="addLevelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center" id="addLevelLabel">Add Level</h2>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" v-if="level_added">
                        <strong>Success!</strong> Level succesfully added
                    </div>
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Name*</label> 
                                <input type="text" class="form-control" v-model="level.name"  >
                                <span class="text-danger" v-if="levelerrors.name">{{ levelerrors.name[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="level_edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="storeLevel(level)">Save</button>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Edit Marital Status Modal -->
    <div class="modal fade" id="editMaritalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center" id="addMaritalLabel">Edit Marital</h2>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" v-if="marital_updated">
                        <strong>Success!</strong> Marital succesfully updated
                    </div>
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Name*</label> 
                                <input type="text"  class="form-control" v-model="marital_copied.name"  >
                                <span class="text-danger" v-if="maritalerrors.name">{{ maritalerrors.name[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="marital_edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateMarital(marital_copied)">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Marital Status Modal -->
    <div class="modal fade" id="addMaritalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center" id="addMaritalLabel">Add Marital</h2>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" v-if="marital_added">
                        <strong>Success!</strong> Marital succesfully added
                    </div>
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role">Name*</label> 
                                <input type="text" class="form-control" v-model="marital.name"  >
                                <span class="text-danger" v-if="maritalerrors.name">{{ maritalerrors.name[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="marital_edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="storeMarital(marital)">Save</button>
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
                loading: false,
                //-----Company
                companyerrors: [],
                companies: [],
                company: [],
                company_copied: [],
                companycurrentPage: 0,
                companyitemsPerPage: 50,
                company_keywords: "",
                company_added: false,
                company_updated: false,
                company_id: '',
                company_image: '',
                company_image_file: '',
                company_divisions: [],
                deletedDivision : [],
                //-------Department
                departmenterrors: [],
                departments: [],
                department: [],
                department_copied: [],
                departmentcurrentPage: 0,
                departmentitemsPerPage: 50,
                department_keywords: "",
                department_added: false,
                department_updated: false,
                department_id: '',
                colors : ['green','grey','orange','red','violet','yellow'],
                //-------Location
                locationerrors: [],
                locations: [],
                location: [],
                location_copied: [],
                locationcurrentPage: 0,
                locationitemsPerPage: 50,
                location_keywords: "",
                location_added: false,
                location_updated: false,
                location_id: '',
                //-------Address
                addresserrors: [],
                addresses: [],
                address: [],
                address_copied: [],
                addresscurrentPage: 0,
                addressitemsPerPage: 50,
                address_keywords: "",
                address_added: false,
                address_updated: false,
                address_id: '',
                //-------Head
                headerrors: [],
                heads: [],
                head: [],
                head_copied: [],
                headcurrentPage: 0,
                headitemsPerPage: 50,
                head_keywords: "",
                head_added: false,
                head_updated: false,
                head_id: '',
                //-------Level
                levelerrors: [],
                levels: [],
                level: [],
                level_copied: [],
                levelcurrentPage: 0,
                levelitemsPerPage: 50,
                level_keywords: "",
                level_added: false,
                level_updated: false,
                level_id: '',
                //-------Marital Status
                maritalerrors: [],
                maritals: [],
                marital: [],
                marital_copied: [],
                maritalcurrentPage: 0,
                maritalitemsPerPage: 50,
                marital_keywords: "",
                marital_added: false,
                marital_updated: false,
                marital_id: '',
                
                
            }
        },
        created(){
            this.fetchCompanies();
            this.fetchDepartments();
            this.fetchLocations();
            this.fetchAddresses();
            this.fetchHeads();
            this.fetchLevels();
            this.fetchMaritals();
        },
        methods:{
            companyImageLoadError(){
                this.company_image = 'storage/image_not_available.png';
            },
            companyHandleFileUpload(){
                var company = document.getElementById("company_image_file");
                this.company_image = window.URL.createObjectURL(company.files[0]);
                this.company_image_file = company.files[0];
            },
            companyresetForm(){
                this.companyerrors = [];
                this.company = [];
                this.company_added = false;
            },
            fetchDivisions(){
                axios.get('/divisions-all/'+this.company_id)
                .then(response => { 
                    this.company_divisions = response.data;
                })
                .catch(error => { 
                    this.errors = error.response.data.error;
                })
            },
            addDivision(){
                this.company_divisions.push({
                    id: "",
                    name: "",
                });
            },
            removeDivision: function(index,id) {
                let division_id = id;
                if(division_id){
                    Swal.fire({
                        title: 'Are you sure you want to remove this division?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Remove'
                        }).then((result) => {
                        if (result.value) {

                            this.deletedDivision.push({
                                id: division_id
                            });
                            this.company_divisions.splice(index, 1);    
                        }
                    })
                }else{
                    this.company_divisions.splice(index, 1);
                } 
            },
            storeCompany(company){
                this.companyerrors = [];
               
                this.company_added = false;
                this.loading = true;
                
                axios.post(`/company`, {
                    name: company.name,
                    domain: company.domain,
                    _method: 'POST'
                })
                .then(response => {
                    this.company_added = true;
                    this.companies.unshift(response.data);
                    this.companyresetForm();   
                    this.loading = false;
                    $('#addCompanyModal').modal('hide');

                    Swal.fire({
                        title: 'Success!',
                        text: 'Company saved successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })

                })
                .catch(error => {
                    this.company_added = false;
                    this.companyerrors = error.response.data.errors;
                    this.loading = false;
                })
            },
            deleteCompany(company){
                var index = this.companies.findIndex(item => item.id == company.id);

                Swal.fire({
                    title: 'Are you sure you want to delete this company?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete'
                    }).then((result) => {
                    if (result.value) {

                        axios.delete(`/company/${company.id}`)
                        .then(response => {
                            $('#deleteCompanyModal').modal('hide');
                            this.companies.splice(index,1);

                            Swal.fire({
                                title: 'Success!',
                                text: 'Company deleted successfully.',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            })
                        })
                        .catch(error => {
                            this.companyerrors = error.response.data.companyerrors;
                        })   
                    }
                })

                
            },
            updateCompany(company_copied){
                this.companyerrors = [];
               
                this.edit_updated = false;
                this.loading = true;
                document.getElementById('company_edit_btn').disabled = true;
                var index = this.companies.findIndex(item => item.id == company_copied.id);

                let formData = new FormData();
                if(this.company_image_file){
                    formData.append('company_image', this.company_image_file);
                }
                formData.append('name', company_copied.name);
                formData.append('company_divisions', this.company_divisions ? JSON.stringify(this.company_divisions) : "");
                formData.append('deleted_divisions', this.deletedDivision ? JSON.stringify(this.deletedDivision) : "");
                formData.append('_method', 'PATCH');

                axios.post(`/company/${company_copied.id}`, 
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }  
                )
                .then(response => {
                    this.company_updated = true;
                    this.companies.splice(index,1,response.data);
                    document.getElementById('company_edit_btn').disabled = false;
                    this.loading = false;
                    $('#editCompanyModal').modal('hide');

                    Swal.fire({
                        title: 'Success!',
                        text: 'Company updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                    
                })
                .catch(error => {
                    this.company_updated = false;
                    this.companyerrors = error.response.data.errors;
                    document.getElementById('company_edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            companycopyObject(company){
                this.companyerrors = [];
                this.company_copied = Object.assign({}, company);
                this.company_id = company.id;
                this.company_added = false;
                this.company_updated = false;

                //Company Image 
                var num = Math.random();
                this.company_image = 'storage/id_image/company/' + company.id + '.png?v='+num;

                //Company Divisions
                this.fetchDivisions();

                //Reset Upload Image
                var company_image_file =  document.getElementById("company_image_file");
                company_image_file.value = '';
            },
           
            fetchCompanies(){
                axios.get('/companies-all')
                .then(response => { 
                    this.companies = response.data;
                })
                .catch(error => { 
                    this.companyerrors = error.response.data.error;
                })
            },
            companiessetPage(pageNumber) {
                this.companycurrentPage = pageNumber;
            },

            companyresetStartRow() {
                this.companycurrentPage = 0;
            },

            companieshowPreviousLink() {
                return this.companycurrentPage == 0 ? false : true;
            },

            companieshowNextLink() {
                return this.companycurrentPage == (this.companytotalPages - 1) ? false : true;
            },
            //----------Department
            departmentresetForm(){
                this.departmenterrors = [];
                this.department = [];
                this.department_added = false;
            },
            storeDepartment(department){
                this.departmenterrors = [];
               
                this.department_added = false;
                this.loading = true;
                
                axios.post(`/department`, {
                    name: department.name,
                    color: department.color,
                    _method: 'POST'
                })
                .then(response => {
                    this.department_added = true;
                    this.departments.unshift(response.data);
                    this.departmentresetForm();   
                    this.loading = false;
                    $('#addDepartmentModal').modal('hide');

                    Swal.fire({
                        title: 'Success!',
                        text: 'Department saved successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                })
                .catch(error => {
                    this.department_added = false;
                    this.departmenterrors = error.response.data.errors;
                    this.loading = false;
                })
            },
            deleteDepartment(department){
                var index = this.departments.findIndex(item => item.id == department.id);

                Swal.fire({
                    title: 'Are you sure you want to delete this department?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete'
                    }).then((result) => {
                    if (result.value) {

                        axios.delete(`/department/${department.id}`)
                        .then(response => {
                            $('#deleteDepartmentModal').modal('hide');
                            this.departments.splice(index,1);

                            Swal.fire({
                                title: 'Success!',
                                text: 'Department deleted successfully.',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            })
                        })
                        .catch(error => {
                            this.departmenterrors = error.response.data.errors;
                        })   
                    }
                })
            },
            updateDepartment(department_copied){
                this.departmenterrors = [];
               
                this.edit_updated = false;
                this.loading = true;
                document.getElementById('department_edit_btn').disabled = true;
                var index = this.departments.findIndex(item => item.id == department_copied.id);

                axios.post(`/department/${department_copied.id}`, {
                    name: department_copied.name,
                    color: department_copied.color,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.department_updated = true;
                    this.departments.splice(index,1,response.data);
                    document.getElementById('department_edit_btn').disabled = false;
                    this.loading = false;
                    $('#editDepartmentModal').modal('hide');

                    Swal.fire({
                        title: 'Success!',
                        text: 'Department updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                    
                })
                .catch(error => {
                    this.department_updated = false;
                    this.departmenterrors = error.response.data.errors;
                    document.getElementById('department_edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            departmentcopyObject(department){
                this.departmenterrors = [];
                this.department_copied = Object.assign({}, department);
                this.department_id = department.id;
                this.department_updated = false;
                this.department_added = false;
            },
           
            fetchDepartments(){

                axios.get('/departments-all')
                .then(response => { 
                    this.departments = response.data;
                })
                .catch(error => { 
                    this.departmenterrors = error.response.data.error;
                });
            },
            departmentssetPage(pageNumber) {
                this.departmentcurrentPage = pageNumber;
            },

            departmentresetStartRow() {
                this.departmentcurrentPage = 0;
            },

            departmentsshowPreviousLink() {
                return this.departmentcurrentPage == 0 ? false : true;
            },

            departmentsshowNextLink() {
                return this.departmentcurrentPage == (this.departmenttotalPages - 1) ? false : true;
            },
            //----------Location
            locationresetForm(){
                this.locationerrors = [];
                this.location = [];
                this.location_added = false;
            },
            storeLocation(location){
                this.locationerrors = [];
               
                this.location_added = false;
                this.loading = true;
                
                axios.post(`/location`, {
                    name: location.name,
                    address_id: location.address_id,
                    _method: 'POST'
                })
                .then(response => {
                    this.location_added = true;
                    this.locations.unshift(response.data);
                    this.locationresetForm();   
                    this.loading = false;
                    $('#addLocationModal').modal('hide');

                    Swal.fire({
                        title: 'Success!',
                        text: 'Location saved successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })

                })
                .catch(error => {
                    this.location_added = false;
                    this.locationerrors = error.response.data.errors;
                    this.loading = false;
                })
            },
            deleteLocation(location){
                var index = this.locations.findIndex(item => item.id == location.id);

                Swal.fire({
                    title: 'Are you sure you want to delete this location?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete'
                    }).then((result) => {
                    if (result.value) {

                        axios.delete(`/location/${location.id}`)
                        .then(response => {
                            $('#deleteLocationModal').modal('hide');
                            this.locations.splice(index,1);

                            Swal.fire({
                                title: 'Success!',
                                text: 'Location deleted successfully.',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            })
                        })
                        .catch(error => {
                            this.locationerrors = error.response.data.errors;
                        })   
                    }
                })
            },
            updateLocation(location_copied){
                this.locationerrors = [];
               
                this.edit_updated = false;
                this.loading = true;
                document.getElementById('location_edit_btn').disabled = true;
                var index = this.locations.findIndex(item => item.id == location_copied.id);

                axios.post(`/location/${location_copied.id}`, {
                    name: location_copied.name,
                    address_id: location_copied.address_id,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.location_updated = true;
                    this.locations.splice(index,1,response.data);
                    document.getElementById('location_edit_btn').disabled = false;
                    this.loading = false;
                    $('#editLocationModal').modal('hide');

                    Swal.fire({
                        title: 'Success!',
                        text: 'Location updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                    
                })
                .catch(error => {
                    this.location_updated = false;
                    this.locationerrors = error.response.data.errors;
                    document.getElementById('location_edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            locationcopyObject(location){
                this.locationerrors = [];
                this.location_copied = Object.assign({}, location);
                this.location_id = location.id;
                this.location_updated = false;
                this.location_added = false;
            },
           
            fetchLocations(){
                axios.get('/locations-all')
                .then(response => { 
                    this.locations = response.data;
                })
                .catch(error => { 
                    this.locationerrors = error.response.data.error;
                })
            },
            locationssetPage(pageNumber) {
                this.locationcurrentPage = pageNumber;
            },

            locationresetStartRow() {
                this.locationcurrentPage = 0;
            },

            locationsshowPreviousLink() {
                return this.locationcurrentPage == 0 ? false : true;
            },

            locationsshowNextLink() {
                return this.locationcurrentPage == (this.locationtotalPages - 1) ? false : true;
            },
            
            //----------Address
            addressresetForm(){
                this.addresserrors = [];
                this.address = [];
                this.address_added = false;
            },
            storeAddress(address){
                this.addresserrors = [];
               
                this.address_added = false;
                this.loading = true;
                
                axios.post(`/address`, {
                    name: address.name,
                    _method: 'POST'
                })
                .then(response => {
                    this.address_added = true;
                    this.addresses.unshift(response.data);
                    this.addressresetForm();   
                    this.loading = false;
                    $('#addAddressModal').modal('hide');

                    Swal.fire({
                        title: 'Success!',
                        text: 'Address saved successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                })
                .catch(error => {
                    this.address_added = false;
                    this.addresserrors = error.response.data.errors;
                    this.loading = false;
                })
            },
            deleteAddress(address){

                var index = this.addresses.findIndex(item => item.id == address.id);

                Swal.fire({
                    title: 'Are you sure you want to delete this address?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete'
                    }).then((result) => {
                    if (result.value) {

                        axios.delete(`/address/${address.id}`)
                        .then(response => {
                            $('#deleteAddressModal').modal('hide');
                            this.addresses.splice(index,1);

                            Swal.fire({
                                title: 'Success!',
                                text: 'Address deleted successfully.',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            })
                        })
                        .catch(error => {
                            this.addresserrors = error.response.data.errors;
                        })   
                    }
                })
            },
            updateAddress(address_copied){
                this.addresserrors = [];
               
                this.edit_updated = false;
                this.loading = true;
                document.getElementById('address_edit_btn').disabled = true;
                var index = this.addresses.findIndex(item => item.id == address_copied.id);

                axios.post(`/address/${address_copied.id}`, {
                    name: address_copied.name,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.address_updated = true;
                    this.addresses.splice(index,1,response.data);
                    document.getElementById('address_edit_btn').disabled = false;
                    this.loading = false;
                    $('#editAddressModal').modal('hide');

                    Swal.fire({
                        title: 'Success!',
                        text: 'Address updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                    
                })
                .catch(error => {
                    this.address_updated = false;
                    this.addresserrors = error.response.data.errors;
                    document.getElementById('address_edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            addresscopyObject(address){
                this.addresserrors = [];
                this.address_copied = Object.assign({}, address);
                this.address_id = address.id;
                this.address_updated = false;
                this.address_added = false;
            },
           
            fetchAddresses(){
                axios.get('/addresses-all')
                .then(response => { 
                    this.addresses = response.data;
                })
                .catch(error => { 
                    this.addresserrors = error.response.data.error;
                })
            },
            addressessetPage(pageNumber) {
                this.addresscurrentPage = pageNumber;
            },

            addressresetStartRow() {
                this.addresscurrentPage = 0;
            },

            addressesshowPreviousLink() {
                return this.addresscurrentPage == 0 ? false : true;
            },

            addressesshowNextLink() {
                return this.addresscurrentPage == (this.addresstotalPages - 1) ? false : true;
            },
            
            //----------Head Positions
            headresetForm(){
                this.headerrors = [];
                this.head = [];
                this.head_added = false;
            },
            storeHead(head){
                this.headerrors = [];
               
                this.head_added = false;
                this.loading = true;
                
                axios.post(`/head`, {
                    name: head.name,
                    _method: 'POST'
                })
                .then(response => {
                    this.head_added = true;
                    this.heads.unshift(response.data);
                    this.headresetForm();   
                    this.loading = false;
                    $('#addHeadModal').modal('hide');

                    Swal.fire({
                        title: 'Success!',
                        text: 'Approver saved successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                })
                .catch(error => {
                    this.head_added = false;
                    this.headerrors = error.response.data.errors;
                    this.loading = false;
                })
            },
            deleteHead(head){
                var index = this.heads.findIndex(item => item.id == head.id);

                Swal.fire({
                    title: 'Are you sure you want to delete this head?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete'
                    }).then((result) => {
                    if (result.value) {

                        axios.delete(`/head/${head.id}`)
                        .then(response => {
                            $('#deleteHeadModal').modal('hide');
                            this.heads.splice(index,1);

                            Swal.fire({
                                title: 'Success!',
                                text: 'Head deleted successfully.',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            })
                        })
                        .catch(error => {
                            this.headerrors = error.response.data.errors;
                        })   
                    }
                })

            },
            updateHead(head_copied){
                this.headerrors = [];
               
                this.edit_updated = false;
                this.loading = true;
                document.getElementById('head_edit_btn').disabled = true;
                var index = this.heads.findIndex(item => item.id == head_copied.id);

                axios.post(`/head/${head_copied.id}`, {
                    name: head_copied.name,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.head_updated = true;
                    this.heads.splice(index,1,response.data);
                    document.getElementById('head_edit_btn').disabled = false;
                    this.loading = false;
                    $('#editHeadModal').modal('hide');

                    Swal.fire({
                        title: 'Success!',
                        text: 'Approver updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                    
                })
                .catch(error => {
                    this.head_updated = false;
                    this.headerrors = error.response.data.errors;
                    document.getElementById('head_edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            headcopyObject(head){
                this.headerrors = [];
                this.head_copied = Object.assign({}, head);
                this.head_id = head.id;
                this.head_updated = false;
                this.head_added = false;
            },
           
            fetchHeads(){
                axios.get('/heads-all')
                .then(response => { 
                    this.heads = response.data;
                })
                .catch(error => { 
                    this.headerrors = error.response.data.error;
                })
            },
            headssetPage(pageNumber) {
                this.headcurrentPage = pageNumber;
            },

            headresetStartRow() {
                this.headcurrentPage = 0;
            },

            headsshowPreviousLink() {
                return this.headcurrentPage == 0 ? false : true;
            },

            headsshowNextLink() {
                return this.headcurrentPage == (this.headtotalPages - 1) ? false : true;
            },

            //----------Levels
            levelresetForm(){
                this.levelerrors = [];
                this.level = [];
                this.level_added = false;
            },
            storeLevel(level){
                this.levelerrors = [];
               
                this.level_added = false;
                this.loading = true;
                
                axios.post(`/level`, {
                    name: level.name,
                    _method: 'POST'
                })
                .then(response => {
                    this.level_added = true;
                    this.levels.unshift(response.data);
                    this.levelresetForm();   
                    this.loading = false;
                    $('#addLevelModal').modal('hide');

                    Swal.fire({
                        title: 'Success!',
                        text: 'Level saved successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })

                })
                .catch(error => {
                    this.level_added = false;
                    this.levelerrors = error.response.data.errors;
                    this.loading = false;
                })
            },
            deleteLevel(level){

                var index = this.levels.findIndex(item => item.id == level.id);
                Swal.fire({
                    title: 'Are you sure you want to delete this Level?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete'
                    }).then((result) => {
                    if (result.value) {
                        axios.delete(`/level/${level.id}`)
                        .then(response => {
                            $('#deleteLevelModal').modal('hide');
                            this.levels.splice(index,1);

                            Swal.fire({
                                title: 'Success!',
                                text: 'Level deleted successfully.',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            })
                        })
                        .catch(error => {
                            this.levelerrors = error.response.data.errors;
                        })   
                    }
                })

            },
            updateLevel(level_copied){
                this.levelerrors = [];
               
                this.edit_updated = false;
                this.loading = true;
                document.getElementById('level_edit_btn').disabled = true;
                var index = this.levels.findIndex(item => item.id == level_copied.id);

                axios.post(`/level/${level_copied.id}`, {
                    name: level_copied.name,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.level_updated = true;
                    this.levels.splice(index,1,response.data);
                    document.getElementById('level_edit_btn').disabled = false;
                    this.loading = false;
                    $('#editLevelModal').modal('hide');

                    Swal.fire({
                        title: 'Success!',
                        text: 'Level updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                    
                })
                .catch(error => {
                    this.level_updated = false;
                    this.levelerrors = error.response.data.errors;
                    document.getElementById('level_edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            levelcopyObject(level){
                this.levelerrors = [];
                this.level_copied = Object.assign({}, level);
                this.level_id = level.id;
                this.level_updated = false;
                this.level_added = false;
            },
           
            fetchLevels(){
                axios.get('/levels-all')
                .then(response => { 
                    this.levels = response.data;
                })
                .catch(error => { 
                    this.levelerrors = error.response.data.error;
                })
            },
            levelssetPage(pageNumber) {
                this.levelcurrentPage = pageNumber;
            },

            levelresetStartRow() {
                this.levelcurrentPage = 0;
            },

            levelsshowPreviousLink() {
                return this.levelcurrentPage == 0 ? false : true;
            },

            levelsshowNextLink() {
                return this.levelcurrentPage == (this.leveltotalPages - 1) ? false : true;
            },

            //----------Marital Statuses
            maritalresetForm(){
                this.maritalerrors = [];
                this.marital = [];
                this.marital_added = false;
            },
            storeMarital(marital){
                this.maritalerrors = [];
               
                this.marital_added = false;
                this.loading = true;
                
                axios.post(`/marital`, {
                    name: marital.name,
                    _method: 'POST'
                })
                .then(response => {
                    this.marital_added = true;
                    this.maritals.unshift(response.data);
                    this.maritalresetForm();   
                    this.loading = false;
                    $('#addMaritalModal').modal('hide');

                    Swal.fire({
                        title: 'Success!',
                        text: 'Marital Status saved successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })
                })
                .catch(error => {
                    this.marital_added = false;
                    this.maritalerrors = error.response.data.error;
                    this.loading = false;
                })
            },
            deleteMarital(marital){
                
                var index = this.maritals.findIndex(item => item.id == marital.id);

                Swal.fire({
                    title: 'Are you sure you want to delete this marital status?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete'
                    }).then((result) => {
                    if (result.value) {

                        axios.delete(`/marital/${marital.id}`)
                        .then(response => {
                            $('#deleteMaritalModal').modal('hide');
                            this.maritals.splice(index,1);

                            Swal.fire({
                                title: 'Success!',
                                text: 'Marital status deleted successfully.',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            })
                        })
                        .catch(error => {
                            this.maritalerrors = error.response.data.errors;
                        })   
                    }
                })


            },
            updateMarital(marital_copied){
                this.maritalerrors = [];
               
                this.edit_updated = false;
                this.loading = true;
                document.getElementById('marital_edit_btn').disabled = true;
                var index = this.maritals.findIndex(item => item.id == marital_copied.id);

                axios.post(`/marital/${marital_copied.id}`, {
                    name: marital_copied.name,
                    _method: 'PATCH'
                })
                .then(response => {
                    this.marital_updated = true;
                    this.maritals.splice(index,1,response.data);
                    document.getElementById('marital_edit_btn').disabled = false;
                    this.loading = false;
                    $('#editMaritalModal').modal('hide');
                    
                    Swal.fire({
                        title: 'Success!',
                        text: 'Marital Status updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    })

                })
                .catch(error => {
                    this.marital_updated = false;
                    this.maritalerrors = error.response.data.errors;
                    document.getElementById('marital_edit_btn').disabled = false;
                    this.loading = false;
                })
            },
            maritalcopyObject(marital){
                this.maritalerrors = [];
                this.marital_copied = Object.assign({}, marital);
                this.marital_id = marital.id;
                this.marital_updated = false;
                this.marital_added = false;
            },
           
            fetchMaritals(){
                axios.get('/maritals-all')
                .then(response => { 
                    this.maritals = response.data;
                })
                .catch(error => { 
                    this.maritalerrors = error.response.data.error;
                })
            },
            maritalssetPage(pageNumber) {
                this.maritalcurrentPage = pageNumber;
            },

            maritalresetStartRow() {
                this.maritalcurrentPage = 0;
            },

            maritalsshowPreviousLink() {
                return this.maritalcurrentPage == 0 ? false : true;
            },
            maritalsshowNextLink() {
                return this.maritalcurrentPage == (this.maritaltotalPages - 1) ? false : true;
            }
        },
        computed:{
            //-----------Companies
            filteredCompanies(){
                let self = this;
                return Object.values(self.companies).filter(company => {
                    return company.name.toLowerCase().includes(this.company_keywords.toLowerCase())
                });
            },
            companytotalPages() {
                return Math.ceil(Object.values(this.companies).length / this.companyitemsPerPage)
            },
            companyfilteredQueues() {
                var index = this.companycurrentPage * this.companyitemsPerPage;
                var queues_array = this.filteredCompanies.slice(index, index + this.companyitemsPerPage);

                if(this.companycurrentPage >= this.companytotalPages) {
                    this.companycurrentPage = this.companytotalPages - 1
                }

                if(this.companycurrentPage == -1) {
                    this.companycurrentPage = 0;
                }

                return queues_array;
            },

            //-----------Departments
            filteredDepartments(){
                let self = this;
                return Object.values(self.departments).filter(department => {
                    return department.name.toLowerCase().includes(this.department_keywords.toLowerCase())
                });
            },
            departmenttotalPages() {
                return Math.ceil(Object.values(this.departments).length / this.departmentitemsPerPage)
            },
            departmentfilteredQueues() {
                var index = this.departmentcurrentPage * this.departmentitemsPerPage;
                var queues_array = this.filteredDepartments.slice(index, index + this.departmentitemsPerPage);

                if(this.departmentcurrentPage >= this.departmenttotalPages) {
                    this.departmentcurrentPage = this.departmenttotalPages - 1
                }

                if(this.departmentcurrentPage == -1) {
                    this.departmentcurrentPage = 0;
                }

                return queues_array;
            },

            //-----------Locations
            filteredLocations(){
                let self = this;
                return Object.values(self.locations).filter(location => {
                    return location.name.toLowerCase().includes(this.location_keywords.toLowerCase())
                });
            },
            locationtotalPages() {
                return Math.ceil(Object.values(this.locations).length / this.locationitemsPerPage)
            },
            locationfilteredQueues() {
                var index = this.locationcurrentPage * this.locationitemsPerPage;
                var queues_array = this.filteredLocations.slice(index, index + this.locationitemsPerPage);

                if(this.locationcurrentPage >= this.locationtotalPages) {
                    this.locationcurrentPage = this.locationtotalPages - 1
                }

                if(this.locationcurrentPage == -1) {
                    this.locationcurrentPage = 0;
                }

                return queues_array;
            },

             //-----------Addresses
            filteredAddresses(){
                let self = this;
                return Object.values(self.addresses).filter(address => {
                    return address.name.toLowerCase().includes(this.address_keywords.toLowerCase())
                });
            },
            addresstotalPages() {
                return Math.ceil(Object.values(this.addresses).length / this.addressitemsPerPage)
            },
            addressfilteredQueues() {
                var index = this.addresscurrentPage * this.addressitemsPerPage;
                var queues_array = this.filteredAddresses.slice(index, index + this.addressitemsPerPage);

                if(this.addresscurrentPage >= this.addresstotalPages) {
                    this.addresscurrentPage = this.addresstotalPages - 1
                }

                if(this.addresscurrentPage == -1) {
                    this.addresscurrentPage = 0;
                }

                return queues_array;
            },

             //-----------Heads
            filteredHeads(){
                let self = this;
                return Object.values(self.heads).filter(head => {
                    return head.name.toLowerCase().includes(this.head_keywords.toLowerCase())
                });
            },
            headtotalPages() {
                return Math.ceil(Object.values(this.heads).length / this.headitemsPerPage)
            },
            headfilteredQueues() {
                var index = this.headcurrentPage * this.headitemsPerPage;
                var queues_array = this.filteredHeads.slice(index, index + this.headitemsPerPage);

                if(this.headcurrentPage >= this.headtotalPages) {
                    this.headcurrentPage = this.headtotalPages - 1
                }

                if(this.headcurrentPage == -1) {
                    this.headcurrentPage = 0;
                }

                return queues_array;
            },

            //-----------Levels
            filteredLevels(){
                let self = this;
                return Object.values(self.levels).filter(level => {
                    return level.name.toLowerCase().includes(this.level_keywords.toLowerCase())
                });
            },
            leveltotalPages() {
                return Math.ceil(Object.values(this.levels).length / this.levelitemsPerPage)
            },
            levelfilteredQueues() {
                var index = this.levelcurrentPage * this.levelitemsPerPage;
                var queues_array = this.filteredLevels.slice(index, index + this.levelitemsPerPage);

                if(this.levelcurrentPage >= this.leveltotalPages) {
                    this.levelcurrentPage = this.leveltotalPages - 1
                }

                if(this.levelcurrentPage == -1) {
                    this.levelcurrentPage = 0;
                }

                return queues_array;
            },
            //-----------Marital Statuses
            filteredMaritals(){
                let self = this;
                return Object.values(self.maritals).filter(marital => {
                    return marital.name.toLowerCase().includes(this.marital_keywords.toLowerCase())
                });
            },
            maritaltotalPages() {
                return Math.ceil(Object.values(this.maritals).length / this.maritalitemsPerPage)
            },
            maritalfilteredQueues() {
                var index = this.maritalcurrentPage * this.maritalitemsPerPage;
                var queues_array = this.filteredMaritals.slice(index, index + this.maritalitemsPerPage);

                if(this.maritalcurrentPage >= this.maritaltotalPages) {
                    this.maritalcurrentPage = this.maritaltotalPages - 1
                }

                if(this.maritalcurrentPage == -1) {
                    this.maritalcurrentPage = 0;
                }

                return queues_array;
            },
        }
    }
</script>