<template>
    <page-box
        class="module-type-form"
        :actions="actions"
    >
        <formComponent
            :value="menuForm"
            :disabled="loading"
            @validate="formValidate=$event"
        />
        <v-container>
            <v-row>
                <v-col
                    cols="12"
                    md="3"
                >
                    <v-expansion-panels
                        accordion
                        focusable
                    >
                        <v-expansion-panel
                            v-for="link in links"
                            :key="link.type"
                        >
                            <v-expansion-panel-header>{{ link.title }}</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <draggable
                                    v-model="link.children"
                                    v-bind="dragOptions"
                                    @end="dragEnd"
                                >
                                    <transition-group type="transition" class="transition-group">
                                        <div
                                            v-for="item in link.children"
                                            :key="item.id"
                                            class="link-item"
                                        >{{item.title}}</div>
                                    </transition-group>
                                </draggable>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-expansion-panels>
                </v-col>
                <v-col
                    cols="12"
                    md="9"
                >
                    <sort-list
                        v-model="list"
                        @add="addElement"
                    >
                        <template v-slot:actions="{item, indexes}">
                            <v-btn
                                small
                                icon
                                class="menu-action-btn"
                                @click="clickEdit(item)"
                            >
                                <v-icon>mdi-pencil</v-icon>
                            </v-btn>
                            <v-btn
                                small
                                icon
                                class="menu-action-btn"
                                @click="clickLink(item)"
                            >
                                <v-icon>mdi-open-in-new</v-icon>
                            </v-btn>
                            <v-btn
                                small
                                icon
                                class="menu-action-btn"
                                @click="clickDelete(item, indexes)"
                            >
                                <v-icon>mdi-close</v-icon>
                            </v-btn>
                        </template>
                    </sort-list>
                </v-col>
            </v-row>
        </v-container>
        <dialog-component
            v-model="elementDialog.show"
            title="Edit"
            :actions="elementDialog.actions"
        >
            <form-component
                v-if="elementDialog.show"
                :value="elementForm"
                @input="selectedElementForm=$event.getFieldValues()"
                @validate="selectedFormValidation = $event"
            />
        </dialog-component>
    </page-box>
</template>
<script>
import Service from './service';
import pageBox from '../../../view/partial/page-box';
import { getPageBoxAction } from '../../../helper';
import { Form as FormClass } from '../../../component/form/classes/form';
import { mapActions, mapGetters } from 'vuex';
import app from "../../../service/app";
import formComponent from '../../../component/form/form-component';
import sortList from '../../../component/sort-list.vue';
import draggable from 'vuedraggable';
import dialogComponent from "../../../component/dialog-component";
import validation from "../../../config/validation";
import * as _ from 'lodash';

