<template>
    <div class="form-constructor">
        <div
            class="overlay"
            v-if="disabled"
        />
        <components-list :list="componentsList"/>
        <v-divider/>
        <tabs-list
                v-model="tab"
                :form="formObject"
                v-if="!hideTabs"
        />
        <v-tabs-items v-model="tab" class="custom-tabs-items">
            <v-tab-item
                    v-for="(tab, index) in formObject.children"
                    :key="'tab' + index"
                    class="constructor-tab-body"
            >
                <v-container class="constructor-container">
                    <v-row
                            v-for="(row, rowIndex) in tab.children"
                            :key="'row' + rowIndex"
                            class="constructor-row"
                    >
                        <row-action
                            :row="row"
                            :tab="tab"
                        />
                        <template v-for="col in row.children">
                            <cell
                                :col="col"
                                :row="row"
                                @edit="editCell($event)"
                                @add="addElement($event)"
                                :dragging="dragging"
                                @dragging="dragging=$event"
                            />
                        </template>
                    </v-row>
                </v-container>
            </v-tab-item>
        </v-tabs-items>
        <dialog-component
            v-model="elementDialog.show"
            :title="elementDialogTitle"
            :actions="elementDialog.actions"
            @close="closeElementForm"
        >
            <form-component
                v-if="elementDialog.show"
                :value="elementForm"
                @input="elementFormChanged"
                @fieldChange="fieldChanged"
                @resetValidation="selectedFormResetValidation = $event"
                @validate="selectedFormValidation = $event"
            />
        </dialog-component>
    </div>
