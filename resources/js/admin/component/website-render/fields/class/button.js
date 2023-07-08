import mainFieldClass from "./mainFieldClass";

export default class button extends mainFieldClass {
    shapesList = {
        'pill': {name: 'Pill', value: 'pill'},
        'rectangle': {name: 'Rectangle', value: 'rectangle'},
        'round-corner': {name: 'Round corner', value: 'round-corner'},
    };

    sizesList = {
        'small': {name: 'Small', style: {'font-size': '12px', padding: '5px 12px'}},
        'medium': {name: 'Medium', style: {'font-size': '17px', padding: '7px 21px'}},
        'large': {name: 'Large', style: {'font-size': '22px', padding: '10px 30px'}},
    };

    appearancesList = {
        'solid': {name: 'Solid button', value: 'solid'},
        'outline': {name: 'Outline', value: 'outline'},
        'text': {name: 'Text link', value: 'text'},
    };

    constructor(values, isSample = false, params = null, lang = null) {
        super(values, isSample, params, lang);
    }

    get classes() {
        const classes = {};
        const id = '#' + this.id
        classes[id] = {};
        if (this.font) {
            classes[id]['font-family'] = this.font;
            //styles.push('font-family: ' + this.font);
        }

        if (this.sizesList[this.size]) {
            for (const styleKey in this.sizesList[this.size].style) {
                classes[id][styleKey] = this.sizesList[this.size].style[styleKey];
                //styles.push(styleKey + ': ' + this.sizesList[this.size].style[styleKey]);
            }
        }

        /*if (this.color) {
            if (this.appearance === 'text' || this.appearance === 'outline') {
                styles.push('color: ' + this.color + ' !important');
            } else {
                styles.push('color: ' + 'inherit');
                styles.push('background: ' + this.color);
            }
        }*/
        if (this.backgroundColor && this.appearance === 'solid') {
            classes[id]['background'] = this.backgroundColor;
            //styles.push('background: ' + this.backgroundColor);
        }

        if (this.textColor) {
            classes[id]['color'] = this.textColor;
            //styles.push('color: ' + this.textColor);
        }

        return classes;
    }

    get font() {
        return this.values['font'] || null;
    }

    get appearance() {
        return this.values['appearance'] || null;
    }

    get shape() {
        return this.values['shape'] || null;
    }

    get backgroundColor() {
        return this.values['background-color'] || null;
    }

    get textColor() {
        return this.values['text-color'] || null;
    }

    get value() {
        return this.values['value'] ? this.translate(this.values['value']) : '';
    }

    get size() {
        return this.values['size'] || 'medium';
    }

    get html() {
        if (!this.value || !this.show) {
            return '';
        }

        let html = '<a href="#"';
        html += ' id="' + this.id + '"';
        //const styles = [];
        const classes = ['field-button button'];



        if (this.appearance && this.appearancesList[this.appearance]) {
            classes.push('button-' + this.appearancesList[this.appearance].value);
        }

        if (this.shape && this.shapesList[this.shape]) {
            classes.push('button-' + this.shapesList[this.shape].value);
        }

        /*if (styles.length) {
            html += ' style="' + styles.join('; ') + ';"';
        }*/

        html += ' class="' + classes.join(' ') + '"'
        html += '><span>' + this.value + '</span></a>';
        return html;
    }
}
