<template>
    <div>
        <v-select
            v-model="selectedList"
            :items="items"
            label="Validation"
            multiple
        ></v-select>
        <v-list-item
            v-for="item in selectedList"
            :key="'list-item' + item"
        >
            <v-list-item-content>
                <v-list-item-title>{{ item }}</v-list-item-title>
                <v-list-item-subtitle v-if="selectedFields[item]?.field">
                    <field-component
                        :fieldKey="selectedFields[item].name"
                        :type="selectedFields[item].field.type"
                        :params="selectedFields[item].field.params"
                        :value="selectedValidations[item]"
                        @input="validationChanged"
                    />
                </v-list-item-subtitle>
            </v-list-item-content>
        </v-list-item>
    </div>
</template>
<script>
import mixins from '../../../mixin';
import validation from "../../../config/validation";
import * as _ from 'lodash';

export default {
    mixins: [mixins.get('formField')],
    data () {
        return {
            selectedList: [],
            selectedFields: {},
            selectedValidations: {},
            list: [
                {
                    name: 'required'
                },
                {
                    name: 'requiredIfNotEmpty',
                    field: {
                        type: 'text',
                        name: 'fieldName',
                        params: {
                            label: 'Field name',
                            hint: 'Enter field name',
                            rules: [validation.required('Field name')]
                        }
                    }
                },
                {
                    name: 'max',
                    field: {
                        type: 'number',
                        name: 'maxLength',
                        params: {
                            label: 'Max length',
                            hint: 'Enter max length',
                            rules: [validation.required('Max length')]
                        }
                    }
                },
                {
                    name: 'min',
                    field: {
                        type: 'number',
                        name: 'minLength',
                        params: {
                            label: 'Min length',
                            hint: 'Enter min length',
                            rules: [validation.required('Min length')]
                        }
                    }
                },
                {
                    name: 'minIfNotEmpty',
                    field: {
                        type: 'number',
                        name: 'minLength',
                        params: {
                            label: 'Min length',
                            hint: 'Enter min length if not field empty',
                            rules: [validation.required('Min length')]
                        }
                    }
                },
                {
                    name: 'isEmail'
                },
                {
                    name: 'in',
                    field: {
                        type: 'textarea',
                        name: 'list',
                        params: {
                            label: 'List',
                            hint: 'Enter item every line',
                            rules: [validation.required('List')]
                        }
                    }
                },
                {
                    name: 'notIn',
                    field: {
                        type: 'textarea',
                        name: 'list',
                        params: {
                            label: 'List',
                            hint: 'Enter item every line',
                            rules: [validation.required('List')]
                        }
                    }
                },
                {
                    name: 'confirmation',
                    field: {
                        type: 'text',
                        name: 'fieldName',
                        params: {
                            label: 'Field name',
                            hint: 'Enter field name',
                            rules: [validation.required('Field name')]
                        }
                    }
                },
            ]
        }
    },
    computed: {
        items () {
            return this.list.map(item => item.name)
        }
    },
    created() {
        const val = this.value || {};
        this.selectedValidations = {...val}
        this.selectedList = Object.keys(val);
    },
    methods: {
        getListItem (key) {
            let result = {};
            this.list.forEach(item => {
                if (item.name === key) {
                    result = item;
                    return;
                }
            });
            return result;
        },
        validationChanged (key, value) {
            this.selectedValidations[key] = value;
            this.selectedValidations = {...this.selectedValidations};
        }
    },
    watch: {
        selectedList: {
            handler(list) {
                const selectedValidations = {...this.selectedValidations};
                this.selectedValidations = {};
                const selectedFields = {...this.selectedFields};
                this.selectedFields = {};

                for (const validationName of list) {
                    if (typeof selectedValidations[validationName] !== 'undefined') {
                        this.selectedValidations[validationName] = selectedValidations[validationName];
                        this.selectedFields[validationName] = selectedFields[validationName] || this.getListItem(validationName);
                    } else {
                        this.selectedValidations[validationName] = '';
                        this.selectedFields[validationName] = this.getListItem(validationName);
                    }
                }
            },
            deep: true
        },
        selectedValidations: {
            handler(val) {
                this.$emit('input', val);
            },
            deep: true
        },
        value: {
            handler(val) {
                /*if (JSON.stringify(this.selectedList.sort()) !== JSON.stringify(Object.keys(val).sort())) {
                    this.selectedList = Object.keys(val);
                }*/
            },
            deep: true
        }
    },
    components: {
        //
    }
}
</script>
