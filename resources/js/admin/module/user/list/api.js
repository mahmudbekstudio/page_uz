import route from '../../../api/route';

const api = {
    getById: {
        ...route.admin('user.byId'),
        callback: function(id) {
            this.urlParam('{id}', id);
        },
        token: true
    },
    create: {
        ...route.admin('user.create'),
        callback: function(data) {
            if(!data.password) {
                delete data.password;
                delete data.password_confirmation;
            }
            this.data(data);
        },
        token: true
    },
    update: {
        ...route.admin('user.update'),
        callback: function(userId, data) {
            this.urlParam('{user}', userId);
            if(!data.password) {
                delete data.password;
                delete data.password_confirmation;
            }
            this.data(data);
        },
        token: true
    },
    delete: {
        ...route.admin('user.delete'),
        callback: function(userId) {
            this.urlParam('{user}', userId);
        },
        token: true
    }
};

export default api;
