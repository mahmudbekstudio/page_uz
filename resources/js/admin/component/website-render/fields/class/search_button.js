import mainFieldClass from "./mainFieldClass";

export default class search_button extends mainFieldClass {
    constructor(values, isSample = false, params = null, lang = null) {
        super(values, isSample, params, lang);
    }

    get color() {
        return this.values['color'] || null;
    }

    get classes() {
        const classes = {};
        const id = '#' + this.id
        classes[id] = {};

        if (this.color) {
            classes[id]['color'] = this.color;
        }

        return classes;
    }

    get html() {
        let result = '<a href="#" id="' + this.id + '" class="field-search_button">';
        result += '<i class="bi bi-search"></i>';
        result += '</a>';


        return result;
    }
}
