import field from './mainField';

export default class checkboxField extends field {
    fillable = [
        {
            type: 'text',
            name: 'value',
            params: {label: 'words.default_value', hint: 'words.multiple_value_with_comma'}
        },
        {
            type: 'textarea',
            name: 'options',
            hasLang: false,
            params: {label: 'words.options_list', hint: 'words.every_item_in_line'}
        },
    ]
    constructor(params) {
        super(params);
    }
}
