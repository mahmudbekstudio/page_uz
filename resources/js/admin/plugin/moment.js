import moment from 'moment';
import i18n from "./i18n";

moment.locale(i18n.locale);

export default function install (Vue) {
    Object.defineProperties(Vue.prototype, {
        $moment: {
            get () {
                return moment;
            }
        }
    })
}
