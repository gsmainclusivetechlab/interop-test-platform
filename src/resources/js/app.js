import Vue from 'vue';
import router from './router';
import App from './views/App.vue';

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

new Vue({
    el: '#app',
    router,
    render: (h) => h(App),
});
