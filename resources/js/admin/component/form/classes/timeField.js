import field from './mainField';

export default class datetimeField extends field {
    fillable = [
        {
            type: 'time',
            name: 'value',
            params: {label: 'words.default_value'}
        },
    ]
    constructor(params) {
        super(params);
    }
}
