import mainFieldClass from "./mainFieldClass";
import store from "../../../../plugin/store";

export default class background extends mainFieldClass {
    stylesList = {
        solid: {name: 'Solid'},
        gradient: {name: 'Gradient'},
        image: {name: 'Image'},
    };
    anglesList = {
        45: {},
        90: {},
        135: {},
        180: {}
    }

    constructor(values, isSample = false, params = null, lang = null) {
        values.style = values.style || 'solid';
        values.repeat = values.repeat || 'repeat';
        values.position_x = values.position_x || 'left';
        values.position_y = values.position_y || 'top';
        values.color = values.color || '#FFF';
        values.angle = values.angle || 90;
        values.image = values.image || [];
        super(values, isSample, params, lang);
    }

    get style() {
        return this.stylesList[this.values['style']] ? this.values['style'] : 'solid';
    }

    get color() {
        return this.values['color'] || '';
    }

    get repeat() {
        return this.values['repeat'] || 'repeat';
    }

    get positionX() {
        return this.values['position_x'] || 'left';
    }

    get positionY() {
        return this.values['position_y'] || 'top';
    }

    get image() {
        if (this.values['image'] && this.values['image'].length) {
            const value = this.values['image'][0];
            let imageUrl = value['folderPath'] + '/' + value['name'] + '.' + value['extension'];
            if (value['is_local']) {
                return imageUrl;
            } else {
                return store.getters['view/website'].fileBaseUrl + imageUrl
            }
        }
        return '';
    }

    get angle() {
        return this.anglesList[parseInt(this.values['angle'])] ? this.values['angle'] : 90;
    }

    get html() {
        return '';
    }

    get css() {
        if (!this.show) {
            return '';
        }

        let result = [
            'background-repeat: ' + this.repeat,
            'background-position: ' + this.positionX + ' ' + this.positionY
        ];

        if (this.style) {
            result.push(this.image ? 'background-image: url(' + this.image + ')' : '');
        }
        if (this.style === 'gradient') {
            if (this.color.length && this.color[0] !== '' && this.color[1] !== '') {
                result.push('background-image: linear-gradient(' + this.angle + 'deg, ' + this.color[0] + ', ' + this.color[1] + ')');
            }
        } else if(this.color) {
            result.push('background-color: ' + this.color);
        }

        return result.join(';');
    }
}
