import ParentRoute from '../../view/parent-route';
import Template from './template.vue';

const route = {
    path: 'template',
    component: ParentRoute,
    children: [
        {path: '', redirect: {name: 'template.list'}},
        {
            path: 'list/:type?',
            name: 'template.list',
            component: Template,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.templates_list'
            }
        },
        {
            path: 'create/:type',
            name: 'template.create',
            component: Template,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.template_create'
            }
        },
        {
            path: 'edit/:id',
            name: 'template.edit',
            component: Template,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.template_edit'
            }
        },
    ]
};

export default route;
