import store from '../../../plugin/store';

store.registerModule('main-setting', {
    namespaced: true,

    state: {
        submitDisabled: true,
        isFormChanged: false,
        isLoading: false,
        form: {},
        errors: {},

        languages: [],
        timezones: [],
        date_formats: [],
        time_formats: [],
        pages: [],
        images_sizes: [],
        statuses: [],
    },

    mutations: {
        changeSubmitDisabled(state, val) {
            state.submitDisabled = !!val;
        },
        changeForm(state, form) {
            state.form = form;
        },
        changeData(state, data) {
            for(let key in data) {
                state[key] = data[key];
            }
        },
        changeErrors(state, errors) {
            state.errors = errors;
        },
        changeIsLoading(state, val) {
            state.isLoading = !!val;
        },
        isFormChanged(state, val) {
            state.isFormChanged = !!val;
        }
    },

    actions: {
        changeSubmitDisabled({commit}, val) {
            commit('changeSubmitDisabled', val)
        },

        changeForm({commit, dispatch}, form) {
            commit('changeForm', form);
            dispatch('changeIsFormChanged', true);
        },

        changeData({commit}, data) {
            commit('changeData', data);
        },

        changeErrors({commit}, errors) {
            commit('changeErrors', errors)
        },

        changeIsLoading({commit}, val) {
            commit('changeIsLoading', val)
        },

        changeIsFormChanged({commit}, val) {
            commit('isFormChanged', val)
        },
    },

    getters: {
        submitDisabled(state) {
            return state.submitDisabled;
        },
        isLoading(state) {
            return state.isLoading;
        },
        isFormChanged(state) {
            return state.isFormChanged;
        },
        form(state) {
            return state.form;
        },
        errors(state) {
            return state.errors;
        },
        languages(state) {
            return state.languages;
        }
    }
});

export default store;
