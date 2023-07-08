import mainFieldClass from "./mainFieldClass";
import i18n from "../../../../plugin/i18n";

export default class text extends mainFieldClass {
    stylesList = {
        b: {name: 'Bold', style: {'font-weight': 'bold'}},
        i: {name: 'Italic', style: {'font-style': 'italic'}},
    };
    constructor(values, isSample = false, params = null, lang = null) {
        super(values, isSample, params, lang);
    }

    get link() {
        return this.values['link'] || null;
    }

    get font() {
        return this.values['font'] || null;
    }

    get size() {
        return this.values['size'] || null;
    }

    get style() {
        return this.values['style'] || [];
    }

    get color() {
        return this.values['color'] || null;
    }

    get value() {
        return this.values['value'] ? this.translate(this.values['value']) : '';
    }

    get classes() {
        const classes = {};
        const id = '#' + this.id
        classes[id] = {};

        if (this.font) {
            classes[id]['font-family'] = this.font;
        }

        if (this.size) {
            classes[id]['font-size'] = this.size + 'px';
        }

        if (this.style.length) {
            for (const style of this.style) {
                if (this.stylesList[style]) {
                    for (const styleKey in this.stylesList[style].style) {
                        classes[id][styleKey] = this.stylesList[style].style[styleKey];
                    }
                }
            }
        }

        if (this.color) {
            classes[id]['color'] = this.color;
        }

        return classes;
    }

    get html() {
        if (!this.value || !this.show) {
            return '';
        }

        let tag = 'span';

        if (this.link) {
            tag = 'a href="' + this.link + '"';
        }

        return '<' + tag + ' id="' + this.id + '">' + this.value + '</' + tag + '>';
    }
}
