import Vue from 'vue';

import {
    AlertPlugin,
    DropdownPlugin,
    NavPlugin,
    NavbarPlugin,
    CollapsePlugin,
    ProgressPlugin,
    VBTooltipPlugin,
} from 'bootstrap-vue';

import hljs from 'highlight.js';
import Clipboard from 'clipboard';

new Clipboard('[data-clipboard-target]');

Vue.use(AlertPlugin);
Vue.use(DropdownPlugin);
Vue.use(NavPlugin);
Vue.use(NavbarPlugin);
Vue.use(CollapsePlugin);
Vue.use(ProgressPlugin);
Vue.use(VBTooltipPlugin);

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

const app = new Vue({
    el: '#app',
});

hljs.initHighlightingOnLoad();
