<template>
    <div class="row-action">
        <div class="row-actions-list" @mouseleave="dotsClicked=false">
            <span @click="actionClicked('up')" class="action-ico"><icon-up></icon-up></span>
            <span @click="actionClicked('down')" class="action-ico"><icon-down></icon-down></span>
            <span v-show="!dotsClicked" @click="dotsClicked=true" class="action-ico"><icon-horizontal-dots></icon-horizontal-dots></span>
            <span v-show="dotsClicked" @click="actionClicked('up-add')" class="action-ico"><icon-up-plus /></span>
            <span v-show="dotsClicked" @click="actionClicked('down-add')" class="action-ico"><icon-down-plus /></span>
            <span v-show="dotsClicked" @click="actionClicked('edit')" class="action-ico"><icon-pen></icon-pen></span>
            <span v-show="dotsClicked" @click="actionClicked('delete')" class="action-ico"><icon-delete></icon-delete></span>
        </div>
        <dialog-component
                :actions="editActions"
                :title="$t('constructor.edit_row')"
                v-model="showEdit"
                size="medium"
        >
            <field-component type="select" :params="editElementParams" :value="editElementValue" @input="editElementValueChanged"></field-component>
            <v-container class="columns-preview">
                <v-row>
                    <v-col
                            v-for="(item, ind) of editSizes"
                            :key="ind"
                            cols="12"
                            :xs="item"
                            :sm="item"
                            :md="item"
                            :lg="item"
                            :xl="item"
                    >
                        <div style="background-color: #AAAAAA;min-height: 25px"></div>
                    </v-col>
                </v-row>
            </v-container>
        </dialog-component>
    </div>
</template>
<script>
    import {
        iconUp,
        iconDown,
        iconHorizontalDots,
        iconUpPlus,
        iconDownPlus,
        iconPen,
        iconDelete
    } from '../icons';
    import { CONSTRUCTOR_COLUMN_TYPES } from '../../constants';
    import dialogComponent from '../dialog-component';
    import fieldComponent from "../form/field-component.vue";
    export default {
        data () {
            return {
                editElementValue: '12',
                dotsClicked: false,
                editActions: [],
                showEdit: false,
                editElementParams: {clearable: false}
            }
        },
        props: {
            row: null,
            template: null
        },
        computed: {
            columnTypes() {
                const result = {};

                for(let i = 0; i < CONSTRUCTOR_COLUMN_TYPES.length; i++) {
                    let item = CONSTRUCTOR_COLUMN_TYPES[i];
                    result[item.label] = {}
                    for(let j = 0; j < item.children.length; j++) {
                        let subItem = item.children[j];
                        result[item.label][subItem.value] = subItem.label;
                    }
                }

                return result;
            },
            editSizes() {
                return this.editElementValue.split('_');
            }
        },
        mounted() {
            this.editElementParams['label'] = 'words.select_type';
            this.editElementParams['options'] = this.columnTypes;

            this.editActions.push({
                color: 'default',
                text: 'words.close',
                click: () => this.editClose()
            });
            this.editActions.push(
                {
                    color: 'primary',
                    text: 'words.save',
                    click: () => this.editSave()
                }
            );
        },
        methods: {
            editClose() {
                this.showEdit = false;
            },
            editSave() {
                const result = [];
                for(let i = 0; i < this.editSizes.length; i++) {
                    if(!this.row.children[i]) {
                        this.row.addCol({})
                    }

                    this.row.children[i].size = this.editSizes[i];
                    result.push(this.row.children[i]);
                }

                this.row.children = result;
                this.editClose();
            },
            actionClicked (actionName) {
                if(actionName === 'edit') {
                    const editSizes = [];
                    for(let i = 0; i < this.row.children.length; i++) {
                        editSizes.push(this.row.children[i].size);
                    }

                    this.editElementValue = editSizes.join('_');
                    this.showEdit = true;
                } else if(actionName === 'up-add' || actionName === 'down-add') {
                    this.template.addRow({});
                    const fromIndex = this.template.children.length - 1;
                    const toIndex = this.template.children.indexOf(this.row) + (actionName === 'up-add' ? 0 : +1);
                    const rowItem = this.template.children[this.template.children.length - 1];
                    this.template.children.splice(fromIndex, 1);
                    this.template.children.splice(toIndex, 0, rowItem);
                } else if(actionName === 'delete') {
                    if(this.template.children.length > 1) {
                        this.template.children.splice(this.template.children.indexOf(this.row), 1);
                    }
                } else if(actionName === 'up' || actionName === 'down') {
                    const fromIndex = this.template.children.indexOf(this.row);
                    const toIndex = fromIndex + (actionName === 'up' ? -1 : +1);

                    if(toIndex < 0 || fromIndex >= this.template.children.length) {
                        return false;
                    }
                    this.template.children.splice(fromIndex, 1);
                    this.template.children.splice(toIndex, 0, this.row);
                }
                this.$emit('actionClick', actionName)
            },
            editElementValueChanged(key, val) {
                this.editElementValue = val;
            }
        },
        components: {
            iconUp,
            iconDown,
            iconHorizontalDots,
            iconUpPlus,
            iconDownPlus,
            iconPen,
            iconDelete,
            dialogComponent,
            fieldComponent
        }
    }
</script>
