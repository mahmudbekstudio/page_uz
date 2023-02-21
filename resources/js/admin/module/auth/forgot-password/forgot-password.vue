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
                <v-toolbar-title>{{ $t('page.forgot-password.forgot_password') }}</v-toolbar-title>
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
                </v-form>
            </v-card-text>
            <v-card-actions>
                <span>
                        <router-link :to="{ name: 'auth.login'}">{{$t('page.login.login_form')}}</router-link>
                    </span>
                <div class="flex-grow-1"></div>
                <v-btn color="primary" @click.prevent="submit" :disabled="submitDisabled || isLoading">{{ $t('words.send') }}</v-btn>
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
                ]
            }
        }),
        computed: {
            ...mapGetters({
                form: 'forgot-password/form',
                submitDisabled: 'forgot-password/submitDisabled',
                errors: 'forgot-password/errors',
                isLoading: 'forgot-password/isLoading'
            })
        },
        watch: {
            form: {
                handler: function() {
                    if(this.errors.email || this.errors.password) {
                        this.changeErrors({email: ''});
                    }

                    this.changeSubmitDisabled(!this.$refs.email || !this.$refs.email.validate())
                },
                deep: true
            }
        },
        methods: {
            ...mapActions({
                changeErrors: 'forgot-password/changeErrors',
                changeSubmitDisabled: 'forgot-password/changeSubmitDisabled'
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
