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
            <span class="hidden-sm-and-down">{{ title }} {{panelTitle}}</span>
        </v-toolbar-title>

        <div class="flex-grow-1"></div>
        <v-btn icon>
            <v-icon>mdi-apps</v-icon>
        </v-btn>
        <!--v-btn icon>
            <v-icon>mdi-bell</v-icon>
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

    export default {
        data: function () {
            return {
                menuIsOpen: false,
                panelTitle: viewSettings.title
            }
        },
        computed: {
            ...mapGetters({
                user: 'storage/user',
                title: 'view/websiteTitle'
            }),
            fullname() {
                return this.user.meta.first_name + ' ' + this.user.meta.last_name;
            },
            menuArrow() {
                return !this.menuIsOpen ? 'mdi-arrow-down-drop-circle-outline' : 'mdi-arrow-up-drop-circle-outline';
            }
        },
        methods: {
            ...mapActions({
                'toggleDrawer': 'view/toggleDrawer'
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
