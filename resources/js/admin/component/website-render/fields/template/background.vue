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
import * as constants from "../../../../constants";

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
        imageValue () {
            return this.value && this.value.image ? this.value.image : [];
        },
        colorValue () {
            if (this.styleValue === 'gradient') {
                return this.value && this.value.color ? (Array.isArray(this.value.color) ? this.value.color : [this.value.color, '#FFF']) : ['#FFF', '#FFF'];
            }

            return this.value && this.value.color ? (Array.isArray(this.value.color) ? this.value.color[0] : this.value.color) : '#FFF';
        },
        positionXValue () {
            return this.value && this.value.position_x ? this.value.position_x : 'left';
        },
        positionYValue () {
            return this.value && this.value.position_y ? this.value.position_y : 'top';
        },
        repeatValue () {
            return this.value && this.value.repeat ? this.value.repeat : 'repeat';
        },
    },
    created() {
        this.fieldForm = new FormClass();
        this.styleField = this.fieldForm.addField({type: 'select'});
        this.styleField.setParams('label', 'words.style');
        this.styleField.name = 'style';
        this.styleField.value = this.styleValue || 'solid';
        const statusOptions = {
            solid: 'Solid',
            gradient: 'Gradient',
            image: 'Image',
        };
        this.styleField.setParams('options', statusOptions);
        this.styleField.setParams('clearable', false);

        const imageField = this.fieldForm.addField({type: 'file'});
        imageField.setParams('label', 'words.image');
        imageField.name = 'image';
        imageField.value = this.imageValue;
        imageField.setParams('fileType', constants.FILE_IMAGE_TYPE);

        const positionXField = this.fieldForm.addField({type: 'select'});
        positionXField.setParams('label', 'words.position_x');
        positionXField.name = 'position_x';
        positionXField.value = this.positionXValue || 'left';
        const positionXOptions = {
            left: 'Left',
            center: 'Center',
            right: 'Right',
        };
        positionXField.setParams('options', positionXOptions);
        positionXField.setParams('clearable', false);

        const positionYField = this.fieldForm.addField({type: 'select'});
        positionYField.setParams('label', 'words.position_y');
        positionYField.name = 'position_y';
        positionYField.value = this.positionYValue || 'top';
        const positionYOptions = {
            top: 'Top',
            center: 'Center',
            bottom: 'Bottom',
        };
        positionYField.setParams('options', positionYOptions);
        positionYField.setParams('clearable', false);

        const repeatField = this.fieldForm.addField({type: 'select'});
        repeatField.setParams('label', 'words.repeat');
        repeatField.name = 'repeat';
        repeatField.value = this.repeatValue || 'repeat';
        const repeatOptions = {
            'no-repeat': 'No Repeat',
            'repeat': 'Repeat',
            'repeat-x': 'Repeat X',
            'repeat-y': 'Repeat Y'
        };
        repeatField.setParams('options', repeatOptions);
        repeatField.setParams('clearable', false);

        this.createForm();
    },
    methods: {
        formChange(event) {
            let result = event.getFieldValues();

            if (this.styleValue === 'gradient') {
                result = {...result, color: [result.color1, result.color2]};
            }

            this.$emit('input', result);
        },
        createForm() {
            this.fieldForm.removeFieldByName('color');
            this.fieldForm.removeFieldByName('color1');
            this.fieldForm.removeFieldByName('color2');
            this.fieldForm.removeFieldByName('angle');
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
