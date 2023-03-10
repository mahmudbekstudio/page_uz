<template>
    <timepicker
        v-bind="params"
        v-on="events"
        v-model="dataValue"
        :disabled="disabled"
    />
</template>
<script>
import mixins from '../../../mixin';
import timepicker from "../../datetime/timepicker";

export default {
    mixins: [mixins.get('formField')],
    props: {
        //
    },
    components: {
        timepicker
    }
}
</script>
<style scoped lang="scss"></style>
