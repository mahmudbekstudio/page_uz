<template>
    <page-box>
        <template #actions>
            <v-btn
                depressed
                color="primary"
                @click="create"
                v-if="isList"
            >
                {{$t('words.create')}}
            </v-btn>
        </template>
        <list-partial v-if="isList" />
        <dialog-component
            :title="dialog.title"
            v-model="dialog.show"
            :actions="dialog.actions"
            @close="gotoList()"
            fullscreen
            :overlay="isLoading"
        ><form-partial
            :id="id"
        /></dialog-component>
    </page-box>
</template>
<script>
import ListPartial from "./partial/list.vue";
import FormPartial from "./partial/form.vue";
import pageBox from "../../view/partial/page-box.vue";
import dialogComponent from "../../component/dialog-component.vue";
import {mapGetters} from "vuex";
import app from "../../service/app";
export default {
    data () {
        return {
            featureValue: {},
            id: null,
            dialog: {
                title: '',
                show: false,
                actions: [],
            }
        }
    },
    created() {
        this.init();
    },
    computed: {
        ...mapGetters({
            isLoading: 'view/loading',
        }),
        isList() {
            return this.$route.name.endsWith('.list')
        }
    },
    methods: {
        init() {
            this.id = parseInt(this.$route.params.id) || 0;
            this.dialog.show = false;

            if (this.isList) {
                //
            } else if(this.id) {
                this.featureValue = {};
                this.showForm();
            } else {
                this.featureValue = {};
                this.showForm();
            }
        },
        showForm() {
            this.dialog.show = true;
            this.dialog.title = 'words.' + (this.id ? 'edit' : 'create');
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
                        console.log('this.templateValue', this.templateValue);
                        this.dialog.show = false;
                        /*this.$options.service.submit(this.templateValue, response => {
                            if (!this.id) {
                                this.dialog.show = false;
                            }

                            app.openMessage(this.$t('words.' + (this.id ? 'save' : 'create') + 'd'))
                        });*/
                    }
                }
            ];
        },
        create () {
            this.$router.push({name: 'feature.create'});
        },
        gotoList() {
            this.$router.push({name: 'feature.list'});
        }
    },
    watch: {
        '$route.fullPath'(value, oldValue) {
            this.$nextTick(this.init);
        },
    },
    components: {
        dialogComponent,
        pageBox,
        ListPartial,
        FormPartial
    }
}
</script>
