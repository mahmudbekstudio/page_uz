<template>
    <div class="template-field-textarea">
        <form-component
            :value="fieldForm"
            @input="$emit('input', $event.getFieldValues())"
        ></form-component>
    </div>
</template>
<script>
import formComponent from "../../../form/form-component";
import { Form as FormClass } from '../../../form/classes/form';

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
        /*textValue () {
            return this.value && this.value.value ? this.value.value : null;
        },*/
    },
    created() {
        this.fieldForm = new FormClass();
        /*const textField = this.fieldForm.addField({type: 'text'});
        textField.setParams('label', 'words.text');
        textField.name = 'value';
        textField.value = this.textValue || '';*/
    },
    components: {
        formComponent,
    }
}
</script>
