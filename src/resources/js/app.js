require('./bootstrap');

Vue.use(BootstrapVue);
// Vue.component('diagram-component', require('./components/DiagramComponent.vue').default);
Vue.component('confirm', require('./components/ConfirmComponent.vue').default);

const app = new Vue({
    el: '#app',
});
