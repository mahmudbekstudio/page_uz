<template>
    <page-box>
        <template #actions v-if="type === 'list'">
            <v-menu
                left
                bottom
            >
                <template v-slot:activator="{ on }">
                    <v-btn
                        depressed
                        color="primary"
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
        </template>

        <list v-if="type === 'list'"></list>

        <dialog-component
            :title="dialog.title"
            v-model="dialog.show"
            :actions="dialog.actions"
            @close="gotoList()"
            fullscreen
        >
            <component :is="formComponent"></component>
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

export default {
    service: new Service(),
    data() {
        return {
            actions: [],
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
            this.id = this.$route.params.id;
            this.dialog.show = false;

            if (this.type === 'list') {
                this.actions = [];
                this.actions.push({title: 'words.layout', on: {click: () => this.gotoCreateType(this.types.layout)}});
                this.actions.push({title: 'words.block', on: {click: () => this.gotoCreateType(this.types.block)}});
                this.actions.push({title: 'words.post', on: {click: () => this.gotoCreateType(this.types.post)}});
                this.actions.push({title: 'words.category', on: {click: () => this.gotoCreateType(this.types.category)}});
            } else {
                this.dialog.show = true;
                this.dialog.title = 'words.create_template_type_' + this.type;
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
                            this.$options.service.submit({}, response => {
                                console.log('response', response);
                                if (!this.id) {
                                    this.dialog.show = false;
                                }
                            })
                        }
                    }
                ];
            }
        },
        gotoCreateType(type) {
            this.$router.push({name: 'template.create', params: {type}});
        },
        gotoList() {
            this.$router.push({name: 'template.list'});
        }
    },
    components: {
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
