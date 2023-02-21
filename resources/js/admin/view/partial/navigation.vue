<template>
    <v-navigation-drawer
            v-model="drawer"
            :clipped="$vuetify.breakpoint.lgAndUp"
            :mini-variant="isMini"
            app
            class="navigation"
    >
        <v-list dense class="primary--text">
            <v-menu
                    transition="slide-y-transition"
                    bottom
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-list-item
                            link
                            v-bind="attrs"
                            v-on="on"
                    >
                        <v-list-item-content>
                            <v-list-item-title class="select-website-title">
                                My Website
                            </v-list-item-title>
                            <v-list-item-subtitle>mywebsite.com</v-list-item-subtitle>
                        </v-list-item-content>

                        <v-list-item-action>
                            <v-icon>mdi-menu-down</v-icon>
                        </v-list-item-action>
                    </v-list-item>
                </template>
                <v-list>
                    <v-list-item
                            v-for="(item, i) in websites"
                            :key="i"
                    >
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
            <v-divider></v-divider>
            <template v-for="item in items">
                <v-subheader
                        v-if="item.heading"
                        :key="item.heading"
                >{{ item.heading }}</v-subheader>
                <v-divider v-else-if="item.divider"></v-divider>
                <v-list-group
                        v-else-if="item.children"
                        :key="item.text"
                        :value="isActiveExist(item.children)"
                        :prepend-icon="item.icon"
                >
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title v-text="item.text"></v-list-item-title>
                        </v-list-item-content>
                    </template>
                    <v-list-item
                            v-for="child in item.children"
                            :key="child.route.name"
                            @click="listItemClick(child.route)"
                            :class="{'v-list-item--active': ($route.name === child.route.name || (child.active && child.active.indexOf($route.name) > -1))}"
                            link
                    >
                        <v-list-item-action v-if="child.icon">
                            <v-icon>{{ child.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title v-text="child.text"></v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-group>
                <v-list-item
                        v-else
                        :key="item.route.name"
                        @click="listItemClick(item.route)"
                        :class="{'v-list-item--active': ($route.name === item.route.name || (item.active && item.active.indexOf($route.name) > -1))}"
                        link
                >
                    <v-list-item-action v-if="item.icon">
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title v-text="item.text"></v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </template>
        </v-list>
    </v-navigation-drawer>
</template>
<script>
    import {mapGetters, mapActions} from 'vuex';
    import navigationList from '../../config/navigation';

    export default {
        data() {
            return {
                activeNav: null,
                websites: [
                    { title: 'Click Me' },
                    { title: 'Click Me' },
                    { title: 'Click Me' },
                    { title: 'Click Me 2' },
                ],
            }
        },

        computed: {
            ...mapGetters({
                getDrawer: 'view/drawer',
                user: 'storage/user',
                isMini: 'view/isMini'
            }),
            items() {
                return navigationList[this.user.role];
            },
            drawer: {
                get() {
                    return this.getDrawer
                },
                set(newVal) {
                    this.updateDrawer(newVal)
                    //this.$store.dispatch('view/updateDrawer', newVal);
                }
            }
        },
        methods: {
            ...mapActions({
                updateDrawer: 'view/updateDrawer'
            }),
            isActiveExist(list) {
                for(let i = 0; i < list.length; i++) {
                    if(this.$route.name === list[i].route.name) {
                        return true;
                    }
                }

                return false;
            },
            listItemClick(route) {
                if(this.$route.name !== route.name) {
                    this.activeNav = route.name;
                    this.$router.push(route);
                }
            }
        }
    }
</script>