export default {
    service: new Service(),
    data() {
        return {
            menuForm: null,
            formValidate: null,
            actions: [],
            list: [],
            links: [],
            addedList: [],
            selectedElement: null,
            selectedIndexes: null,
            selectedElementForm: {},
            selectedFormValidation: null,
            elementDialog: {
                actionType: '',
                show: false,
                actions: [
                    {
                        color: 'default',
                        text: 'Cancel',
                        click: () => {
                            if (this.elementDialog.actionType === 'add') {
                                this.deleteListItem(this.selectedElement, this.selectedIndexes);
                            }
                            this.selectedElement = null;
                            this.selectedIndexes = null;
                            this.elementDialog.show = false;
                            this.elementDialog.actionType = '';
                        }
                    },
                    {
                        color: 'primary',
                        text: 'Save',
                        click: () => {
                            if (this.selectedFormValidation()) {
                                for (const fieldKey in this.selectedElementForm) {
                                    this.selectedElement[fieldKey] = this.selectedElementForm[fieldKey];
                                }

                                this.selectedElement = null;
                                this.selectedIndexes = null;
                                this.elementDialog.show = false;
                                this.elementDialog.actionType = '';
                            }
                        }
                    }
                ]
            }
        }
    },
    created() {
        this.currentLangChanged();
        this.loadLinks();

        if (this.$options.service.id) {
            this.$options.service.get(
                response => {
                    this.list = response.data.menu.items;
                    this.createMenuForm(response.data.menu);
                }
            );
        } else {
            this.createMenuForm();
        }
    },
    computed: {
        ...mapGetters({
            loading: 'view/loading',
            website: 'view/website',
        }),
        dragOptions() {
            return {
                animation: 200,
                group: {
                    name: 'menu-list',
                    pull: 'clone',
                    put: false,
                },
                disabled: false,
                ghostClass: "ghost",
                sort: false
            };
        },
        elementForm () {
            const form = new FormClass();
            form.addField({
                type: 'text',
                value: this.selectedElement?.title,
                name: 'title',
                params: {label: 'Title', rules: [validation.required('Title')]}
            });

            if (this.selectedElement?.id === 'custom') {
                form.addField({
                    type: 'text',
                    value: this.selectedElement?.url,
                    name: 'url',
                    params: {label: 'Url', rules: [validation.required('Url')]}
                });
            }

            return form;
        },
    },
    watch: {
        loading(val) {
            this.actions.forEach(item => item.bind.disabled = val);
        },
        'website.lang'(newLang, oldLang) {
            this.currentLangChanged(newLang, oldLang);
        },
    },
    methods: {
        ...mapActions({
            changeTitle: 'view/changeTitle',
        }),
        currentLangChanged () {
            this.actions.push(getPageBoxAction(this.$t('words.back'), '', {color: 'default'}, {click: this.backClick}));
            this.actions.push(getPageBoxAction(this.$t('words.' + (this.$options.service.id ? 'update' : 'create')), '', {color: 'primary'}, {click: this.saveForm}));
        },
        addElement (e) {
            const item = _.get(this.list, e.indexes.join('.'));
            if (this.addedList.indexOf(item.key) === -1) {
                this.addedList.push(item.key);
                this.clickEdit(item);
            }
            this.selectedIndexes = e.indexes;
        },
        loadLinks () {
            this.$options.service.links(response => {
                this.links = response.data.links;
            })
        },
        backClick () {
            this.$router.push({name: 'menu.list'});
        },
        createMenuForm (values) {
            this.menuForm = new FormClass();
            this.menuForm.addField({
                type: 'text',
                name: 'name',
                value: values?.name || '',
                params: {label: 'Menu name', rules: [validation.required('Menu name')]}
            });
        },
        saveForm() {
            this.formValidate();
            if (!this.formValidate()) {
                app.errorMessage('Please, fill all required fields');
                return false;
            }

            const formValues = {...this.menuForm.getFieldValues(), items: this.list};
            this.$options.service.submit(formValues, response => {
                if (!this.$options.service.id) {
                    this.backClick();
                }
                app.successMessage('Saved');
            });
        },
        dragEnd (e) {
            this.elementDialog.actionType = 'add';
        },
        clickDelete(item, indexes) {
            app.openConfirm("Do you really want to delete menu item \"" + item.title + '"', () => {
                this.deleteListItem(item, indexes);
            })
        },
        clickLink (item) {
            const url = '//' + this.website.domain + '/' + (this.website.metas.language || this.website.lang) + '/' + item.url;
            window.open(url,'_blank');
        },
        deleteListItem(item, indexes) {
            const path = indexes.slice(0, indexes.length - 1).join('.');

            if (path) {
                const list = _.get(this.list, path);
                list.splice(list.indexOf(item), 1);
            } else {
                this.list.splice(this.list.indexOf(item), 1);
            }
        },
        clickEdit (item) {
            this.selectedElement = item;

            for (const itemKey in this.elementForm.getFieldValues()) {
                this.selectedElementForm[itemKey] = item[itemKey];
            }

            this.elementDialog.show = true;
            this.elementDialog.actionType = this.elementDialog.actionType || 'edit';
        }
    },
    components: {
        pageBox,
        formComponent,
        sortList,
        draggable,
        dialogComponent,
    }
}
</script>
<style lang="scss" scoped>
.menu-action-btn {
    height: 24px;
}
.link-item {
    box-shadow: 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12);
    border-radius: 4px;
    padding: 4px 10px;
    margin: 10px 0 0 0;
    cursor: pointer;
}
</style>
