import mainFieldClass from "./mainFieldClass";

export default class account_button extends mainFieldClass {
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
        let result = '<a href="#" id="' + this.id + '" class="field-account_button">';
        result += '<i class="bi bi-person-circle"></i>';
        result += '</a>';
        return result;
    }
}
