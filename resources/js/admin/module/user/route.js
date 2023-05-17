import ParentRoute from '../../view/parent-route';
import profile from './profile/profile';
import usersList from './list/list.vue';

const route = {
    path: 'user',
    component: ParentRoute,
    children: [
        {path: '', redirect: {name: 'user.profile'}},
        {
            path: 'profile',
            name: 'user.profile',
            component: profile,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.profile'
            }
        },
        {
            path: 'list',
            name: 'user.list',
            component: usersList,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.users'
            }
        }
    ]
};

export default route;
