import Vue from 'vue';
import VueMeta from 'vue-meta';
import { InertiaApp } from '@inertiajs/inertia-vue';

Vue.mixin({
    methods: {route: window.route}
});
Vue.use(InertiaApp);
Vue.use(VueMeta);

const app = document.getElementById('app');

new Vue({
    metaInfo: {
        titleTemplate: (title) => title ? `${title} - Interoperability Test Platform - GSMA` : 'Interoperability Test Platform - GSMA',
    },
    render: h => h(InertiaApp, {
        props: {
            initialPage: JSON.parse(app.dataset.page),
            resolveComponent: name => import(/* webpackChunkName: "pages/[request]" */ `./pages/${name}`).then(module => module.default),
        },
    }),
}).$mount(app);
