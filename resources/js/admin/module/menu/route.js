import ParentRoute from '../../view/parent-route';
import MenuList from './list/list';
import MenuForm from './form/form';

const route = {
    path: 'menu',
    component: ParentRoute,
    children: [
        {path: '', redirect: {name: 'menu.list'}},
        {
            path: 'list',
            name: 'menu.list',
            component: MenuList,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.menu_list'
            }
        },
        {
            path: 'create',
            name: 'menu.create',
            component: MenuForm,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.menu_create'
            }
        },
        {
            path: 'edit/:menu',
            name: 'menu.edit',
            component: MenuForm,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.menu_edit'
            }
        },
    ]
};

export default route;
