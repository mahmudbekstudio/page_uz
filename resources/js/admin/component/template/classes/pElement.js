import element from './mainElement';

export default class pElement extends element {
    fillable = []
    constructor(params, lang = null, withAllTranslations = false) {
        super(params, lang, withAllTranslations);
        if (typeof params.hasLang !== 'undefined') {
            this.hasLang = params.hasLang;
        }
    }
}
