import route from '../../../api/route';

const api = {
    edit: {
        ...route.admin('setting.edit'),
        callback: function(typeId, id, form) {
            this.urlParam('{type}', typeId);
            this.urlParam('{setting}', id);
            this.data(form);
        },
        token: true
    },
    get: {
        ...route.admin('setting.get'),
        callback: function(typeId, id) {
            this.urlParam('{type}', typeId);
            this.urlParam('{setting}', id);
        },
        token: true
    },
    create: {
        ...route.admin('setting.create'),
        callback: function(typeId, form) {
            this.urlParam('{type}', typeId);
            this.data(form);
        },
        token: true
    },
};

export default api;
