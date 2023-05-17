import field from '../mainField';

export default class requiredPublishEndField extends field {
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.label = 'words.publish_end_date';
        this.fieldObject.name = 'publishEnd';
    }
}
