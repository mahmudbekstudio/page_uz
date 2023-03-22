import field from '../mainField';

export default class requiredTemplateField extends field {
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.label = 'Template';
        this.fieldObject.name = 'template';
    }
}
