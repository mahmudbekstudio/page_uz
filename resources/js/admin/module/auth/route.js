import ParentRoute from '../../view/parent-route';
import Login from './login/login';
import ForgotPassword from './forgot-password/forgot-password';
import resetPassword from './reset-password/reset-password';

const route = {
    path: 'auth',
    component: ParentRoute,
    children: [
        {path: '', redirect: {name: 'auth.login'}},
        {
            path: 'login',
            name: 'auth.login',
            component: Login,
            meta: {
                layout: 'centered',
            }
        },
        {
            path: 'forgot-password',
            name: 'auth.forgot-password',
            component: ForgotPassword,
            meta: {
                layout: 'centered',
            }
        },
        {
            path: 'reset-password/:token',
            name: 'auth.reset-password',
            component: resetPassword,
            meta: {
                layout: 'centered',
            }
        }
    ]
};

export default route;
