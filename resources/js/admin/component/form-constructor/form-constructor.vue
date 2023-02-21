<template>
    <div class="form-constructor">
        <draggable
                :v-bind="dragOptions"
        ></draggable>
        <hr>
        <hr>
        <tabs-list
                v-model="tab"
                :form="formObject"
        ></tabs-list>
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
                        <row-action :row="row" :tab="tab"></row-action>
                        <v-col
                                v-for="(col, colIndex) in row.children"
                                :key="'col' + colIndex"
                                class="constructor-col"
                                cols="12"
                                :md="col.size"
                        >
                            <cell-action :col="col" :row="row"></cell-action>
                            <div
                                    v-for="(field, fieldIndex) in col.children"
                                    :key="'col' + fieldIndex"
                                    class="constructor-field"
                            >
                                {{[index, rowIndex, colIndex, fieldIndex].join(fieldSplitter)}}
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </v-tab-item>
        </v-tabs-items>
    </div>
</template>
<script>
    import { Form as FormClass } from '../form/classes/form';
    import {FORM} from '../../constants';
    import draggable from 'vuedraggable';
    import tabsList from './tabs-list';
    import rowAction from './row-action';
    import cellAction from './cell-action';
    export default {
        data() {
            return {
                fieldSplitter: FORM.fieldKeySplitter,
                tab: 0,
                formObject: null,
                dragOptions: {
                    animation: 200,
                    group: 'description',
                    disabled: false,
                    ghostClass: 'ghost'
                },
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
                handler: (newVal, oldVal) => {
                    console.log('formObject', newVal);
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
        methods: {
            setForm(val) {
                this.formObject = val instanceof FormClass ? val : new FormClass(val);
                this.formObject.addTab({title: 'Tab 111'});
            }
        },
        components: {
            draggable,
            tabsList,
            rowAction,
            cellAction,
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