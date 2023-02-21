import formField from './form-field';

const mixinsList = {
    formField
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
