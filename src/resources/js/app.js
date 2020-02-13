import Vue from 'vue';

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

Vue.component('confirm-button', () =>
    import(/* webpackChunkName: "confirm" */ './components/ConfirmButton.vue'),
);
Vue.component('chart', () =>
    import(/* webpackChunkName: "chart" */ './components/Chart.vue'),
);
Vue.component('web-editor', () =>
    import(/* webpackChunkName: "editor" */ './components/WebEditor.vue'),
);

const app = new Vue({
    el: '#app',
});
