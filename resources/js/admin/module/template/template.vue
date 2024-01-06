<template>
    <page-box>
        <template #actions>
            <v-menu
                left
                bottom
            >
                <template v-slot:activator="{ on }">
                    <v-btn
                        depressed
                        color="default"
                        v-on="on"
                    >
                        {{$t('words.create')}}
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item
                        v-for="(btn, i) in actions"
                        :key="i"
                        v-on="btn.on"
                        v-bind="btn.bind"
                    >
                        <v-list-item-content>
                            <v-list-item-title>{{$t(btn.title)}}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-menu>
            <v-btn
                color="primary"
                icon
                @click="openThemeDialog()"
            ><v-icon>mdi-plus</v-icon></v-btn>
        </template>

        <list v-if="type === 'list'"></list>

        <dialog-component
            :title="themeDialog.title"
            v-model="themeDialog.show"
            :actions="themeDialog.actions"
            @close="gotoList()"
            :overlay="isLoading"
            size="small"
        >
            <form-component
                :value="themeForm"
                :disabled="isLoading"
                @validate="themeFormValidation = $event"
            />
        </dialog-component>

        <dialog-component
            :title="dialog.title"
            v-model="dialog.show"
            :actions="dialog.actions"
            @close="gotoList()"
            fullscreen
            :overlay="isLoading"
        >
            <component
                :is="formComponent"
                v-model="templateValue"
                :is-edit="!!id"
            ></component>
        </dialog-component>
    </page-box>
</template>
<script>
import pageBox from '../../view/partial/page-box';
import list from './partial/list.vue';
import formBlock from './partial/block.vue';
import formCategory from './partial/category.vue';
import formLayout from './partial/layout.vue';
import formPost from './partial/post.vue';
import dialogComponent from "../../component/dialog-component";
import Service from './js/service';
import {mapGetters} from "vuex";
import app from "../../service/app";
import formComponent from "../../component/form/form-component.vue";
import {Form as FormClass} from "../../component/form/classes/form";

export default {
    service: new Service(),
    data() {
        return {
            themeForm: null,
            themeFormValidation: null,
            templateValue: {},
            //actions: [],
            types: {
                layout: 'layout',
                block: 'block',
                post: 'post',
                category: 'category'
            },
            type: null,
            id: null,
            themeDialog: {
                title: 'words.create',
                show: false,
                actions: [
                    {
                        color: 'default',
                        text: 'words.cancel',
                        click: () => this.themeDialog.show = false
                    },
                    {
                        color: 'primary',
                        text: 'words.create',
                        click: () => {
                            this.themeDialog.show = false;
                            /*this.$options.service.submit(this.templateValue, response => {
                                if (!this.id) {
                                    this.dialog.show = false;
                                }

                                app.openMessage(this.$t('words.' + (this.id ? 'save' : 'create') + 'd'))
                            });*/
                        }
                    }
                ],
            },
            dialog: {
                title: '',
                show: false,
                actions: [],
            }
        }
    },
    watch: {
        '$route.fullPath'() {
            this.$nextTick(this.init);
        },
    },
    computed: {
        ...mapGetters({
            isLoading: 'view/loading',
        }),
        formComponent () {
            if (this.types[this.type]) {
                return 'form' + this.type.charAt(0).toUpperCase() + this.type.slice(1);
            }

            return null;
        },
    },
    created() {
        this.init();
    },
    methods: {
        openThemeDialog() {
            this.themeDialog.show = true;
            this.themeForm = new FormClass();
        },
        init() {
            this.type = this.$route.params.type || 'list';
            this.id = parseInt(this.$route.params.id) || 0;
            this.dialog.show = false;
            this.themeDialog.show = false;

            if (this.type === 'list' && !this.id) {
                this.$options.service.loadThemes(response => {
                    console.log('response', response);
                });
                /*this.actions = [];
                this.actions.push({title: 'words.layout', on: {click: () => this.gotoCreateType(this.types.layout)}});
                //this.actions.push({title: 'words.block', on: {click: () => this.gotoCreateType(this.types.block)}});
                this.actions.push({title: 'words.post', on: {click: () => this.gotoCreateType(this.types.post)}});
                this.actions.push({title: 'words.category', on: {click: () => this.gotoCreateType(this.types.category)}});*/
            } else if (this.id) {
                this.$options.service.get(response => {
                    this.templateValue = response.data.template;
                    this.type = this.templateValue.type;
                    this.showForm();
                });
            } else {
                this.templateValue = {};
                this.showForm();
            }
        },
        showForm() {
            this.dialog.show = true;
            this.dialog.title = 'words.' + (this.id ? 'edit' : 'create') + '_template_type_' + this.type;
            this.dialog.actions = [
                {
                    color: 'default',
                    text: 'words.cancel',
                    click: () => this.dialog.show = false
                },
                {
                    color: 'primary',
                    text: 'words.' + (this.id ? 'save' : 'create'),
                    click: () => {
                        this.$options.service.submit(this.templateValue, response => {
                            if (!this.id) {
                                this.dialog.show = false;
                            }

                            app.openMessage(this.$t('words.' + (this.id ? 'save' : 'create') + 'd'))
                        });
                    }
                }
            ];
        },
        gotoCreateType(type) {
            this.$router.push({name: 'template.create', params: {type}});
        },
        gotoList() {
            this.$router.push({name: 'template.list'});
        }
    },
    components: {
        formComponent,
        pageBox,
        list,
        dialogComponent,
        formBlock,
        formCategory,
        formLayout,
        formPost,
    }
}
</script>
