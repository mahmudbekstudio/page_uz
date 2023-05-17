import field from '../mainField';
import http from "../../../../service/http";
import api from "../../../../api";
import route from '../../../../plugin/route';
import { listToTree } from '../../../../helper';
import mainConfig from '../../../../config/main';

export default class advancedParentField extends field {
    hasFillable = false;
    constructor(params) {
        super(params);

        this.fieldObject.params.valueType = 'int';
        this.defaultObject.value = 0;
        this.fieldObject.params.label = 'words.select_parent';
        this.fieldObject.name = 'parent';
        this.fieldObject.value = this.fieldObject.value || this.defaultObject.value;

        if (typeof params.isConstructor !== 'undefined' && !this.isConstructor) {

            const routeNames = route.currentRoute.name.split('.');
            const name = routeNames.length && routeNames[0] === 'category' ? 'category' : 'post';

            this.setParam('options', {});
            http(api.components[name + 'ActiveList'])
                .callback(route.currentRoute.params.typeId)
                .send()
                .then(response => {
                    const result = [];
                    const key = name === 'post' ? 'posts' : 'categories';
                    const tree = listToTree(response.data.data[key]);

                    for (const item of tree) {
                        const disabled = mainConfig.app.parentPageDeepLimit < item.deep || item.ids.indexOf(parseInt(route.currentRoute.params?.id)) > -1;
                        result.push({text: item.name, value: String(item.id), disabled});
                    }

                    this.setParam('options', result);
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
}
