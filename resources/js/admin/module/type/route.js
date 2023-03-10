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
                title: 'Types list'
            }
        },
        {
            path: 'create/:type',
            name: 'type.create',
            component: TypeForm,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'Type create'
            }
        },
        {
            path: 'edit/:id',
            name: 'type.edit',
            component: TypeForm,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'Type edit'
            }
        },
    ]
};

export default route;
