export default class field {
    hasLang = false;
    fieldObject = {};
    id = null;
    isConstructor = false;
    defaultObject = {
        type: 'text',
        name: '',
        disabled: false,
        value: '',
        params: {
            valueType: 'string',
        },
        events: {}
    };
    hasFillable = true;
    fillable = [];
    constructor(params = {}) {
        this.fieldObject = Object.assign({}, this.defaultObject);
        this.fieldObject.type = params.type || this.defaultObject.type;
        this.fieldObject.name = params.name || this.defaultObject.name;
        this.fieldObject.disabled = params.disabled || this.defaultObject.disabled;
        this.fieldObject.value = typeof params.value === 'undefined' ? this.defaultObject.value : params.value;
        this.fieldObject.params = params.params || this.defaultObject.params;
        this.fieldObject.params.valueType = this.fieldObject.params.valueType || this.defaultObject.params.valueType;
        this.fieldObject.events = params.events || this.defaultObject.events;
        this.isConstructor = !!params.isConstructor;
    }

    get type() {
        return this.fieldObject.type;
    }

    set type(val) {
        this.fieldObject.type = val;
    }

    get name() {
        return this.fieldObject.name;
    }

    set name(val) {
        this.fieldObject.name = val;
    }

    get disabled() {
        return this.fieldObject.disabled;
    }

    set disabled(val) {
        this.fieldObject.disabled = !!val;
    }

    get value() {
        return this.fieldObject.value;
    }

    set value(val) {
        this.fieldObject.value = val;
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

    get events() {
        return this.fieldObject.events;
    }

    set events(val) {
        this.fieldObject.events = val;
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

    setEvents(key, value) {
        this.fieldObject.events[key] = value;
    }

    click(clickFunction) {
        this.setEvents('click', clickFunction);
    }

    mouseup(mouseupFunction) {
        this.setEvents('mouseup', mouseupFunction);
    }

    mousedown(mousedownFunction) {
        this.setEvents('mousedown', mousedownFunction);
    }

    set json(val) {
        this.fieldObject = val;
    }

    get json() {
        return this.fieldObject;
    }
}
