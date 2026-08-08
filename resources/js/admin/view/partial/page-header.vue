<template>
    <div
        v-if="showHeader"
        class="page-header v-footer--fixed"
        :style="{left: navigationWidth + 'px'}"
    >
        <v-app-bar>
            <v-toolbar-title>{{ $t(pageTitle) }}</v-toolbar-title>
            <div class="flex-grow-1"></div>

            <slot name="actions"></slot>

            <v-btn v-for="(btn, i) in actions" class="hidden-sm-and-down" :key="i" v-on="btn.on" v-bind="btn.bind">
                <v-icon v-if="btn.icon">{{btn.icon}}</v-icon>
                {{$t(btn.title)}}
            </v-btn>

            <v-menu
                    left
                    bottom
            >
                <template v-slot:activator="{ on }">
                    <v-btn icon v-on="on" class="hidden-md-and-up">
                        <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item
                            v-for="(btn, i) in actions"
                            :key="i"
                            v-on="btn.on"
                            v-bind="btn.bind"
                    >
                        <v-list-item-icon v-if="btn.icon">
                            <v-icon v-text="btn.icon"></v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>{{$t(btn.title)}}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>
    </div>
</template>
<script>
    import {mapGetters} from 'vuex';
    export default {
        data () {
            return {
                navigationClientWidth: 0,
            };
        },
        props: {
            actions: {
                type: Array,
                default: () => []
            },
            title: {
                type: String,
                default: () => null
            },
            forceShow: {
                type: Boolean,
                default () {
                    return false;
                }
            }
        },
        mounted() {
            this.navigationClientWidth = document.querySelector('.navigation').clientWidth;
        },
        computed: {
            ...mapGetters({
                drawer: 'view/drawer',
                viewTitle: 'view/title'
            }),
            navigationWidth () {
                return this.drawer ? this.navigationClientWidth : 0;
            },
            showHeader() {
                return this.pageTitle || this.actions.length || typeof this.$slots.actions !== 'undefined' || this.forceShow;
            },
            pageTitle () {
                return typeof this.title === 'string' ? this.title : this.viewTitle;
            },
        }
    }
</script>
<style scoped lang="scss"></style>
