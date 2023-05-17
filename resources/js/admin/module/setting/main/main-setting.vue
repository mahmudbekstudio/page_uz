<template>
    <page-box
        class="module-main-setting"
        :actions="actionsList"
        :footerActions="footerActionsList"
    >
        <form-component
            :value="settingForm"
            :disabled="isLoading"
            @input="formChanged($event)"
            @validate="formValidateFunc($event)"
            @valid="formValid($event)"
        />
    </page-box>
</template>
<script>
    import pageBox from '../../../view/partial/page-box';
    import { getPageBoxAction } from '../../../helper';
    import { mapGetters, mapActions } from 'vuex';
    import Service from './service';
    import formComponent from '../../../component/form/form-component';
    import app from "../../../service/app";
    import * as constants from '../../../constants';
    import {Form as FormClass} from "../../../component/form/classes/form";

    export default {
        service: new Service(),
        data () {
            return {
                actionsList: [],
                footerActionsList: [],
                settingForm: null,
                formValidate: null,
                formReset: null,
                initialFormValues: null,
            };
        },
        created() {
            const saveButton = getPageBoxAction(this.$t('words.save'), '', {color: 'primary', disabled: true}, {
                click: () => {
                    this.submit();
                }
            });
            const resetButton = getPageBoxAction(this.$t('words.reset'), '', {color: 'default', disabled: true}, {
                click: () => {
                    this.initForm();
                }
            });
            this.actionsList.push(resetButton);
            this.actionsList.push(saveButton);
            this.footerActionsList.push(resetButton);
            this.footerActionsList.push(saveButton);

            this.$options.service.getSettings(response => {
                this.initialFormValues = response;
                this.initForm();
            }, () => {
                app.openMessage(this.$t('words.error'), constants.SNACKBAR_COLORS.error);
            });
        },
        components: {
            formComponent,
            pageBox,
        },
        computed: {
            ...mapGetters('main-setting', ['isLoading', 'isFormChanged', 'form', 'languages'])
        },
        methods: {
            ...mapActions('main-setting', ['changeForm']),
            initForm() {
                this.settingForm = new FormClass();
                this.settingForm.children = [];
                for (const item of this.initialFormValues) {
                    const tab = this.settingForm.addTab({title: this.$t(item.title)});
                    for (const field of item.children) {
                        if (field?.params?.label) {
                            field.params.label = this.$t(field.params.label);
                        }
                        tab.addField(field);
                    }
                }
            },
            submit() {
                this.$options.service.submit();
            },
            actionButtonsDisabling (value) {
                for (let item of this.actionsList) {
                    item.bind.disabled = value;
                }
            },
            formChanged(form) {
                this.changeForm(form.getFieldValues());
            },
            formValidateFunc(formValidate) {
                this.formValidate = formValidate;
            },
            formValid(valid) {
                this.actionButtonsDisabling(!valid);
            }
        },
        watch: {
            isLoading (value) {
                this.actionButtonsDisabling(value || !this.isFormChanged);
            },
            isFormChanged (value) {
                this.actionButtonsDisabling(!value);
            }
        }
    }
</script>
