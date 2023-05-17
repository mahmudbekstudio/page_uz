import TypeService from '../../type/form/service';
import route from "../../../plugin/route";
import http from '../../../service/http';
import api from './api';
import app from "../../../service/app";
import logger from "../../../service/logger";
import i18n from "../../../plugin/i18n";

export default class Service {
    get(id, successCallback, errorCallback) {
        return (new TypeService()).get(id, successCallback, errorCallback);
    }

    getCategory(successCallback, errorCallback) {
        this.loading(true);
        http(api.get)
            .callback(this.typeId, this.id)
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
                    logger.error('category submit', error);
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

        if (this.isEdit) {
            request = http(api.edit).callback(this.typeId, this.id, form);
        } else {
            request = http(api.create).callback(this.typeId, form);
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
                    logger.error('category submit', error);
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
            .callback(this.typeId, id)
            .send()
            .then(response => {
                if (typeof successCallback === 'function') {
                    successCallback(response.data);
                } else {
                    app.successMessage(i18n.t('words.success'));
                }
            })
            .catch(error => {
                logger.error('category delete', error);
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

    get typeId() {
        return route.currentRoute?.params?.typeId;
    }

    get id() {
        return route.currentRoute?.params?.id;
    }

    get isEdit() {
        return !!this.id;
    }
}
