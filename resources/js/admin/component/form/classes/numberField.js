import field from './textField';

export default class numberField extends field {
    fillable = [
        {
            type: 'number',
            name: 'value',
            params: {label: 'Default value'}
        },
        {
            type: 'text',
            name: 'hint',
            params: {label: 'Hint'}
        },
        {
            type: 'text',
            name: 'placeholder',
            params: {label: 'Placeholder'}
        },
    ]

    constructor(params) {
        super(params);
        this.fieldObject.params.valueType = 'int';
        this.defaultObject.value = 0;
        this.fieldObject.value = this.fieldObject.value || this.defaultObject.value;
    }

    get value() {
        return this.fieldObject.value || 0;
    }

    set value(val) {
        this.fieldObject.value = val;
    }
}
