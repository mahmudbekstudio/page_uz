import field from './mainField';

export default class textareaField extends field {
    hasLang = true;
    fillable = [
        {
            type: 'number',
            name: 'rows',
            value: 5,
            hasLang: false,
            params: {label: 'words.rows'}
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
        if (typeof params.hasLang !== 'undefined') {
            this.hasLang = params.hasLang;
        }
    }
}
