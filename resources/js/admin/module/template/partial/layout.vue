<template>
    <div class="module-template-layout">
        <formComponent
            :value="templateForm"
            :disabled="isLoading"
            @input="formChanged($event)"
            @validate="formValidateFunc($event)"
        />
        <v-container fluid>
            <v-row>
                <v-col
                    cols="12"
                    sm="3"
                >
                    <div class="edit-block" v-if="editBlock && activeBlock">
                        <h1 class="edit-block-title">
                            <v-btn
                                small
                                icon
                                class="edit-block-back-btn"
                                @click="editBlock=false"
                            >
                                <v-icon>mdi-keyboard-backspace</v-icon>
                            </v-btn>
                            Edit block "{{activeBlock.title}}"
                        </h1>
                        <edit-block-form
                            :block="activeBlock"
                        />
                    </div>
                    <div class="navigation" v-else>
                        <sort-list
                            v-model="websiteHtmlObject.blocks"
                        >
                            <template v-slot:actions="{item, indexes}">
                                <v-btn
                                    small
                                    icon
                                    class="menu-action-btn btn-inactive"
                                    @click="clickBlockEdit(item, indexes)"
                                >
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn
                                    v-if="!item.hide && !item.notSort"
                                    small
                                    icon
                                    class="menu-action-btn btn-inactive"
                                    @click="clickBlockDelete(item, indexes)"
                                >
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                            </template>
                            <template v-slot:append-item="props">
                                <div class="list-group" v-if="props.indexes.length !== 2">
                                    <div class="list-item">
                                        <v-btn
                                            block
                                            color="primary"
                                            plain
                                            @click="addSection(props)"
                                        ><v-icon>mdi-plus-circle-outline</v-icon></v-btn>
                                    </div>
                                </div>
                            </template>
                        </sort-list>
                    </div>
                </v-col>
                <v-col
                    cols="12"
                    sm="9"
                >
                    <div class="website-render">
                        <div class="lang-list" v-if="website.metas.languages_list.length > 1">
                            <v-btn-toggle v-model="langToggle">
                                <v-btn
                                    plain
                                    x-small
                                    v-for="(lang, index) of website.metas.languages_list"
                                    :key="lang"
                                    :class="{'v-item--active v-btn--active': index === langToggle}"
                                >
                                    {{ lang }}
                                </v-btn>
                            </v-btn-toggle>
                        </div>
                        <website-render
                            :website-html="websiteHtmlDocument"
                            :website-html-object="websiteHtmlObject"
                        />
                    </div>
                </v-col>
            </v-row>
        </v-container>
        <dialog-component
            :title="dialog.title"
            v-model="dialog.show"
            :actions="dialog.actions"
            fullscreen
            classes="layout-templates-list"
        >
            <templates-list
                :website-render="websiteRender"
                @select="selectTemplate($event)"
                :is-sample="true"
            ></templates-list>

        </dialog-component>
    </div>
</template>
<script>
import websiteRender from "../../../component/website-render/website-render.vue";
import websiteBlockRender from "../../../component/website-render/website-block-render.vue";
import dialogComponent from "../../../component/dialog-component";
import modal from "bootstrap/js/src/modal";
import Service from '../js/service';
import {WebsiteHtml, WebsiteRender as WebsiteRenderClass} from "../../../component/website-render/website-render.js";
import templatesList from "../../../component/website-render/templates-list.vue";
import sortList from "../../../component/sort-list.vue";
import editBlockForm from "../../../component/website-render/fields/edit-block-form.vue";
import * as _ from 'lodash';
import {mapGetters} from "vuex";
import formComponent from "../../../component/form/form-component.vue";
import {Form as FormClass} from "../../../component/form/classes/form";
import validation from "../../../config/validation";

