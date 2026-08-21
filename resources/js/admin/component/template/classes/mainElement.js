import {translate} from '../../../helper/index';
import i18n from "../../../plugin/i18n";
export default class element {
    hasLang = true;
    fieldObject = {};
    id = null;
    defaultObject = {
        tag: 'div',
        name: '',
        params: {
            text_style: [],
        },
    };
    hasFillable = true;
    fillable = [];
    lang = null;
    withAllTranslations = false;
    constructor(params = {}, lang = null, withAllTranslations = false) {
        this.lang = lang;
        this.withAllTranslations = withAllTranslations;
        this.fieldObject = Object.assign({}, this.defaultObject);
        this.fieldObject.tag = params.tag || this.defaultObject.tag;
        this.fieldObject.name = params.name || this.defaultObject.name;
        this.fieldObject.params = {...this.defaultObject.params, ...params.params};
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
        let wrapper = 'div';
        if (this.params.wrapper) {
            if (this.params.wrapper.wrapper === 'paragraph') {
                wrapper = 'p';
            } else if (this.params.wrapper.wrapper === 'header') {
                wrapper = this.params.wrapper.header;
            }
        }

        let classes = this.params.class;

        for (const styleClass of this.params.text_style) {
            classes += ' text-' + styleClass;
        }

        let subTag = 'span';

        if (this.params.wrapper && this.params.wrapper.wrapper === 'link') {
            subTag = 'a';
        }

        let result = '<' + wrapper;
        if (this.params.id) {
            result += ' id="' + this.params.id + '"';
        }

        if (classes) {
            result += ' class="' + classes + '"';
        }

        if (this.params.title) {
            result += ' title="' + this.translate(this.params.title) + '"';
        }

        result += '>';
        result += '<' + subTag;

        if (this.params.wrapper && this.params.wrapper.wrapper === 'link') {
            result += ' href="' + this.params.wrapper.linkUrl + '" target="' + this.params.wrapper.linkTarget + '"';
        }
        result += '>';
        result += '{ $' + this.name + ' }';
        result += '</' + subTag + '>';
        result += '</' + wrapper + '>';

        return result;
    }

    translate(key) {
        if (this.withAllTranslations) {
            return '<!--translateStart-->' + (typeof key === 'string' ? key : JSON.stringify(key)) + '<!--translateEnd-->';
        }

        return translate(key, i18n, this.lang);
    }
}
