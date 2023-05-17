import field from './mainField';

export default class dateRangeField extends field {
    fillable = [
        {
            type: 'dateMultiple',
            name: 'value',
            params: {label: 'words.default_value'}
        },
    ]
    constructor(params) {
        super(params);

        this.fieldObject.params.valueType = 'array';
        this.defaultObject.value = [];
        this.fieldObject.value = this.fieldObject.value || this.defaultObject.value;
    }
}
