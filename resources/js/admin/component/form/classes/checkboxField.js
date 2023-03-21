import field from './mainField';

export default class checkboxField extends field {
    fillable = [
        {
            type: 'text',
            name: 'value',
            params: {label: 'Default value', hint: 'Multiple value with comma'}
        },
        {
            type: 'textarea',
            name: 'options',
            params: {label: 'Options list', hint: 'Every item in line, key:value'}
        },
    ]
    constructor(params) {
        super(params);
    }
}
