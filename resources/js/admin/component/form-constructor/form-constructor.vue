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
                            />
                        </template>
                    </v-row>
                </v-container>
            </v-tab-item>
        </v-tabs-items>
        {{formObject}}
    </div>
</template>
<script>
    import { Form as FormClass, Field } from '../form/classes/form';
    import tabsList from './tabs-list';
    import rowAction from './row-action';
    import componentsList from "./components-list";
    import cell from './cell';

    export default {
        data() {
            return {
                componentsList: {
                    basic: [new Field({type: 'text', label: 'Test 1'}), new Field({type: 'textarea', label: 'Test 2'})],
                    advanced: [],
                    required: []
                },
                tab: 0,
                formObject: null,
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
                console.log(this.formObject);
                //this.formObject.addTab({title: 'Tab 111'});
            },
        },
        components: {
            tabsList,
            rowAction,
            componentsList,
            cell,
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
