import { translate } from '../../../../helper/index';
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
    withAllTranslations = false;
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
        if (this.withAllTranslations) {
            return '<!--translateStart-->' + (typeof key === 'string' ? key : JSON.stringify(key)) + '<!--translateEnd-->';
        }

        return translate(key, i18n, this.lang);
    }
}
