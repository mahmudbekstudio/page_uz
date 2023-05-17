import field from '../mainField';
import validation from "../../../../config/validation";
import api from '../../../../api';
import http from '../../../../service/http';
import { listToTree } from '../../../../helper';
import mainConfig from "../../../../config/main";
import store from "../../../../plugin/store";
import i18n from "../../../../plugin/i18n";

export default class advancedChildOfField extends field {
    fillable = [
        {
            type: 'text',
            name: 'name',
            disabled: true,
            value: 'childOf',
            params: {label: 'words.name', rules: [validation.required('words.name')]}
        },
        {
            type: 'text',
            name: 'label',
            disabled: true,
            value: 'Category',
            params: {label: 'words.label', rules: [validation.required('words.label')]}
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
            params: {label: 'words.category_type', options: {}, rules: [validation.required('words.category_type')]}
        },
    ];
    constructor(params) {
        super(params);

        this.fieldObject.params.valueType = 'int';
        this.defaultObject.value = 0;
        this.fieldObject.value = this.fieldObject.value || this.defaultObject.value;
        const languagesList = store.getters['view/website'].metas.languages_list;
        const labelType = this.fillable.find(item => item.name === 'label');

        if (languagesList.length) {
            labelType.value = {};
            for (const langCode of languagesList) {
                labelType.value[langCode] = i18n.t('words.category', langCode);
            }
        }

        if (typeof params.isConstructor !== 'undefined') {
            if (this.isConstructor) {
                const categoryType = this.fillable.find(item => item.name === 'child_of');
                http(api.components.notUsedCategories)
                    .callback(params.params.child_of)
                    .send()
                    .then(response => {
                        for (const item of response.data.data.list) {
                            categoryType.params.options[item.id] = item.title;
                        }

                        categoryType.params.options = {...categoryType.params.options};
                    })
                    .catch(error => {
                        console.log(error);
                    });
            } else {
                this.setParam('label', 'words.category');
                this.setParam('options', {});
                http(api.components.categoryActiveList)
                    .callback(params.params.child_of)
                    .send()
                    .then(response => {
                        const result = [];
                        const tree = listToTree(response.data.data.categories);

                        for (const item of tree) {
                            result.push({text: item.title, value: String(item.id), disabled: mainConfig.app.parentPageDeepLimit < item.deep});
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
