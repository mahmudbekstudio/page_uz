import store from '../../../plugin/store';

store.registerModule('forgot-password', {
    namespaced: true,

    state: {
        submitDisabled: true,
        isLoading: false,
        form: {
            email: ''
        },
        errors: {
            email: ''
        }
    },

    mutations: {
        changeSubmitDisabled(state, val) {
            state.submitDisabled = !!val;
        },
        changeForm(state, form) {
            state.form.email = typeof form.email !== 'undefined' ? form.email : state.form.email;
        },
        changeErrors(state, errors) {
            state.errors.email = typeof errors.email !== 'undefined' ? errors.email : state.errors.email;
        },
        changeIsLoading(state, val) {
            state.isLoading = !!val;
        }
    },

    actions: {
        changeSubmitDisabled({commit}, val) {
            commit('changeSubmitDisabled', val)
        },

        changeForm({commit}, form) {
            commit('changeForm', form)
        },

        changeErrors({commit}, errors) {
            commit('changeErrors', errors)
        },

        changeIsLoading({commit}, val) {
            commit('changeIsLoading', val)
        },
    },

    getters: {
        submitDisabled(state) {
            return state.submitDisabled;
        },
        isLoading(state) {
            return state.isLoading;
        },
        form(state) {
            return state.form;
        },
        errors(state) {
            return state.errors;
        }
    }
});

export default store;