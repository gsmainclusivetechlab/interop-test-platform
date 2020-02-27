import Vue from 'vue';
import {
    DropdownPlugin,
    CollapsePlugin,
    CardPlugin,
    TablePlugin,
} from 'bootstrap-vue';

import router from './router';
import App from './views/App.vue';

Vue.use(DropdownPlugin);
Vue.use(CollapsePlugin);
Vue.use(CardPlugin);
Vue.use(TablePlugin);

Vue.component('confirm-button', () =>
    import(/* webpackChunkName: "confirm" */ './components/ConfirmButton.vue'),
);
Vue.component('chart', () =>
    import(/* webpackChunkName: "chart" */ './components/Chart.vue'),
);
Vue.component('web-editor', () =>
    import(/* webpackChunkName: "editor" */ './components/WebEditor.vue'),
);
Vue.component('flow-chart', () =>
    import(/* webpackChunkName: "flow-chart" */ './components/FlowChart.vue'),
);
Vue.component('notification', () =>
    import(
        /* webpackChunkName: "notification" */ './components/Notification.vue'
    ),
);

if (document.querySelector('#app')) {
    new Vue({
        el: '#app',
        router,
        render: (h) => h(App),
    });
}
