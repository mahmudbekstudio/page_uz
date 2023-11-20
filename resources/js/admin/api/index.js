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
        callback: function(typeId, selectedId = 0) {
            this.urlParam('{type}', typeId);
            this.urlParam('{selectedId}', selectedId);
        },
        token: true
    },
    categoryActiveList: {
        ...route.admin('category.active-list'),
        callback: function(typeId, selectedId = 0) {
            this.urlParam('{type}', typeId);
            this.urlParam('{selectedId}', selectedId);
        },
        token: true
    },
}

export default {
    default: defaultApi,
    user: userApi,
    components,
};
