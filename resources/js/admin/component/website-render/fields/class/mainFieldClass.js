import i18n from "../../../../plugin/i18n";
let fieldTimer = null;
let fieldIndex = 0;

export default class mainFieldClass {
    values = null;
    show = true;
    resId = '';
    isSample = false;
    params = null;
    lang = null;
    constructor(values, isSample = false, params = null, lang = null) {
        this.values = values;
        this.isSample = isSample;
        this.params = params;
        this.lang = lang;
    }

    get id() {
        if (!fieldTimer) {
            fieldTimer = (new Date()).getTime();
        }

        if (!this.resId) {
            this.resId = 'field-' + this.constructor.name + '-' + fieldTimer + '-' + (++fieldIndex);
        }

        return this.resId;
    }

    get classes() {
        return {};
    }

    get html() {
        return 'Unknown';
    }

    get css() {
        return '';
    }

    translate(key) {
        const selectedLang = this.lang || i18n.locale;
        if (key && (typeof key === 'object') && Object.keys(key).length) {
            if (typeof key[selectedLang] !== 'undefined') {
                return key[selectedLang];
            }

            return Object.values(key)[0];
        }

        return i18n.t(key, selectedLang);
    }
}
