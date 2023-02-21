import route from '../../../api/route';

const api = {
    resetPassword: {
        ...route.api('user.reset-password'),
        callback: function(token, email, password, password_confirmation) {
            this.data({
                token,
                email,
                password,
                password_confirmation
            });
        }
    }
};

export default api;