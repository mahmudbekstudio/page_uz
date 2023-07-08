import mainFieldClass from "./mainFieldClass";

export default class menu extends mainFieldClass {
    defaultMenuList = [
        {
            label: 'Store',
            link: '#',
        },
        {
            label: 'About',
            link: '#',
        },
        {
            label: 'Delivery',
            link: '#',
        },
        {
            label: 'Contact Us',
            link: '#',
        }
    ];
    constructor(values, isSample = false, params = null, lang = null) {
        super(values, isSample, params, lang);
    }

    get linkColor() {
        return this.values['link-color'] || null;
    }
    get class() {
        return this.values['class'] || '';
    }

    get classes() {
        const classes = {};
        const linkClass = '.' + this.id + '-link';
        classes[linkClass] = {};

        if (this.linkColor) {
            classes[linkClass]['color'] = this.linkColor + ' !important';
            classes[linkClass + ':hover'] = {
                'color': this.linkColor + ' !important'
            };
        }

        return classes;
    }

    get html() {
        let list = '<ul class="field-menu navbar-nav ' + this.class +'">';

        for (const item of this.defaultMenuList) {
            list += '<li class="field-menu-item nav-item">';
            list += '<a class="field-menu-link ' + this.id + '-link nav-link" href="' + item.link + '">';
            list += item.label;
            list += '</a>';
            list += '</li>';
        }

        list += '</ul>';
        return list;
    }
}
