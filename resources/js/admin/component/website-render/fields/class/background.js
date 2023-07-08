import mainFieldClass from "./mainFieldClass";

export default class background extends mainFieldClass {
    stylesList = {
        solid: {name: 'Solid'},
        gradient: {name: 'Gradient'},
    };
    anglesList = {
        45: {},
        90: {},
        135: {},
        180: {}
    }

    constructor(values, isSample = false, params = null, lang = null) {
        super(values, isSample, params, lang);
    }

    //attachment
    //image
    //position
    //repeat

    get style() {
        return this.stylesList[this.values['style']] ? this.values['style'] : 'solid';
    }

    get color() {
        return this.values['color'] || '';
    }

    get angle() {
        return this.anglesList[parseInt(this.values['angle'])] ? this.values['angle'] : 90;
    }

    get html() {
        return '';
    }

    get css() {
        if (!this.color || !this.show) {
            return '';
        }

        if (this.style === 'gradient') {
            return 'background-image: linear-gradient(' + this.angle + 'deg, ' + this.color[0] + ', ' + this.color[1] + ')';
        }

        return 'background-color: ' + this.color;
    }
}
