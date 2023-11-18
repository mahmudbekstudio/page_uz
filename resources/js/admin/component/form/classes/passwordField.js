import textField from './textField';

export default class passwordField extends textField {
    hasLang = false;
    fillable = [
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
    }
}
