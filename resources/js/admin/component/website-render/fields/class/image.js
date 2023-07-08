import mainFieldClass from "./mainFieldClass";

export default class image extends mainFieldClass {
    constructor(values, isSample = false, params = null, lang = null) {
        super(values, isSample, params, lang);
    }

    get value() {
        return this.values['value'] || '';
    }

    get link() {
        return this.values['link'] || null;
    }

    get css() {
        return 'background-image: url("' + this.value + '")';
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
