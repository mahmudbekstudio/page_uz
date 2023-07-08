<template>
    <div class="edit-block-form">
        <template-field
            v-for="(item, index) of fields"
            :key="index"
            :type="item.type"
            v-model="values[item.name]"
        ></template-field>
    </div>
</template>
<script>
import templateField from "./template/template-field.vue";

export default {
    computed: {
        fields () {
            return this.block.fields || [];
        },
        values () {
            return this.block.values || {};
        },
    },
    props: {
        block: {
            type: Object,
            default() {
                return {};
            }
        }
    },
    components: {
        templateField,
    }
}
</script>
