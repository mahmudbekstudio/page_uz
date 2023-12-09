<template>
    <div :class="fieldClassName">
        <form-component
            :value="elementForm"
            :disabled="disabled"
            @input="formChanged"
            fluid
            container-class="container-without-padding"
        ></form-component>
    </div>
</template>
<script>
import formComponent from "../../../../component/form/form-component.vue";
import {Form as FormClass} from "../../classes/form";

export default {
    data () {
        return {
            fieldClassName: '',
        }
    },
    props: {
        params: {
            type: Object,
            default() {
                return {}
            }
        },
        value: null,
        disabled: {
            type: Boolean,
            default: false
        },
    },
    computed: {
        elementForm () {
            const form = new FormClass();

            this.addFields(form);

            return form;
        }
    },
    methods: {
        addFields (form) {
            // add fields to form
        },
        formChanged () {
            this.$emit('input', this.elementForm.getFieldValues());
        }
    },
    components: {
        formComponent
    }
}
</script>
