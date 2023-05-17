<template>
    <div>
        <v-select
            v-model="selectedList"
            :items="items"
            :label="$t('words.validation')"
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
                            label: 'words.field_name',
                            hint: 'words.enter_field_name',
                            rules: [validation.required('words.field_name')]
                        }
                    }
                },
                {
                    name: 'max',
                    field: {
                        type: 'number',
                        name: 'maxLength',
                        params: {
                            label: 'words.max_length',
                            hint: 'words.enter_max_length',
                            rules: [validation.required('words.max_length')]
                        }
                    }
                },
                {
                    name: 'min',
                    field: {
                        type: 'number',
                        name: 'minLength',
                        params: {
                            label: 'words.min_length',
                            hint: 'words.enter_min_length',
                            rules: [validation.required('words.min_length')]
                        }
                    }
                },
                {
                    name: 'minIfNotEmpty',
                    field: {
                        type: 'number',
                        name: 'minLength',
                        params: {
                            label: 'words.min_length',
                            hint: 'words.enter_min_length_if_not_empty',
                            rules: [validation.required('words.min_length')]
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
                            label: 'words.list',
                            hint: 'words.enter_item_every_line',
                            rules: [validation.required('words.list')]
                        }
                    }
                },
                {
                    name: 'notIn',
                    field: {
                        type: 'textarea',
                        name: 'list',
                        params: {
                            label: 'words.list',
                            hint: 'words.enter_item_every_line',
                            rules: [validation.required('words.list')]
                        }
                    }
                },
                {
                    name: 'confirmation',
                    field: {
                        type: 'text',
                        name: 'fieldName',
                        params: {
                            label: 'words.field_name',
                            hint: 'words.enter_field_name',
                            rules: [validation.required('words.field_name')]
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
