import field from './mainField';

export default class textField extends field {
    constructor(params) {
        super(params);
    }

    get hint() {
        return this.getParam('hint');
    }

    set hint(val) {
        this.setParam('hint', val);
    }

    get label() {
        return this.getParam('label');
    }

    set label(val) {
        this.setParam('label', val);
    }

    get placeholder() {
        return this.getParam('placeholder');
    }

    set placeholder(val) {
        this.setParam('placeholder', val);
    }

    get rules() {
        return this.getParam('rules');
    }

    set rules(val) {
        this.setParam('rules', val);
    }

    blur(blurFunction) {
        this.setEvents('blur', blurFunction);
    }

    focus(focusFunction) {
        this.setEvents('focus', focusFunction);
    }

    keydown(keydownFunction) {
        this.setEvents('keydown', keydownFunction);
    }

    input(inputFunction) {
        this.setEvents('input', inputFunction);
    }

    change(changeFunction) {
        this.setEvents('change', changeFunction);
    }
}
