import http from '../../../service/http';
import app from '../../../service/app';
import store from './store';
import api from './api';
import logger from '../../../service/logger';

export default class Service {
    getProfile(successCallback, failCallback) {
        this.loading(true);
        http(api.getProfile)
            .send()
            .then(response => {
                const data = response.data.data;
                store.dispatch('profile/changeForm', Object.assign({}, data, data.meta));
                if (typeof successCallback === 'function') {
                    successCallback();
                }
            })
            .catch(error => {
                logger.error('user.profile', error);
                app.errorMessage('Error');
                if (typeof failCallback === 'function') {
                    failCallback();
                }
            }).then(() => {
                this.loading(false);
            });
    }

    submit() {
        this.loading(true);
        const form = store.getters['profile/form'];
        http(api.updateProfile)
            .callback(form)
            .send()
            .then(response => {
                const data = response.data;

                if(data.result) {
                    store.dispatch('profile/changeForm', data.data);
                    const storageUser = store.getters['storage/user'];
                    storageUser.meta = Object.assign({}, data.data.meta);
                    store.dispatch('storage/changeUser', storageUser);
                    app.openMessage('Updated');
                } else {
                    let errorMsgs = [];

                    for(let val in data.message) {
                        if(data.message.hasOwnProperty(val)) {
                            if(typeof data.message[val] === 'string') {
                                errorMsgs.push(data.message[val]);
                            } else {
                                for(let val2 in data.message[val]) {
                                    if(data.message[val].hasOwnProperty(val2)) {
                                        errorMsgs.push(data.message[val][val2]);
                                    }
                                }
                            }
                        }
                    }

                    app.errorMessage(errorMsgs.join('<br />'));
                }
            }).catch(error => {
                logger.error('updateProfile', error);
                app.errorMessage('Error');
            }).then(() => {
                this.loading(false);
        })
    }

    loading(isStart) {
        app.loading(isStart);
        store.dispatch('profile/changeIsLoading', isStart)
    }
}