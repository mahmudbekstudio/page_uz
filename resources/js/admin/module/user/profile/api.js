import route from '../../../api/route';

const api = {
    getProfile: {
        ...route.admin('user.getProfile'),
        token: true
    },
    updateProfile: {
        ...route.admin('user.updateProfile'),
        callback: function(data) {
            if(!data.password) {
                delete data.password;
                delete data.password_confirmation;
                delete data.old_password;
            }
            this.data(data);
        },
        token: true
    }
};

export default api;