import http from '../../../service/http';
import app from '../../../service/app';
import store from './store';
import api from './api';
import logger from '../../../service/logger';
import i18n from '../../../plugin/i18n';

export default class Service {
    getSettings(successCallback, failCallback) {
        this.loading(true);
        http(api.getSettings)
            .send()
            .then(response => {
                const data = response.data.data;
                /*store.dispatch('main-setting/changeForm', data.metas);
                store.dispatch('main-setting/changeData', {
                    languages: data.languages,
                    timezones: data.timezones,
                    date_formats: data.date_formats,
                    time_formats: data.time_formats,
                    pages: data.pages,
                    images_sizes: data.images_sizes
                })*/
                if (typeof successCallback === 'function') {
                    successCallback(data);
                }
            })
            .catch(error => {
                logger.error('main-settings', error);
                app.errorMessage(i18n.t('words.error'));
                if (typeof failCallback === 'function') {
                    failCallback();
                }
            })
            .then(() => {
                this.loading(false);
                store.dispatch('main-setting/changeIsFormChanged', false);
            });
    }

    submit(successCallback, failCallback) {
        this.loading(true);
        http(api.updateSettings)
            .callback(store.getters['main-setting/form'])
            .send()
            .then(response => {
                const data = response.data.data;
                if (typeof successCallback === 'function') {
                    successCallback(data, response.data.setting);
                }
            })
            .catch(error => {
                logger.error('main-settings', error);
                app.errorMessage(i18n.t('words.error'));
                if (typeof failCallback === 'function') {
                    failCallback();
                }
            })
            .then(() => {
                this.loading(false);
                store.dispatch('main-setting/changeIsFormChanged', false);
            });
    }

    loading(isStart) {
        app.loading(isStart);
        store.dispatch('main-setting/changeIsLoading', isStart)
    }

    formChanged(isChanged) {
        store.dispatch('main-setting/formChanged', isChanged)
    }
}
