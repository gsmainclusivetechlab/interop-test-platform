require('./bootstrap');

import {
    AlertPlugin,
    DropdownPlugin,
    NavPlugin,
    NavbarPlugin,
    CollapsePlugin,
    ProgressPlugin,
} from 'bootstrap-vue';

Vue.use(AlertPlugin);
Vue.use(DropdownPlugin);
Vue.use(NavPlugin);
Vue.use(NavbarPlugin);
Vue.use(CollapsePlugin);
Vue.use(ProgressPlugin);

Vue.component(
    'confirm-button',
    require('./components/ConfirmButton.vue').default,
);
Vue.component('chart', require('./components/Chart.vue').default);
Vue.component('web-editor', require('./components/WebEditor.vue').default);

const app = new Vue({
    el: '#app',
});
