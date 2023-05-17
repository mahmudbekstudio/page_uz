import field from '../mainField';

export default class requiredStatusField extends field {
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.valueType = 'bool';
        this.defaultObject.value = true;
        this.fieldObject.params.label = 'words.status';
        this.fieldObject.name = 'status';
        this.fieldObject.value = this.fieldObject.value || this.defaultObject.value;
    }
}
