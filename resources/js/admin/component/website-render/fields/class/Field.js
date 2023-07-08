import FieldsList from "./FieldsList";
import mainFieldClass from './mainFieldClass';

export default class Field {
    type = null;
    value = null;
    resTypeField = null;
    isSample = false;
    params = null;
    lang = null;
    constructor(type, value, isSample = false, params = null, lang = null) {
        this.type = type;
        this.value = value;
        this.isSample = isSample;
        this.params = params;
        this.lang = lang;
    }

    get typeField() {
        if (!this.resTypeField) {
            this.resTypeField =
                FieldsList[this.type] ?
                    new FieldsList[this.type](this.value, this.isSample, this.params, this.lang) :
                    new mainFieldClass(null, this.isSample, this.params, this.lang);
        }

        return this.resTypeField;
    }

    get classes() {
        return this.typeField.classes;
    }

    get html() {
        return this.typeField.html;
    }

    get css() {
        return this.typeField.css;
    }
}
