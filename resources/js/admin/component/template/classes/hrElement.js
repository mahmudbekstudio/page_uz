import element from './mainElement';

export default class hrElement extends element {
    fillable = [
        {
            name: 'content',
            hide: true
        },
    ];
    hasLang = false;
    constructor(params) {
        super(params);
    }
}
