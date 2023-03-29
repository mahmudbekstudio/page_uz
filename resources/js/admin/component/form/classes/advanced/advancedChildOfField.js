import field from '../mainField';
import validation from "../../../../config/validation";
import typeApi from '../../../../module/type/form/api';
import http from '../../../../service/http';
import {temporaryCache} from "../../../../helper";

export default class advancedChildOfField extends field {
    fillable = [
        {
            type: 'text',
            name: 'name',
            disabled: true,
            value: 'childOf',
            params: {label: 'Name', rules: [validation.required('Name')]}
        },
        {
            type: 'text',
            name: 'label',
            disabled: true,
            value: 'Child of',
            params: {label: 'Label', rules: [validation.required('Label')]}
        },
        {
            type: 'validation',
            name: 'validation',
            hide: true,
        },
        {
            type: 'text',
            name: 'value',
            hide: true,
        },
        {
            type: 'select',
            name: 'child_of',
            params: {label: 'Category type', options: {}, rules: [validation.required('Category type')]}
        },
    ];
    constructor(params) {
        super(params);

        if (typeof params.isConstructor !== 'undefined') {
            if (this.isConstructor) {
                const categoryType = this.fillable.find(item => item.name === 'child_of');
                http(typeApi.notUsedCategories)
                    .send()
                    .then(response => {
                        for (const item of response.data.data.list) {
                            categoryType.params.options[item.id] = item.name;
                        }

                        categoryType.params.options = {...categoryType.params.options};
                    })
                    .catch(error => {
                        console.log(error);
                    });
            } else {
                console.log('load categories list');
            }
        }
    }
}
