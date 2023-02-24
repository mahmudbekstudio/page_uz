import route from '../../../api/route';

const api = {
    getSettings: {
        ...route.admin('setting.get'),
        token: true
    },

    updateSettings: {
        ...route.admin('setting.update'),
        callback: function(data) {
            this.data(data);
        },
        token: true
    },
};
export default api;
