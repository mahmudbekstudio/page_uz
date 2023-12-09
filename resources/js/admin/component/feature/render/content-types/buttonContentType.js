import i18n from "../../../../plugin/i18n";
export default function (domValue, value) {
    let html = '<a';

    if (domValue.id) {
        html += ' id="' + domValue.id + '"';
    }

    if (domValue.title) {
        html += ' title="' + i18n.t(domValue.title) + '"';
    }

    if (domValue.style) {
        html += ' style="' + domValue.style + '"';
    }

    html += ' href="' + value.buttonLink + '" target="' + value.linkTarget + '"';

    const classesList = ['btn', 'btn-' + value.typeValue];

    if (value.buttonSize === 'lg' || value.buttonSize === 'lg') {
        classesList.push('btn-' + value.buttonSize);
    }

    if (domValue.class) {
        classesList.push(domValue.class);
    }

    html += ' class="' + classesList.join(' ') + '"';

    html += '>';
    html += i18n.t(value.textValue);
    html += '</a>';

    return html;
}
