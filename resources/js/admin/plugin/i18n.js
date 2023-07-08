import Vue from 'vue'
import VueI18n from 'vue-i18n'
import {set as setVal} from 'lodash';
import config from '../config/main';
import translations from 'Static/translations';
import store from './store';
import {cache} from "../helper";

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

Vue.prototype.$t = function (key) {
    var i18n = this.$i18n;

    try {
        //console.log(key, key === 'object', Object.keys(key));
        if (key && (typeof key === 'object') && Object.keys(key).length) {
            if (typeof key[i18n.locale] !== 'undefined') {
                return key[i18n.locale];
            }

            return Object.values(key)[0];
        }
        /*if (typeof key !== 'string' && typeof key !== 'number') {
            console.log(key);
            key = JSON.stringify(key);
        }*/

        if (key && typeof key === 'string' && key.startsWith('{') && key.endsWith('}')) {
            key = JSON.parse(key);

            if (typeof key[i18n.locale] !== 'undefined') {
                return key[i18n.locale];
            }

            return Object.values(key)[0];
        }
    } catch (e) {
        console.log(e);
    }


    var values = [], len = arguments.length - 1;
    while ( len-- > 0 ) values[ len ] = arguments[ len + 1 ];

    let result = i18n._t.apply(i18n, [ key, i18n.locale, i18n._getMessages(), this ].concat( values ));

    if (typeof result === 'object') {
        return key;
    }

    return result;
};

export default new VueI18n({
    locale: store.getters['view/website']?.lang || cache('current-lang') || config.lang.locale,
    messages: loadLocaleMessages(),
    fallbackLocale: config.lang.fallback_locale,
    silentTranslationWarn: true,
});
