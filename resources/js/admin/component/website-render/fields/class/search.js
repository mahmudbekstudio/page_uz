import mainFieldClass from "./mainFieldClass";

export default class search extends mainFieldClass {
    constructor(values, isSample = false, params = null, lang = null) {
        super(values, isSample, params, lang);
    }

    get html() {
        let form = '<div class="field-search">';
        form += '<form>';
        form += '<div class="input-group">';
        form += '<input type="text" name="s" class="form-control form-control-sm" placeholder="Search">';
        form += '<div class="input-group-append">';
        form += '<button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>';
        form += '</div>';
        form += '</div>';
        form += '</form>';
        form += '</div>';
        return form;
    }
}
