import ParentRoute from '../../view/parent-route';
import NotFound from './not-found/not-found'

const route = {
    path: 'error',
    component: ParentRoute,
    children: [
        {path: '', redirect: {name: 'error.not-found'}},
        {
            path: 'not-found',
            name: 'error.not-found',
            component: NotFound,
            meta: {
                layout: 'centered',
            }
        }
    ]
};

export default route;
