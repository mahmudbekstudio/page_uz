<template>
    <div>
        <html-render
            :id="type + '-' + id"
            :styles="styles"
            :style-files="styleFiles"
            :script-files="scriptFiles"
            :structure="htmlStructure()"
            :fields="fields"
            :values="values"
            :is-sample="isSample"
        />
        <hr>
    </div>
</template>
<script>
import htmlRender from "./html-render.vue";

export default {
    props: {
        id: {
            type: String|Number,
            default () {
                return '';
            }
        },
        type: {
            type: String,
            default () {
                return '';
            }
        },
        fields: {
            type: Object|Array,
            default () {
                return {};
            }
        },
        styleFiles: {
            type: Object|Array,
            default () {
                return {};
            }
        },
        scriptFiles: {
            type: Object|Array,
            default () {
                return {};
            }
        },
        values: {
            type: Object,
            default () {
                return {};
            }
        },
        styles: {
            type: Object,
            default () {
                return {};
            }
        },
        structure: {
            type: Object|Array,
            default () {
                return {};
            }
        },
        isSample: {
            type: Boolean,
            default () {
                return false;
            }
        },
    },
    methods: {
        htmlStructure() {
            /*console.log(this.structure.attributes['id']);
            if (!this.structure.attributes['id']) {
                window.website_block_counter = window.website_block_counter || 0;
                window.website_block_counter++;
                this.structure.attributes['id'] = this.type + '-' + window.website_block_counter;
            }*/
            //this.structure.attributes['id'] = this.type + '-' + this.id;

            /*this.structure.attributes = this.structure.attributes || {};
            this.structure.attributes.class = this.structure?.attributes?.class || '';

            if (this.structure.attributes.class.indexOf('border border-dark') === -1) {
                this.structure.attributes.class += ' border border-dark';
            }*/

            return this.structure;
        }
    },
    components: {
        htmlRender
    }
}
</script>
