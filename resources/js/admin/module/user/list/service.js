import http from '../../../service/http';
import app from '../../../service/app';
import store from './store';
import api from './api';
import logger from '../../../service/logger';
import { Form as FormClass } from '../../../component/form/classes/form';
import validation from "../../../config/validation";
import mainConfig from "../../../config/main";
import i18n from "../../../plugin/i18n";

export default class Service {
    getUserForm(userData) {
        const userForm = new FormClass();

        const emailField = userForm.addField({type: 'text'});
        emailField.setParams('label', 'E-mail');
        emailField.setParams('rules', [validation.required('E-mail'), validation.isEmail('E-mail')]);
        emailField.disabled = !!userData?.id;
        emailField.name = 'email';
        emailField.value = userData?.email;

        const statusField = userForm.addField({type: 'select'});
        statusField.setParams('label', 'words.status');
        statusField.setParams('rules', [validation.required('words.status')]);
        const statusOptions = {};
        for (let statusId in mainConfig.app.status.user) {
            statusOptions[statusId] = i18n.t('words.' + mainConfig.app.status.user[statusId]);
        }
        statusField.setParams('options', statusOptions);
        statusField.name = 'status';
        statusField.value = typeof userData?.status === 'undefined' ? null : userData.status + '';

        const roleField = userForm.addField({type: 'select'});
        roleField.setParams('label', 'words.role');
        roleField.setParams('rules', [validation.required('words.role')]);
        const roleOptions = {};
        for(let role of mainConfig.app.userRoles) {
            roleOptions[role] = i18n.t('words.' + role);
        }
        roleField.setParams('options', roleOptions);
        roleField.name = 'role';
        roleField.value = typeof userData?.role === 'undefined' ? null : userData.role + '';

        const firstName = userForm.addField({type: 'text'});
        firstName.setParams('label', 'words.first_name');
        firstName.setParams('rules', [validation.required('words.first_name')]);
        firstName.name = 'first_name';
        firstName.value = userData?.first_name;

        const lastName = userForm.addField({type: 'text'});
        lastName.setParams('label', 'words.last_name');
        lastName.setParams('rules', [validation.required('words.last_name')]);
        lastName.name = 'last_name';
        lastName.value = userData?.last_name;

        const password = userForm.addField({type: 'password'});
        password.setParams('label', 'words.password');
        const passwordRules = [validation.minIfNotEmpty('words.password', mainConfig.app.min_password_length)];
        if (!userData?.id) {
            passwordRules.push(validation.required('words.password'));
        }
        password.setParams('rules', passwordRules);
        password.name = 'password';

        const passwordConfirmation = userForm.addField({type: 'password'});
        passwordConfirmation.setParams('label', 'words.confirm_password');
        const passwordConfirmationRules = [validation.confirmation('words.confirm_password', () => password.value)];
        if (!userData?.id) {
            passwordConfirmationRules.push(validation.required('words.confirm_password'));
        }
        passwordConfirmation.setParams('rules', passwordConfirmationRules);
        passwordConfirmation.name = 'password_confirmation';

        return {form: userForm, data: userData};
    }

    userById(id, successCallback) {
        this.loading(true);
        http(api.getById)
            .callback(id)
            .send()
            .then(response => {
                if (response.data.data.meta) {
                    response.data.data.first_name = response.data.data.meta.first_name;
                    response.data.data.last_name = response.data.data.meta.last_name;
                }

                response.data.data.id && successCallback(response.data.data);
            })
            .catch(error => {
                console.log(error);
            })
            .then(() => this.loading(false));
    }

    create(data, successCallback, errorCallback) {
        this.loading(true);
        http(api.create)
            .callback(data)
            .send()
            .then(response => {
                if (response.data.result) {
                    successCallback(response.data);
                } else {
                    errorCallback(response.data);
                }
            })
            .catch(error => {
                console.log(error);
                errorCallback(error);
            })
            .then(() => this.loading(false));
    }

    update(id, data, successCallback, errorCallback) {
        this.loading(true);
        http(api.update)
            .callback(id, data)
            .send()
            .then(response => {
                if (response.data.result) {
                    successCallback(response.data);
                } else {
                    errorCallback(response.data);
                }
            })
            .catch(error => {
                console.log(error);
                errorCallback(error);
            })
            .then(() => this.loading(false));
    }

    delete(userId, successCallback, errorCallback) {
        this.loading(true);
        http(api.delete)
            .callback(userId)
            .send()
            .then(response => {
                if (response.data.result) {
                    successCallback(response.data);
                } else {
                    errorCallback(response.data);
                }
            })
            .catch(error => {
                console.log(error);
                errorCallback(error);
            })
            .then(() => this.loading(false));
    }

    loading(isStart) {
        app.loading(isStart);
        store.dispatch('users-list/changeIsLoading', isStart)
    }
}
