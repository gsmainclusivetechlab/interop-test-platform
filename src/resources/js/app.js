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
    TabsPlugin,
} from 'bootstrap-vue';
import VueI18n from 'vue-i18n';

window.string = require('string');
window.collect = require('collect.js');
window.axios = require('axios');
window.axios.defaults.withCredentials = true;

Vue.use(VueMeta);
Vue.use(InertiaApp);
Vue.use(VueI18n);

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
    TabsPlugin,
].forEach((plugin) => {
    Vue.use(plugin);
});

Vue.mixin({
    methods: {
        route: window.route,
        string: window.string,
        collect: window.collect,
    },
});

Vue.config.productionTip = false;

Vue.component('icon', () =>
    import(/* webpackChunkName: "icon" */ '@/components/icon.vue')
);
Vue.component('breadcrumb', () =>
    import(/* webpackChunkName: "breadcrumb" */ '@/components/breadcrumb.vue')
);
Vue.component('pagination', () =>
    import(/* webpackChunkName: "pagination" */ '@/components/pagination.vue')
);
Vue.component('input-search', () =>
    import(
        /* webpackChunkName: "input-search" */ '@/components/input-search.vue'
    )
);
Vue.component('selectize', () =>
    import(/* webpackChunkName: "selectize" */ '@isneezy/vue-selectize')
);
Vue.component('confirm-link', () =>
    import(
        /* webpackChunkName: "confirm-link" */ '@/components/confirm-link.vue'
    )
);
Vue.component('json-tree', () =>
    import(/* webpackChunkName: "json-tree" */ '@/components/json-tree.vue')
);
Vue.component('clipboard-copy-btn', () =>
    import(
        /* webpackChunkName: "clipboard-copy-btn" */ '@/components/clipboard-copy-btn.vue'
    )
);
Vue.component('clipboard-json-to-curl', () =>
    import(
        /* webpackChunkName: "clipboard-json-to-curl" */ '@/components/clipboard-json-to-curl.vue'
    )
);
Vue.component('text-editor', () =>
    import(/* webpackChunkName: "text-editor" */ '@/components/text-editor.vue')
);
const i18n = new VueI18n({
    locale: 'en-GB',
    fallbackLocale: 'en-GB',
    messages: {},
});

let app = document.getElementById('app');

new Vue({
    i18n,
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) =>
                    import(
                        /* webpackChunkName: "pages/[request]" */ `@/pages/${name}`
                    ).then((module) => module.default),
            },
        }),
}).$mount(app);
