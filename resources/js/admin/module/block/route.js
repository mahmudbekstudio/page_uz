import ParentRoute from '../../view/parent-route';
import BlockList from './list/list.vue';
import BlockForm from './form/form.vue';

const route = {
    path: 'block',
    component: ParentRoute,
    children: [
        {path: '', redirect: {name: 'block.list'}},
        {
            path: 'list/:typeId',
            name: 'block.list',
            component: BlockList,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.block_list'
            }
        },
        {
            path: 'create/:typeId',
            name: 'block.create',
            component: BlockForm,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.block_create'
            }
        },
        {
            path: 'edit/:typeId/:id',
            name: 'block.edit',
            component: BlockForm,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.block_edit'
            }
        },
    ]
};

export default route;
