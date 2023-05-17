import ParentRoute from '../../view/parent-route';
import list from "./list/list";

const route = {
    path: 'website',
    component: ParentRoute,
    children: [
        {path: '', redirect: {name: 'website.list'}},
        {
            path: 'list',
            name: 'website.list',
            component: list,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.websites'
            }
        },
    ]
};

export default route;
