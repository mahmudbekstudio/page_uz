import route from '../../../api/route';

const api = {
    create: {
        ...route.admin('type.create'),
        callback: function(data) {
            this.data(data);
        },
        token: true
    },
    edit: {
        ...route.admin('type.edit'),
        callback: function(id, data) {
            this.urlParam('{id}', id);
            this.data(data);
        },
        token: true
    },
    get: {
        ...route.admin('type.get'),
        callback: function(id) {
            this.urlParam('{type}', id);
        },
        token: true
    },
    getByType: {
        ...route.admin('type.get-by-type'),
        callback: function(type) {
            this.urlParam('{type}', type);
        },
        token: true
    },
    delete: {
        ...route.admin('type.delete'),
        callback: function(id) {
            this.urlParam('{type}', id);
        },
        token: true
    },
    categories: {
        ...route.admin('type.categories'),
        token: true
    },
};

export default api;
