require('./bootstrap');

Vue.use(BootstrapVue);
Vue.component('confirm-button', require('./components/ConfirmButton.vue').default);
Vue.component('chart', require('./components/Chart.vue').default);

const app = new Vue({
    el: '#app',
});
