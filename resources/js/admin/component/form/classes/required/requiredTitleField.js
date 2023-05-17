import field from '../mainField';

export default class requiredTitleField extends field {
    hasLang = true;
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.validation = {required: null, ...(this.fieldObject.params.validation || {})};
        this.fieldObject.params.label = 'words.title';
        this.fieldObject.name = 'title';
    }
}
