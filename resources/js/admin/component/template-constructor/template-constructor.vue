<template>
    <v-container class="template-constructor">
        <v-row>
            <v-col>
                <div
                    class="overlay"
                    v-if="disabled"
                />
                <components-list :list="componentsList"/>
                <v-divider/>
                {{templateObject}}
                <v-container class="constructor-container">
                    <v-row
                        v-for="(row, rowIndex) in templateObject.children"
                        :key="'row' + rowIndex"
                        class="constructor-row"
                    >
                        <row-action
                            :row="row"
                            :template="templateObject"
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
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import componentsList from "./components-list";
import { Template, Element } from '../template/classes/template';
import rowAction from "./row-action.vue";
import cell from "./cell.vue";
import app from "../../service/app";
import dialogComponent from "../dialog-component";
import formComponent from "../form/form-component.vue";

export default {
    data () {
        return {
            templateObject: null,
            componentsList: {
                basic: [
                    new Element({tag: 'h'}),
                    new Element({tag: 'p'}),
                    new Element({tag: 'div'}),
                    new Element({tag: 'hr'}),
                ]
            },
            selectedElement: null,
            selectedElementForm: null,
            selectedFormResetValidation: null,
            selectedFormValidation: null,
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
                                this.selectedElement.item.element.fill = this.selectedElementForm;
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
        }
    },
    created() {
        this.setTemplate(this.value);
    },
    watch: {
        value(val) {
            this.setTemplate(val);
        },
    },
    computed: {
        elementForm () {
            const elements = this.selectedElement?.item?.fillableFields || [];
            const elementHasLang = !!this.selectedElement?.item?.element?.hasLang;
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
                    const fieldHasLang = this.selectedElement.item.element.fill.hasLang;
                    hasLangObj.value = typeof fieldHasLang === 'undefined' ? true : !!fieldHasLang;
                }

                for (const element of elements) {
                    if (typeof this.selectedElement.item.element.fill[element.name] !== 'undefined') {
                        element.value = this.selectedElement.item.element.fill[element.name];

                        if (elementHasLang && element.name === 'content') {
                                element.hasLang = element.hasLang || true;
                                valueObj = element;
                            } else if (element.name === 'content') {
                                element.hasLang = false;
                            }
                    }
                }
            } else {
                for (const element of elements) {
                    if (elementHasLang && element.name === 'content') {
                        element.hasLang = element.hasLang || true;
                        valueObj = element;
                    } else if (element.name === 'content') {
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
            const fieldType = this.selectedElement?.item?.element?.constructor?.name;
            let title = this.elementDialog.actionType === 'add' ? this.$t('words.add') : this.$t('words.edit');

            if(fieldType) {
                title += ' - ' + this.$t('words.components.tag_' + fieldType.substr(0, fieldType.length - 7));
            }

            return title;
        }
    },
    methods: {
        setTemplate(val) {
            this.templateObject = val instanceof Template ? val : new Template(val);
        },
        editCell (cell) {
            this.selectedElement = cell;
            this.elementDialog.actionType = 'edit';
            this.elementDialog.show = true;
        },
        addElement (cell) {
            if (!cell.item.element.hasFillable) {
                return false;
            }

            this.selectedElement = cell;
            this.elementDialog.actionType = 'add';
            this.elementDialog.show = true;
        },
        closeElementForm() {
            if (this.selectedElement && this.elementDialog.actionType === 'add') {
                this.selectedElement.col.children = this.selectedElement.col.children.filter(item => this.selectedElement.item.element.id !== item.element.id);
            }
        },
        elementFormChanged(form)  {
            this.selectedElementForm = form.getFieldValues();
        },
        fieldChanged(params) {
            if (params.field.name === 'hasLang') {
                const fields = params.form.getFields();
                for (const field of fields) {
                    if (field.name === 'content') {
                        field.field.hasLang = params.value;
                        break;
                    }
                }
            }
        },
    },
    components: {
        formComponent,
        componentsList,
        rowAction,
        cell,
        dialogComponent,
    }
}
</script>

<style scoped lang="scss">
.template-constructor {
    position: relative;

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
