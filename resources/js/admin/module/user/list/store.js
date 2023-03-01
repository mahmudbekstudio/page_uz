import store from '../../../plugin/store';

store.registerModule('users-list', {
    namespaced: true,

    state: {
        isLoading: false,
    },

    mutations: {
        changeIsLoading(state, val) {
            state.isLoading = !!val;
        },
    },

    actions: {
        changeIsLoading({commit}, val) {
            commit('changeIsLoading', val)
        },
    },

    getters: {
        isLoading(state) {
            return state.isLoading;
        },
    },
});

export default store;
