<template>
    <v-app-bar
            :clipped-left="$vuetify.breakpoint.lgAndUp"
            app
            color="blue darken-3"
            dark
    >
        <v-toolbar-title
                style="width: 300px"
                class="ml-0"
        >
            <v-app-bar-nav-icon @click.stop="toggleDrawer"></v-app-bar-nav-icon>
            <span class="hidden-sm-and-down">{{ $t(title) }} {{$t(panelTitle)}}</span>
        </v-toolbar-title>

        <div class="flex-grow-1"></div>
        <v-menu
            v-if="website.metas.languages_list.length > 1"
            offset-y
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    v-bind="attrs"
                    v-on="on"
                    text
                >
                    {{ selectedLangName }}
                    <v-icon right>
                        mdi-menu-down
                    </v-icon>
                </v-btn>
            </template>
            <v-list>
                <v-list-item-group
                    v-model="selectedLang"
                    color="primary"
                >
                    <v-list-item
                        v-for="item in languagesList"
                        :key="item.code"
                        :value="item.code"
                    >
                        <v-list-item-title>{{ item.name }}</v-list-item-title>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </v-menu>
        <!--v-btn icon>
            <v-icon>mdi-apps</v-icon>
        </v-btn>
        <v-btn icon>
            <v-badge
                color="green"
                content="6"
                overlap
            >
                <v-icon>mdi-bell</v-icon>
            </v-badge>
        </v-btn>
        <v-btn
                icon
                large
        >
            <v-avatar
                    size="32px"
                    item
            >
                <v-img
                        src="/img/logo.svg"
                        alt="Vuetify"
                >
                </v-img>
            </v-avatar>
        </v-btn-->
        <v-menu
                offset-y
                v-model="menuIsOpen"
                origin="center center"
        >
            <template v-slot:activator="{ on }">
                <div v-on="on" class="profile-menu">
                    <v-avatar

                            color="accent"
                            size="40"
                    >
                        <v-icon>mdi-account-outline</v-icon>
                    </v-avatar>
                    <div class="user-name hidden-xs-only">{{ fullname }}</div>
                    <v-icon class="user-arrow">{{menuArrow}}</v-icon>
                </div>
            </template>

            <v-list>
                <v-list-item class="hidden-sm-and-up text-center">
                    <v-list-item-title><i>{{ fullname }}</i></v-list-item-title>
                </v-list-item>
                <v-list-item class="text-center" @click="clickProfile">
                    <v-list-item-title>{{ $t('words.profile') }}</v-list-item-title>
                </v-list-item>
                <v-list-item class="text-center" link @click="clickLogout">
                    <v-list-item-title>{{ $t('words.logout') }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
    </v-app-bar>
</template>
<script>
    import {mapActions, mapGetters} from 'vuex';
    import auth from '../../service/auth';
    import viewSettings from '../../config/view';
    import {cache} from '../../helper';

    export default {
        data: function () {
            return {
                menuIsOpen: false,
                panelTitle: viewSettings.title,
                languages: viewSettings.languages,
                selectedLang: '',
            }
        },
        created() {
            this.selectedLang = this.language;
        },
        computed: {
            ...mapGetters({
                user: 'storage/user',
                title: 'view/websiteTitle',
                language: 'view/language',
                website: 'view/website',
            }),
            selectedLangName () {
                return this.languages[this.selectedLang] || this.selectedLang;
            },
            languagesList () {
                const result = [];

                for (const langCode of this.website.metas.languages_list) {
                    result.push({
                        code: langCode,
                        name: this.languages[langCode],
                    });
                }

                return result;
            },
            fullname() {
                return this.user.meta.first_name + ' ' + this.user.meta.last_name;
            },
            menuArrow() {
                return !this.menuIsOpen ? 'mdi-arrow-down-drop-circle-outline' : 'mdi-arrow-up-drop-circle-outline';
            }
        },
        watch: {
            selectedLang(val) {
                this.changeLanguage(val);
            }
        },
        methods: {
            ...mapActions({
                'toggleDrawer': 'view/toggleDrawer',
                changeLanguage: 'view/changeLanguage',
            }),
            clickLogout() {
                auth.logout();
            },
            clickProfile() {
                this.$router.push({name: 'user.profile'})
            },
        }
    }
</script>
<style scoped lang="scss"></style>
