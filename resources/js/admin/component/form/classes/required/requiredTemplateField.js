import field from '../mainField';
import route from "../../../../plugin/route";
import http from "../../../../service/http";
import templateApi from '../../../../module/template/js/api';

export default class requiredTemplateField extends field {
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.valueType = 'int';
        this.defaultObject.value = 0;
        this.fieldObject.params.label = 'words.template';
        this.fieldObject.name = 'template';
        this.fieldObject.value = this.fieldObject.value || this.defaultObject.value;

        if (typeof params.isConstructor !== 'undefined' && !this.isConstructor) {
            const routeNames = route.currentRoute.name.split('.');
            const type = routeNames.length && routeNames[0] === 'category' ? 'category' : 'post';

            this.setParam('options', {});
            http(templateApi.getByType)
                .callback(type)
                .send()
                .then(response => {
                    const result = [];

                    for (const item of response.data.data.list) {
                        result.push({text: item.name, value: item.id});
                    }

                    this.setParam('options', result);
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
}
