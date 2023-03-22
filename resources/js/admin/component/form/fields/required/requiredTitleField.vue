<template>
    <v-text-field
        v-bind="params"
        v-on="events"
        v-model="dataValue"
        :disabled="disabled"
        :rules="rules"
    ></v-text-field>
</template>
<script>
import mixins from '../../../../mixin';
import validation from "../../../../config/validation";

export default {
    mixins: [mixins.get('formField')],
    data () {
        return {
            rules: [validation.required()]
        }
    },
}
</script>
<style scoped lang="scss">
</style>
