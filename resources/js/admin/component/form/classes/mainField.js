export default class field {
    fieldObject = {};
    defaultObject = {
        type: 'text',
        name: '',
        disabled: false,
        value: null,
        params: {},
        events: {}
    };
    constructor(params = {}) {
        this.fieldObject = Object.assign({}, this.defaultObject);
        this.fieldObject.type = params.type || this.defaultObject.type;
        this.fieldObject.name = params.name || this.defaultObject.name;
        this.fieldObject.disabled = params.disabled || this.defaultObject.disabled;
        this.fieldObject.value = params.value || this.defaultObject.value;
        this.fieldObject.params = params.params || this.defaultObject.params;
        this.fieldObject.events = params.events || this.defaultObject.events;
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
