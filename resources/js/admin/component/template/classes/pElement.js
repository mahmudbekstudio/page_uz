import element from './mainElement';

export default class pElement extends element {
    fillable = []
    constructor(params) {
        super(params);
        if (typeof params.hasLang !== 'undefined') {
            this.hasLang = params.hasLang;
        }
    }
}
