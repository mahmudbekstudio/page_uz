import store from '../../../plugin/store';

store.registerModule('type-form', {
    namespaced: true,

    state: {
        isLoading: false,
        /*submitDisabled: true,

        form: {
            email: '',
            first_name: '',
            last_name: '',
            old_password: '',
            password: '',
            password_confirmation: ''
        },
        errors: {
            email: '',
            first_name: '',
            last_name: '',
            old_password: '',
            password: '',
            password_confirmation: ''
        }*/
    },

    mutations: {
        /*changeSubmitDisabled(state, val) {
            state.submitDisabled = !!val;
        },
        changeForm(state, form) {
            state.form.email = typeof form.email !== 'undefined' ? form.email : state.form.email;
            state.form.first_name = typeof form.first_name !== 'undefined' ? form.first_name : state.form.first_name;
            state.form.last_name = typeof form.last_name !== 'undefined' ? form.last_name : state.form.last_name;
            state.form.old_password = typeof form.old_password !== 'undefined' ? form.old_password : state.form.old_password;
            state.form.password = typeof form.password !== 'undefined' ? form.password : state.form.password;
            state.form.password_confirmation = typeof form.password_confirmation !== 'undefined' ? form.password_confirmation : state.form.password_confirmation;
        },
        changeErrors(state, errors) {
            state.errors.email = typeof errors.email !== 'undefined' ? errors.email : state.errors.email;
            state.errors.first_name = typeof errors.first_name !== 'undefined' ? errors.first_name : state.errors.first_name;
            state.errors.last_name = typeof errors.last_name !== 'undefined' ? errors.last_name : state.errors.last_name;
            state.errors.old_password = typeof errors.old_password !== 'undefined' ? errors.old_password : state.errors.old_password;
            state.errors.password = typeof errors.password !== 'undefined' ? errors.password : state.errors.password;
            state.errors.password_confirmation = typeof errors.password_confirmation !== 'undefined' ? errors.password_confirmation : state.errors.password_confirmation;
        },
        */
        changeIsLoading(state, val) {
            state.isLoading = !!val;
        }
    },

    actions: {
        /*changeSubmitDisabled({commit}, val) {
            commit('changeSubmitDisabled', val)
        },

        changeForm({commit}, form) {
            commit('changeForm', form)
        },

        changeErrors({commit}, errors) {
            commit('changeErrors', errors)
        },

        */
        changeIsLoading({commit}, val) {
            commit('changeIsLoading', val)
        },
    },

    getters: {
        isLoading(state) {
            return state.isLoading;
        },
        /*submitDisabled(state) {
            return state.submitDisabled;
        },

        form(state) {
            return state.form;
        },
        errors(state) {
            return state.errors;
        }*/
    }
});

export default store;
