import field from './mainField';

export default class checkboxField extends field {
    fillable = [
        {
            type: 'text',
            name: 'value',
            params: {label: 'words.default_value', hint: 'words.multiple_value_with_comma'}
        },
        {
            type: 'options',
            name: 'options',
            params: {label: 'words.options_list'}
        },
    ]
    constructor(params) {
        super(params);
        this.fieldObject.params.valueType = 'array';
        this.defaultObject.value = [];
        this.fieldObject.value = this.fieldObject.value || this.defaultObject.value;
    }
}
