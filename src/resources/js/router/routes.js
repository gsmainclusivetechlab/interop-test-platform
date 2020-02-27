import Dashboard from '../views/Dashboard.vue';
import Sessions from '../views/Sessions.vue';
import SessionsRegister from '../views/SessionsRegister.vue';
import SessionsRegisterSelection from '../views/SessionsRegisterSelection.vue';
import SessionsRegisterConfiguration from '../views/SessionsRegisterConfiguration.vue';
import SessionsRegisterInfo from '../views/SessionsRegisterInfo.vue';

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
    {
        path: '/sessions/register',
        redirect: {
            name: 'sessions.register.selection',
        },
        name: 'sessions.register',
        component: SessionsRegister,
        children: [
            {
                path: 'selection',
                name: 'sessions.register.selection',
                component: SessionsRegisterSelection,
                meta: {
                    title: 'Select SUT',
                },
            },
            {
                path: 'configuration',
                name: 'sessions.register.configuration',
                component: SessionsRegisterConfiguration,
                meta: {
                    title: 'Configure SUT',
                },
            },
            {
                path: 'info',
                name: 'sessions.register.info',
                component: SessionsRegisterInfo,
                meta: {
                    title: 'Sessions info',
                },
            },
        ],
    },
];
