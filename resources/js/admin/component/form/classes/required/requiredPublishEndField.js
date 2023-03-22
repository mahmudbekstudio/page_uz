import field from '../mainField';

export default class requiredPublishEndField extends field {
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.label = 'Publish end date';
        this.fieldObject.name = 'publishEnd';
    }
}
