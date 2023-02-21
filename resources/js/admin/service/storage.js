import * as constants from '../constants';
import {parseJson, isObject} from '../helper';

class Storage {
    constructor() {
        this.prefix = constants.STORAGE_PREFIX + '--';
    }

    install(Vue) {
        Vue.prototype.$storage = this;
    }

    set(name, val) {
        val = isObject(val) ? JSON.stringify(val) : val;
        window.localStorage.setItem(this.prefix + name, val);
    }

    get(name) {
        let val = window.localStorage.getItem(this.prefix + name);
        return parseJson(val) || val;
    }

    remove(name) {
        window.localStorage.removeItem(this.prefix + name);
    }

    clear() {
        window.localStorage.clear();
    }
}

export default new Storage();
