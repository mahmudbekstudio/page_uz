import baseElement from './baseElement';

export default class textElement extends baseElement {
    fillable = [
        /*{
            name: 'content',
            hide: true,
        },*/
        {
            type: 'wrapper',
            name: 'wrapper',
            params: {label: 'words.wrapper'}
        },
        {
            type: 'select',
            name: 'text_style',
            params: {
                label: 'words.text_style',
                multiple: true,
                options: {
                    'strong': 'Strong',
                    'italic': 'Italic',
                    'strike': 'Strike',
                    'underline': 'Underline',
                }
            }
        },
    ]
}
