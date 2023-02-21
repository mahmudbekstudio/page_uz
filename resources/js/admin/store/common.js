const defaultStates = {
    isLoading: false
};

export default {
    namespaced: true,

    state: Object.assign({}, defaultStates),

    mutations: {
        changeIsLoading(state, val) {
            val = !!val;
            state.isLoading = val;
        }
    },

    actions: {
        startLoading({state, commit, rootState}) {
            commit('changeIsLoading', true);
        },

        stopLoading({state, commit, rootState}) {
            commit('changeIsLoading', false);
        },

        toggleLoading({state, commit, rootState}) {
            commit('changeIsLoading', !state.isLoading);
        }
    },

    getters: {
        isLoading(state) {
            return state.isLoading;
        }
    }
}
