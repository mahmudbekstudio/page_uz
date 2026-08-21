import element from './mainElement';

export default class simpleTextElement extends element {
    fillable = [
        {
            type: 'wrapper',
            name: 'wrapper',
            params: {
                label: 'words.wrapper',
                hideId: true,
                hideClass: true,
                acceptedWrappers: ['container', 'link', 'paragraph', 'header'],
            }
        },
        {
            type: 'textEditor',
            name: 'content',
            params: {
                label: 'words.text',
            }
        },
    ];

    constructor(params, lang = null, withAllTranslations = false) {
        super(params, lang, withAllTranslations);
        if (typeof params.hasLang !== 'undefined') {
            this.hasLang = params.hasLang;
        }
    }

    get html() {
        let tag = 'div';
        let extraParams = '';
        if (this.params.wrapper) {
            if (this.params.wrapper.wrapper === 'paragraph') {
                tag = 'p';
            } else if (this.params.wrapper.wrapper === 'header') {
                tag = this.params.wrapper.header;
            } else if (this.params.wrapper.wrapper === 'link') {
                tag = 'a';
                extraParams += ' href="' + this.params.wrapper.linkUrl + '" target="' + this.params.wrapper.linkTarget + '"';
            }
        }
        let result = '<' + tag;
        if (this.params.id) {
            result += ' id="' + this.params.id + '"';
        }
        if (this.params.class) {
            result += ' class="' + this.params.class + '"';
        }
        if (this.params.title) {
            result += ' title="' + this.translate(this.params.title) + '"';
        }
        result += extraParams + '>';
        result += this.translate(this.params.content.value);
        result += '</' + tag + '>';

        if (this.params.wrapper && this.params.wrapper.wrapper === 'link') {
            result = '<div>' + result + '</div>'
        }

        return result;
    }
}
