import Vue from 'vue';

import i18n from './plugin/i18n';
import vuetify from './plugin/vuetify';
import router from './plugin/route';
import store from './plugin/store';
import moment from './plugin/moment';
import Editor from '@tinymce/tinymce-vue';

import App from './view/app';
import Logger from './service/logger';

Vue.use(Logger);
Vue.use(moment);

import fileSelect from "./component/file-select";
import fileManager from "./component/file-manager/file-manager";
Vue.component('file-select', fileSelect);
Vue.component('tinyMceEditor', Editor);
Vue.component('file-manager', fileManager);

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
