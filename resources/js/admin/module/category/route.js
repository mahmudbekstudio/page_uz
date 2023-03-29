import ParentRoute from '../../view/parent-route';
import CategoryList from './list/list.vue';
import CategoryForm from './form/form.vue';

const route = {
    path: 'category',
    component: ParentRoute,
    children: [
        {path: '', redirect: {name: 'category.list'}},
        {
            path: 'category/:typeId',
            name: 'category.list',
            component: CategoryList,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'Category list'
            }
        },
        {
            path: 'create/:typeId',
            name: 'category.create',
            component: CategoryForm,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'Category create'
            }
        },
        {
            path: 'edit/:typeId/:id',
            name: 'category.edit',
            component: CategoryForm,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'Category edit'
            }
        },
    ]
};

export default route;
