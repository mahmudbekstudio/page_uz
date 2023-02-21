import Route from "./route";
import auth from '../service/auth';

const defaultApi = {
    settings: {
        ...Route.admin('settings'),
        token: true
    },
};

const userApi = {
    refreshToken: {
        checkExcept: true,
        ...Route.api('user.refresh-token'),
        token: true
    },
    logout: {
        ...Route.api('user.logout'),
        callback: function() {
            this.params({token: auth.getRefreshToken(false)});
        }
    }
};

export default {
    default: defaultApi,
    user: userApi
};
