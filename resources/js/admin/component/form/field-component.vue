<template>
    <div class="field-component">
        <div class="lang-list" v-if="hasLang && (website.metas.languages_list.length > 1)">
            <v-btn-toggle v-model="langToggle">
                <v-btn
                    plain
                    x-small
                    v-for="(lang, index) of website.metas.languages_list"
                    :key="lang"
                    :class="{'v-item--active v-btn--active': index === langToggle, 'has-error': (hasError && params.fieldObject.params.errorList.indexOf(index) > -1)}"
                >
                    {{ lang }}
                </v-btn>
            </v-btn-toggle>
        </div>
        <div v-if="hasLang">
            <component
                v-for="(lang, index) of website.metas.languages_list"
                :key="lang"
                v-show="index===langToggle"
                :is="typeComponent"
                :params="{...params, rules}"
                :events="{...events}"
                :disabled="disabled"
                :value="getValue(lang)"
                @input="changed($event, hasLang ? lang : null)"
                class="has-lang"
                :class="'lang-' + index"
            ></component>
        </div>
        <component
            v-if="!hasLang"
            :is="typeComponent"
            :params="{...params, rules}"
            :events="events"
            :disabled="disabled"
            :value="getValue()"
            @input="changed($event)"
        ></component>
    </div>
</template>
<script>
    import textField from './fields/textField';
    import numberField from './fields/numberField';
    import passwordField from './fields/passwordField';
    import textareaField from './fields/textareaField';
    import optionsField from './fields/optionsField.vue';
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
    import colorField from "./fields/colorField";

    import validation from "../../config/validation";
    import {mapGetters} from "vuex";

    export default {
        data () {
            return {
                langToggle: 0,
            }
        },
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
            form: null,
            disabled: {
                type: Boolean,
                default: false
            },
            hasLang: {
                type: Boolean,
                default() {
                    return false
                }
            },
        },
        computed: {
            ...mapGetters({
                website: 'view/website',
            }),
            hasError() {
                const errorList = this.params?.fieldObject?.params?.errorList;

                if (!errorList || !Array.isArray(errorList) || !errorList.length) {
                    return false;
                }
                
                return true;
            },
            typeComponent() {
                return this.type + 'Field';
            },
            fieldValue() {
                let result = this.value;

                if (result && result[0] === '{' && result[result.length - 1] === '}') {
                    result = JSON.parse(result);
                }

                return result;
            },
            rules() {
                if (this.params.validation && this.form) {
                    const result = [];

                    for (const rule in this.params.validation) {
                        result.push(validation[rule](this.params.label, this.params.validation[rule], this.form.getFieldValues()));
                    }

                    return result;
                }

                return this.params.rules || [];
            }
        },
        watch: {
            langToggle(newVal, oldVal) {
                if (typeof newVal !== 'number') {
                    this.langToggle = oldVal;
                }
            }
        },
        methods: {
            changed(value, lang=null) {
                if (this.hasLang && typeof this.value === 'string' && this.value[0] === '{' && this.value[this.value.length - 1] === '}') {
                    const newVal = {...this.fieldValue};
                    newVal[lang] = value;
                    this.$emit('input', this.fieldKey, newVal);
                } else {
                    this.$emit('input', this.fieldKey, value, lang);
                }
            },
            getValue(lang) {
                if (this.hasLang && this.fieldValue && typeof this.fieldValue === 'object' && typeof this.fieldValue[lang] === 'undefined') {
                    return Object.values(this.fieldValue)[0];
                }

                return this.hasLang && this.fieldValue && typeof this.fieldValue[lang] !== 'undefined' ? this.fieldValue[lang] : this.fieldValue;
            }
        },
        components: {
            textField,
            numberField,
            passwordField,
            textareaField,
            optionsField,
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
            colorField,
        }
    }
</script>
<style lang="scss">
.field-component {
    position: relative;
    .lang-list {
        position: absolute;
        bottom: 16px;
        left: 0;
        z-index: 1;

        .v-btn.has-error {
            background-color: #F00;
            color: #FFF;
        }
    }
    .v-btn.v-item--active,
    .v-btn.has-error.v-item--active {
        background-color: #757575;
        color: #FFF !important;
    }

    .btn-inactive.v-btn.v-item--active {
        background-color: transparent !important;
    }

    .v-btn-toggle {
        border-radius: 0 0 4px 4px
    }

    .has-lang {
        .v-text-field__details {
            margin-top: 15px;
        }
        &.editor-field > div {
            padding-bottom: 35px
        }
    }
}
</style>
