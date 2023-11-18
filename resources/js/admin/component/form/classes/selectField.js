import field from './mainField';

export default class selectField extends field {
    fillable = [
        {
            type: 'text',
            name: 'value',
            params: {label: 'words.default_value', hint: 'words.multiple_value_with_comma'}
        },
        {
            type: 'switch',
            name: 'multiple',
            value: false,
            params: {label: 'words.multiple'}
        },
        {
            type: 'options',
            name: 'options',
            params: {label: 'words.options_list'}
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
