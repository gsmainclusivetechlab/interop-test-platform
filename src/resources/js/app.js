import Vue from 'vue';
import VueMeta from 'vue-meta';
import { InertiaApp } from '@inertiajs/inertia-vue';
import {
    NavPlugin,
    NavbarPlugin,
    CollapsePlugin,
    DropdownPlugin,
    ProgressPlugin,
    VBTooltipPlugin
} from 'bootstrap-vue';

Vue.use(VueMeta);
Vue.use(InertiaApp);

Vue.use(NavPlugin);
Vue.use(NavbarPlugin);
Vue.use(CollapsePlugin);
Vue.use(DropdownPlugin);
Vue.use(ProgressPlugin);
Vue.use(VBTooltipPlugin);

Vue.mixin({
    methods: { route: window.route }
});

Vue.component('icon', () =>
    import(/* webpackChunkName: "icon" */ '@/components/icon.vue')
);
Vue.component('pagination', () =>
    import(/* webpackChunkName: "pagination" */ '@/components/pagination.vue')
);

const app = document.getElementById('app');

new Vue({
    metaInfo: {
        titleTemplate: title =>
            title
                ? `${title} - Interoperability Test Platform - GSMA`
                : 'Interoperability Test Platform - GSMA'
    },
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
