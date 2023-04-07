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
                    {{ tab.title }}
                </v-tab>
            </v-tabs>
            <v-tabs-items v-model="tab">
                <v-tab-item
                        v-for="(tab, index) in formObject.children"
                        :key="'tab' + index"
                        eager
                >
                    <v-container :fluid="fluid">
                        <v-row
                                v-for="(row, rowIndex) in tab.children"
                                :key="'row' + rowIndex"
                        >
                            <v-col
                                    v-for="(col, colIndex) in row.children"
                                    :key="'col' + colIndex"
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
                                            :value="field.value"
                                            @input="fieldChanged"
                                            :params="{...field.params, fieldObject: field.field.fieldObject, defaultObject: field.field.defaultObject}"
                                            :events="{...field.events}"
                                            :form="formObject"
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
            }
        },
        created() {
            this.$emit('validate', () => {
                this.validateTab();
                return this.$refs.form.validate();
            });
            this.$emit('resetValidation', () => this.$refs.form.resetValidation());
            this.initFormObject(this.value);
        },
        computed: {
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
            validateTab() {
                const errors = this.$refs.form.inputs.filter(e => (e.hasError && e.$attrs.fieldObject && (e.hasFocused || e.hasInput))).map(e => e.$attrs.fieldObject.name);

                for (const tab of this.formObject.children) {
                    tab.hasError = !!tab.getFields().filter(field => errors.indexOf(field.field.fieldObject.name) > -1).length;
                }
            },
            fieldChanged(key, val) {
                this.formObject.setFieldValue(key, val);
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
