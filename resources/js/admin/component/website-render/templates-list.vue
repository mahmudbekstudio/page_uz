<template>
    <v-container fluid class="templates-list">
        <v-row>
            <v-col
                cols="12"
                sm="3"
            >
                <div class="navigation">
                    <v-list v-if="websiteRender">
                        <v-list-item
                            v-for="block in websiteRender.blocks"
                            :key="block.type"
                        >
                            <v-list-item-content>
                                <v-list-item-title><a :href="'#' + block.type">{{$t(block.name)}}</a></v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                </div>
            </v-col>
            <v-col
                cols="12"
                sm="9"
            >
                <div class="website-render" v-if="websiteRender">
                    <div v-for="block in websiteRender.blocks">
                        <div :id="block.type">{{$t(block.name)}}</div>
                        <div>{{$t(block.description)}}</div>
                        <div
                            v-for="(sample, index) in block.samples"
                            class="template-item"
                            @click="clickTemplateItem(block, sample, index)"
                        >
                            <website-block-render
                                :type="block.type"
                                :fields="websiteRender.getFields(block.type, sample.layout)"
                                :values="sample.values"
                                :styles="{...websiteRender.styles, ...block.styles}"
                                :styleFiles="websiteRender.styleFiles"
                                :script-files="websiteRender.scriptFiles"
                                :id="index"
                                :structure="websiteRender.getBlockLayout(block.type, sample.layout).structure"
                                :is-sample="isSample"
                            />
                        </div>
                    </div>
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>
<script>
import websiteBlockRender from "./website-block-render.vue";

export default {
    data () {
        return {
            //
        }
    },
    props: {
        websiteRender: null,
        isSample: false,
    },
    methods: {
        clickTemplateItem(block, sample, index) {
            /*const template = {
                type: block.type,
                canHasChild: block.canHasChild,
                fields: this.websiteRender.getFields(block.type, sample.layout),
            };
            //template.type = block.type;
            //template.fields = this.websiteRender.getFields(block.type, sample.layout);
            template.values = sample.values;
            template.styles = {...this.websiteRender.styles, ...block.styles};
            template.styleFiles = this.websiteRender.styleFiles;
            template.scriptFiles = this.websiteRender.scriptFiles;
            const item = this.websiteRender.getBlockLayout(block.type, sample.layout);
            template.structure = item.structure;
            template.children = item.children;*/
            this.$emit('select', this.websiteRender.getTemplate(block, sample));
            //this.$emit('select', block.samples[index]);
        }
    },
    components: {websiteBlockRender}
}
</script>
<style lang="scss">
.templates-list {
    .navigation {
        border: #000 1px solid;
    }

    .template-item {
        position: relative;
        &:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            cursor: pointer;
        }
        &:hover {
            &:before {
                background-color: rgba(255, 255, 255, 0.5);
            }
        }
    }
}
</style>
