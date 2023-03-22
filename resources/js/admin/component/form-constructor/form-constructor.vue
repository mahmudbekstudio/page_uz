<template>
    <div class="form-constructor">
        <components-list :list="componentsList"/>
        <v-divider/>
        <tabs-list
                v-model="tab"
                :form="formObject"
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

    const advanced = {
        parent: new Field({type: 'advancedParent'}),
    };

    const required = {
        publishEnd: new Field({type: 'requiredPublishEnd'}),
        publishStart: new Field({type: 'requiredPublishStart'}),
        routeName: new Field({type: 'requiredRouteName'}),
        seoDescription: new Field({type: 'requiredSeoDescription'}),
        seoKeyword: new Field({type: 'requiredSeoKeyword'}),
        status: new Field({type: 'requiredStatus'}),
        template: new Field({type: 'requiredTemplate'}),
        title: new Field({type: 'requiredTitle'}),
    };

    export default {
        data() {
            return {
                componentsList: {
                    basic: [
                        new Field({type: 'text'}),
                        new Field({type: 'textarea'}),
                        new Field({type: 'number'}),
                        new Field({type: 'checkbox'}),
                        new Field({type: 'radio'}),
                        new Field({type: 'divider'}),
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
                            text: 'Cancel',
                            click: () => {
                                this.closeElementForm();
                                this.selectedElement = null;
                                this.elementDialog.show = false;
                            }
                        },
                        {
                            color: 'primary',
                            text: 'Save',
                            click: () => {
                                if (this.selectedFormValidation()) {
                                    if (this.checkAdvanced()) {
                                        app.errorMessage('Field with special name ' + this.selectedElementForm.name + ' must be added from advanced tab');
                                        return false;
                                    }

                                    if (this.checkRequired()) {
                                        app.errorMessage('Field with special name ' + this.selectedElementForm.name + ' must be added from required tab');
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
                                        app.errorMessage('Field with name ' + this.selectedElementForm.name + ' exist');
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
                    for (const key in advanced) {
                        if (typeof values[key] === 'undefined') {
                            this.componentsList.advanced.push(advanced[key]);
                        }
                    }

                    for (const key in required) {
                        if (typeof values[key] === 'undefined') {
                            this.componentsList.required.push(required[key]);
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
            }
        },
        computed: {
            elementForm () {
                const elements = this.selectedElement?.item?.fillableFields || [];
                if (this.elementDialog.actionType === 'edit') {
                    for (const element of elements) {
                        if (typeof this.selectedElement.item.field.fill[element.name] !== 'undefined') {
                            if (element.name === 'validation') {
                                element.value = this.selectedElement.item.field.fill[element.name];
                            } else {
                                element.value = this.selectedElement.item.field.fill[element.name];
                            }
                        }
                    }
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
                return this.elementDialog.actionType === 'add' ? 'Add' : 'Edit';
            }
        },
        methods: {
            checkAdvanced () {
                return advanced[this.selectedElementForm.name] && this.selectedElement.item.field.fieldObject.type !== advanced[this.selectedElementForm.name].type;
            },
            checkRequired () {
                return required[this.selectedElementForm.name] && this.selectedElement.item.field.fieldObject.type !== required[this.selectedElementForm.name].type;
            },
            closeElementForm() {
                if (this.selectedElement && this.elementDialog.actionType === 'add') {
                    this.selectedElement.col.children = this.selectedElement.col.children.filter(item => this.selectedElement.item.field.id !== item.field.id);
                }
            },
            elementFormChanged(form)  {
                this.selectedElementForm = form.getFieldValues();
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
        .constructor-col {
            border: dashed 1px #EEE;
        }
    }
</style>
