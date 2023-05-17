<template>
    <v-switch
        v-bind="params"
        v-on="events"
        v-model="dataValue"
        :disabled="disabled"
        :hint="labelText"
        :label="params?.label ? $t(params.label) : null"
    ></v-switch>
</template>
<script>
import mixins from '../../../../mixin';

export default {
    mixins: [mixins.get('formField')],
    props: {
        //
    },
}
</script>
<style scoped lang="scss">
</style>
