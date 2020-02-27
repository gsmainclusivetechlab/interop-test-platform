import Dashboard from '../views/Dashboard.vue';
import Sessions from '../views/Sessions.vue';

export default [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            title: 'Dashboard',
        },
    },
    {
        path: '/sessions',
        name: 'sessions',
        component: Sessions,
        meta: {
            title: 'Sessions',
        },
    },
];
