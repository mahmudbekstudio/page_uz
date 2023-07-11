<template>
    <div class="template-field-textarea">
        <form-component
            :value="fieldForm"
            @input="$emit('input', $event.getFieldValues().value)"
        ></form-component>
    </div>
</template>
<script>
import formComponent from "../../../form/form-component";
import { Form as FormClass } from '../../../form/classes/form';
import * as constants from "../../../../constants";

export default {
    data () {
        return {
            fieldForm: null,
        }
    },
    props: {
        value: null,
    },
    computed: {
        imageValue () {
            return this.value || [];
        },
    },
    created() {
        this.fieldForm = new FormClass();
        const textField = this.fieldForm.addField({type: 'file'});
        textField.setParams('label', 'words.image');
        textField.name = 'value';
        textField.value = this.imageValue;
        textField.setParams('fileType', constants.FILE_IMAGE_TYPE);
    },
    components: {
        formComponent,
    }
}
</script>
