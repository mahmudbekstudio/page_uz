<script>
import mainFeatureField from "./mainFeatureField.vue";

export default {
    extends: mainFeatureField,
    data () {
        return {
            fieldClassName: 'text-feature-field',
        }
    },
    methods: {
        addFields (form) {
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
