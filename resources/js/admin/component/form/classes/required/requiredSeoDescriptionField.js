import field from '../mainField';

export default class requiredSeoDescriptionField extends field {
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.label = 'SEO description';
        this.fieldObject.name = 'seoDescription';
    }
}
