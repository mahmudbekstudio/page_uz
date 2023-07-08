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
            valueFieldType: 'text',
        }
    },
    props: {
        value: null,
    },
    computed: {
        textValue () {
            return this.value && this.value.value ? this.value.value : null;
        },
        colorValue () {
            return this.value && this.value.color ? this.value.color : null;
        },
        sizeValue () {
            return this.value && this.value.size ? this.value.size : null;
        },
        styleValue () {
            return this.value && this.value.style ? this.value.style : null;
        }
    },
    created() {
        this.fieldForm = new FormClass();
        const textField = this.fieldForm.addField({type: this.valueFieldType});
        textField.setParams('label', 'words.text');
        textField.name = 'value';
        textField.value = this.textValue || '';

        const colorField = this.fieldForm.addField({type: 'color'});
        colorField.setParams('label', 'words.color');
        colorField.name = 'color';
        colorField.value = this.colorValue;

        const sizeField = this.fieldForm.addField({type: 'number'});
        sizeField.setParams('label', 'words.size');
        sizeField.setParams('hasLang', false);
        sizeField.name = 'size';
        sizeField.value = this.sizeValue;

        const styleField = this.fieldForm.addField({type: 'select'});
        styleField.setParams('label', 'words.style');
        styleField.name = 'style';
        styleField.value = this.styleValue || 'solid';
        const statusOptions = {
            b: 'Bold',
            i: 'Italic'
        };
        styleField.setParams('options', statusOptions);
        styleField.setParams('multiple', true);
    },
    components: {
        formComponent,
    }
}
</script>
