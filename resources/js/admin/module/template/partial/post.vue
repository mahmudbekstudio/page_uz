<template>
    <div class="module-template-post">
        <formComponent
            v-if="layoutFieldLoaded && typeFieldLoaded"
            :value="templateForm"
            :disabled="isLoading"
            @input="formChanged($event)"
            @validate="formValidateFunc($event)"
        />
        <v-container v-if="selectedLayout && selectedType">
            <v-row>
                <v-col>
                    <div v-if="selectedType">
                        <v-btn @click="openEditForm">{{$t('words.edit_content')}}</v-btn>
                        <v-btn @click="openStyleEditForm">{{$t('words.edit_style')}}</v-btn>

                        <dialog-component
                            :title="dialog.title"
                            v-model="dialog.show"
                            :actions="dialog.actions"
                            fullscreen
                        >
                            <template-constructor
                                v-if="contentField && contentField.children"
                                v-model="contentField.children"
                                :required-fields="selectedType.fields"
                            ></template-constructor>
                        </dialog-component>

                        <dialog-component
                            :title="styleDialog.title"
                            v-model="styleDialog.show"
                            :actions="styleDialog.actions"
                            fullscreen
                        >
                            <style-constructor
                                :blocks="websiteHtmlObject.blocks"
                                @getStylesCallback="getStylesCallback=$event"
                            ></style-constructor>
                        </dialog-component>
                    </div>
                    <div class="website-render" v-if="selectedLayout">
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
                        />
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>
<script>
import formComponent from "../../../component/form/form-component.vue";
import {mapGetters} from "vuex";
import {Form as FormClass} from "../../../component/form/classes/form";
import validation from "../../../config/validation";
import websiteRender from "../../../component/website-render/website-render.vue";
import Service from "../js/service";
import {WebsiteHtml, WebsiteRender as WebsiteRenderClass} from "../../../component/website-render/website-render.js";
import dialogComponent from "../../../component/dialog-component";
import templatesList from "../../../component/website-render/templates-list.vue";
import templateConstructor from '../../../component/template-constructor/template-constructor.vue';
import styleConstructor from "../../../component/template-constructor/style-constructor.vue";
import mainConfig from '../../../config/main';

