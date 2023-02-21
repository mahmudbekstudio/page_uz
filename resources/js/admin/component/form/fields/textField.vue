<template>
    <v-text-field
            v-bind="params"
            v-on="events"
            v-model="dataValue"
            :disabled="disabled"
    ></v-text-field>
</template>
<script>
    import mixins from '../../../mixin';

    export default {
        mixins: [mixins.get('formField')],
        props: {
            //
        }
    }
</script>
<style scoped lang="scss"></style>
