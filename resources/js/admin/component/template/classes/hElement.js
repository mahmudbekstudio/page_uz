import element from './mainElement';

export default class hElement extends element {
    fillable = [
        {
            type: 'select',
            name: 'number',
            params: {label: 'words.number', clearable: false}
        },
    ];

    constructor(params) {
        super(params);
        if (typeof params.hasLang !== 'undefined') {
            this.hasLang = params.hasLang;
        }
        const numberType = this.fillable.find(item => item.name === 'number');
        numberType.params.options = {'1': 'H1', '2': 'H2', '3': 'H3', '4': 'H4', '5': 'H5', '6': 'H6'};
        numberType.value = Object.keys(numberType.params.options)[0];
    }
}
