import Vue from 'vue';
import VueMeta from 'vue-meta';
import { InertiaApp } from '@inertiajs/inertia-vue';
import {
    NavPlugin,
    NavbarPlugin,
    ModalPlugin,
    CollapsePlugin,
    DropdownPlugin,
    FormFilePlugin,
    ProgressPlugin,
    VBTooltipPlugin,
    PopoverPlugin,
    TabsPlugin
} from 'bootstrap-vue';

window.string = require('string');
window.collect = require('collect.js');

Vue.use(VueMeta);
Vue.use(InertiaApp);

[
    NavPlugin,
    NavbarPlugin,
    ModalPlugin,
    CollapsePlugin,
    DropdownPlugin,
    FormFilePlugin,
    ProgressPlugin,
    VBTooltipPlugin,
    PopoverPlugin,
    TabsPlugin
].forEach(plugin => {
    Vue.use(plugin);
});

Vue.mixin({
    methods: {
        route: window.route,
        string: window.string,
        collect: window.collect
    }
});

Vue.config.productionTip = false;

Vue.component('icon', () =>
    import(/* webpackChunkName: "icon" */ '@/components/icon.vue')
);
Vue.component('pagination', () =>
    import(/* webpackChunkName: "pagination" */ '@/components/pagination.vue')
);
Vue.component('confirm-link', () =>
    import(
        /* webpackChunkName: "confirm-link" */ '@/components/confirm-link.vue'
    )
);
Vue.component('json-tree', () =>
    import(/* webpackChunkName: "json-tree" */ 'vue-json-pretty')
);

const app = document.getElementById('app');

new Vue({
    render: h =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: name =>
                    import(
                        /* webpackChunkName: "pages/[request]" */ `@/pages/${name}`
                    ).then(module => module.default)
            }
        })
}).$mount(app);
