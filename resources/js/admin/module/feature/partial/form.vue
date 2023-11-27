<template>
    <div class="module-feature-form">
        <form-component
            :value="featureForm"
            :disabled="isLoading"
            @input="formChanged($event)"
        ></form-component>
        <v-container fluid>
            <v-row>
                <v-col
                    cols="12"
                    sm="3"
                >
                    <sort-list
                        v-model="domsList"
                    ></sort-list>
                </v-col>
                <v-col
                    cols="12"
                    sm="9"
                ></v-col>
            </v-row>
        </v-container>
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

export default {
    service: new Service(),
    data () {
        return {
            featureForm: null,
            typeField: null,
            domsList: [],
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
    },
    methods: {
        initForm(formValue = {}) {
            this.featureForm = new FormClass();

            const nameField = this.featureForm.addField({type: 'text'});
            nameField.setParams('label', 'words.name');
            nameField.setParams('rules', [validation.required('words.name')]);
            nameField.name = 'name';
            //nameField.value = formValue.name || '';

            this.typeField = this.featureForm.addField({type: 'select'});
            this.typeField.setParams('label', 'words.type');
            this.typeField.name = 'type_id';
            this.typeField.setParams('clearable', false);
            const typeOptions = mainConfig.app.feature_types.map(item => ({value: item.id, text: 'words.feature.' + item.name}));
            this.typeField.setParams('options', typeOptions);
            this.typeField.setParams('valueType', 'int');
            this.typeField.value = formValue.type_id || 0;
        },
        formChanged (e) {
            //console.log('formChanged', e);
        },
    },
    watch: {
        'typeField.value' (newValue) {
            if (newValue) {
                this.$options.service.getTypesList(newValue, response => {
                    console.log('response', response.data.typesList);
                })
            }
        }
    },
    components: {
        sortList,
        formComponent
    }
}
</script>
