import field from './mainField';

export default class switchField extends field {
    fillable = [
        {
            type: 'switch',
            name: 'value',
            params: {label: 'words.default_value'}
        },
    ]
    constructor(params) {
        super(params);

        this.fieldObject.params.valueType = 'bool';
        this.defaultObject.value = true;

        const value = this.fieldObject.value;

        if (typeof value === 'string' && (value === 'true' || value === 'false')) {
            this.fieldObject.value = value === 'true';
        } else if(typeof value === 'number') {
            this.fieldObject.value = !!value;
        } else if(typeof value === 'boolean') {
            this.fieldObject.value = value;
        } else {
            this.fieldObject.value = this.defaultObject.value;
        }
    }
}
