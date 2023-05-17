import http from '../../../service/http';
import app from '../../../service/app';
import auth from '../../../service/auth';
import store from './store';
import api from './api';
import logger from '../../../service/logger';
import i18n from "../../../plugin/i18n";

export default class Service {
    submit() {
        this.loading(true);
        const form = store.getters['login/form'];
        http(api.login)
            .callback(form.email, form.password)
            .send()
            .then(response => {
                const data = response.data;
                if(data.result) {
                    store.dispatch('login/changeForm', {email: '', password: ''});
                    auth.login(data.data.token, data.data.user);
                } else {
                    store.dispatch('login/changeErrors', {email: ' ', password: ' '});
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
                    console.log('errorMsgs', errorMsgs);
                    app.errorMessage(errorMsgs.join('<br />'));
                }
            }).catch(error => {
                logger.error('api.login', error);
            console.log('errorMsgs2', error);
                app.errorMessage(i18n.t('words.error'));
            }).then(() => {
                this.loading(false);
        })
    }

    loading(isStart) {
        app.loading(isStart);
        store.dispatch('login/changeIsLoading', isStart)
    }
}
