import ParentRoute from '../../view/parent-route';
import TypeList from './list/type-list';
import TypeForm from './form/type-form';

const route = {
    path: 'type',
    component: ParentRoute,
    children: [
        {path: '', redirect: {name: 'type.list'}},
        {
            path: 'list',
            name: 'type.list',
            component: TypeList,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.type_list'
            }
        },
        {
            path: 'create/:type',
            name: 'type.create',
            component: TypeForm,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.type_create'
            }
        },
        {
            path: 'edit/:id',
            name: 'type.edit',
            component: TypeForm,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.type_edit'
            }
        },
    ]
};

export default route;
