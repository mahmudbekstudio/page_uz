import Route from "./route";
import auth from '../service/auth';
import route from "./route";

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

const components = {
    notUsedCategories: {
        ...route.admin('type.not-used-categories'),
        callback: function(id) {
            this.params({id});
        },
        token: true
    },
    postActiveList: {
        ...route.admin('post.active-list'),
        callback: function(typeId) {
            this.urlParam('{type}', typeId);
        },
        token: true
    },
    categoryActiveList: {
        ...route.admin('category.active-list'),
        callback: function(typeId) {
            this.urlParam('{type}', typeId);
        },
        token: true
    },
}

export default {
    default: defaultApi,
    user: userApi,
    components,
};
