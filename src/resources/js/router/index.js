import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './routes';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'active',
    linkExactActiveClass: 'exact-active',
    routes,
});

const baseDocumentTitle = 'Interoperability Test Platform - GSMA';

router.beforeEach((to, from, next) => {
    const nearestWithTitle = to.matched
        .slice()
        .reverse()
        .find((r) => r.meta && r.meta.title);

    const nearestWithMeta = to.matched
        .slice()
        .reverse()
        .find((r) => r.meta && r.meta.metaTags);

    if (nearestWithTitle) {
        document.title = `${nearestWithTitle.meta.title} - ${baseDocumentTitle}`;
    }

    Array.from(
        document.querySelectorAll('[data-vue-router-controlled]'),
    ).map((el) => el.parentNode.removeChild(el));

    if (!nearestWithMeta) return next();

    nearestWithMeta.meta.metaTags
        .map((tagDef) => {
            const tag = document.createElement('meta');

            Object.keys(tagDef).forEach((key) => {
                tag.setAttribute(key, tagDef[key]);
            });

            tag.setAttribute('data-vue-router-controlled', '');

            return tag;
        })
        .forEach((tag) => document.head.appendChild(tag));

    next();
});

export default router;
