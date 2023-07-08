import element from './mainElement';

export default class divElement extends element {
    fillable = []
    constructor(params) {
        super(params);
        if (typeof params.hasLang !== 'undefined') {
            this.hasLang = params.hasLang;
        }
    }
}
