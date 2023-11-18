<template>
    <div>
        <div>{{labelText}}</div>
        <div><small>{{ params?.placeholder ? $t(params.placeholder) : null }}</small></div>
        <sort-list v-model="list" @end="setData">
            <template v-slot:title="{item}">
                {{ item.value }} => {{ $t(item.label) }}
            </template>
            <template v-slot:actions="{item, indexes}">
                <v-btn
                    small
                    icon
                    class="menu-action-btn btn-inactive"
                    @click="clickEdit(item)"
                    :disabled="disabled"
                >
                    <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-btn
                    small
                    icon
                    class="menu-action-btn btn-inactive"
                    @click="clickDelete(item, indexes)"
                    :disabled="disabled"
                >
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </template>
        </sort-list>

        <v-btn :disabled="disabled" @click="openDialog(true)">{{$t('words.create')}}</v-btn>
        <div><small>{{params?.hint ? $t(params.hint) : null}}</small></div>
        <dialog-component
            v-model="elementDialog.show"
            title="Option"
            :actions="elementDialog.actions"
        >
            <v-form
                ref="optionValueForm"
                lazy-validation
                v-model="valid"
            >
                <div v-for="field of formFields">
                    <field-component
                        :type="field.type"
                        :value="field.value"
                        :params="{...field.params, hasLang: field.hasLang, fieldObject: field.params.fieldObject}"
                        :has-lang="field.hasLang"
                        @input="fieldValueChanged"
                        :field-key="field.key"
                    />
                </div>
            </v-form>
        </dialog-component>
    </div>
</template>
<script>
import mixins from '../../../mixin';
import sortList from "../../sort-list";
import dialogComponent from "../../dialog-component";
import validation from "../../../config/validation";
import fieldsList from "../../website-render/fields/class/FieldsList";
import app from "../../../service/app";
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            valid: true,
            list: [],
            formFields: [
                {
                    type: 'text',
                    value: null,
                    hasLang: false,
                    params: {
                        label: 'Value',
                        rules: [validation.required('Value')],
                        fieldObject: {params: {errorList: []}}
                    },
                    key: 'value',
                },
                {
                    type: 'text',
                    value: null,
                    hasLang: true,
                    params: {
                        label: 'Label',
                        rules: [validation.required('Label')],
                        fieldObject: {params: {errorList: []}}
                    },
                    key: 'label',
                }
            ],
            elementDialog: {
                show: false,
                dialogTypeIsCreate: true,
                item: null,
                actions: [
                    {
                        color: 'default',
                        text: 'words.cancel',
                        click: () => {
                            this.elementDialog.show = false;
                        }
                    },
                    {
                        color: 'primary',
                        text: 'words.save',
                        click: () => {
                            this.$refs.optionValueForm.resetValidation();
                            this.$refs.optionValueForm.inputs
                                .map(e => {
                                    if (e.$attrs.fieldObject?.params?.errorList) {
                                        e.$attrs.fieldObject.params.errorList = [];
                                    }

                                    return e;
                                });
                            if (this.$refs.optionValueForm.validate()) {
                                if (this.elementDialog.dialogTypeIsCreate) {
                                    if(this.list.filter(item => item.value === this.formFields[0].value).length) {
                                        app.openMessage('Item exist in the list with this value');
                                    } else {
                                        this.elementDialog.item.value = this.formFields[0].value;
                                        this.elementDialog.item.label = this.formFields[1].value;
                                        this.list.push(this.elementDialog.item);
                                        this.elementDialog.show = false;
                                        this.setData();
                                    }
                                } else {
                                    if (this.elementDialog.item.value === this.formFields[0].value) {
                                        this.elementDialog.item.label = {...this.formFields[1].value};
                                        this.elementDialog.show = false;
                                        this.setData();
                                    } else if(this.list.filter(item => item.value === this.formFields[0].value).length) {
                                        app.openMessage('Item exist in the list with this value');
                                    } else {
                                        this.elementDialog.item.value = this.formFields[0].value;
                                        this.elementDialog.item.label = {...this.formFields[1].value};
                                        this.elementDialog.show = false;
                                        this.setData();
                                    }
                                }
                            } else {
                                const errorInputs = this.$refs.optionValueForm.inputs
                                    .map(e => {
                                        if (e.$attrs.fieldObject?.params?.errorList) {
                                            e.$attrs.fieldObject.params.errorList = [];
                                        }

                                        return e;
                                    })
                                    .filter(e => (!e.valid && e.$attrs.fieldObject && (e.hasFocused || e.hasInput)));

                                for (const errorInput of errorInputs.filter(e => e.$attrs.hasLang)) {
                                    for (const classItem of errorInput.$el.classList) {
                                        if (classItem.startsWith('lang-')) {
                                            errorInput.$attrs.fieldObject.params.errorList.push(parseInt(classItem.replaceAll('lang-', '')))
                                        }
                                    }
                                }
                            }
                        }
                    }
                ]
            }
        }
    },
    created() {
        this.initList(this.dataValue);
    },
    mixins: [mixins.get('formField')],
    computed: {
        ...mapGetters({
            website: 'view/website',
        }),
        fieldsList() {
            return fieldsList
        }
    },
    methods: {
        setData() {
            const result = [];
            for (const item of this.list) {
                result.push({text: item.label, value: item.value});
            }

            this.dataValue = [...result];
        },
        fieldValueChanged (key, value, lang) {
            for (const fieldIndex in this.formFields) {
                if (this.formFields[fieldIndex].key === key) {
                    if (lang) {
                        if (typeof this.formFields[fieldIndex].value === 'object') {
                            this.formFields[fieldIndex].value[lang] = value;
                        } else {
                            this.formFields[fieldIndex].value = {[lang]: value};
                        }
                    } else {
                        this.formFields[fieldIndex].value = value;
                    }
                    break;
                }
            }
        },
        openDialog(isCreate = false, item = {value: null, label: null}) {
            if (typeof item.label === 'string' || item.label === null) {
                item.label = {[this.website.metas.languages_list[0]]: item.label};
            }
            this.formFields[0].value = item.value;
            this.formFields[1].value = item.label;
            this.elementDialog.item = item;
            this.elementDialog.dialogTypeIsCreate = isCreate;
            this.elementDialog.show = true;
            this.$nextTick(() => {
                this.$refs.optionValueForm.resetValidation();
                this.$refs.optionValueForm.inputs
                    .map(e => {
                        if (e.$attrs.fieldObject?.params?.errorList) {
                            e.$attrs.fieldObject.params.errorList = [];
                        }

                        return e;
                    });
            });
        },
        clickEdit(item) {
            this.openDialog(false, item);
        },
        clickDelete(item) {
            this.list.splice(this.list.indexOf(item), 1);
        },
        initList(value) {
            if (typeof value === 'string') {
                for (const line of value.split("\n")) {
                    if (line.trim() ===  '') continue;
                    const lineArr = line.split(':');
                    this.list.push({
                        value: lineArr[0].trim(),
                        label: lineArr[1].trim()
                    });
                }
            } else {
                this.list = [];
                for (const item of value) {
                    this.list.push({
                        value: item.value,
                        label: item.text
                    })
                }
            }
        }
    },
    watch: {
        dataValue(value) {
            this.initList(value);
        }
    },
    components: {
        dialogComponent,
        sortList
    }
}
</script>
<style scoped lang="scss"></style>
