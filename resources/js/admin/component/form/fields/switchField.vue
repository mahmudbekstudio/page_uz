<template>
    <v-switch
        v-bind="params"
        v-on="events"
        v-model="dataValue"
        :disabled="disabled"
        :hint="params?.hint ? $t(params.hint) : null"
        :label="labelText"
    ></v-switch>
</template>
<script>
import mixins from '../../../mixin';

export default {
    mixins: [mixins.get('formField')],
}
</script>
<style scoped lang="scss"></style>
