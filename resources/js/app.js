/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Form = require('./util/Form').default;
require('./util/Event');


import vueAwesomeCountdown from 'vue-awesome-countdown'

Vue.use(vueAwesomeCountdown, 'vac'); // Component name, `countdown` and `vac` by default


import Toasted from 'vue-toasted';

var toastedOptions = {
    theme: "bubble",
    position: "bottom-center",
    duration : 5000
};
Vue.use(Toasted, toastedOptions);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('category', require('./components/category.vue').default);
Vue.component('collapse-group', require('./components/collapseGroup.vue').default);
Vue.component('vote-item-choice', require('./components/voteItemChoice.vue').default);
Vue.component('vote-item', require('./components/voteItem.vue').default);
Vue.component('login', require('./components/login.vue').default);
Vue.component('mobile-verify', require('./components/mobileVerify.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

