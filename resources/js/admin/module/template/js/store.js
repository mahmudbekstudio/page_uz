import store from '../../../plugin/store';
import app from "../../../service/app";
import i18n from "../../../plugin/i18n";


store.registerModule('template', {
    namespaced: true,

    state: {
        changed: false,
    },

    mutations: {
        changed(state, value) {
            state.changed = !!value;
        }
    },

    actions: {
        changed({commit}, value) {
            commit('changed', value)
        },
    },

    getters: {
        changed(state) {
            return state.changed;
        }
    },
});

export default store;
