<template>
    <component :is="typeComponent" :params="params" :events="events" :disabled="disabled" :value="value" @input="changed"></component>
</template>
<script>
    import textField from './fields/textField';
    import passwordField from './fields/passwordField';
    import textareaField from './fields/textareaField';
    import selectField from './fields/selectField';
    import fileField from "./fields/fileField";
    import switchField from "./fields/switchField";

    export default {
        props: {
            params: {
                type: Object,
                default() {
                    return {}
                }
            },
            events: {
                type: Object,
                default() {
                    return {}
                }
            },
            type: {
                type: String,
                default: 'text'
            },
            value: null,
            fieldKey: null,
            disabled: {
                type: Boolean,
                default: false
            }
        },
        computed: {
            typeComponent() {
                return this.type + 'Field';
            }
        },
        methods: {
            changed(value) {
                this.$emit('input', this.fieldKey, value);
            }
        },
        components: {
            textField,
            passwordField,
            textareaField,
            selectField,
            fileField,
            switchField,
        }
    }
</script>
