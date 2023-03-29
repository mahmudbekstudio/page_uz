import store from "../plugin/store";

export default {
    check: (response, params, http) => {
        const typeNavigation = response?.setting?.typeNavigation;
        return !!typeNavigation && typeNavigation.length;
    },
    callback: (response, params, http) => {
        store.dispatch('view/changeTypeNavigation', response.setting.typeNavigation);
        return response;
    }
}
