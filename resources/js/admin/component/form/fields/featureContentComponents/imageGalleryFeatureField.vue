<script>
import mainFeatureField from "./mainFeatureField.vue";
import {FILE_IMAGE_TYPE} from "../../../../constants";

export default {
    extends: mainFeatureField,
    data () {
        return {
            fieldClassName: 'image-gallery-feature-field',
        }
    },
    methods: {
        addFields (form) {
            form.addField({
                type: 'file',
                value: this.value && this.value['imageGalleryValue'] ? this.value['imageGalleryValue'] : [],
                name: 'imageGalleryValue',
                params: {...this.params, label: 'words.imageGalleryValue', multiple: true, fileType: FILE_IMAGE_TYPE}
            });
        },
    },
}
</script>
