import field from './mainField';

export default class selectField extends field {
    fillable = [
        {
            type: 'text',
            name: 'value',
            params: {label: 'Default value', hint: 'Multiple value with comma'}
        },
        {
            type: 'switch',
            name: 'multiple',
            value: false,
            params: {label: 'Multiple'}
        },
        {
            type: 'textarea',
            name: 'options',
            params: {label: 'Options list', hint: 'Every item in line, key:value'}
        },
    ]
    constructor(params) {
        super(params);

        if (params?.params?.multiple) {
            this.fieldObject.params.valueType = 'array';
            this.defaultObject.value = [];
            this.fieldObject.value = this.fieldObject.value || this.defaultObject.value;
        }
    }
}
