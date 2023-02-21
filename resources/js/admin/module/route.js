import viewConfig from '../config/view';
import authRoute from './auth/route';
import dashboardRoute from './dashboard/route';
import settingRoute from './setting/route';
import errorRoute from './error/route';
import ParentRoute from '../view/parent-route';
import UserRoute from './user/route';
import TypeRoute from './type/route';

const route = [
    {
        path: '/admin',
        component: ParentRoute,
        children: [
            {path: '', redirect: {name: viewConfig.page.default}},
            authRoute,
            dashboardRoute,
            settingRoute,
            errorRoute,
            UserRoute,
            TypeRoute,
            {path: '*', redirect: {name: viewConfig.page.notFound}}
        ]
    }
];

export default route;
