<template>
    <div class="template-field-button">
        <form-component
            :value="fieldForm"
            @input="$emit('input', $event.getFieldValues())"
        ></form-component>
    </div>
</template>
<script>
import formComponent from "../../../form/form-component.vue";
import {Form as FormClass} from "../../../form/classes/form";

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
        textValue () {
            return this.value && this.value.value ? this.value.value : null;
        },
        sizeValue () {
            return this.value && this.value.size ? this.value.size : null;
        },
        shapeValue () {
            return this.value && this.value.shape ? this.value.shape : null;
        },
        appearanceValue () {
            return this.value && this.value.appearance ? this.value.appearance : null;
        },
        textColorValue () {
            return this.value && this.value['text-color'] ? this.value['text-color'] : null;
        },
        backgroundColorValue () {
            return this.value && this.value['background-color'] ? this.value['background-color'] : null;
        },
    },
    created() {
        this.fieldForm = new FormClass();
        const textField = this.fieldForm.addField({type: 'text'});
        textField.setParams('label', 'words.value');
        textField.name = 'value';
        textField.value = this.textValue || '';

        const sizeField = this.fieldForm.addField({type: 'select'});
        sizeField.setParams('label', 'words.size');
        sizeField.name = 'size';
        sizeField.value = this.sizeValue || 'medium';
        const sizeOptions = {
            small: 'Small',
            medium: 'Medium',
            large: 'Large'
        };
        sizeField.setParams('options', sizeOptions);
        sizeField.setParams('clearable', false);

        const shapeField = this.fieldForm.addField({type: 'select'});
        shapeField.setParams('label', 'words.shape');
        shapeField.name = 'shape';
        shapeField.value = this.shapeValue;
        const shapeOptions = {
            pill: 'Pill',
            rectangle: 'Rectangle',
            'round-corner': 'Round corner'
        };
        shapeField.setParams('options', shapeOptions);

        const appearanceField = this.fieldForm.addField({type: 'select'});
        appearanceField.setParams('label', 'words.appearance');
        appearanceField.name = 'appearance';
        appearanceField.value = this.appearanceValue;
        const appearanceOptions = {
            solid: 'Solid',
            outline: 'Outline',
            text: 'Text'
        };
        appearanceField.setParams('options', appearanceOptions);

        const colorField = this.fieldForm.addField({type: 'color'});
        colorField.setParams('label', 'words.color');
        colorField.name = 'text-color';
        colorField.value = this.textColorValue;

        const backgroundColorField = this.fieldForm.addField({type: 'color'});
        backgroundColorField.setParams('label', 'words.backgroundColor');
        backgroundColorField.name = 'background-color';
        backgroundColorField.value = this.backgroundColorValue;

    },
    components: {
        formComponent
    }
}
</script>
