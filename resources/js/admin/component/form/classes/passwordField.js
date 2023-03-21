import textField from './textField';

export default class passwordField extends textField {
    fillable = [
        {
            type: 'text',
            name: 'hint',
            params: {label: 'Hint'}
        },
        {
            type: 'text',
            name: 'placeholder',
            params: {label: 'Placeholder'}
        },
    ]
    constructor(params) {
        super(params);
    }
}
