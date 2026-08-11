<template>
    <page-box
        class="module-block-form"
        :header-title="headerTitle"
        :actions="actions"
    >
        <form-component
            v-if="formValue"
            :value="formValue"
            @validate="formValidate = $event"
            @input="formInput($event)"
            @fieldChange="fieldChanged($event)"
            :disabled="loading"
        ></form-component>
    </page-box>
</template>
<script>
import pageBox from '../../../view/partial/page-box';
import {getPageBoxAction} from "../../../helper";
import Service from "./service";
import formComponent from '../../../component/form/form-component.vue';
import { Form as FormClass } from '../../../component/form/classes/form';
import {mapGetters} from "vuex";
import app from '../../../service/app';

export default {
    service: new Service(),
    data() {
        return {
            actions: [],
            formValue: null,
            formValidate: null,
        }
    },
    created() {
        this.init();
    },
    computed: {
        ...mapGetters({
            loading: 'view/loading',
            activeNavigation: 'view/activeNavigation',
            viewTitle: "view/title"
        }),
        headerTitle () {
            return this.$t(this.viewTitle) + ': ' + this.$t(this.activeNavigation.text);
        },
    },
    watch: {
        '$options.service.typeId'() {
            this.init();
        },
        '$options.service.id'() {
            this.init();
        },
        '$route.fullPath'() {
            this.init();
        },
        loading(val) {
            this.actions.forEach(item => item.bind.disabled = val);
        },
    },
    methods: {
        init() {
            this.formValue = null;
            this.actions = [];
            this.actions.push(getPageBoxAction('words.back', '', {color: 'default', disabled: false}, {
                click: this.back
            }));
            this.actions.push(getPageBoxAction('words.' + (this.$options.service.isEdit ? 'update' : 'create'), '', {color: 'primary', disabled: false}, {
                click: this.save
            }));
            this.loadType();
        },
        loadType() {
            this.$options.service.get(this.$options.service.typeId, response => {
                const formValue = new FormClass(response.data.type.structure);

                if (this.$options.service.isEdit) {
                    this.getBlock(formValue);
                } else {
                    this.formValue = formValue;
                }
            })
        },
        getBlock(formValue) {
            this.$options.service.getBlock(response => {
                formValue.getFields().forEach(item => {
                    item.field.fieldObject.value = response.data.block[item.field.fieldObject.name];
                });
                this.formValue = formValue;
            });
        },
        save() {
            if (this.formValidate()) {
                const values = this.formValue.getFieldValues();
                this.$options.service.submit(
                    values,
                    response => {
                        if (this.$options.service.isEdit) {
                            app.successMessage(this.$t('words.updated'));
                        } else {
                            app.successMessage(this.$t('words.created'));
                            this.back(response);
                        }
                    }
                );
            }
        },
        back() {
            this.$router.push({name: 'block.list', params: {typeId: this.$options.service.typeId}});
        },
        formInput(event) {
            //
        },
        fieldChanged(event) {
            //
        },
    },
    components: {
        pageBox,
        formComponent,
    }
}
</script>
