const defaultStates = {
    inited: false,
};

export default {
    namespaced: true,

    state: Object.assign({}, defaultStates),

    mutations: {
        changeInited(state) {
            state.inited = true;
        },
    },

    actions: {
        init({commit}, setting) {
            commit('changeInited');
        },
    },

    getters: {
        inited(state) {
            return state.inited;
        },
    }
}