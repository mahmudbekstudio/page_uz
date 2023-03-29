<template>
    <v-app id="module-admin">
        <component :is="layout" v-if="inited">
            <router-view/>
        </component>
        <centeredLayout v-if="!inited">
            {{$t('words.loading')}}
        </centeredLayout>
    </v-app>
</template>

<script>
    import {mapGetters} from 'vuex';
    import emptyLayout from './layout/empty';
    import mainLayout from './layout/main';
    import centeredLayout from './layout/centered';
    import viewConfig from '../config/view';

    export default {
        name: 'App',
        created() {
            this.$vuetify.theme.dark = viewConfig.isDark;
            this.$store.dispatch('view/changeWebsite', this.website);
            this.$store.dispatch('view/changeTypeNavigation', this.typeNavigation);
            this.$store.dispatch('view/changeWebsiteTitle', this.website.metas.name);
        },
        props: {
            website: {
                type: Object,
                default () {
                    return {};
                }
            },
            typeNavigation: {
                type: Array,
                default () {
                    return [];
                }
            },
        },
        computed: {
            ...mapGetters({
                layout: 'view/layout',
                inited: 'setting/inited',
            })
        },
        components: {
            emptyLayout,
            mainLayout,
            centeredLayout
        }
    }
</script>
