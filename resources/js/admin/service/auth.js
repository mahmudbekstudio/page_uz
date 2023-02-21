import http from './http';
import app from './app';
import viewConfig from '../config/view';
import store from '../plugin/store';
import logger from './logger';
import router from '../plugin/route';

class Auth {
    install(Vue, options) {
        Vue.prototype.$auth = this
    }

    isLogged() {
        return !!this.getTokenObj();
    }

    getTokenObj() {
        return store.getters['storage/token'];
    }

    getTokenField() {
        return 'Authorization';
    }

    getAccessToken(withPrefix = true) {
        let tokenObj = this.getTokenObj();

        if (!tokenObj) return null;
        const tokenType = tokenObj.token_type.charAt(0).toUpperCase() + tokenObj.token_type.slice(1);
        return withPrefix ? tokenType + ' ' + tokenObj.access_token : tokenObj.access_token;
    }

    getRefreshToken(withPrefix = true) {
        return this.getAccessToken(withPrefix);
    }

    login(token, user, redirect = true) {
        store.dispatch('storage/changeToken', token);
        store.dispatch('storage/changeUser', user);
        store.dispatch('setting/init', {});

        app.settings(() => {
            redirect && router.push({name: viewConfig.page.default});
        }, {name: ''});
    }

    isAccessTokenExpired() {
        let tokenObj = this.getTokenObj();

        if (!tokenObj) return null;

        let createdAt = new Date(tokenObj.token_created);
        createdAt.setMinutes(createdAt.getMinutes() + parseInt(tokenObj.expires_in));

        return (new Date()).getTime() > createdAt.getTime();
    }

    logout(sendRequest = true) {
        app.loading(true);
        if(sendRequest && this.isLogged()) {
            http('user.logout')
                .callback()
                .send()
                .then(response => {
                    logger.info(response);
                })
                .catch(error => {
                    logger.log(error);
                })
                .then(() => {
                    app.loading(false);
                    this.clearAndRedirect();
                });
        } else {
            app.loading(false);
            this.clearAndRedirect();
        }
    }

    clearAndRedirect() {
        store.dispatch('storage/clear');
        app.redirectToLogin();
    }

    isRefreshTokenExpired() {
        let tokenObj = this.getTokenObj();

        if (!tokenObj) return null;

        let createdAt = new Date(tokenObj.token_created);
        createdAt.setMinutes(createdAt.getMinutes() + parseInt(tokenObj.refresh_expires_in));

        return (new Date()).getTime() > createdAt.getTime();
    }

    check(to, from, next) {
        this.checkAccess(
            () => {
                next();
            },
            () => {
                const routes = router.options.routes;
                let meta = null;
                for (let i = 0; i < routes.length; i++) {
                    if (routes[i].name === viewConfig.page.login) {
                        meta = routes[i].meta;
                        break;
                    }

                    if (routes[i].children) {
                        for (let j = 0; j < routes[i].children.length; j++) {
                            if (routes[i].children[i].name === viewConfig.page.login) {
                                meta = routes[i].children[i].meta;
                                break;
                            }
                        }
                    }

                    if (meta) {
                        break;
                    }
                }

                to = Object.assign({}, to);
                to.meta = meta;
                app.routeInit(to, from, next);
                this.logout(false);
            }
        );
    }

    checkAccess(successCallback, failCallback) {
        if (!this.isLogged() || this.isRefreshTokenExpired()) {
            failCallback();
        } else if (this.isAccessTokenExpired()) {
            app.loading(true);
            let headers = {};
            headers[this.getTokenField()] = this.getRefreshToken();

            http('user.refreshToken').headers(headers).send()
                .then(response => {
                    if (response.data.result) {
                        this.login(response.data.data.token, response.data.data.user, false);
                        successCallback()
                    } else {
                        failCallback();
                    }
                })
                .catch(error => {
                    logger.log(error);
                    failCallback();
                })
                .then(() => {
                    app.loading(false);
                });
        } else {
            successCallback()
        }
    }
}

export default new Auth();
