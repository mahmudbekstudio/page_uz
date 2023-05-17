import field from '../mainField';

export default class requiredTemplateField extends field {
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.valueType = 'int';
        this.defaultObject.value = 0;
        this.fieldObject.params.label = 'words.template';
        this.fieldObject.name = 'template';
        this.fieldObject.value = this.fieldObject.value || this.defaultObject.value;
    }
}