export default {
    service: new Service(),
    computed: {
        ...mapGetters({
            website: 'view/website',
            isLoading: 'view/loading',
        }),
        modal() {
            return modal
        }
    },
    data () {
        return {
            templateValue: {},
            templateForm: null,
            formValidate: null,
            langToggle: 0,
            websiteRender: null,
            websiteHtmlObject: null,
            websiteHtmlDocument: '',
            activeList: null,
            editBlock: false,
            activeBlock: null,
            reRenderWebsite: true,
            dialog: {
                title: 'words.sections',
                show: false,
                actions: [
                    {
                        color: 'default',
                        text: 'words.close',
                        click: () => this.dialog.show = false
                    }
                ],
            }
        }
    },
    props: {
        value: {
            type: Object,
            default () {
                return {};
            }
        },
    },
    created() {
        this.setValue();
        window.iframeClick = e => {
            const id = e.currentTarget.getAttribute('id');
            this.toggleActiveBlock(id);
        };
        if (this.websiteHtmlObject) {
            this.$nextTick(() => this.renderWebsite());
        } else {
            this.websiteHtmlObject = new WebsiteHtml();
        }
        //this.websiteHtmlObject.setSample(true);
        /*this.selectTemplate({
            "type": "grid",
            "canHasChild": true,
            "styleFiles": [],
            "scriptFiles": [],
            "structure": {
                "tag": "grid",
                "attributes": {
                    "id": "grid-3"
                },
                "children": [
                    {
                        "tag": "div",
                        "attributes": {
                            "class": "grid-wrap"
                        },
                        "children": []
                    }
                ]
            }
        });*/
        /*setTimeout(() => {

        }, 100);*/
        this.$options.service.blocks(response => {
            this.websiteRender = new WebsiteRenderClass(response.data.templates);

            if (!(Object.keys(this.value).length && this.value?.content?.length)) {
                const contentBlock = this.websiteRender.getBlock('content');
                this.websiteHtmlObject.addBlock(this.websiteRender.getTemplate(contentBlock, contentBlock.samples[0]));
            }
        });
        this.$nextTick(() => {
            //this.dialog.show = true;



            //const node = doc.createElement("p");
            //node.innerHTML = 't<strong>es</strong>t';

            //doc.open();
            //doc.write('<p>t<strong>es</strong>t</p>');
            //doc.close();
        });

        this.templateForm = new FormClass();
        const nameField = this.templateForm.addField({type: 'text'});
        nameField.setParams('label', 'words.name');
        nameField.setParams('rules', [validation.required('words.name')]);
        nameField.name = 'name';
        nameField.value = this.templateValue.name;
    },
    methods: {
        setValue (value = null) {
            this.templateValue = value || this.value;
            this.websiteHtmlObject = new WebsiteHtml(this.templateValue.content);
            this.renderWebsite();
        },
        toggleActiveBlock(id) {
            const block = this.websiteHtmlObject.getBlockById(id);
            this.changeActiveBlock(block);
        },
        changeActiveBlock(block) {
            this.editBlock = false;

            if (this.activeBlock && this.activeBlock !== block && this.activeBlock.isActive) {
                this.activeBlock.isActive = false;

                if (window.activeBlock) {
                    window.activeBlock.isActive = false;
                }
            }

            block.isActive = !block.isActive;
            this.reRenderWebsite = false;

            if (block.isActive) {
                this.activeBlock = block;
                window.activeBlock = block;
                this.editBlock = true;
            } else {
                this.activeBlock = null;
                window.activeBlock = null;
            }
        },
        selectTemplate(template) {
            this.dialog.show = false;
            this.websiteHtmlObject.addBlock(template, this.activeList);
            this.renderWebsite();
        },
        renderWebsite () {
            this.websiteHtmlDocument = this.websiteHtmlObject.htmlDocument(true, this.website.metas.languages_list[this.langToggle]);
            this.templateValue.content = this.websiteHtmlObject.blocks;
            this.templateChanged();
        },
        addSection(e) {
            if (e.value) {
                this.activeList = e.value;
            } else {
                this.activeList = null;
            }

            this.dialog.show = true;
        },
        clickBlockEdit(item, indexes) {
            item.isActive = false;
            this.changeActiveBlock(item);
        },
        clickBlockDelete(item, indexes) {
            const path = indexes.slice(0, -1).join('.');
            let blockList = this.websiteHtmlObject.blocks;

            if (path) {
                blockList = _.get(this.websiteHtmlObject.blocks, path);
            }

            blockList.splice(indexes[indexes.length - 1], 1);
        },
        formChanged (e) {
            const values = e.getFieldValues();
            this.templateValue.name = values.name;
        },
        formValidateFunc (e) {
            this.formValidate = e;
        },
        templateChanged() {
            this.templateValue.type = 'layout';
            this.templateValue.params = [];
            this.$emit('input', this.templateValue);
        }
    },
    watch: {
        value (newValue) {
            this.setValue(newValue);
        },
        'websiteHtmlObject.blocks': {
            handler() {
                if (this.reRenderWebsite) {
                    this.renderWebsite();
                } else {
                    this.reRenderWebsite = true;
                }
            },
            deep: true
        },
        langToggle() {
            this.renderWebsite();
        },
        /*websiteHtmlObject: {
            handler() {
                this.websiteHtmlDocument = this.websiteHtmlObject.htmlDocument();
            },
            deep: true
        }*/
    },
    components: {
        formComponent,
        sortList,
        websiteRender,
        dialogComponent,
        websiteBlockRender,
        templatesList,
        editBlockForm,
    }
}
</script>
<style lang="scss">
.module-template-layout {
    .navigation {
        border: #000 1px solid;
    }
    .edit-block {
        border: #000 1px solid;
        .edit-block-title {
            border-bottom: 1px solid #999;
            padding: 3px 5px;
            margin-bottom: 10px;
            font-size: 20px;
            background-color: #EEE;
        }
    }
    .website-render {
        .lang-list {
            text-align: right;
            .v-item--active {
                background-color: #757575;
                color: #FFF !important;
            }
            .v-btn-toggle {
                border-radius: 4px 4px 0 0;
            }
        }
    }

    .list-item {
        .v-input {
            .v-input__prepend-outer {
                margin-top: 7px;
            }
            .v-input__control {
                margin-top: 3px;
            }
        }
    }
}
</style>
