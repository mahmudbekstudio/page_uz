import field from './mainField';

export default class datetimeField extends field {
    fillable = [
        {
            type: 'datetime',
            name: 'value',
            params: {label: ['words.date_picker', 'words.time_picker'], hint: 'words.split_by_comma'}
        },
    ]
    constructor(params) {
        super(params);
    }
}