export default {
    service: new Service(),
    data () {
        return {
            itemType: 'post',
            contentField: null,
            layoutFieldLoaded: false,
            typeFieldLoaded: false,
            templateForm: null,
            formValidate: null,
            langToggle: 0,
            websiteHtmlObject: null,
            websiteHtmlDocument: '',
            layoutField: null,
            typeField: null,
            selectedLayout: null,
            selectedType: null,
            templateValue: null,
            dialog: {
                title: 'words.content',
                show: false,
                actions: [
                    {
                        color: 'default',
                        text: 'words.cancel',
                        click: () => this.dialog.show = false
                    },
                    {
                        color: 'primary',
                        text: 'words.save',
                        click: () => {
                            this.dialog.show = false;
                            //this.contentField.children = this.templateValue.json;
                            //his.contentField.values.content = this.templateValue.json;
                            this.renderWebsite();
                            this.emit();
                        }
                    }
                ],
            },
            getStylesCallback: null,
            styleDialog: {
                title: 'words.style',
                show: false,
                actions: [
                    {
                        color: 'default',
                        text: 'words.cancel',
                        click: () => this.styleDialog.show = false
                    },
                    {
                        color: 'primary',
                        text: 'words.save',
                        click: () => {
                            const customStyles = this.getStylesCallback();

                            for (const block of this.websiteHtmlObject.blocks) {
                                if (customStyles[block.id]) {
                                    block.customStyles = {...customStyles[block.id]};
                                }
                            }

                            this.styleDialog.show = false;
                            this.renderWebsite();
                            this.emit();
                        }
                    }
                ],
            },
        };
    },
    props: {
        value: {
            type: Object,
            default () {
                return {};
            }
        },
        isEdit: {
            type: Boolean,
            default () {
                return false;
            }
        }
    },
    created () {
        this.setValue();
        this.templateForm = new FormClass();
        const titleField = this.templateForm.addField({type: 'text'});
        titleField.setParams('label', 'words.title');
        titleField.setParams('rules', [validation.required('words.title')]);
        titleField.name = 'title';
        titleField.value = this.templateValue.name;

        this.layoutField = this.templateForm.addField({type: 'select'});
        this.layoutField.setParams('label', 'words.layout');
        this.layoutField.name = 'layout';
        this.layoutField.setParams('clearable', false);

        this.typeField = this.templateForm.addField({type: 'select'});
        this.typeField.setParams('label', 'words.type');
        this.typeField.name = 'type';
        //this.typeField.value = this.styleValue || 'solid';
        this.typeField.setParams('clearable', false);



        this.$options.service.getAllLayouts(response => {
            const result = {};
            for (const item of response.data.list) {
                result[item.id] = item.name;
            }

            this.layoutField.setParams('options', result);
            this.layoutFieldLoaded = true;
            this.layoutField.value = this.templateValue.layout_id;
        });
        this.$options.service.getAllTypes(this.itemType, response => {
            const result = {};

            for (const item of response.data.list) {
                result[item.id] = this.$t(item.title);
            }

            this.typeField.setParams('options', result);
            this.typeFieldLoaded = true;
            this.typeField.value = this.templateValue.type_id;
        });
        //this.$options.service.getThemeConfig();
    },
    computed: {
        ...mapGetters({
            isLoading: 'view/loading',
            website: 'view/website',
        })
    },
    methods: {
        setValue (value = null) {
            this.templateValue = value || this.value;

            if (this.isEdit) {
                this.initWebsite(this.templateValue);
                this.renderWebsite();
            }
        },
        openEditForm () {
            this.dialog.show = true;
        },
        openStyleEditForm () {
            this.styleDialog.show = true;
        },
        formChanged (e) {
            this.emit();
        },
        formValidateFunc (e) {
            this.formValidate = e;
        },
        renderWebsite () {
            this.websiteHtmlObject.setThemeConfig(this.$options.service.themeConfig);
            this.websiteHtmlDocument = this.websiteHtmlObject.htmlDocument(false, this.website.metas.languages_list[this.langToggle], ['content']);
        },
        emit () {
            const values = this.templateForm.getFieldValues();
            const websiteHtmlObj = new WebsiteHtml(this.websiteHtmlObject?.blocks);
            websiteHtmlObj.setThemeConfig(this.$options.service.themeConfig);
            websiteHtmlObj.htmlDocument();
            const contentHtml = websiteHtmlObj.getContentBlockStructureHtml();
            const styles = websiteHtmlObj.contentStructureStyles;
            const customStyles = websiteHtmlObj.customStyles;

            if (this.websiteHtmlObject) {
                this.$emit('input', {
                    name: values.title,
                    layout_id: values.layout,
                    type_id: values.type,
                    type: this.itemType,
                    content: websiteHtmlObj.blocks,
                    params: {styles, customStyles, contentHtml},
                });
            }
        },
        initWebsite(value) {
            this.websiteHtmlObject = new WebsiteHtml(value.content);

            for (const contentItem of value.content) {
                if (contentItem.type === 'content') {
                    this.contentField = this.websiteHtmlObject.getBlockById(contentItem.id);
                    break;
                }
            }
        }
    },
    watch: {
        value (newValue) {
            this.setValue(newValue);
        },
        langToggle() {
            this.renderWebsite();
        },
        'typeField.value' (typeId) {
            if (typeId) {
                this.selectedType = null;
                this.$options.service.getType(typeId, response => {
                    response.data.type.fields = response.data.type.fields.filter(
                        item => mainConfig.template.exceptFields.indexOf(item.name) === -1
                    );
                    this.selectedType = response.data.type;
                });
            }
        },
        'layoutField.value' (layoutId) {
            if (layoutId) {
                this.selectedLayout = null;
                this.$options.service.get(response => {
                    this.selectedLayout = response.data.template;
                    if (!this.isEdit) {
                        this.initWebsite(this.selectedLayout);
                        this.renderWebsite();
                    }
                }, error => {
                    console.log('error', error);
                }, layoutId);
            }
        }
    },
    components: {
        templatesList,
        websiteRender,
        formComponent,
        dialogComponent,
        templateConstructor,
        styleConstructor,
    }
}
</script>
<style scoped lang="scss">
.module-template-post {
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
}
</style>
