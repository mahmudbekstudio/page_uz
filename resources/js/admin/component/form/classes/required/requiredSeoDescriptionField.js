import field from '../mainField';

export default class requiredSeoDescriptionField extends field {
    hasLang = true;
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.label = 'words.seo_description';
        this.fieldObject.name = 'seoDescription';
    }
}
