import http from '../../../service/http';
import app from '../../../service/app';
import store from './store';
import api from './api';
import logger from '../../../service/logger';

export default class Service {
    submit() {
        this.loading(true);
        const form = store.getters['forgot-password/form'];
        http(api.forgotPassword)
            .callback(form.email)
            .send()
            .then(response => {
                const data = response.data;
                if(data.result) {
                    store.dispatch('forgot-password/changeForm', {email: '', password: ''});
                    app.openMessage(data.message.join('<br />'));
                    app.redirectToLogin();
                } else {
                    store.dispatch('forgot-password/changeErrors', {email: ' ', password: ' '});
                    let errorMsgs = [];

                    for(let val in data.message) {
                        if(data.message.hasOwnProperty(val)) {
                            errorMsgs.push(data.message[val]);
                        }
                    }

                    app.errorMessage(errorMsgs.join('<br />'));
                }
            }).catch(error => {
                logger.error('api.forgotPassword', error);
                app.errorMessage('Error');
            }).then(() => {
                this.loading(false);
        })
    }

    loading(isStart) {
        app.loading(isStart);
        store.dispatch('forgot-password/changeIsLoading', isStart)
    }
}