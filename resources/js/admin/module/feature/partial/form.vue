<template>
    <div class="module-feature-form">
        <form-component
            :value="featureForm"
            :disabled="isLoading"
            @input="formChanged($event)"
        ></form-component>
        <v-container fluid v-if="typeField.value">
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
            featureTypeField: null,
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
            nameField.value = formValue.name || '';

            this.featureTypeField = this.featureForm.addField({type: 'select'});
            this.featureTypeField.setParams('label', 'words.featureType');
            this.featureTypeField.name = 'feature_type';
            this.featureTypeField.setParams('clearable', false);
            const typeOptions = mainConfig.app.feature_types.map(item => ({value: item, text: 'words.feature.' + item}));
            this.featureTypeField.setParams('options', typeOptions);
            this.featureTypeField.value = formValue.feature_type || '';

            this.typeField = this.featureForm.addField({type: 'select'});
            this.typeField.setParams('label', 'words.type');
            this.typeField.name = 'type_id';
            this.typeField.setParams('clearable', false);
            this.typeField.value = formValue.type_id || 0;
        },
        formChanged (e) {
            //console.log('formChanged', e);
        },
    },
    watch: {
        'featureTypeField.value' (newValue) {
            if (newValue) {
                this.$options.service.getTypesList(newValue, response => {
                    this.typeField.value = 0;
                    this.typeField.setParams('options', response.data.typesList.map(item => ({value: item.id, text: item.title})));
                })
            }
        },
        'typeField.value' (typeId) {
            if (typeId) {
                this.$options.service.getTypeDetail(typeId, response => {
                    console.log('response', response);
                });
            }
        }
    },
    components: {
        sortList,
        formComponent
    }
}
</script>
