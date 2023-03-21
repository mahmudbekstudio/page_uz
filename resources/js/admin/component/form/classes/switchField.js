import field from './mainField';

export default class switchField extends field {
    fillable = [
        {
            type: 'switch',
            name: 'value',
            params: {label: 'Default value'}
        },
    ]
    constructor(params) {
        super(params);
    }
}
