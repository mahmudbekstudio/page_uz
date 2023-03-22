import field from '../mainField';

export default class requiredTitleField extends field {
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.label = 'Title';
        this.fieldObject.name = 'title';
    }
}
