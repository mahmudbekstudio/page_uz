import store from '../../../plugin/store';

store.registerModule('reset-password', {
    namespaced: true,

    state: {
        submitDisabled: true,
        isLoading: false,
        form: {
            token: '',
            email: '',
            password: '',
            password_confirmation: ''
        },
        errors: {
            password: '',
            password_confirmation: ''
        }
    },

    mutations: {
        changeSubmitDisabled(state, val) {
            state.submitDisabled = !!val;
        },
        changeForm(state, form) {
            state.form.token = typeof form.token !== 'undefined' ? form.token : state.form.token;
            state.form.email = typeof form.email !== 'undefined' ? form.email : state.form.email;
            state.form.password = typeof form.password !== 'undefined' ? form.password : state.form.password;
            state.form.password_confirmation =
                typeof form.password_confirmation !== 'undefined' ?
                    form.password_confirmation :
                    state.form.password_confirmation;
        },
        changeErrors(state, errors) {
            state.errors.password =
                typeof errors.password !== 'undefined' ?
                    errors.password :
                    state.errors.password;
            state.errors.password_confirmation =
                typeof errors.password_confirmation !== 'undefined' ?
                    errors.password_confirmation :
                    state.errors.password_confirmation;
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