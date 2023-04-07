import field from '../mainField';
import validation from "../../../../config/validation";
import api from '../../../../api';
import http from '../../../../service/http';
import { listToTree } from '../../../../helper';
import mainConfig from "../../../../config/main";

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
            value: 'Category',
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

        this.fieldObject.params.valueType = 'int';
        this.defaultObject.value = 0;
        this.fieldObject.value = this.fieldObject.value || this.defaultObject.value;

        if (typeof params.isConstructor !== 'undefined') {
            if (this.isConstructor) {
                const categoryType = this.fillable.find(item => item.name === 'child_of');
                http(api.components.notUsedCategories)
                    .callback(params.params.child_of)
                    .send()
                    .then(response => {
                        const tree = listToTree(response.data.data.list);
                        for (const item of tree) {
                            categoryType.params.options[item.id] = item.name;
                        }

                        categoryType.params.options = {...categoryType.params.options};
                    })
                    .catch(error => {
                        console.log(error);
                    });
            } else {
                this.setParam('label', 'Category');
                this.setParam('options', {});
                http(api.components.categoryActiveList)
                    .callback(params.params.child_of)
                    .send()
                    .then(response => {
                        const result = [];
                        const tree = listToTree(response.data.data.categories);

                        for (const item of tree) {
                            result.push({text: item.name, value: String(item.id), disabled: mainConfig.app.parentPageDeepLimit < item.deep});
                        }

                        this.setParam('options', result);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        }
    }
}
