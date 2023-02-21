import Vue from 'vue'
import VueI18n from 'vue-i18n'
import {set as setVal} from 'lodash';
import config from '../config/main';
import translations from 'Static/translations';

Vue.use(VueI18n);

function loadLocaleMessages() {
    // const locales = require.context('../translation/', true, /[A-Za-z0-9-_,\s]+\.json$/i);
    const messages = {};

    /*locales.keys().forEach(key => {
      const langKey = key.split('/')[1];
      const moduleName = key.match(/([A-Za-z0-9-_]+)\./i)[1];

      if(lang.langsList.indexOf(langKey) > -1) {
        let keys = [langKey, moduleName];
        setVal(messages, keys.join('.'), locales(key));
      }
    });*/
    for (let langKey in translations) {
        setVal(messages, langKey, translations[langKey]);
    }

    return messages
}

export default new VueI18n({
    locale: config.lang.locale,
    messages: loadLocaleMessages(),
    fallbackLocale: config.lang.fallback_locale
});
