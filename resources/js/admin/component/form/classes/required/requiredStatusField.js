import field from '../mainField';

export default class requiredStatusField extends field {
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.label = 'Status';
        this.fieldObject.name = 'status';
        this.fieldObject.value = true;
    }
}
