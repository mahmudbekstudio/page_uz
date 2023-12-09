<script>
import mainFeatureField from "./mainFeatureField.vue";
import {FILE_IMAGE_TYPE} from "../../../../constants";

export default {
    extends: mainFeatureField,
    data () {
        return {
            fieldClassName: 'image-feature-field',
        }
    },
    methods: {
        addFields (form) {
            form.addField({
                type: 'file',
                value: this.value && this.value['imageValue'] ? this.value['imageValue'] : [],
                name: 'imageValue',
                params: {...this.params, label: 'words.imageValue', fileType: FILE_IMAGE_TYPE}
            });
        },
    },
}
</script>
