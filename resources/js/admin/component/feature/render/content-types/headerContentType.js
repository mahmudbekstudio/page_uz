import i18n from "../../../../plugin/i18n";
export default function (domValue, value) {
    let html = '<' + value.headerValue;

    if (domValue.id) {
        html += ' id="' + domValue.id + '"';
    }

    if (domValue.class) {
        html += ' class="' + domValue.class + '"';
    }

    if (domValue.title) {
        html += ' title="' + i18n.t(domValue.title) + '"';
    }

    if (domValue.style) {
        html += ' style="' + domValue.style + '"';
    }

    html += '';
    html += '>';
    html += i18n.t(value.textValue);
    html += '</' + value.headerValue + '>';

    return html;
}
