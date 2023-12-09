<template>
    <div class="module-feature-form">
        <form-component
            :value="featureForm"
            :disabled="isLoading"
            @input="formChanged($event)"
        ></form-component>
        <v-container fluid v-if="typeField.value !== null">
            <v-row>
                <v-col
                    cols="12"
                    sm="3"
                >
                    <sort-list
                        v-model="domsList"
                        :can-has-child="true"
                    >
                        <template v-slot:actions="{item, indexes}">
                            <v-btn
                                small
                                icon
                                class="menu-action-btn btn-inactive"
                                @click="clickEdit(item, indexes)"
                            >
                                <v-icon>mdi-pencil</v-icon>
                            </v-btn>
                            <v-btn
                                v-if="item.canHasChild !== false"
                                small
                                icon
                                class="menu-action-btn btn-inactive"
                                @click="clickAdd(item, indexes)"
                            >
                                <v-icon>mdi-playlist-plus</v-icon>
                            </v-btn>
                            <v-btn
                                small
                                icon
                                class="menu-action-btn btn-inactive"
                                @click="clickDelete(item, indexes)"
                            >
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </template>
                        <template v-slot:append-item="{item, indexes}">
                            <div class="list-group add-new-row" v-if="!indexes.length">
                                <div class="list-item">
                                    <v-btn
                                        block
                                        color="primary"
                                        plain
                                        @click="clickAdd(item, indexes)"
                                    ><v-icon>mdi-plus-circle-outline</v-icon></v-btn>
                                </div>
                            </div>
                        </template>
                    </sort-list>
                </v-col>
                <v-col
                    cols="12"
                    sm="9"
                >
                    <feature-render :doms-list="domsList" />
                </v-col>
            </v-row>
        </v-container>
        <dialog-component
            v-model="elementDialog.show"
            :title="$t('words.' + elementDialog.actionType)"
            :actions="elementDialog.actions"
        >
            <form-component
                v-if="elementDialog.show"
                :value="elementForm"
                @validate="selectedFormValidation = $event"
            ></form-component>
        </dialog-component>
    </div>
</template>
<script>
import {mapGetters} from "vuex";
import formComponent from "../../../component/form/form-component.vue";
import {Form as FormClass} from "../../../component/form/classes/form";
import validation from "../../../config/validation";
import mainConfig from '../../../config/main';
import Service from "../js/service";
import sortList from "../../../component/sort-list.vue";
import dialogComponent from "../../../component/dialog-component.vue";
import app from '../../../service/app';
import * as _ from "lodash";
import { FEATURE_CONTENT_TYPES } from '../../../component/form/classes/featureContentField';
import featureRender from '../../../component/feature/render/feature-render';

