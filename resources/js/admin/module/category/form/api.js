import route from '../../../api/route';

const api = {
    create: {
        ...route.admin('category.create'),
        callback: function(typeId, form) {
            this.urlParam('{type}', typeId);
            this.data(form);
        },
        token: true
    },
    edit: {
        ...route.admin('category.edit'),
        callback: function(typeId, id, form) {
            this.urlParam('{type}', typeId);
            this.urlParam('{category}', id);
            this.data(form);
        },
        token: true
    },
    delete: {
        ...route.admin('category.delete'),
        callback: function(typeId, id) {
            this.urlParam('{type}', typeId);
            this.urlParam('{category}', id);
        },
        token: true
    },
    get: {
        ...route.admin('category.get'),
        callback: function(typeId, id) {
            this.urlParam('{type}', typeId);
            this.urlParam('{category}', id);
        },
        token: true
    },
};

export default api;
