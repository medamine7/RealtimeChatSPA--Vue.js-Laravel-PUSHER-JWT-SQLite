
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import App from './components/app'
import router from './router'
import store from './store'
import VeeValidate from 'vee-validate'



require('./bootstrap');

window.Vue = require('vue');

Vue.use(VeeValidate, {
  events: ''
});
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


new Vue({
    el: '#app',
    router,
    store,
    components: {App}
});
