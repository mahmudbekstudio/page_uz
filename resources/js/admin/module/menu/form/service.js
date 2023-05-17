import route from "../../../plugin/route";
import http from '../../../service/http';
import api from './api';
import app from "../../../service/app";
import logger from "../../../service/logger";
import i18n from "../../../plugin/i18n";

export default class Service {
    get(successCallback, errorCallback) {
        this.loading(true);
        http(api.get)
            .callback(this.id)
            .send()
            .then(response => {
                if (typeof successCallback === 'function') {
                    successCallback(response.data);
                }
            })
            .catch(error => {
                if (typeof errorCallback === 'function') {
                    errorCallback(error);
                } else {
                    logger.error('post submit', error);
                    app.errors(error);
                }
            })
            .then(() => {
                this.loading(false);
            })
    }

    submit(form, successCallback, errorCallback) {
        this.loading(true);
        let request = null;

        if (this.id) {
            request = http(api.edit).callback(this.id, form);
        } else {
            request = http(api.create).callback(form);
        }

        request
            .send()
            .then(response => {
                if (typeof successCallback === 'function') {
                    successCallback(response.data);
                }
            })
            .catch(error => {
                if (typeof errorCallback === 'function') {
                    errorCallback(error);
                } else {
                    logger.error('post submit', error);
                    app.errors(error);
                }
            })
            .then(() => {
                this.loading(false);
            })
    }

    delete(id, successCallback, errorCallback) {
        this.loading(true);
        http(api.delete)
            .callback(id)
            .send()
            .then(response => {
                if (typeof successCallback === 'function') {
                    successCallback(response.data);
                } else {
                    app.successMessage(i18n.t('words.success'));
                }
            })
            .catch(error => {
                logger.error('menu delete', error);
                if (typeof errorCallback === 'function') {
                    errorCallback(error);
                } else {
                    app.errorMessage(i18n.t('words.error'));
                }
            }).then(() => {
                this.loading(false);
            });
    }

    links(successCallback, errorCallback) {
        this.loading(true);
        http(api.links)
            .send()
            .then(response => {
                if (typeof successCallback === 'function') {
                    successCallback(response.data);
                }
            })
            .catch(error => {
                if (typeof errorCallback === 'function') {
                    errorCallback(error);
                } else {
                    app.errorMessage(i18n.t('words.error'));
                }
            }).then(() => {
            this.loading(false);
        });
    }

    loading(isStart) {
        app.loading(isStart);
    }

    get id() {
        return route.currentRoute?.params?.menu;
    }
}
