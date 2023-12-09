import i18n from "../../../../plugin/i18n";
import store from "../../../../plugin/store";

export default function (domValue, value) {
    let imageUrl = '';
    const imageValue = value.imageValue;

    if (Array.isArray(imageValue) && imageValue.length) {
        const imageItem = imageValue[0];
        imageUrl = imageItem['folderPath'] + '/' + imageItem['name'] + '.' + imageItem['extension'];

        if (!imageItem['is_local']) {
            imageUrl = store.getters['view/website'].fileBaseUrl + imageUrl
        }
    }

    if (!imageUrl) return '';

    let html = '<img src="' + imageUrl + '"';

    if (domValue.id) {
        html += ' id="' + domValue.id + '"';
    }

    const classNames = ['img-fluid'];

    if (domValue.class) {
        classNames.push(domValue.class);
    }

    html += ' class="' + classNames.join(' ') + '"';

    if (domValue.title) {
        html += ' title="' + i18n.t(domValue.title) + '"';
    }

    if (domValue.style) {
        html += ' style="' + domValue.style + '"';
    }

    html += '/>';

    return html;
}
