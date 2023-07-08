import mainFieldClass from "./mainFieldClass";

export default class icon_list extends mainFieldClass {
    constructor(values, isSample = false, params = null, lang = null) {
        super(values, isSample, params, lang);
    }

    get list() {
        return this.values['list'] || [];
    }

    get color() {
        return this.values['color'] || null;
    }

    get classes() {
        const classes = {};
        const linkClass = '.' + this.id + '-link';
        classes[linkClass] = {};

        if (this.color) {
            classes[linkClass]['color'] = this.color + ' !important';
            classes[linkClass + ':hover'] = {
                'color': this.color + ' !important'
            };
        }

        return classes;
    }

    get html() {
        let result = '<div class="field-icon_list">';

        for (const item of this.list) {
            result += this.getListItem(item)
        }

        result += '</div>';

        return result;
    }

    getListItem(item) {
        let result = '<i class="bi bi-' + item.icon + '"></i>';

        if (item.link) {
            result = '<a href="' + item.link + '" class="field-icon_list-link ' + this.id + '-link">' + result + '</a>';
        }

        return result;
    }
}
