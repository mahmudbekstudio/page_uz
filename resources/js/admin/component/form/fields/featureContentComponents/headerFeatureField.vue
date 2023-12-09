<script>
import mainFeatureField from "./mainFeatureField.vue";

export default {
    extends: mainFeatureField,
    data () {
        return {
            fieldClassName: 'header-feature-field',
        }
    },
    methods: {
        addFields (form) {
            form.addField({
                type: 'select',
                value: this.value && this.value['headerValue'] ? this.value['headerValue'] : 'h3',
                name: 'headerValue',
                params: {label: 'words.headerValue', clearable: false, options: [
                        {
                            text: 'words.h1',
                            value: 'h1',
                        },
                        {
                            text: 'words.h2',
                            value: 'h2',
                        },
                        {
                            text: 'words.h3',
                            value: 'h3',
                        },
                        {
                            text: 'words.h4',
                            value: 'h4',
                        },
                        {
                            text: 'words.h5',
                            value: 'h5',
                        },
                        {
                            text: 'words.h6',
                            value: 'h6',
                        },
                    ]}
            });
            form.addField({
                type: 'text',
                value: this.value && this.value['textValue'] ? this.value['textValue'] : '',
                name: 'textValue',
                params: {...this.params, label: 'words.textValue', hasLang: true}
            });
        },
    },
}
</script>
