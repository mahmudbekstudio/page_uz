<template>
    <v-expansion-panels class="edit-block-form">
        <v-expansion-panel
            v-for="(item, index) of fields"
            :key="index"
            class="template-field"
        >
            <v-expansion-panel-header class="template-field-title">
                {{$t('words.' + item.type)}}
            </v-expansion-panel-header>
            <v-expansion-panel-content>
                <template-field
                    :type="item.type"
                    v-model="values[item.name]"
                ></template-field>
            </v-expansion-panel-content>
        </v-expansion-panel>
    </v-expansion-panels>
</template>
<script>
import templateField from "./template/template-field.vue";

export default {
    computed: {
        fields () {
            return this.block.fields.filter(item => !item.hide) || [];
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
<style scoped lang="scss">
.template-field {
    &-title {
        padding: 12px;
        background-color: #EEE;
    }
}
</style>
