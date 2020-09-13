
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import moment from 'moment'
import VueProgressBar from 'vue-progressbar'

window.Vue = require('vue');
import { Form, HasError, AlertError } from 'vform'

import Gate from './Gate';
Vue.prototype.$gate = new Gate(window.user);

// ES6 Modules or TypeScript
import swal from 'sweetalert2'
// CommonJS
window.swal = swal;

let toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
})

window.toast = toast;


Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
window.Form = Form;

import VueRouter from 'vue-router'
Vue.use(VueRouter)

//filters
//Capitalize
Vue.filter('capitalize',function(value){
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
})

//date format
Vue.filter('dateformat', function (value) {
  return moment(value).format('MMMM DD YYYY');
})
//Filters End

window.Fire = new Vue();

// 2. Define some routes
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// `Vue.extend()`, or just a component options object.
// We'll talk about nested routes later.
let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue') },
    { path: '/profile', component: require('./components/Profile.vue') },
    { path: '/users', component: require('./components/Users.vue') },
    { path: '/hotelpackages', component: require('./components/HotelPacakges.vue') },
    { path: '/developer', component: require('./components/Developer.vue') },
    { path: '*', component: require('./components/NotFound.vue') }, //should last for 404 error
]

 // 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
  })

  //progress bar
  Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '3px'
  })
  
Vue.component('passport-clients',require('./components/passport/Clients.vue'));
Vue.component('passport-authorized-clients',require('./components/passport/AuthorizedClients.vue'));
Vue.component('passport-personal-access-tokens',require('./components/passport/PersonalAccessTokens.vue'));
Vue.component('notfound', require('./components/NotFound.vue'));
Vue.component('pagination', require('laravel-vue-pagination'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app',
    router
});
