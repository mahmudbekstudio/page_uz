import field from '../mainField';

export default class requiredSeoKeywordField extends field {
    hasLang = true;
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.label = 'words.seo_keywords';
        this.fieldObject.name = 'seoKeyword';
    }
}
