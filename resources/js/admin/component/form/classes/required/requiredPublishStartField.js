import field from '../mainField';

export default class requiredPublishStartField extends field {
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.label = 'Publish start date';
        this.fieldObject.name = 'publishStart';
    }
}
