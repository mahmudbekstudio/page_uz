import route from '../../../api/route';

const api = {
    create: {
        ...route.admin('template.create'),
        callback: function(form) {
            this.data(form);
        },
        token: true
    },
    edit: {
        ...route.admin('template.edit'),
        callback: function(id, form) {
            this.urlParam('{template}', id);
            this.data(form);
        },
        token: true
    },
    delete: {
        ...route.admin('template.delete'),
        callback: function(id) {
            this.urlParam('{template}', id);
        },
        token: true
    },
    get: {
        ...route.admin('template.get'),
        callback: function(id) {
            this.urlParam('{template}', id);
        },
        token: true
    },
    getByType: {
        ...route.admin('template.get-by-type'),
        callback: function(type) {
            this.urlParam('{type}', type);
        },
        token: true
    },
    blocks: {
        ...route.admin('template.blocks'),
        token: true
    },
    settings: {
        ...route.admin('template.settings'),
        token: true
    },
    themeConfig: {
        ...route.admin('template.theme-config'),
        token: true
    },
};

export default api;
