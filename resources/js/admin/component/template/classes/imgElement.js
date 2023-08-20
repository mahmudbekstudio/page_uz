import element from './mainElement';
import {FILE_IMAGE_TYPE} from "../../../constants";

export default class imgElement extends element {
    fillable = [
        {
            name: 'content',
            hide: true,
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
    constructor(params) {
        super(params);
    }
}
