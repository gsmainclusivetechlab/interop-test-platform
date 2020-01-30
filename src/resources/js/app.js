require('./bootstrap');

Vue.use(BootstrapVue);
// Vue.component('diagram-component', require('./components/DiagramComponent.vue').default);
Vue.component('confirm-button', require('./components/ConfirmButton.vue').default);

const app = new Vue({
    el: '#app',
});
