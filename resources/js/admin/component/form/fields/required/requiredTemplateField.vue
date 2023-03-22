<template>
    <v-select
        :items="[]"
        v-bind="params"
        v-on="events"
        :value="''"
        :disabled="disabled"
    ></v-select>
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
