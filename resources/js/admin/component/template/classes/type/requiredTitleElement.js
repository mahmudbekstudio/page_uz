import element from '../mainElement';

export default class textElement extends element {
    fillable = [
        {
            name: 'content',
            hide: true,
        },
    ];

    constructor(params) {
        super(params);
    }
}
