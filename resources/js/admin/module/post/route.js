import ParentRoute from '../../view/parent-route';
import PostList from './list/list.vue';
import PostForm from './form/form.vue';

const route = {
    path: 'post',
    component: ParentRoute,
    children: [
        {path: '', redirect: {name: 'post.list'}},
        {
            path: 'list/:typeId',
            name: 'post.list',
            component: PostList,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'Post list'
            }
        },
        {
            path: 'create/:typeId',
            name: 'post.create',
            component: PostForm,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'Post create'
            }
        },
        {
            path: 'edit/:typeId/:id',
            name: 'post.edit',
            component: PostForm,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'Post edit'
            }
        },
    ]
};

export default route;
