<template>
    <component :is="typeComponent" :params="params" :events="events" :disabled="disabled" :value="value" @input="changed"></component>
</template>
<script>
    import textField from './fields/textField';
    import numberField from './fields/numberField';
    import passwordField from './fields/passwordField';
    import textareaField from './fields/textareaField';
    import selectField from './fields/selectField';
    import fileField from "./fields/fileField";
    import switchField from "./fields/switchField";
    import dividerField from "./fields/dividerField";
    import datetimeField from "./fields/datetimeField";
    import dateField from "./fields/dateField";
    import dateRangeField from "./fields/dateRangeField";
    import dateMultipleField from "./fields/dateMultipleField";
    import timeField from "./fields/timeField";
    import radioField from "./fields/radioField";
    import checkboxField from "./fields/checkboxField";
    import editorField from "./fields/editorField";
    import validationField from "./fields/validationField";

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
            numberField,
            passwordField,
            textareaField,
            selectField,
            fileField,
            switchField,
            dividerField,
            datetimeField,
            dateField,
            dateRangeField,
            dateMultipleField,
            timeField,
            radioField,
            checkboxField,
            editorField,
            validationField,
        }
    }
</script>
