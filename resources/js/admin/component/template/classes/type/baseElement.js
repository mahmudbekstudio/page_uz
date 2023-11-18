import element from '../mainElement';

export default class textElement extends element {
    fillable = [
        {
            name: 'content',
            hide: true,
        },
    ];

    constructor(params, lang = null, withAllTranslations = false) {
        super(params, lang, withAllTranslations);
        if (typeof params.hasLang !== 'undefined') {
            this.hasLang = params.hasLang;
        }
    }
}
