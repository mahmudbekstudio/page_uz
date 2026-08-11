import route from '../../../api/route';

const api = {
    create: {
        ...route.admin('block.create'),
        callback: function(typeId, form) {
            this.urlParam('{type}', typeId);
            this.data(form);
        },
        token: true
    },
    edit: {
        ...route.admin('block.edit'),
        callback: function(typeId, id, form) {
            this.urlParam('{type}', typeId);
            this.urlParam('{block}', id);
            this.data(form);
        },
        token: true
    },
    delete: {
        ...route.admin('block.delete'),
        callback: function(typeId, id) {
            this.urlParam('{type}', typeId);
            this.urlParam('{block}', id);
        },
        token: true
    },
    get: {
        ...route.admin('block.get'),
        callback: function(typeId, id) {
            this.urlParam('{type}', typeId);
            this.urlParam('{block}', id);
        },
        token: true
    },
};

export default api;
