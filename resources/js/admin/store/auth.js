const defaultStates = {
    isLogged: false
};

export default {
    namespaced: true,

    state: Object.assign({}, defaultStates),

    mutations: {
        changeIsLogged(state, val) {
            state.isLogged = !!val;
        }
    },

    actions: {
        changeIsLogged({commit}, val) {
            commit('changeIsLogged', val)
        }
    },

    getters: {
        isLogged(state) {
            return state.isLogged
        }
    }
}
