import formField from './form-field';
import fileField from "./file-field";
import templateElement from "./template-element";

const mixinsList = {
    formField,
    fileField,
    templateElement,
};

class Mixins {
    constructor(list) {
        this.list = list;
    }

    get(mixName, props = []) {
        let mixin = this.list[mixName] || null;
        let result = {};

        if(mixin && props.length) {
            props.forEach(el => {
                if(mixin[el]) {
                    result[el] = mixin[el];
                }
            });
        } else {
            result = mixin;
        }

        return result;
    }
}

export default new Mixins(mixinsList)
