<template>
    <v-text-field
        v-bind="params"
        v-on="events"
        v-model="dataValue"
        :disabled="disabled"
        :hint="params?.hint ? $t(params.hint) : null"
        :label="labelText"
        :placeholder="params?.placeholder ? $t(params.placeholder) : null"
    ></v-text-field>
</template>
<script>
import mixins from '../../../../mixin';
import validation from "../../../../config/validation";

export default {
    mixins: [mixins.get('formField')],
}
</script>
<style scoped lang="scss">
</style>
