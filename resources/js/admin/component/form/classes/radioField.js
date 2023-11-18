import field from './mainField';

export default class radioField extends field {
    fillable = [
        {
            type: 'options',
            name: 'options',
            hasLang: false,
            params: {label: 'words.options_list'}
        },
    ]
    constructor(params) {
        super(params);
    }
}
