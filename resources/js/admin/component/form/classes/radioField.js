import field from './mainField';

export default class radioField extends field {
    fillable = [
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
