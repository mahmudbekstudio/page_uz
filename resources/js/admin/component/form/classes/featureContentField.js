import field from './mainField';

export const DEFAULT_FEATURE_CONTENT_TYPE = 'text';
export const FEATURE_CONTENT_TYPES = {
    text: 'words.text',
    editor: 'words.editor',
    image: 'words.image',
    imageGallery: 'words.imageGallery',
    header: 'words.header',
    button: 'words.button',
};

export default class featureContentField extends field {
    constructor(params) {
        params.featureType = FEATURE_CONTENT_TYPES[params.featureType] ? params.featureType : DEFAULT_FEATURE_CONTENT_TYPE;
        super(params);
    }
}
