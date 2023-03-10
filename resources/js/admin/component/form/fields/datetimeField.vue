<template>
    <datetimepicker
        v-bind="params"
        v-on="events"
        v-model="dataValue"
        :disabled="disabled"
    />
</template>
<script>
import mixins from '../../../mixin';
import datetimepicker from "../../datetime/datetimepicker";

export default {
    mixins: [mixins.get('formField')],
    props: {
        //
    },
    components: {
        datetimepicker
    }
}
</script>
<style scoped lang="scss"></style>
