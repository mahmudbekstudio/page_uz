import field from '../mainField';

export default class requiredSeoKeywordField extends field {
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.label = 'SEO keywords';
        this.fieldObject.name = 'seoKeyword';
    }
}
