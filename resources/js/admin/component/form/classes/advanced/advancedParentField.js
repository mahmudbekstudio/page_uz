import field from '../mainField';

export default class advancedParentField extends field {
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.label = 'Select parent';
        this.fieldObject.name = 'parent';
    }
}
