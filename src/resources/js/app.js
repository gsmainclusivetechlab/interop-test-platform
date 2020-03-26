import Vue from 'vue';
import '@fancyapps/fancybox';
$.fancybox.defaults.touch = false;

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
import jsonTree from './components/jsonTreeViewer';

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
    methods: {
        toggleCheckboxes(e) {
            const btn = e.target;
            const closestParentList = btn.closest('.list-group-item');
            const checkboxes = Array.from(
                closestParentList.querySelectorAll('input[type="checkbox"]'),
            );
            const isChecked = checkboxes.every(
                (checkbox) => checkbox.checked === true,
            );

            checkboxes.forEach((checkbox) => {
                checkbox.checked = !isChecked;
            });
        },
    },
});

hljs.initHighlightingOnLoad();
jsonTree.init();

new Clipboard('[data-clipboard-target]');
