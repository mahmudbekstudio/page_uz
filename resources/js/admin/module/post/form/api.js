import route from '../../../api/route';

const api = {
    create: {
        ...route.admin('post.create'),
        callback: function(typeId, form) {
            this.urlParam('{type}', typeId);
            this.data(form);
        },
        token: true
    },
    edit: {
        ...route.admin('post.edit'),
        callback: function(typeId, id, form) {
            this.urlParam('{type}', typeId);
            this.urlParam('{post}', id);
            this.data(form);
        },
        token: true
    },
    delete: {
        ...route.admin('post.delete'),
        callback: function(typeId, id) {
            this.urlParam('{type}', typeId);
            this.urlParam('{post}', id);
        },
        token: true
    },
    get: {
        ...route.admin('post.get'),
        callback: function(typeId, id) {
            this.urlParam('{type}', typeId);
            this.urlParam('{post}', id);
        },
        token: true
    },
};

export default api;
