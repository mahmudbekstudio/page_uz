import {translate} from '../../../helper/index';
import i18n from "../../../plugin/i18n";
export default class element {
    hasLang = true;
    fieldObject = {};
    id = null;
    defaultObject = {
        tag: 'div',
        name: '',
        params: {},
    };
    hasFillable = true;
    fillable = [];
    lang = null;
    constructor(params = {}, lang = null) {
        this.lang = lang;
        this.fieldObject = Object.assign({}, this.defaultObject);
        this.fieldObject.tag = params.tag || this.defaultObject.tag;
        this.fieldObject.name = params.name || this.defaultObject.name;
        this.fieldObject.params = params.params || this.defaultObject.params;
        this.fieldObject.isKeyValue = !!params.name;
    }

    get tag() {
        return this.fieldObject.tag;
    }

    set tag(val) {
        this.fieldObject.tag = val;
    }

    get name() {
        return this.fieldObject.name;
    }

    set name(val) {
        this.fieldObject.name = val;
    }

    get params() {
        return this.fieldObject.params;
    }

    set params(val) {
        this.fieldObject.params = val;
    }

    setParam(key, value) {
        this.fieldObject.params[key] = value;
    }

    getParam(key) {
        return this.fieldObject.params[key];
    }

    set fill(val) {
        if (val.name) {
            this.name = val.name;
            delete val.name;
        }

        if (typeof val.value !== 'undefined') {
            this.value = val.value;
            delete val.value;
        }

        this.params = val;
    }

    get fill () {
        const params = {...this.params};
        params.name = this.name;
        params.value = this.value;

        return params;
    }

    set json(val) {
        this.fieldObject = val;
    }

    get json() {
        return this.fieldObject;
    }

    get html() {
        const wrapper = this.params.wrapper || 'div';
        let classes = this.params.class;

        for (const styleClass of this.params.text_style) {
            classes += ' text-' + styleClass;
        }

        const subTag = this.params.link_url ? 'a' : 'span';

        let result = '<' + wrapper;
        result += ' id="' + this.params.id + '"';
        result += ' class="' + classes + '"';
        result += ' title="' + this.translate(this.params.title) + '"';
        result += '>';
        result += '<' + subTag;

        if (this.params.link_url) {
            result += ' href="' + this.params.link_url + '" target="' + this.params.link_target + '"';
        }
        result += '>';
        result += '{ $' + this.name + ' }';
        result += '</' + subTag + '>';
        result += '</' + wrapper + '>';

        return result;
    }

    translate(key) {
        return translate(key, i18n, this.lang);
    }
}
