import http from '../../../service/http';
import app from '../../../service/app';
import store from './store';
import api from './api';
import logger from '../../../service/logger';

export default class Service {
    get(id, successCallback, errorCallback) {
        this.loading(true);
        http(api.get)
            .callback(id)
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
                    logger.error('type get', error);
                    app.errorMessage('Error');
                }
            })
            .then(() => {
                this.loading(false);
            })
    }

    submit(id, form, successCallback, errorCallback) {
        this.loading(true);
        let request = null;

        if (id) {
            request = http(api.edit).callback(id, form);
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
                    logger.error('type submit', error);
                    app.errorMessage('Error');
                }
            }).then(() => {
                this.loading(false);
            });
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
                    app.successMessage('Success');
                }
            })
            .catch(error => {
                logger.error('type delete', error);
                if (typeof successCallback === 'function') {
                    errorCallback(error);
                } else {
                    app.errorMessage('Error');
                }
            }).then(() => {
                this.loading(false);
            });
    }

    loading(isStart) {
        app.loading(isStart);
        store.dispatch('type-form/changeIsLoading', isStart)
    }
}
