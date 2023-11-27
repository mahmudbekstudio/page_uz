import ParentRoute from '../../view/parent-route';
import Feature from "./feature.vue";

const route = {
    path: 'feature',
    component: ParentRoute,
    children: [
        {path: '', redirect: {name: 'feature.list'}},
        {
            path: 'list',
            name: 'feature.list',
            component: Feature,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.feature_list'
            }
        },
        {
            path: 'create',
            name: 'feature.create',
            component: Feature,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.feature_create'
            }
        },
        {
            path: 'edit/:id',
            name: 'feature.edit',
            component: Feature,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.feature_edit'
            }
        },
    ]
};

export default route;
