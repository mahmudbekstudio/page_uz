import element from './mainElement';
import {FILE_IMAGE_TYPE} from "../../../constants";
import store from '../../../plugin/store';

export default class imgElement extends element {
    fillable = [
        /*{
            name: 'content',
            hide: true,
        },*/
        {
            type: 'wrapper',
            name: 'wrapper',
            params: {
                label: 'words.wrapper',
                acceptedWrappers: ['none', 'container', 'link'],
            }
        },
        {
            type: 'number',
            name: 'height',
            hasLang: false,
        },
        {
            type: 'number',
            name: 'width',
            hasLang: false,
        },
        {
            type: 'file',
            name: 'src',
            params: {
                multiple: false,
                fileType: FILE_IMAGE_TYPE
            }
        },
    ];
    hasLang = false;
    constructor(params, lang = null, withAllTranslations = false) {
        super(params, lang, withAllTranslations);
    }

    get html() {
        let tag = '';
        if (this.params.wrapper) {
            if (this.params.wrapper.wrapper === 'none') {
                tag = '';
            } else if (this.params.wrapper.wrapper === 'container') {
                tag = 'div';
            } else if (this.params.wrapper.wrapper === 'link') {
                tag = 'a';
            }
        }

        let result = '';

        if (tag) {
            result += '<' + tag + ' id="' + this.params.wrapper.id + '" class="' + this.params.wrapper.class + '"';

            if (this.params.wrapper.wrapper === 'link') {
                result += ' href="' + this.params.wrapper.linkUrl + '" target="' + this.params.wrapper.linkTarget + '"';
            }
            result += '>';
        }

        result += '<img';
        if (this.params.id) {
            result += ' id="' + this.params.id + '"';
        }
        if (this.params.class) {
            result += ' class="' + this.params.class + '"';
        }
        if (this.params.title) {
            result += ' title="' + this.translate(this.params.title) + '"';
            result += ' alt="' + this.translate(this.params.title) + '"';
        }
        if (this.params.width) {
            result += ' width="' + this.params.width + '"';
        }
        if (this.params.height) {
            result += ' height="' + this.params.height + '"';
        }

        if (this.params.src && this.params.src.length) {
            const src = this.params.src[0];
            const url = (src.is_local ? '' : store.getters['view/website'].fileBaseUrl) + src.folderPath + '/' + src.name + '.' + src.extension;
            result += ' src="' + url + '"';
        }

        result += '/>';

        if (tag) {
            result += '</' + tag + '>';
        }

        return result;
    }
}
