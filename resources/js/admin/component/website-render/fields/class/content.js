import mainFieldClass from "./mainFieldClass";

export default class content extends mainFieldClass {
    constructor(values, isSample = false, params = null, lang = null) {
        super(values, isSample, params, lang);
    }

    get classes() {
        return {};
    }

    get html() {
        return '<div class="field-content" id="' + this.id + '">Content</div>';
    }
}
