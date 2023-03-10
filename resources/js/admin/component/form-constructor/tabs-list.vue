<template>
    <v-tabs
            v-model="tab"
            align-with-title
            v-if="form"
    >
        <v-tabs-slider color="yellow" class="tab-active-line"></v-tabs-slider>

        <v-tab
                v-for="(tabItem, index) in form.children"
                :key="'tab' + index"
                class="constructor-tab-header"
        >
            {{ tabItem.title }}
            <v-menu offset-y>
                <template v-slot:activator="{ on, attrs }">
                    <v-icon
                            right
                            v-bind="attrs"
                            v-on="on"
                    >mdi-dots-vertical</v-icon>
                </template>
                <v-list>
                    <v-list-item @click="openTabFormDialog(tabItem, 'edit')">
                        <v-list-item-title><icon-pen class="icon-action"></icon-pen></v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="deleteTab(tabItem)">
                        <v-list-item-title><icon-delete class="icon-action"></icon-delete></v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="modeItem(tabItem, index, index - 1)">
                        <v-list-item-title><icon-left class="icon-action"></icon-left></v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="modeItem(tabItem, index, index + 1)">
                        <v-list-item-title><icon-right class="icon-action"></icon-right></v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-tab>
        <div class="add-new-tab">
            <v-btn icon @click="addNewTab()">
                <v-icon>mdi-plus</v-icon>
            </v-btn>
        </div>
        <dialog-component
                :actions="editActions"
                :title="tabFormTitle"
                :value="tabForm.show"
                @input="!$event && tabFormClose()"
                size="small"
        >
            <v-form ref="editTabForm" v-model="tabForm.valid">
                <FormField :value="tabForm.name" :params="tabNameParams" :events="{'change' : tabFormNameChanged, 'keyup': tabFormNameKeyup}"></FormField>
            </v-form>
        </dialog-component>
    </v-tabs>
</template>
<script>
    import dialogComponent from '../dialog-component';
    import app from '../../service/app';
    import {
        iconHorizontalDots,
        iconPen,
        iconDelete,
        iconLeftPlus,
        iconRightPlus,
        iconLeft,
        iconRight
    } from '../icons';
    import FormField from '../form/field-component';
    import validation from '../../config/validation';
    export default {
        name: 'tabs-list',
        data() {
            return {
                tabForm: {
                    show: false,
                    name: '',
                    valid: false,
                    action: '',
                },
                tabNameParams: {
                    label: 'Name *',
                    placeholder: 'Enter Tab Name',
                    rules: [
                        validation.required('Name')
                    ]
                },
                tab: 0,
                editActions: [],
                selectedTab: null
            };
        },
        props: {
            value: {
                type: Number,
                default() {
                    return 0;
                }
            },
            form: {
                default() {
                    return null;
                }
            }
        },
        watch: {
            value(val) {
                this.tab = val;
            },
            tab(val) {
                this.$emit('input', val);
            }
        },
        created() {
            this.tab = this.value;
            this.editActions.push({
                color: 'default',
                text: this.$t('words.close'),
                click: () => this.tabFormClose()
            });
            this.editActions.push(
                {
                    color: 'primary',
                    text: this.$t('words.save'),
                    click: () => this.editSave()
                }
            );
        },
        methods: {
            addNewTab() {
                this.openTabFormDialog(this.form.addTab({}), 'create');
            },
            modeItem(tabItem, fromIndex, toIndex) {
                if(toIndex < 0 || toIndex >= this.form.children.length) {
                    return false;
                }
                this.form.children.splice(fromIndex, 1);
                this.form.children.splice(toIndex, 0, tabItem);
            },
            deleteTab(tabItem) {
                app.openConfirm('Do you really want to delete tab ' + tabItem.title + '?', () => {
                    if(this.form.children.length === 1) {
                        app.errorMessage('You can not delete last tab');
                    } else {
                        this.deleteTabItem(tabItem);
                    }
                })
            },
            deleteTabItem(tabItem) {
                const itemIndex = this.form.children.indexOf(tabItem);
                this.form.children.splice(itemIndex, 1);
            },
            openTabFormDialog(tabItem, action) {
                this.selectedTab = tabItem;
                this.tabForm.show = true;
                this.tabForm.action = action;
                this.tabForm.name = tabItem.title;
            },
            tabFormNameChanged: function (val) {
                this.tabForm.name = val;
            },
            tabFormNameKeyup: function(e) {
                let key = e.which || e.keyCode || 0;
                if(e instanceof KeyboardEvent && key === 13) {
                    this.editSave();
                }
            },
            tabFormClose(isSaved = false) {
                if (!isSaved && this.tabForm.action === 'create') {
                    this.deleteTabItem(this.selectedTab);
                }

                this.tabForm.show = false;
                this.tabForm.name = '';
                this.tabForm.action = '';
                this.$refs.editTabForm.reset();
                //this.$emit('oneditTabClose');
            },
            editSave() {
                this.$refs.editTabForm.validate();
                if(!this.tabForm.valid) {
                    app.errorMessage('Form is not valid');
                    return false;
                }
                this.selectedTab.title = this.tabForm.name;
                this.tabFormClose(true);
                //this.$emit('onEditTabSave', this.tabForm);
            }
        },
        computed: {
            tabFormTitle () {
                return this.$t('words.' + this.tabForm.action);
            }
        },
        components: {
            dialogComponent,
            iconHorizontalDots,
            iconPen,
            iconDelete,
            iconLeftPlus,
            iconRightPlus,
            iconLeft,
            iconRight,
            FormField
        }
    }
</script>
<style scoped lang="scss">
    .icon-action {
        fill: #757575;
        height: 24px;
    }
    .add-new-tab {
        padding-top: 6px;
    }
</style>
