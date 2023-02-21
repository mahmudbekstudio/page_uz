import Vue from 'vue';

import i18n from './plugin/i18n';
import vuetify from './plugin/vuetify';
import router from './plugin/route';
import store from './plugin/store';

import App from './view/app';
import Logger from './service/logger';

Vue.use(Logger);

(new Vue({
    el: '.admin-app',
    router,
    store,
    i18n,
    vuetify,
    components: {
        App
    }
}));
