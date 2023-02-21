import route from '../../../api/route';

const api = {
    login: {
        ...route.api('user.login'),
        callback: function(email, password) {
            this.data({
                email,
                password
            });
        }
    }
};

export default api;