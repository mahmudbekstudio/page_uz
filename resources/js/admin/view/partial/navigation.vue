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
                            <v-list-item-title>{{$t(item.text)}}</v-list-item-title>
                        </v-list-item-content>
                    </template>
                    <v-list-item
                            v-for="child in item.children"
                            :key="getKeyByRoute(child.route)"
                            @click="listItemClick(child.route)"
                            :class="{'v-list-item--active': (checkForActive(child.route) || isActive(child))}"
                            link
                    >
                        <v-list-item-action v-if="child.icon">
                            <v-icon>{{ child.icon }}</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>{{$t(child.text)}}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-group>
                <v-list-item
                        v-else
                        :key="getKeyByRoute(item.route)"
                        @click="listItemClick(item.route)"
                        :class="{'v-list-item--active': (checkForActive(item.route) || isActive(item))}"
                        link
                >
                    <v-list-item-action v-if="item.icon">
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>{{$t(item.text)}}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </template>
        </v-list>
    </v-navigation-drawer>
</template>
<script>
    import {mapGetters, mapActions} from 'vuex';
    import navigationList from '../../config/navigation';
    import * as _ from 'lodash';

    export default {
        data() {
            return {
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
                isMini: 'view/isMini',
                typeNavigation: 'view/typeNavigation',
            }),
            items() {
                return [navigationList[this.user.role][0], ...this.typeNavigation.map(item => {
                    if (['string', 'number'].indexOf(typeof item.text) === -1) {
                        item.text = JSON.stringify(item.text);
                    }
                    return item;
                }), ...navigationList[this.user.role].slice(1)];
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
                    if(this.isActive(list[i])) {
                        return true;
                    }
                }

                return false;
            },
            isActive(item) {
                const isActive = item.active && item.active.indexOf(this.$route.name) > -1;

                return (this.$route.name === item.route.name || isActive) &&
                    this.isExistEqual(item.route.params, this.$route.params);
            },
            listItemClick(route) {
                if(this.$route.name !== route.name || !this.isEqual(this.$route.params, route.params)) {
                    this.$router.push(route);
                }
            },
            getKeyByRoute(route) {
                let key = route.name;

                if(route.params) {
                    for (const paramKey in route.params) {
                        key += '_' + paramKey + route.params[paramKey]
                    }
                }

                return key;
            },
            checkForActive(route) {
                return this.$route.name === route.name && this.isEqual(this.$route.params, route.params)
            },

            isEqual(obj1, obj2) {
                const obj1Keys = Object.keys(obj1 || {}).map(item => item.toString()).join(',');
                const obj2Keys = Object.keys(obj2 || {}).map(item => item.toString()).join(',');
                const obj1Values = Object.values(obj1 || {}).map(item => item.toString()).join(',');
                const obj2Values = Object.values(obj2 || {}).map(item => item.toString()).join(',');

                return obj1Keys === obj2Keys && obj1Values === obj2Values;
            },
            isExistEqual(obj1, obj2) {
                for (const obj1Key in obj1) {
                    if(!obj2[obj1Key] || String(obj2[obj1Key]) !== String(obj1[obj1Key])) {
                        return false;
                    }
                }

                return true;
            }
        }
    }
</script>