</template>
<script>
    import { Form as FormClass, Field } from '../form/classes/form';
    import tabsList from './tabs-list';
    import rowAction from './row-action';
    import componentsList from "./components-list";
    import cell from './cell';
    import dialogComponent from "../dialog-component";
    import formComponent from '../form/form-component';
    import app from "../../service/app";

    export default {
        data() {
            return {
                dragging: false,
                componentsList: {
                    basic: [
                        new Field({type: 'text'}),
                        new Field({type: 'textarea'}),
                        new Field({type: 'number'}),
                        new Field({type: 'checkbox'}),
                        new Field({type: 'radio'}),
                        //new Field({type: 'divider'}),
                        new Field({type: 'date'}),
                        new Field({type: 'datetime'}),
                        new Field({type: 'dateMultiple'}),
                        new Field({type: 'dateRange'}),
                        new Field({type: 'time'}),
                        new Field({type: 'switch'}),
                        new Field({type: 'password'}),
                        new Field({type: 'select'}),
                        new Field({type: 'file'}),
                        new Field({type: 'editor'}),
                    ],
                    advanced: [],
                    required: []
                },
                tab: 0,
                formObject: null,
                selectedElement: null,
                selectedFormResetValidation: null,
                selectedFormValidation: null,
                selectedElementForm: null,
                elementDialog: {
                    actionType: '',
                    show: false,
                    actions: [
                        {
                            color: 'default',
                            text: 'words.cancel',
                            click: () => {
                                this.closeElementForm();
                                this.selectedElement = null;
                                this.elementDialog.show = false;
                            }
                        },
                        {
                            color: 'primary',
                            text: 'words.save',
                            click: () => {
                                if (this.selectedFormValidation()) {
                                    if (this.checkAdvanced()) {
                                        app.errorMessage(this.$t('words.field_with_special_name') + ' ' + this.selectedElementForm.name + ' ' + this.$t('words.must_added_advanced_tab'));
                                        return false;
                                    }

                                    if (this.checkRequired()) {
                                        app.errorMessage(this.$t('words.field_with_special_name') + ' ' + this.selectedElementForm.name + ' ' + this.$t('words.must_added_required_tab'));
                                        return false;
                                    }
                                    const fieldValues = Object.keys(this.formObject.getFieldValues());
                                    let nameExist = false;

                                    if (fieldValues.indexOf(this.selectedElementForm.name) > -1) {
                                        nameExist = this.elementDialog.actionType === 'add';

                                        if (!nameExist) {
                                            const fields = this.formObject.getFields();
                                            const currentElementIndex = fields.indexOf(this.selectedElement.item);
                                            nameExist = fields[currentElementIndex].name !== this.selectedElementForm.name;
                                        }
                                    }

                                    if (nameExist) {
                                        app.errorMessage(this.$t('words.field_with_name') + ' ' + this.selectedElementForm.name + ' ' + this.$t('words.exist'));
                                        return false;
                                    }

                                    this.selectedElement.item.field.fill = this.selectedElementForm;
                                    this.selectedElement.col.children = [...this.selectedElement.col.children];
                                    this.selectedElement = null;
                                    this.elementDialog.show = false;
                                }
                            }
                        }
                    ]
                }
            }
        },
        created() {
            this.setForm(this.value);
        },
        watch: {
            value(val) {
                this.setForm(val);
            },
            formObject: {
                handler(newVal, oldVal) {
                    const values = newVal.getFieldValues();
                    this.componentsList.advanced = [];
                    this.componentsList.required = [];
                    for (const key in this.advanced) {
                        if (typeof values[key] === 'undefined') {
                            this.componentsList.advanced.push(this.advanced[key]);
                        }
                    }

                    for (const key in this.required) {
                        if (typeof values[key] === 'undefined') {
                            this.componentsList.required.push(this.required[key]);
                        }
                    }
                },
                deep: true
            }
        },
        props: {
            value: {
                default() {
                    return []
                }
            },
            advanced: {
                type: Object,
                default() {
                    return {};
                }
            },
            required: {
                type: Object,
                default() {
                    return {};
                }
            },
            disabled: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            hideTabs: {
                type: Boolean,
                default() {
                    return false;
                }
            },
        },
        computed: {
            elementForm () {
                const elements = this.selectedElement?.item?.fillableFields || [];
                const elementHasLang = !!this.selectedElement?.item?.field?.hasLang;
                let hasLangObj = null;

                if (elementHasLang) {
                    hasLangObj = {
                        type: 'switch',
                        name: 'hasLang',
                        value: true,
                        params: {
                            label: 'words.has_lang',
                            valueType: 'bool'
                        }
                    };
                }

                let valueObj = null;
                if (this.elementDialog.actionType === 'edit') {
                    if (elementHasLang && hasLangObj) {
                        const fieldHasLang = this.selectedElement.item.field.fill.hasLang;
                        hasLangObj.value = typeof fieldHasLang === 'undefined' ? true : !!fieldHasLang;
                    }

                    for (const element of elements) {
                        if (typeof this.selectedElement.item.field.fill[element.name] !== 'undefined') {
                            if (element.name === 'validation') {
                                element.value = this.selectedElement.item.field.fill[element.name];
                            } else {
                                element.value = this.selectedElement.item.field.fill[element.name];
                            }

                            if (elementHasLang && element.name === 'value') {
                                element.hasLang = element.hasLang || true;
                                valueObj = element;
                            } else if (element.name === 'value') {
                                element.hasLang = false;
                            }
                        }
                    }
                } else {
                    for (const element of elements) {
                        if (elementHasLang && element.name === 'value') {
                            element.hasLang = element.hasLang || true;
                            valueObj = element;
                        } else if (element.name === 'value') {
                            element.hasLang = false;
                        }
                    }
                }

                if (elementHasLang && hasLangObj && valueObj) {
                    valueObj.hasLang = hasLangObj.value;
                    elements.unshift(hasLangObj);
                }

                return [
                    {
                        type: 'tab',
                        children: [
                            {
                                type: 'row',
                                children: [
                                    {
                                        type: 'col',
                                        size: '12',
                                        children: elements
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            elementDialogTitle () {
                const fieldType = this.selectedElement?.item?.field?.constructor?.name;
                let title = this.elementDialog.actionType === 'add' ? this.$t('words.add') : this.$t('words.edit');

                if(fieldType) {
                    title += ' - ' + fieldType.substr(0, fieldType.length - 5);
                }

                return title;
            }
        },
        methods: {
            checkAdvanced () {
                return this.advanced[this.selectedElementForm.name] && this.selectedElement.item.field.fieldObject.type !== this.advanced[this.selectedElementForm.name].type;
            },
            checkRequired () {
                return this.required[this.selectedElementForm.name] && this.selectedElement.item.field.fieldObject.type !== this.required[this.selectedElementForm.name].type;
            },
            closeElementForm() {
                if (this.selectedElement && this.elementDialog.actionType === 'add') {
                    this.selectedElement.col.children = this.selectedElement.col.children.filter(item => this.selectedElement.item.field.id !== item.field.id);
                }
            },
            elementFormChanged(form)  {
                this.selectedElementForm = form.getFieldValues();
            },
            fieldChanged(params) {
                if (params.field.name === 'hasLang') {
                    const fields = params.form.getFields();
                    for (const field of fields) {
                        if (field.name === 'value') {
                            field.field.hasLang = params.value;
                            break;
                        }
                    }
                }
            },
            setForm(val) {
                this.formObject = val instanceof FormClass ? val : new FormClass(val);
                //this.formObject.addTab({title: 'Tab 111'});
            },
            editCell (cell) {
                this.selectedElement = cell;
                this.elementDialog.actionType = 'edit';
                this.elementDialog.show = true;
            },
            addElement (cell) {
                if (!cell.item.field.hasFillable) {
                    return false;
                }

                this.selectedElement = cell;
                this.elementDialog.actionType = 'add';
                this.elementDialog.show = true;
            }
        },
        components: {
            tabsList,
            rowAction,
            componentsList,
            cell,
            dialogComponent,
            formComponent,
        }
    }
</script>
<style scoped lang="scss">
    .form-constructor {
        position: relative;

        .constructor-col {
            border: dashed 1px #EEE;
        }

        .overlay {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background: #000;
            opacity: 0.2;
            z-index: 1;
        }
    }
</style>
