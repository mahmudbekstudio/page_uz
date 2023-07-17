<template>
    <component
        :is="fieldComponent"
        v-model="fieldValue"
    ></component>
</template>
<script>
import backgroundField from "./background.vue";
import textareaField from './textarea.vue';
import buttonField from "./button.vue";
import textField from "./text.vue";
import imageField from "./image.vue";
import block_next_buttonField from "./block_next_button.vue";
import contentField from "./content.vue";

export default {
    computed: {
        fieldComponent() {
            return this.type + 'Field';
        },
        fieldValue: {
            get() {
                return this.value;
            },
            set(newValue) {
                this.$emit('input', newValue);
            }
        }
    },
    props: {
        type: {
            type: String,
            default () {
                return '';
            }
        },
        value: null,
    },
    components: {
        backgroundField,
        textareaField,
        buttonField,
        textField,
        imageField,
        block_next_buttonField,
        contentField,
    }
}
</script>
<style scoped lang="scss">
</style>
