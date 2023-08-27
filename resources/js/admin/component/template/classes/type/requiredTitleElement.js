import element from '../mainElement';

export default class requiredTitleElement extends element {
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
