import route from '../../../api/route';

const api = {
    forgotPassword: {
        ...route.api('user.forgot-password'),
        callback: function(email) {
            this.data({
                email
            });
        }
    }
};

export default api;