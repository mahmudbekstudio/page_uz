import field from '../mainField';

export default class requiredRouteNameField extends field {
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.label = params?.params?.label || 'Route name';
        this.fieldObject.name = this.fieldObject.name || 'routeName';
    }
}
