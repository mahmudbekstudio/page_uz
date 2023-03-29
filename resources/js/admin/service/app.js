import store from '../plugin/store';
import auth from './auth';
import router from '../plugin/route';
import viewConfig from '../config/view';
import http from './http';
import logger from './logger';
import * as constants from '../constants';

class App {
    install(Vue) {
        Vue.prototype.$app = this
    }

    settings(successCallback, to) {
        if (store.getters['setting/inited']) {
            if (typeof successCallback === 'function') {
                successCallback();
            }
        } else if(store.getters['storage/token']) {
            http('default.settings')
                .callback()
                .send()
                .then(response => {
                    store.dispatch('storage/changeUser', response.data.data.user);
                    if (typeof successCallback === 'function') {
                        successCallback();
                    }
                })
                .catch(error => {
                    logger.error(error);
                    auth.logout(false);
                })
                .then(() => {
                    store.dispatch('setting/init', {});
                });
        } else {
            store.dispatch('setting/init', {});
            successCallback();
        }
    }

    redirectToLogin() {
        if(router.history.current.name !== viewConfig.page.login) {
            router.push({name: viewConfig.page.login});
        }
    }

    loading(isStart) {
        store.dispatch('view/changeLoading', isStart);
    }

    routeInit(to, from, next) {
        const metaLayout = to.meta && to.meta.layout ? to.meta.layout : viewConfig.defaultLayout;
        const metaTitle = to.meta && to.meta.title ? to.meta.title : '';

        store.dispatch('view/changeLayout', metaLayout);
        store.dispatch('view/changeTitle', metaTitle);
    }

    snackbar(params) {
        store.dispatch('view/openSnackbar', params);
    }

    openMessage(text, color = constants.SNACKBAR_COLORS.info, timeout = viewConfig.snackbar.timeout) {
        if (typeof text !== 'string') {
            let textsList = [];

            for (let key in text) {
                textsList.push(text[key]);
            }

            text = textsList.join('<br />');
        }

        this.snackbar({
            color: color,
            slot: text,
            timeout: timeout
        })
    }

    infoMessage(text, timeout = viewConfig.snackbar.timeout) {
        this.openMessage(text, constants.SNACKBAR_COLORS.info, timeout);
    }

    successMessage(text, timeout = viewConfig.snackbar.timeout) {
        this.openMessage(text, constants.SNACKBAR_COLORS.success, timeout);
    }

    errorMessage(text, timeout = viewConfig.snackbar.timeout) {
        this.openMessage(text, constants.SNACKBAR_COLORS.error, timeout);
    }

    errors(errors) {
        errors = errors?.response?.data?.errors;

        if (!errors) {
            this.errorMessage('Error');
            return false;
        }

        let messages = [];
        for (let errorKey in errors) {
            messages.push(errors[errorKey].join("\n"));
        }

        this.errorMessage(messages.join("\n"));
    }

    closeMessage() {
        store.dispatch('view/closeSnackbar');
    }

    openConfirm(question, yesCallback, noCallback) {
        store.dispatch('view/changeConfirm', {
            show: true,
            question,
            yesCallback: () => {
                if(typeof yesCallback === 'function') {
                    yesCallback();
                }

                this.closeConfirm();
            },
            noCallback: () => {
                if(typeof noCallback === 'function') {
                    noCallback();
                }

                this.closeConfirm();
            }
        })
    }

    closeConfirm() {
        store.dispatch('view/changeConfirm', {
            show: false,
            question: '',
            yesCallback: () => {},
            noCallback: () => {}
        })
    }
}

export default new App();
