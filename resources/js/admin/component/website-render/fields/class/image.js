import mainFieldClass from "./mainFieldClass";
import store from '../../../../plugin/store';

export default class image extends mainFieldClass {
    constructor(values, isSample = false, params = null, lang = null) {
        super(values, isSample, params, lang);
    }

    get value() {
        if (Array.isArray(this.values) && this.values.length) {
            const value = this.values[0];
            let imageUrl = value['folderPath'] + '/' + value['name'] + '.' + value['extension'];
            if (value['is_local']) {
                return imageUrl;
            } else {
                return store.getters['view/website'].fileBaseUrl + imageUrl
            }
        }

        return '';
    }

    get link() {
        if (Array.isArray(this.values) && this.values.length) {
            return this.values[0]['link'] || null;
        }

        return null;
    }

    get css() {
        return this.value ? 'background-image: url("' + this.value + '")' : '';
    }

    get classes() {
        const classes = {};
        const id = '#' + this.id
        classes[id + '-image'] = {
            'max-width': '100%',
        };
        classes[id + '-link'] = {};

        return classes;
    }

    get html() {
        const img = '<img id="' + this.id + '-image" src="' + this.value + '">';

        if (this.link) {
            return '<a id="' + this.id + '-link" href="' + this.link + '">' + img + '</a>';
        }

        return img;
    }
}
