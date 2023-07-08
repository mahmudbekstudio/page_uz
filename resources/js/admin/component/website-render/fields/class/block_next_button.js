import mainFieldClass from "./mainFieldClass";

export default class block_next_button extends mainFieldClass {
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
            classes[id]['color'] = this.color + ' !important';
        }

        return classes;
    }

    get html() {
        if (!this.show) {
            return '';
        }

        let html = '<a id="' + this.id + '" href="#"';
        const classes = ['arrow-button'];
        html += ' class="' + classes.join(' ') + '"'
        html += '></a>';
        return html;
    }
}
