import route from '../../../api/route';

const api = {
    create: {
        ...route.admin('menu.create'),
        callback: function(form) {
            this.data(form);
        },
        token: true
    },
    edit: {
        ...route.admin('menu.edit'),
        callback: function(id, form) {
            this.urlParam('{menu}', id);
            this.data(form);
        },
        token: true
    },
    delete: {
        ...route.admin('menu.delete'),
        callback: function(id) {
            this.urlParam('{menu}', id);
        },
        token: true
    },
    get: {
        ...route.admin('menu.get'),
        callback: function(id) {
            this.urlParam('{menu}', id);
        },
        token: true
    },

    links: {
        ...route.admin('menu.links'),
        token: true
    },
};

export default api;
