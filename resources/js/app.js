/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('dashboard', require('./components/Dashboard.vue').default);
Vue.component('loader', require('./components/Loader.vue').default);
Vue.component('users', require('./components/Users.vue').default);
Vue.component('employees', require('./components/Employees.vue').default);
Vue.component('addemployee', require('./components/AddEmployee.vue').default);
Vue.component('settings', require('./components/Settings.vue').default);
Vue.component('activities', require('./components/Activities.vue').default);
Vue.component('userprofile', require('./components/UserProfile.vue').default);
Vue.component('userprofileview', require('./components/UserProfileView.vue').default);
Vue.component('userprofileverification', require('./components/UserProfileVerification.vue').default);
Vue.component('employeeapprovalrequest', require('./components/EmployeeApprovalRequest.vue').default);
Vue.component('employeeid', require('./components/EmployeeId.vue').default);
Vue.component('employee-rfid', require('./components/EmployeeRfid.vue').default);

Vue.component('verifiedemployees', require('./components/VerifiedEmployees.vue').default);

Vue.component('changepassword', require('./components/ChangePassword.vue').default);



Vue.component('health-declaration-forms', require('./components/HealthDeclarationForms.vue').default);
Vue.component('health-declaration-forms-qr', require('./components/HealthDeclarationFormsQr.vue').default);

Vue.component('health-declaration-forms-ic', require('./components/HealthDeclarationFormsIc.vue').default);

Vue.component('health-declaration-forms-set-up', require('./components/HealthDeclarationFormsSetup.vue').default);


//Survey
Vue.component('survey', require('./components/Surveys/Survey.vue').default);
Vue.component('survey-culture', require('./components/Surveys/SurveyCulture.vue').default);

Vue.component('survey-user', require('./components/Surveys/SurveyUser.vue').default);
Vue.component('survey-culture-export', require('./components/Surveys/SurveyCultureExport.vue').default);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
