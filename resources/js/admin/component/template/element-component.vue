<template>
    <component
        :is="tagComponent"
        :name="name"
        :params="params"
    ></component>
</template>
<script>
import hElement from "./elements/hElement";
import pElement from "./elements/pElement";
import hrElement from "./elements/hrElement.vue";
import divElement from "./elements/divElement.vue";
import imgElement from "./elements/imgElement.vue";


import textElement from "./elements/type/textElement.vue";
import textareaElement from "./elements/type/textareaElement.vue";
import numberElement from "./elements/type/numberElement.vue";
import passwordElement from "./elements/type/passwordElement.vue";
import selectElement from "./elements/type/selectElement.vue";
import fileElement from "./elements/type/fileElement.vue";
import switchElement from "./elements/type/switchElement.vue";
import dividerElement from "./elements/type/dividerElement.vue";
import datetimeElement from "./elements/type/datetimeElement.vue";
import dateElement from "./elements/type/dateElement.vue";
import dateRangeElement from "./elements/type/dateRangeElement.vue";
import dateMultipleElement from "./elements/type/dateMultipleElement.vue";
import timeElement from "./elements/type/timeElement.vue";
import radioElement from "./elements/type/radioElement.vue";
import checkboxElement from "./elements/type/checkboxElement.vue";
import editorElement from "./elements/type/editorElement.vue";
import advancedParentElement from "./elements/type/advancedParentElement.vue";
import advancedChildOfElement from "./elements/type/advancedChildOfElement.vue";
import requiredPublishStartElement from "./elements/type/requiredPublishStartElement.vue";
import requiredPublishEndElement from "./elements/type/requiredPublishEndElement.vue";
import requiredRouteNameElement from "./elements/type/requiredRouteNameElement.vue";
import requiredSeoKeywordElement from "./elements/type/requiredSeoKeywordElement.vue";
import requiredSeoDescriptionElement from "./elements/type/requiredSeoDescriptionElement.vue";
import requiredStatusElement from "./elements/type/requiredStatusElement.vue";
import requiredTemplateElement from "./elements/type/requiredTemplateElement.vue";
import requiredTitleElement from "./elements/type/requiredTitleElement.vue";


export default {
    props: {
        params: {
            type: Object,
            default() {
                return {}
            }
        },
        tag: {
            type: String,
            default: 'div'
        },
        name: {
            type: String,
            default: ''
        },
    },
    computed: {
        tagComponent() {
            return this.tag + 'Element';
        },
    },
    components: {
        hElement,
        pElement,
        divElement,
        hrElement,
        imgElement,

        // type fields
        textElement,
        textareaElement,
        numberElement,
        passwordElement,
        selectElement,
        fileElement,
        switchElement,
        dividerElement,
        datetimeElement,
        dateElement,
        dateRangeElement,
        dateMultipleElement,
        timeElement,
        radioElement,
        checkboxElement,
        editorElement,
        advancedParentElement,
        advancedChildOfElement,
        requiredPublishStartElement,
        requiredPublishEndElement,
        requiredRouteNameElement,
        requiredSeoKeywordElement,
        requiredSeoDescriptionElement,
        requiredStatusElement,
        requiredTemplateElement,
        requiredTitleElement,
    }
}
</script>
