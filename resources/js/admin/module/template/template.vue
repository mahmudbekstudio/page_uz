<template>
    <page-box>
        <list v-if="type === 'list'"></list>

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
import validation from "../../config/validation";

export default {
    service: new Service(),
    data() {
        return {
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
            website: 'view/website',
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
        init() {
            this.type = this.$route.params.type || 'list';
            this.id = parseInt(this.$route.params.id) || 0;
            this.dialog.show = false;

            if (this.type === 'list' && !this.id) {
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
            this.$router.push({name: 'template.tab', params: {tab: this.type}});
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
