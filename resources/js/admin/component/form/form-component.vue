<template>
    <div>
        <v-form
            ref="form"
            lazy-validation
            :disabled="disabled"
            v-model="valid"
            v-if="formObject"
        >
            <v-tabs
                    v-model="tab"
                    align-with-title
                    v-if="showTabs"
            >
                <v-tabs-slider color="yellow"></v-tabs-slider>

                <v-tab
                        v-for="(tab, index) in formObject.children"
                        :key="'tab' + index"
                        :class="{'tab-has-error': tab.hasError}"
                >
                    {{ $t(tab.title) }}
                </v-tab>
            </v-tabs>
            <v-tabs-items v-model="tab">
                <v-tab-item
                        v-for="(tab, index) in formObject.children"
                        :key="'tab' + index"
                        eager
                >
                    <v-container
                        :fluid="fluid"
                        :class="containerClass"
                    >
                        <v-row
                                v-for="(row, rowIndex) in tab.children"
                                :key="'row' + rowIndex"
                        >
                            <v-col
                                    v-for="(col, colIndex) in row.children"
                                    :key="'col' + colIndex"
                                    cols="12"
                                    :md="col.size"
                            >
                                <div
                                        v-for="(field, fieldIndex) in col.children"
                                        :key="'col' + fieldIndex"
                                        class="cell-field-component"
                                >
                                    <field-component
                                            :fieldKey="[index, rowIndex, colIndex, fieldIndex].join(fieldSplitter)"
                                            :type="field.type"
                                            :disabled="!!field.disabled"
                                            :hide="!!field.hide"
                                            :value="field.value"
                                            @input="fieldChanged"
                                            :params="{...field.params, hasLang: fieldHasLang(field.field), fieldObject: field.field.fieldObject, defaultObject: field.field.defaultObject}"
                                            :events="{...field.events}"
                                            :form="formObject"
                                            :has-lang="fieldHasLang(field.field)"
                                    />
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-tab-item>
            </v-tabs-items>
        </v-form>
    </div>
</template>
<script>
    import { Form as FormClass } from './classes/form';
    import fieldComponent from './field-component';
    import {FORM} from '../../constants';
    import {mapGetters} from "vuex";
    export default {
        data() {
            return {
                fieldSplitter: FORM.fieldKeySplitter,
                tab: 0,
                valid: true,
                formObject: null
            }
        },
        props: {
            fluid: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            disabled: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            value: {
                default() {
                    return []
                }
            },
            containerClass: {
                type: String,
                default () {
                    return '';
                }
            },
        },
        created() {
            this.$emit('validate', () => {
                this.validateTab();
                return this.$refs.form.validate();
            });
            this.$emit('resetValidation', () => this.$refs.form.resetValidation());
            this.$emit('reset', () => this.$refs.form.reset());
            this.initFormObject(this.value);
            this.$emit('input', this.formObject);
        },
        computed: {
            ...mapGetters({
                website: 'view/website',
            }),
            showTabs() {
                return this.formObject.children.length > 1;
            }
        },
        watch: {
            value(val) {
                this.initFormObject(val);
            },
            valid(val) {
                this.validateTab();
                this.$emit('valid', val);
            }
        },
        methods: {
            fieldHasLang(field) {
                if (!this.website.metas.languages_list.length) {
                    return false;
                }
                return typeof field.fieldObject.params.hasLang === 'undefined' ? field.hasLang : field.fieldObject.params.hasLang;
            },
            validateTab() {
                const errorInputs = this.$refs.form.inputs
                    .map(e => {
                        if (e.$attrs.fieldObject?.params?.errorList) {
                            e.$attrs.fieldObject.params.errorList = [];
                        }

                        return e;
                    })
                    .filter(e => (e.hasError && e.$attrs.fieldObject && (e.hasFocused || e.hasInput)));

                for (const errorInput of errorInputs.filter(e => e.$attrs.hasLang)) {
                    for (const classItem of errorInput.$el.classList) {
                        if (classItem.startsWith('lang-')) {
                            errorInput.$attrs.fieldObject.params.errorList.push(parseInt(classItem.replaceAll('lang-', '')))
                        }
                    }
                }

                const errorInputNames = errorInputs.map(e => e.$attrs.fieldObject.name)
                for (const tab of this.formObject.children) {
                    tab.hasError = !!tab.getFields().filter(field => errorInputNames.indexOf(field.field.fieldObject.name) > -1).length;
                }
            },
            fieldChanged(key, val, lang) {
                const field = this.formObject.setFieldValue(key, val, lang);
                this.$emit('fieldChange', {form: this.formObject, field, value: val, language: lang});
                this.$emit('input', this.formObject)
            },
            initFormObject (val) {
                if ((val && val.length) || val instanceof FormClass) {
                    this.formObject = val instanceof FormClass ? val : new FormClass(val);
                } else {
                    this.formObject = new FormClass();
                }
            }
        },
        components: {
            fieldComponent
        }
    }
</script>
<style lang="scss" scoped>
.tab-has-error {
    color: red !important;
}
</style>
