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
                value: this.value && this.value['typeValue'] ? this.value['typeValue'] : 'info',
                name: 'typeValue',
                params: {label: 'words.buttonType', clearable: false, options: [
                        {
                            text: 'words.primary',
                            value: 'primary',
                        },
                        {
                            text: 'words.secondary',
                            value: 'secondary',
                        },
                        {
                            text: 'words.success',
                            value: 'success',
                        },
                        {
                            text: 'words.danger',
                            value: 'danger',
                        },
                        {
                            text: 'words.warning',
                            value: 'warning',
                        },
                        {
                            text: 'words.info',
                            value: 'info',
                        },
                        {
                            text: 'words.light',
                            value: 'light',
                        },
                        {
                            text: 'words.dark',
                            value: 'dark',
                        },
                        {
                            text: 'words.link',
                            value: 'link',
                        },
                    ]}
            });
            form.addField({
                type: 'select',
                value: this.value && this.value['buttonSize'] ? this.value['buttonSize'] : '',
                name: 'buttonSize',
                params: {label: 'words.buttonSize', clearable: false, options: [
                        {
                            text: 'words.buttonLarge',
                            value: 'lg',
                        },
                        {
                            text: 'words.buttonMedium',
                            value: '',
                        },
                        {
                            text: 'words.buttonSmall',
                            value: 'sm',
                        },
                    ]}
            });
            form.addField({
                type: 'select',
                value: this.value && this.value['linkTarget'] ? this.value['linkTarget'] : '_self',
                name: 'linkTarget',
                params: {label: 'words.linkTarget', clearable: false, options: [
                        {
                            text: 'words.linkTargetBlank',
                            value: '_blank',
                        },
                        {
                            text: 'words.linkTargetSelf',
                            value: '_self',
                        },
                        {
                            text: 'words.linkTargetParent',
                            value: '_parent',
                        },
                        {
                            text: 'words.linkTargetTop',
                            value: '_top',
                        },
                    ]}
            });
            form.addField({
                type: 'text',
                value: this.value && this.value['buttonLink'] ? this.value['buttonLink'] : '',
                name: 'buttonLink',
                params: {...this.params, label: 'words.buttonLink'}
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
