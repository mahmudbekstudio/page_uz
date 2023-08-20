import route from "../../../plugin/route";
import http from '../../../service/http';
import api from './api';
import app from "../../../service/app";
import logger from "../../../service/logger";
import i18n from "../../../plugin/i18n";
import typeApi from '../../type/form/api';

export default class Service {
    blocks(successCallback, errorCallback) {
        this.loading(true);
        http(api.blocks)
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
                    logger.error('template blocks', error);
                    app.errors(error);
                }
            })
            .then(() => {
                this.loading(false);
            })
    }
    get(successCallback, errorCallback, id = null) {
        this.loading(true);
        http(api.get)
            .callback(id || this.id)
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
                    logger.error('template submit', error);
                    app.errors(error);
                }
            })
            .then(() => {
                this.loading(false);
            })
    }

    getAllTypes(type, successCallback, errorCallback) {
        this.loading(true);
        http(typeApi.getByType)
            .callback(type)
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
                    logger.error('template submit', error);
                    app.errors(error);
                }
            })
            .then(() => {
                this.loading(false);
            })
    }

    getType(typeId, successCallback, errorCallback) {
        this.loading(true);
        http(typeApi.get)
            .callback(typeId)
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
                    logger.error('template submit', error);
                    app.errors(error);
                }
            })
            .then(() => {
                this.loading(false);
            })
    }

    getAllLayouts(successCallback, errorCallback) {
        this.loading(true);
        http(api.getByType)
            .callback('layout')
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
                    logger.error('template getAllLayouts', error);
                    app.errors(error);
                }
            })
            .then(() => {
                this.loading(false);
            });
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
                logger.error('template delete', error);
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
        return route.currentRoute?.params?.id;
    }

    get type() {
        return route.currentRoute?.params?.type;
    }
}
