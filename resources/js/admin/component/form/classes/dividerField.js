import field from './mainField';

export default class dividerField extends field {
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.label = 'words.divider';
        this.fieldObject.name = 'divider_' + (new Date()).getTime();
    }
}
