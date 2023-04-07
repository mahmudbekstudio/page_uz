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
        this.fieldObject.params.valueType = 'array';
        this.defaultObject.value = [];
        this.fieldObject.value = this.fieldObject.value || this.defaultObject.value;
    }
}
