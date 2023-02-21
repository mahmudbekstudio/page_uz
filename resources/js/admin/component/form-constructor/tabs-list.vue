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
                    <v-list-item @click="openEditDialog(tabItem)">
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
                :title="$t('words.edit')"
                :value="editForm.show"
                @input="!$event && editClose()"
                size="small"
        >
            <v-form ref="editTabForm" v-model="editForm.valid">
                <FormField :value="editForm.name" :params="tabNameParams" :events="{'change' : editNameChanged, 'keyup': editKeyup}"></FormField>
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
                editForm: {
                    show: false,
                    name: '',
                    valid: false,
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
                click: () => this.editClose()
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
                this.form.addTab({});
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
                       const itemIndex = this.form.children.indexOf(tabItem);
                        this.form.children.splice(itemIndex, 1);
                    }
                })
            },
            openEditDialog(tabItem) {
                this.selectedTab = tabItem;
                this.editForm.show = true;
                this.editForm.name = tabItem.title;
            },
            editNameChanged: function (val) {
                this.editForm.name = val;
            },
            editKeyup: function(e) {
                let key = e.which || e.keyCode || 0;
                if(e instanceof KeyboardEvent && key === 13) {
                    this.editSave();
                }
            },
            editClose() {
                this.editForm.show = false;
                this.editForm.name = '';
                this.$refs.editTabForm.reset();
                //this.$emit('oneditTabClose');
            },
            editSave() {
                this.$refs.editTabForm.validate();
                if(!this.editForm.valid) {
                    app.errorMessage('Form is not valid');
                    return false;
                }
                this.selectedTab.title = this.editForm.name;
                this.editClose();
                //this.$emit('onEditTabSave', this.editForm);
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