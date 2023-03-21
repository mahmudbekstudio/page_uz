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
    }

    get value() {
        return this.fieldObject.value || 0;
    }

    set value(val) {
        this.fieldObject.value = val;
    }
}
