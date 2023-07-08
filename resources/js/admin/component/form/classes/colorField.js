import field from './textField';

export default class colorField extends field {
    hasLang = false;
    fillable = [
        {
            type: 'color',
            name: 'value',
            params: {label: 'words.default_value'}
        },
        {
            type: 'text',
            name: 'hint',
            params: {label: 'words.hint'}
        },
        {
            type: 'text',
            name: 'placeholder',
            params: {label: 'words.placeholder'}
        },
    ]

    constructor(params) {
        super(params);
        this.fieldObject.value = this.fieldObject.value || this.defaultObject.value;
    }

    get value() {
        return this.fieldObject.value;
    }

    set value(val) {
        this.fieldObject.value = val;
    }
}
