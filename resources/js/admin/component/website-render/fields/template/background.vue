<template>
    <div class="template-field-background">
        <form-component
            :value="fieldForm"
            @input="formChange($event)"
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
            styleField: null,
        }
    },
    props: {
        value: null,
    },
    computed: {
        styleValue () {
            return this.value && this.value.style ? this.value.style : null;
        },
        angleValue () {
            return this.value && this.value.angle ? this.value.angle : null;
        },
        colorValue () {
            if (this.styleValue === 'gradient') {
                return this.value && this.value.color ? (Array.isArray(this.value.color) ? this.value.color : [this.value.color, '']) : null;
            }

            return this.value && this.value.color ? (Array.isArray(this.value.color) ? this.value.color[0] : this.value.color) : null;
        }
    },
    created() {
        this.fieldForm = new FormClass();
        this.styleField = this.fieldForm.addField({type: 'select'});
        this.styleField.setParams('label', 'words.style');
        this.styleField.name = 'style';
        this.styleField.value = this.styleValue || 'solid';
        const statusOptions = {
            solid: 'Solid',
            gradient: 'Gradient'
        };
        this.styleField.setParams('options', statusOptions);
        this.styleField.setParams('clearable', false);

        this.createForm();
    },
    methods: {
        formChange(event) {
            let result = event.getFieldValues();

            if (this.styleValue === 'gradient') {
                const values = event.getFieldValues();
                result = {...result, color: [values.color1, values.color2]};
            }

            this.$emit('input', result);
        },
        createForm() {
            this.fieldForm.removeFieldByName('color');
            this.fieldForm.removeFieldByName('color1');
            this.fieldForm.removeFieldByName('color2');
            this.fieldForm.removeFieldByName('angel');
            if (this.styleValue === 'gradient') {
                const color1Field = this.fieldForm.addField({type: 'color'});
                color1Field.setParams('label', 'words.color1');
                color1Field.name = 'color1';
                color1Field.value = this.colorValue[0];

                const color2Field = this.fieldForm.addField({type: 'color'});
                color2Field.setParams('label', 'words.color2');
                color2Field.name = 'color2';
                color2Field.value = this.colorValue[1];

                const angleField = this.fieldForm.addField({type: 'select'});
                angleField.setParams('label', 'words.angle');
                angleField.name = 'angle';
                angleField.value = this.angleValue || 90;
                const angleOptions = {
                    45: '45',
                    90: '90',
                    135: '135',
                    180: '180'
                };
                angleField.setParams('options', angleOptions);
                angleField.setParams('clearable', false);
            } else {
                const colorField = this.fieldForm.addField({type: 'color'});
                colorField.setParams('label', 'words.color0');
                colorField.name = 'color';
                colorField.value = this.colorValue;
            }
        }
    },
    watch: {
        'styleField.value'() {
            this.createForm();
        }
    },
    components: {
        formComponent,
    }
}
</script>
