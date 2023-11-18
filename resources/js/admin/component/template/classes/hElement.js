import element from './mainElement';

export default class hElement extends element {
    fillable = [
        {
            type: 'select',
            name: 'number',
            params: {label: 'words.number', clearable: false}
        },
        {
            name: 'wrapper',
            hide: true
        }
    ];

    constructor(params, lang = null, withAllTranslations = false) {
        super(params, lang, withAllTranslations);
        if (typeof params.hasLang !== 'undefined') {
            this.hasLang = params.hasLang;
        }
        const numberType = this.fillable.find(item => item.name === 'number');
        numberType.params.options = {'1': 'H1', '2': 'H2', '3': 'H3', '4': 'H4', '5': 'H5', '6': 'H6'};
        numberType.value = Object.keys(numberType.params.options)[0];
    }

    get html() {
        let result = '<h' + this.params.number;
        if (this.params.id) {
            result += ' id="' + this.params.id + '"';
        }
        if (this.params.class) {
            result += ' class="' + this.params.class + '"';
        }
        if (this.params.title) {
            result += ' title="' + this.translate(this.params.title) + '"';
        }
        result += '>';
        result += this.translate(this.params.content);
        result += '</h' + this.params.number + '>';
        return result;
    }
}
