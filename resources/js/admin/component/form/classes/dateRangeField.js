import field from './mainField';

export default class dateRangeField extends field {
    fillable = [
        {
            type: 'dateRange',
            name: 'value',
            params: {label: 'Default value'}
        },
    ]
    constructor(params) {
        super(params);
    }
}
