import app from "../../../service/app";
import route from "../../../plugin/route";
import http from "../../../service/http";
import api from "./api";
import i18n from "../../../plugin/i18n";
import logger from "../../../service/logger";

export default class Service {
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
                logger.error('feature delete', error);
                if (typeof errorCallback === 'function') {
                    errorCallback(error);
                } else {
                    app.errorMessage(i18n.t('words.error'));
                }
            }).then(() => {
            this.loading(false);
        });
    }

    getTypesList(featureType, successCallback, errorCallback) {
        this.loading(true);
        http(api.getTypesList)
            .callback(featureType)
            .send()
            .then(response => {
                if (typeof successCallback === 'function') {
                    successCallback(response.data);
                }
            })
            .catch(error => {
                logger.error('get types list delete', error);
                if (typeof errorCallback === 'function') {
                    errorCallback(error);
                } else {
                    app.errorMessage(i18n.t('words.error'));
                }
            })
            .then(() => {
                this.loading(false);
            });
    }

    getTypeDetail(typeId, successCallback, errorCallback) {
        this.loading(true);
        http(api.getTypeDetail)
            .callback(typeId)
            .send()
            .then(response => {
                if (typeof successCallback === 'function') {
                    successCallback(response.data);
                }
            })
            .catch(error => {
                logger.error('get type detail', error);
                if (typeof errorCallback === 'function') {
                    errorCallback(error);
                } else {
                    app.errorMessage(i18n.t('words.error'));
                }
            })
            .then(() => {
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
