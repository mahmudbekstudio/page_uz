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
                type: 'editor',
                value: this.value && this.value['editorValue'] ? this.value['editorValue'] : '',
                name: 'editorValue',
                params: {...this.params, label: 'words.editorValue', hasLang: true, type: 'simple'}
            });
        },
    },
}
</script>
