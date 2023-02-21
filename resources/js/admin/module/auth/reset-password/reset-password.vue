<template>
    <v-col
            cols="12"
            sm="8"
            md="4"
    >
        <v-card class="elevation-12">
            <v-toolbar
                    color="primary"
                    dark
                    flat
            >
                <v-toolbar-title>{{ $t('page.reset-password.reset_password') }}</v-toolbar-title>
                <div class="flex-grow-1"></div>
            </v-toolbar>
            <v-card-text>
                <v-form>
                    <v-text-field
                            ref="email"
                            v-model="form.email"
                            :label="$t('words.email')"
                            :disabled="true"
                            prepend-icon="mdi-email-outline"
                            type="text"
                    ></v-text-field>

                    <v-text-field
                            ref="password"
                            v-model="form.password"
                            :rules="rules.password"
                            :error-messages="errors.password"
                            :label="$t('words.password')"
                            prepend-icon="mdi-textbox-password"
                            :append-icon="!showpassword ? 'mdi-eye-off' : 'mdi-eye'"
                            :min="minPasswordLength"
                            :type="showpassword ? 'text' : 'password'"
                            @click:append="showpassword = !showpassword"
                            required
                            :disabled="isLoading"
                            @keyup.enter="submit"
                            autofocus
                    ></v-text-field>
                    <v-text-field
                            ref="password"
                            v-model="form.password_confirmation"
                            :rules="rules.password_confirmation"
                            :error-messages="errors.password_confirmation"
                            :label="$t('words.confirm_password')"
                            prepend-icon="mdi-textbox-password"
                            :append-icon="!showpassword2 ? 'mdi-eye-off' : 'mdi-eye'"
                            :min="minPasswordLength"
                            :type="showpassword2 ? 'text' : 'password'"
                            @click:append="showpassword2 = !showpassword2"
                            required
                            :disabled="isLoading"
                            @keyup.enter="submit"
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <span>
                    <router-link :to="{ name: 'auth.login'}">{{$t('page.login.login_form')}}</router-link>
                </span>
                <div class="flex-grow-1"></div>
                <v-btn color="primary" @click.prevent="submit" :disabled="submitDisabled || isLoading">{{ $t('words.change') }}</v-btn>
            </v-card-actions>
        </v-card>
    </v-col>
</template>
<script>
    import {mapActions, mapGetters} from 'vuex';
    import Service from './service';
    import * as constants from '../../../constants';
    import validation from '../../../config/validation';
    import app from '../../../service/app';

    export default {
        service: new Service(),
        data: () => ({
            showpassword: false,
            showpassword2: false,
            minPasswordLength: constants.VALIDATION_MIN_PASSWORD_LENGTH,
            rules: {
                password: [
                    validation.required('Password'),
                    validation.min('Password', constants.VALIDATION_MIN_PASSWORD_LENGTH)
                ],
                password_confirmation: [
                    validation.required('Password'),
                    validation.min('Password', constants.VALIDATION_MIN_PASSWORD_LENGTH)
                ]
            }
        }),
        created() {
            console.log('constants', constants);
            if(!this.$route.params.token || !this.$route.query.email) {
                app.redirectToLogin();
                return ;
            }

            this.rules.password_confirmation.push(validation.confirmation('Password confirmation', () => {
                return this.form.password;
            }));

            this.changeForm({
                token: this.$route.params.token,
                email: this.$route.query.email
            });
        },
        computed: {
            ...mapGetters({
                form: 'reset-password/form',
                submitDisabled: 'reset-password/submitDisabled',
                errors: 'reset-password/errors',
                isLoading: 'reset-password/isLoading'
            })
        },
        watch: {
            form: {
                handler: function(val) {
                    this.changeForm({
                        password: val.password,
                        password_confirmation: val.password_confirmation
                    });
                    if(this.errors.password) {
                        this.changeErrors({password: ''});
                    }

                    if(this.errors.password_confirmation) {
                        this.changeErrors({password_confirmation: ''});
                    }

                    this.changeSubmitDisabled(!this.$refs.password || !this.$refs.password.validate())
                },
                deep: true
            }
        },
        methods: {
            ...mapActions({
                changeErrors: 'reset-password/changeErrors',
                changeSubmitDisabled: 'reset-password/changeSubmitDisabled',
                changeForm: 'reset-password/changeForm'
            }),
            submit() {
                if(this.submitDisabled || this.isLoading) {
                    return false;
                }

                this.$options.service.submit();
            }
        }
    }
</script>
