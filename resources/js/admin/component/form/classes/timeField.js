import field from './mainField';

export default class datetimeField extends field {
    fillable = [
        {
            type: 'time',
            name: 'value',
            params: {label: 'Default value'}
        },
    ]
    constructor(params) {
        super(params);
    }
}
