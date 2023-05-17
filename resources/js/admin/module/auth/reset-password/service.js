import http from '../../../service/http';
import app from '../../../service/app';
import store from './store';
import api from './api';
import logger from '../../../service/logger';
import auth from "../../../service/auth";
import i18n from "../../../plugin/i18n";

export default class Service {
    submit() {
        this.loading(true);
        const form = store.getters['reset-password/form'];
        http(api.resetPassword)
            .callback(form.token, form.email, form.password, form.password_confirmation)
            .send()
            .then(response => {
                const data = response.data;

                if(data.result) {
                    store.dispatch('reset-password/changeForm', {token: '', email: '', password: '', password_confirmation: ''});
                    auth.login(data.data.token, data.data.user);
                    app.openMessage(data.message.join('<br />'));
                } else {
                    store.dispatch('reset-password/changeErrors', {password_confirmation: ' ', password: ' '});
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
                logger.error('api.reset-password', error);
                app.errorMessage(i18n.t('words.error'));
            }).then(() => {
                this.loading(false);
        })
    }

    loading(isStart) {
        app.loading(isStart);
        store.dispatch('reset-password/changeIsLoading', isStart)
    }
}
