import mainFieldClass from "./mainFieldClass";

export default class container extends mainFieldClass {
    constructor(values, isSample = false, params = null, lang = null) {
        super(values, isSample, params || '', lang);
    }

    get classes() {
        const classes = {};
        const id = '#' + this.id

        classes[id] = {
            'min-height': '30px',
        };

        if (this.isSample) {
            classes[id + '.sample-field-container'] = {
                'background-color': '#999',
                'margin': '0 5px',
            };
        }

        return classes;
    }

    get html() {
        return '<div class="field-container' + (this.isSample ? ' sample-field-container' : '') + '" id="' + this.id + '">' + this.params + '</div>';
    }
}
