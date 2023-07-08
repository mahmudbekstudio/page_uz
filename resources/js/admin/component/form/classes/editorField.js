import field from './mainField';

export default class editorField extends field {
    hasLang = true;
    constructor(params) {
        super(params);
        if (typeof params.hasLang !== 'undefined') {
            this.hasLang = params.hasLang;
        }
    }
}
