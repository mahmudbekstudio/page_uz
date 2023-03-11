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
                    //this.submit();
                }
            });
            this.actionsList.push(resetButton);
            this.actionsList.push(saveButton);
            this.footerActionsList.push(resetButton);
            this.footerActionsList.push(saveButton);

            this.$options.service.getSettings(() => {
                this.settingForm = new FormClass();
                const tabMain = this.settingForm.getTab();
                tabMain.title = this.$t('words.main');
                const tabSeo = this.settingForm.addTab({title: this.$t('words.seo')});
                const tabImage = this.settingForm.addTab({title: this.$t('words.image')});
                const tabSocial = this.settingForm.addTab({title: this.$t('words.social')});

                const nameField = tabMain.addField({type: 'text'});
                nameField.setParams('label', this.$t('words.name'));
                nameField.name = 'name';
                nameField.value = this.form.name;

                const logoField = tabMain.addField({type: 'file'});
                logoField.setParams('label', this.$t('words.logo'));
                logoField.setParams('fileType', constants.FILE_IMAGE_TYPE);
                logoField.name = 'logo';
                logoField.value = this.form.logo;/*[
                    {
                        "extension": "docx",
                        "folderId": 0,
                        "id": 2,
                        "name": "перечин платформи янги",
                        "size": 13510
                    }
                ];*/

                const languageField = tabMain.addField({type: 'select', params: {options: this.languages}});
                languageField.setParams('label', this.$t('words.language'));
                languageField.name = 'language';
                languageField.value = this.form.language;

                const languagesListField = tabMain.addField({type: 'select', params: {options: this.languages, multiple: true}});
                languagesListField.setParams('label', this.$t('words.languages'));
                languagesListField.name = 'languages_list';
                languagesListField.value = this.form.languages_list;

                const statusField = tabMain.addField({type: 'switch'});
                statusField.setParams('label', this.$t('words.status'));
                statusField.name = 'status';
                statusField.value = this.form.status;

                const nameField1 = tabSeo.addField({type: 'text'});
                nameField1.setParams('label', this.$t('words.name'));
                nameField1.name = 'name1';
                nameField1.value = this.form.name;

                const nameField2 = tabImage.addField({type: 'text'});
                nameField2.setParams('label', this.$t('words.name'));
                nameField2.name = 'name2';
                nameField2.value = this.form.name;

                const nameField3 = tabSocial.addField({type: 'text'});
                nameField3.setParams('label', this.$t('words.name'));
                nameField3.name = 'name3';
                nameField3.value = this.form.name;
                /*const nameField = this.settingForm.addField({type: 'text'});
                nameField.setParams('label', this.$t('words.name'));
                nameField.name = 'name';
                nameField.value = this.form.name;

                console.log('==', this.settingForm.json);*/



                // todo: remove after testing
                tabMain.addField({type: 'text', value: 'text field value', name: 'text_field', params: {label: 'text field'}});
                tabMain.addField({type: 'password', value: 'password field value', name: 'password_field', params: {label: 'password field'}});
                tabMain.addField({type: 'textarea', value: 'textarea field value', name: 'textarea_field', params: {label: 'textarea field'}});
                tabMain.addField({type: 'select', value: '2', name: 'select_field', params: {label: 'select field', options: {1: '11', 2: '22'}}});
                tabMain.addField({type: 'file', name: 'file_field', params: {label: 'file field', fileType: constants.FILE_IMAGE_TYPE}});
                tabMain.addField({type: 'switch', value: true, name: 'switch_field', params: {label: 'switch field'}});
                tabMain.addField({type: 'divider'});
                tabMain.addField({type: 'datetime', name: 'datetime_field', params: {label: ['date picker', 'time picker'], multiple: true}});
                tabMain.addField({type: 'date', name: 'date_field', params: {label: 'date field', multiple: true}});
                tabMain.addField({type: 'time', name: 'time_field', params: {label: 'time field'}});
                tabMain.addField({type: 'radio', name: 'radio_field', value: '22', params: {label: 'radio field', options: {'11': 'First', '22': 'Second', '33': 'Third'}}});
                tabMain.addField({type: 'checkbox', name: 'checkbox_field', value: ['22', '33'], params: {label: 'checkbox field', options: {'11': 'First', '22': 'Second', '33': 'Third'}}});
                tabMain.addField({type: 'editor', name: 'editor_field', value: 'test', params: {label: 'editor field'}});
                console.log(tabMain);
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
