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
    }
}
