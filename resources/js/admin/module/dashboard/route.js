import Dashboard from './dashboard.vue'

const route = {
    path: 'dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: {
        title: 'words.pages.dashboard',
        layout: 'main',
        requiresAuth: true
    }
};

export default route;
