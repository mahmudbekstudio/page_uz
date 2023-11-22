import viewConfig from '../config/view';
import authRoute from './auth/route';
import dashboardRoute from './dashboard/route';
import settingRoute from './setting/route';
import errorRoute from './error/route';
import ParentRoute from '../view/parent-route';
import UserRoute from './user/route';
import TypeRoute from './type/route';
import WebsiteRoute from './website/route';
import PostRoute from './post/route';
import CategoryRoute from './category/route';
import MenuRoute from './menu/route';
import TemplateRoute from './template/route';
import FeatureRoute from './feature/route';

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
            WebsiteRoute,
            PostRoute,
            CategoryRoute,
            MenuRoute,
            TemplateRoute,
            FeatureRoute,
            {path: '*', redirect: {name: viewConfig.page.notFound}}
        ]
    }
];

export default route;
