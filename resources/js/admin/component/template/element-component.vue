<template>
    <component
        :is="tagComponent"
        :params="params"
    ></component>
</template>
<script>
import hElement from "./elements/hElement";
import pElement from "./elements/pElement";
import hrElement from "./elements/hrElement.vue";
import divElement from "./elements/divElement.vue";

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
    }
}
</script>
