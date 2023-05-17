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
                <v-toolbar-title>{{ $t('page.login.login_form') }}</v-toolbar-title>
                <div class="flex-grow-1"></div>
            </v-toolbar>
            <v-card-text>
                <v-form>
                    <v-text-field
                            ref="email"
                            v-model="form.email"
                            :rules="rules.email"
                            :error-messages="errors.email"
                            :label="$t('words.email')"
                            required
                            :disabled="isLoading"
                            @keyup.enter="submit"
                            autofocus
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
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <span>
                        <router-link :to="{ name: 'auth.forgot-password'}">{{$t('page.forgot-password.forgot_password')}}</router-link>
                    </span>
                <div class="flex-grow-1"></div>
                <v-btn color="primary" @click.prevent="submit" :disabled="submitDisabled || isLoading">{{ $t('words.login') }}</v-btn>
            </v-card-actions>
        </v-card>
    </v-col>
</template>
<script>
    import {mapActions, mapGetters} from 'vuex';
    import Service from './service';
    import * as constants from '../../../constants';
    import validation from '../../../config/validation';
    import i18n from '../../../plugin/i18n';

    export default {
        service: new Service(),
        data: () => ({
            showpassword: false,
            minPasswordLength: constants.VALIDATION_MIN_PASSWORD_LENGTH,
            rules: {
                email: [
                    validation.required(i18n.t('words.email')),
                    validation.isEmail(i18n.t('words.email'))
                ],
                password: [
                    validation.required('words.password'),
                    validation.min('words.password', constants.VALIDATION_MIN_PASSWORD_LENGTH)
                ]
            }
        }),
        computed: {
            ...mapGetters({
                form: 'login/form',
                submitDisabled: 'login/submitDisabled',
                errors: 'login/errors',
                isLoading: 'login/isLoading'
            })
        },
        watch: {
            form: {
                handler: function() {
                    if(this.errors.email || this.errors.password) {
                        this.changeErrors({email: '', password: ''});
                    }

                    this.changeSubmitDisabled(!this.$refs.email || !this.$refs.password || !this.$refs.email.validate() || !this.$refs.password.validate())
                },
                deep: true
            }
        },
        methods: {
            ...mapActions({
                changeErrors: 'login/changeErrors',
                changeSubmitDisabled: 'login/changeSubmitDisabled'
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
