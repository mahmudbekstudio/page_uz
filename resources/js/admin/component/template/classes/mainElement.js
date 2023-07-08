export default class element {
    hasLang = true;
    fieldObject = {};
    id = null;
    defaultObject = {
        tag: 'div',
        params: {},
    };
    hasFillable = true;
    fillable = [];
    constructor(params = {}) {
        this.fieldObject = Object.assign({}, this.defaultObject);
        this.fieldObject.tag = params.tag || this.defaultObject.tag;
        this.fieldObject.params = params.params || this.defaultObject.params;
    }

    get tag() {
        return this.fieldObject.tag;
    }

    set tag(val) {
        this.fieldObject.tag = val;
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
}