export default {
    service: new Service(),
    data () {
        return {
            featureForm: null,
            featureTypeField: null,
            typeField: null,
            domType: null,
            contentType: null,
            domsList: [],
            selectedFormValidation: null,
            elementDialog: {
                actionType: '',
                show: false,
                selectedItem: null,
                actions: [
                    {
                        color: 'default',
                        text: 'words.cancel',
                        click: () => {
                            this.closeModal();
                        }
                    },
                    {
                        color: 'primary',
                        text: 'words.save',
                        click: () => {
                            if (this.selectedFormValidation()) {
                                const value = this.elementForm.getFieldValues();
                                const child = value.domType === 'container' ? {
                                    domType: value.domType,
                                    title: value.domType + ': ' + value.wrapper,
                                    value: _.omit(value, ['domType'])
                                } : {
                                    domType: value.domType,
                                    contentType: value.contentType,
                                    contentDomValue: _.omit(value, ['domType', 'contentValue', 'contentType']),
                                    title: value.domType + ': ' + value.contentType,
                                    canHasChild: false,
                                    value: value.contentValue
                                };
                                this.elementDialog.selectedItem.children.push(child);
                                this.closeModal();
                            }
                        }
                    }
                ]
            }
        }
    },
    props: {
        id: {
            type: Number,
            default () {
                return 0;
            }
        }
    },
    created() {
        if (this.id) {
            // TODO: load values
            this.initForm();
        } else {
            this.initForm();
        }
    },
    computed: {
        ...mapGetters({
            isLoading: 'view/loading',
        }),
        elementForm () {
            if (!this.elementDialog.actionType) {
                return null;
            }

            const form = new FormClass();

            if (this.elementDialog.actionType === 'create') {
                this.domType = form.addField({
                    type: 'radio',
                    value: this.domType?.value || 'container',
                    name: 'domType',
                    params: {/*label: 'words.type',*/ options: [
                            {text: 'words.container', value: 'container'},
                            {text: 'words.content', value: 'content'},
                        ]}
                });
            }

            if (this.domType.value === 'container') {
                form.addField({
                    type: 'select',
                    value: 'div',
                    name: 'wrapper',
                    params: {label: 'words.wrapper', clearable: false, options: [
                            {
                                text: 'words.div',
                                value: 'div',
                            },
                            {
                                text: 'words.span',
                                value: 'span',
                            }
                        ]}
                });
                this.addUniversalFields(form);
            } else {
                const options = [];

                for (const typeKey in FEATURE_CONTENT_TYPES) {
                    options.push({
                        text: FEATURE_CONTENT_TYPES[typeKey],
                        value: typeKey,
                    });
                }

                this.contentType = form.addField({
                    type: 'select',
                    value: this.contentType?.value || 'text',
                    name: 'contentType',
                    params: {label: 'words.contentType', clearable: false, options: options}
                });

                if (this.contentType.value) {
                    form.addField({
                        type: 'featureContent',
                        value: null,
                        name: 'contentValue',
                        params: {featureType: this.contentType.value}
                    });
                }

                this.addUniversalFields(form);
            }

            return form;
        },
    },
    methods: {
        closeModal() {
            this.elementDialog.show = false;
            this.elementDialog.actionType = '';
            this.elementDialog.selectedItem = null;
            this.domType = null;
            this.contentType = null;
        },
        clickEdit(item, indexes) {
            this.elementDialog.show = true;
            this.elementDialog.actionType = 'edit';
            console.log('clickEdit', item, indexes);
        },
        clickAdd(item, indexes) {
            this.elementDialog.show = true;
            this.elementDialog.actionType = 'create';
            this.elementDialog.selectedItem = item || {children: this.domsList};
        },
        clickDelete(item, indexes) {
            app.openConfirm(this.$t('words.do_you_really_want_to_delete'), () => {
                const path = indexes.slice(0, indexes.length - 1).join('.');
                const list = path ? _.get(this.domsList, path) : this.domsList;

                list.splice(list.indexOf(item), 1);
            });
        },
        initForm(formValue = {}) {
            this.featureForm = new FormClass();

            const nameField = this.featureForm.addField({type: 'text'});
            nameField.setParams('label', 'words.name');
            nameField.setParams('rules', [validation.required('words.name')]);
            nameField.name = 'name';
            nameField.value = formValue.name || '';

            this.featureTypeField = this.featureForm.addField({type: 'select'});
            this.featureTypeField.setParams('label', 'words.featureType');
            this.featureTypeField.name = 'feature_type';
            this.featureTypeField.setParams('clearable', false);
            const typeOptions = mainConfig.app.feature_types.map(item => ({value: item, text: 'words.feature.' + item}));
            this.featureTypeField.setParams('options', typeOptions);
            this.featureTypeField.value = formValue.feature_type || mainConfig.app.default_feature_type;

            this.typeField = this.featureForm.addField({type: 'select'});
            this.typeField.setParams('label', 'words.type');
            this.typeField.name = 'type_id';
            this.typeField.setParams('clearable', false);
            this.typeField.value = formValue.type_id || null;
            //this.typeField.hide = true;
        },
        formChanged (e) {
            //console.log('formChanged', e);
        },
        featureTypeChange(newValue) {
            if (newValue) {
                this.$options.service.getTypesList(newValue, response => {
                    const options = response.data.typesList.map(item => ({value: item.id, text: item.title}));
                    //this.typeField.hide = options.length === 1;

                    if (options.filter(item => item.value === this.typeField.value).length) {
                        this.typeChange(this.typeField.value);
                    } else if(options.length === 1) {
                        this.typeField.value = options[0].value;
                        this.typeChange(this.typeField.value);
                    } else {
                        this.typeField.value = 0;
                    }

                    this.typeField.setParams('options', options);
                });
            }
        },
        typeChange(typeId) {
            if (typeId && mainConfig.app.type.page.indexOf(this.featureTypeField.value) > -1) {
                this.$options.service.getTypeDetail(typeId, response => {
                    console.log('response', response);
                });
            }
        },
        addUniversalFields(form) {
            form.addField({
                type: 'text',
                value: '',
                name: 'id',
                params: {label: 'words.id', hasLang: false}
            });
            form.addField({
                type: 'text',
                value: '',
                name: 'class',
                params: {label: 'words.class', hasLang: false}
            });
            form.addField({
                type: 'text',
                value: '',
                name: 'title',
                params: {label: 'words.title'}
            });
            form.addField({
                type: 'text',
                value: '',
                name: 'style',
                params: {label: 'words.style', hasLang: false}
            });
        }
    },
    watch: {
        'domType.value' (newValue) {
            //console.log('domType.value', newValue);
        },
        'featureTypeField.value' (newValue) {
            this.featureTypeChange(newValue);
        },
        'typeField.value' (typeId) {
            this.typeChange(typeId);
        }
    },
    components: {
        dialogComponent,
        sortList,
        formComponent,
        featureRender,
    }
}
</script>
<style scoped lang="scss">
.add-new-row {
    margin-top: -5px;
}
</style>
