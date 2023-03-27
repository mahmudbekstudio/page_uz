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
    import advancedParentField from './fields/advanced/advancedParentField';
    import advancedChildOfField from './fields/advanced/advancedChildOfField';
    import requiredPublishEndField from './fields/required/requiredPublishEndField';
    import requiredPublishStartField from './fields/required/requiredPublishStartField';
    import requiredRouteNameField from './fields/required/requiredRouteNameField';
    import requiredSeoDescriptionField from './fields/required/requiredSeoDescriptionField';
    import requiredSeoKeywordField from './fields/required/requiredSeoKeywordField';
    import requiredStatusField from './fields/required/requiredStatusField';
    import requiredTemplateField from './fields/required/requiredTemplateField';
    import requiredTitleField from './fields/required/requiredTitleField';

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
            advancedParentField,
            advancedChildOfField,
            requiredPublishEndField,
            requiredPublishStartField,
            requiredRouteNameField,
            requiredSeoDescriptionField,
            requiredSeoKeywordField,
            requiredStatusField,
            requiredTemplateField,
            requiredTitleField,
        }
    }
</script>
