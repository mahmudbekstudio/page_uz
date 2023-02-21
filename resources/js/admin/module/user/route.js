import ParentRoute from '../../view/parent-route';
import profile from './profile/profile';

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
                title: 'Profile'
            }
        }
    ]
};

export default route;