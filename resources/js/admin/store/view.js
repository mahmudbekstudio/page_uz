import viewSettings from '../config/view';
import * as constants from '../constants';
import {cache} from '../helper';
import i18n from "../plugin/i18n";

const defaultStates = {
    website: null,
    typeNavigation: [],
    drawer: true,
    title: '',
    websiteTitle: '',
    layout: viewSettings.defaultLayout,
    footerInset: viewSettings.footerInset,
    isDark: viewSettings.isDark,
    isMini: viewSettings.isMini,
    containerFillHeight: false,
    loading: false,
    snackbar: {
        show: false,
        color: viewSettings.snackbar.color,
        showButton: viewSettings.snackbar.showButton,
        timeout: viewSettings.snackbar.timeout,
        slot: ''
    },
    confirm: {
        show: false,
        question: '',
        yesCallback: () => {},
        noCallback: () => {}
    }
};

export default {
    namespaced: true,

    state: Object.assign({}, defaultStates),

    mutations: {
        changeTitle(state, title) {
            state.title = title;
        },
        changeWebsiteTitle(state, websiteTitle) {
            state.websiteTitle = websiteTitle;
        },
        changeDrawer(state, val) {
            state.drawer = val;
        },
        changeWebsite(state, val) {
            state.website = val;
        },
        changeTypeNavigation(state, val) {
            state.typeNavigation = val;
        },
        changeLayout(state, val) {
            state.layout = (viewSettings.layoutsList.indexOf(val) > -1 ? val : viewSettings.defaultLayout) + 'Layout';
        },
        changeFooterInset(state, val) {
            state.footerInset = !!val;
        },
        changeIsDark(state, val) {
            state.isDark = !!val;
        },
        changeContainerFillHeight(state, val) {
            state.containerFillHeight = !!val;
        },
        changeLoading(state, val) {
            state.loading = !!val;
        },
        changeSnackbar(state, val) {
            state.snackbar = {
                show: val.show || false,
                color: val.color && constants.SNACKBAR_COLORS[val.color] ? val.color : viewSettings.snackbar.color,
                showButton: typeof val.showButton !== 'undefined' ? val.showButton : viewSettings.snackbar.showButton,
                timeout: typeof val.timeout !== 'undefined' ? val.timeout : viewSettings.snackbar.timeout,
                slot: val.slot || ''
            };
        },
        changeConfirm(state, val) {
            state.confirm = {
                show: val.show,
                question: val.question,
                yesCallback: val.yesCallback,
                noCallback: val.noCallback
            };
        },
    },

    actions: {
        updateDrawer({commit}, val) {
            commit('changeDrawer', val);
        },
        toggleDrawer({commit, state}) {
            commit('changeDrawer', !state.drawer);
        },
        changeWebsite({commit}, val) {
            if (!val.lang) {
                val.lang = cache('current-lang') || i18n.locale;
            }
            commit('changeWebsite', val);
        },
        changeTypeNavigation({commit}, val) {
            commit('changeTypeNavigation', val);
        },
        changeLayout({commit}, val) {
            commit('changeLayout', val);
        },
        changeFooterInset({commit}, val) {
            commit('changeFooterInset', val);
        },
        changeIsDark({commit}, val) {
            commit('changeIsDark', val);
        },
        changeContainerFillHeight({commit}, val) {
            commit('changeContainerFillHeight', val);
        },
        changeTitle({commit}, title) {
            commit('changeTitle', title);
        },
        changeWebsiteTitle({commit}, title) {
            commit('changeWebsiteTitle', title);
        },
        changeLoading({commit}, val) {
            commit('changeLoading', val);
        },
        openSnackbar({ commit }, val) {
            commit('changeSnackbar', {
                show: true,
                color: val.color && constants.SNACKBAR_COLORS[val.color] ? val.color : viewSettings.snackbar.color,
                showButton: typeof val.showButton !== 'undefined' ? val.showButton : viewSettings.snackbar.showButton,
                timeout: typeof val.timeout !== 'undefined' ? val.timeout : viewSettings.snackbar.timeout,
                slot: val.slot || ''
            });
        },
        closeSnackbar({ commit }) {
            commit('changeSnackbar', {
                show: false,
                color: viewSettings.snackbar.color,
                showButton: viewSettings.snackbar.showButton,
                timeout: viewSettings.snackbar.timeout,
                slot: ''
            });
        },
        changeConfirm({commit}, val) {
            commit('changeConfirm', {
                show: val.show,
                question: val.question,
                yesCallback: val.yesCallback,
                noCallback: val.noCallback
            })
        },
        setCurrentLang({commit, state}, val) {
            cache('current-lang', val);
            i18n.locale = val;
            commit('changeWebsite', {...state.website, lang: val});
        }
    },

    getters: {
        website(state) {
            return state.website;
        },
        typeNavigation(state) {
            return state.typeNavigation;
        },
        drawer(state) {
            return state.drawer;
        },
        title(state) {
            return state.title;
        },
        websiteTitle(state) {
            return state.websiteTitle;
        },
        layout(state) {
            return state.layout;
        },
        footerInset(state) {
            return state.footerInset;
        },
        isDark(state) {
            return state.isDark;
        },
        isMini(state) {
            return state.isMini;
        },
        containerFillHeight(state) {
            return state.containerFillHeight;
        },
        loading(state) {
            return state.loading;
        },
        snackbar(state) {
            return state.snackbar;
        },
        confirm(state) {
            return state.confirm;
        }
    }
}
