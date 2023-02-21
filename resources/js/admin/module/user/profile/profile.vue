<template>
    <page-box :actions="actionsList" :footerActions="footerActionsList">
        <formComponent :value="profileForm" :disabled="isLoading" @input="formChanged($event)" @validate="formValidateFunc($event)" @valid="formValid($event)"></formComponent>
    </page-box>
</template>
<script>
    import {mapActions, mapGetters} from 'vuex';
    import Service from './service';
    import pageBox from '../../../view/partial/page-box';
    import { getPageBoxAction } from '../../../helper';
    import formComponent from '../../../component/form/form-component';
    import { Form as FormClass } from '../../../component/form/classes/form';
    import validation from '../../../config/validation';
    import mainConfig from '../../../config/main';
    import * as _ from 'lodash';

    export default {
        service: new Service(),
        data: () => ({
            formValidate: null,
            profileForm: null,
            actionsList: [],
            footerActionsList: [],
        }),
        computed: {
            ...mapGetters({
                form: 'profile/form',
                submitDisabled: 'profile/submitDisabled',
                errors: 'profile/errors',
                isLoading: 'profile/isLoading'
            })
        },
        watch: {
            form: {
                handler: function() {
                    /*if(this.errors.email || this.errors.password) {
                        this.changeErrors({email: '', password: ''});
                    }

                    this.changeSubmitDisabled(!this.$refs.email || !this.$refs.password || !this.$refs.email.validate() || !this.$refs.password.validate())*/
                },
                deep: true
            },
            isLoading(val) {
                this.disabledChanged(val);
            }
        },
        created() {
            this.actionsList.push(getPageBoxAction('Save', '', {color: 'primary', disabled: false}, {
                click: () => {
                    this.submit();
                }
            }));
            this.footerActionsList.push(getPageBoxAction('Save', '', {color: 'primary'}, {
                click: () => {
                    this.submit();
                }
            }));
            this.$options.service.getProfile(() => {
                this.profileForm = new FormClass();
                const emailField = this.profileForm.addField({type: 'text'});
                emailField.setParams('label', 'E-mail');
                emailField.disabled = true;
                emailField.name = 'email';
                emailField.value = this.form.email;

                const firstName = this.profileForm.addField({type: 'text'});
                firstName.setParams('label', 'First name');
                firstName.setParams('rules', [validation.required('First name')]);
                firstName.name = 'first_name';
                firstName.value = this.form.first_name;

                const lastName = this.profileForm.addField({type: 'text'});
                lastName.setParams('label', 'Last name');
                lastName.setParams('rules', [validation.required('Last name')]);
                lastName.name = 'last_name';
                lastName.value = this.form.last_name;

                const oldPassword = this.profileForm.addField({type: 'password'});
                oldPassword.setParams('label', 'Old Password');
                oldPassword.setParams('rules', [
                    validation.requiredIfNotEmpty('Old Password', () => this.form.password),
                    validation.minIfNotEmpty('Old Password', mainConfig.app.min_password_length)
                ]);
                oldPassword.name = 'old_password';

                const password = this.profileForm.addField({type: 'password'});
                password.setParams('label', 'Password');
                password.setEvents('input', () => {
                    this.formValidate();
                });
                password.setParams('rules', [validation.minIfNotEmpty('Password', mainConfig.app.min_password_length)])
                password.name = 'password';

                const passwordConfirmation = this.profileForm.addField({type: 'password'});
                passwordConfirmation.setParams('label', 'Password Confirmation');
                passwordConfirmation.setParams('rules', [validation.confirmation('Password Confirmation', () => this.form.password)]);
                passwordConfirmation.name = 'password_confirmation';
            });
        },
        methods: {
            ...mapActions({
                changeErrors: 'profile/changeErrors',
                changeForm: 'profile/changeForm',
                changeSubmitDisabled: 'profile/changeSubmitDisabled'
            }),
            formChanged(form) {
                this.changeForm(form.getFieldValues());
            },
            formValidateFunc(formValidate) {
                this.formValidate = formValidate;
            },
            submit() {
                if(this.submitDisabled || this.isLoading) {
                    return false;
                }

                this.$options.service.submit();
            },
            disabledChanged(val) {
                this.changeSubmitDisabled(val);
                this.actionsList[0].bind.disabled = val;
                this.footerActionsList[0].bind.disabled = val;
                this.actionsList = _.cloneDeep(this.actionsList);
                this.footerActionsList = _.cloneDeep(this.footerActionsList);
            },
            formValid(valid) {
                this.disabledChanged(!valid);
            }
        },
        components: {
            pageBox,
            formComponent
        }
    }
</script>
