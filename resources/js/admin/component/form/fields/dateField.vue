<template>
    <datepicker
        v-bind="params"
        v-on="events"
        v-model="dataValue"
        :disabled="disabled"
    />
</template>
<script>
import mixins from '../../../mixin';
import datepicker from "../../datetime/datepicker";

export default {
    mixins: [mixins.get('formField')],
    props: {
        //
    },
    components: {
        datepicker
    }
}
</script>
<style scoped lang="scss"></style>
