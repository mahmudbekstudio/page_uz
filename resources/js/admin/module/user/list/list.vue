<template>
    <page-box :actions="actionsList">
        <data-table
            :headers="headers"
            route="admin.user.list"
            route-need-token
            row-clickable
            :filter="filter"
            @click:row="clickRow"
            @reloadCallback="listReload($event)"
        >
            <template v-slot:filter="props">
                <v-container class="grey lighten-5">
                    <v-row no-gutters>
                        <v-col
                            cols="12"
                            sm="4"
                        >
                            <v-text-field
                                v-model="search"
                                label="Search by fields First name, Last name and Email"
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="4"
                        >
                            <v-select
                                :items="statusOptions"
                                v-model="filter.status.value"
                                label="Status"
                            ></v-select>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="4"
                        >
                            <v-select
                                :items="roleOptions"
                                v-model="filter.role.value"
                                label="Role"
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
            <template v-slot:item.status="props">
                <v-chip
                    :color="getStatusColor(props.value)"
                    dark
                >
                    {{ $t('words.' + mainConfig.app.status.user[props.value]) }}
                </v-chip>
            </template>
            <template v-slot:item.role="props">{{ $t('words.' + props.value) }}</template>
            <template v-slot:item.created_at="props">{{ $moment(props.value).format("YYYY-MM-DD HH:mm:ss") }}</template>

        </data-table>

        <dialog-component
            :title="dialogTitle"
            v-model="showDialog"
            :actions="dialogActions"
            fullscreen
        >
            <template v-slot:top v-if="userForm?.data?.id">
                <v-btn
                    color="red"
                    @click="clickDelete"
                >
                    Delete user
                </v-btn>
            </template>
            <form-component
                :value="userForm?.form"
                :disabled="isLoading"
                @resetValidation="getResetFormValidationCallback($event)"
                @validate="getValidationFormCallback($event)"
            ></form-component>
        </dialog-component>
    </page-box>
</template>
<script>
import Service from './service';
import pageBox from '../../../view/partial/page-box';
import { getPageBoxAction } from '../../../helper';
import dataTable from '../../../component/table/data-table';
import mainConfig from '../../../config/main';
import dialogComponent from "../../../component/dialog-component";
import formComponent from "../../../component/form/form-component";
import {mapGetters} from 'vuex';
import app from "../../../service/app";
import * as constants from "../../../constants";

export default {
    service: new Service(),

    data () {
        return {
            listReloadCallback: null,
            resetFormValidation: null,
            validationForm: null,
            showDialog: false,
            dialogActions: [
                {
                    color: 'default',
                    text: 'Cancel',
                    click: () => this.showDialog = false
                },
                {
                    color: 'primary',
                    text: 'Save',
                    click: () => {
                        if (this.validationForm()) {
                            if (this.userForm?.data?.id) {
                                this.$options.service.update(
                                    this.userForm.data.id,
                                    this.userForm.form.getFieldValues(),
                                    () => {
                                        this.showDialog = false;
                                        app.openMessage('Saved');
                                        this.listReloadCallback();
                                    },
                                    () => {
                                        app.openMessage('Error', constants.SNACKBAR_COLORS.error)
                                    }
                                );
                            } else {
                                this.$options.service.create(
                                    this.userForm.form.getFieldValues(),
                                    () => {
                                        this.showDialog = false;
                                        app.openMessage('Created');
                                        this.listReloadCallback();
                                    },
                                    () => {
                                        app.openMessage('Error', constants.SNACKBAR_COLORS.error)
                                    }
                                );
                            }
                        }
                    }
                },
            ],

            mainConfig: mainConfig,
            search: '',
            searchTimeout: null,
            filter: {
                status: {condition: '=', value: ''},
                role: {condition: '=', value: ''},
                search: {
                    first_name: {condition: 'LIKE', value: ''},
                    last_name: {condition: 'LIKE', value: '', logic: 'or'},
                    email: {condition: 'LIKE', value: '', logic: 'or'}
                },
            },
            actionsList: [],
            headers: [],
            userForm: null,
        }
    },

    created () {
        if(this.$route.query.userid) {
            this.$options.service.userById(this.$route.query.userid, response => {
                this.openDialog(response);
            });
        }
        const saveButton = getPageBoxAction(this.$t('words.create'), '', {color: 'primary'}, {
            click: () => {
                this.openDialog();
            }
        });
        this.actionsList.push(saveButton);

        this.headers = [
            { text: 'Id', value: 'id' },
            { text: 'First name', value: 'first_name' },
            { text: 'Last name', value: 'last_name' },
            { text: 'Email', value: 'email' },
            { text: 'Status', value: 'status' },
            { text: 'Role', value: 'role' },
            { text: 'Created', value: 'created_at' },
        ];
    },

    computed: {
        ...mapGetters({
            isLoading: 'users-list/isLoading',
        }),
        dialogTitle () {
            return this.userForm?.data?.id ?
                this.userForm.data.first_name + ' ' + this.userForm.data.last_name :
                'Create user';
        },
        roleOptions () {
            const result = [
                {text: 'Show all roles', value: ''}
            ];

            for(let role of mainConfig.app.userRoles) {
                result.push({text: this.$t('words.' + role), value: role});
            }

            return result;
        },
        statusOptions () {
            const result = [
                {text: 'Show all status', value: ''}
            ];

            for(let statusId in mainConfig.app.status.user) {
                result.push({text: this.$t('words.' + mainConfig.app.status.user[statusId]), value: statusId});
            }

            return result;
        }
    },

    methods: {
        listReload (listReloadCallback) {
            this.listReloadCallback = listReloadCallback;
        },
        getResetFormValidationCallback (resetCallback) {
            this.resetFormValidation = resetCallback;
        },
        getValidationFormCallback (validationCallback) {
            this.validationForm = validationCallback;
        },
        clickDelete () {
            const userName = this.userForm.data.first_name + ' ' + this.userForm.data.last_name;
            app.openConfirm('Do you realy want to delete user ' + userName + '?', () => {
                this.$options.service.delete(this.userForm.data.id, () => {
                    this.showDialog = false;
                    app.openMessage('Deleted');
                    this.listReloadCallback();
                }, () => app.openMessage('Error'));
            });
        },
        getStatusColor (statusId) {
            const colors = [
                'silver',
                'green',
                'red'
            ];
            return colors[statusId];
        },
        clickRow (row) {
            this.openDialog(row);
        },
        openDialog(user) {
            this.showDialog = true;
            this.$nextTick(() => {
                this.userForm = this.$options.service.getUserForm(user);
                this.$nextTick(() => {
                    this.resetFormValidation();
                });

            });
        }
    },

    watch: {
        search (val) {
            clearTimeout(this.searchTimeout);
            if (val.length >= 3) {
                this.searchTimeout = setTimeout(() => {
                    this.filter.search.first_name.value = '%' + val + '%';
                    this.filter.search.last_name.value = '%' + val + '%';
                    this.filter.search.email.value = '%' + val + '%';
                }, 1000);
            } else {
                this.searchTimeout = setTimeout(() => {
                    this.filter.search.first_name.value = '';
                    this.filter.search.last_name.value = '';
                    this.filter.search.email.value = '';
                }, 1000);
            }
        }
    },

    components: {
        pageBox,
        dataTable,
        dialogComponent,
        formComponent,
    }
}
</script>
