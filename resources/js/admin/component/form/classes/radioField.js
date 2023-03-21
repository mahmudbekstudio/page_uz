import field from './mainField';

export default class radioField extends field {
    fillable = [
        {
            type: 'textarea',
            name: 'options',
            params: {label: 'Options list', hint: 'Every item in line, key:value'}
        },
    ]
    constructor(params) {
        super(params);
    }
}
