import route from "../../../plugin/route";
import http from '../../../service/http';
import BaseService from "../../../service/BaseService";
import api from './api';
import app from "../../../service/app";
import logger from "../../../service/logger";
import i18n from "../../../plugin/i18n";
import typeApi from '../../type/form/api';
import store from './store';

export default class Service extends BaseService {
    //themeConfig = null;
    settings (successCallback, errorCallback) {
        this.callback().request(api.settings, successCallback, errorCallback);
    }
/*    getThemeConfig(successCallback, errorCallback) {
        this.callback(this.selectedTheme).request(api.themeConfig, response => {
            this.themeConfig = response.data.theme_config;
            return typeof successCallback === 'function' ? successCallback(response) : null;
        }, errorCallback);
    }*/
    blocks(successCallback, errorCallback) {
        this.callback().request(api.blocks, successCallback, errorCallback);
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
        this.callback('layout').request(api.getByType, successCallback, errorCallback);
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
