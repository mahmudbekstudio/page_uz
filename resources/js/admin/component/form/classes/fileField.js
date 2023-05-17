import field from './mainField';
import {FILE_TYPE_LIST, FILE_DEFAULT_TYPE} from '../../../constants';
import * as _ from 'lodash';

export default class fileField extends field {
    fillable = [
        {
            type: 'file',
            name: 'value',
            params: {label: 'words.default_value', multiple: false, fileType: FILE_DEFAULT_TYPE}
        },
        {
            type: 'switch',
            name: 'multiple',
            params: {label: 'words.multiple'},
            events: {change: e => this.fillable[0].params.multiple = e}
        },
        {
            type: 'select',
            name: 'fileType',
            value: FILE_DEFAULT_TYPE,
            params: {label: 'words.type', options: _.zipObject(FILE_TYPE_LIST, FILE_TYPE_LIST)},
            events: {change: e => this.fillable[0].params.fileType = e}
        },
    ]
    constructor(params) {
        super(params);
        this.fieldObject.params.valueType = 'array';
        this.defaultObject.value = [];
        this.fieldObject.value = this.fieldObject.value || this.defaultObject.value;
    }
}
