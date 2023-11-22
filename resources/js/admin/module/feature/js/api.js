import route from '../../../api/route';

const api = {
    create: {
        ...route.admin('feature.create'),
        callback: function(form) {
            this.data(form);
        },
        token: true
    },
    edit: {
        ...route.admin('feature.edit'),
        callback: function(id, form) {
            this.urlParam('{feature}', id);
            this.data(form);
        },
        token: true
    },
    delete: {
        ...route.admin('feature.delete'),
        callback: function(id) {
            this.urlParam('{feature}', id);
        },
        token: true
    },
    get: {
        ...route.admin('feature.get'),
        callback: function(id) {
            this.urlParam('{feature}', id);
        },
        token: true
    },
    getByType: {
        ...route.admin('feature.get-by-type'),
        callback: function(type) {
            this.urlParam('{feature}', type);
        },
        token: true
    },
};

export default api;
