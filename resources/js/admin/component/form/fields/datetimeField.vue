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
    created() {
        this.initLabel(this.params.label);
    },
    watch:{
        'params.label' (val) {
            this.initLabel(val);
        }
    },
    methods: {
        initLabel(label) {
            if (typeof label === 'string') {
                this.params.label = label.split(',')
            }
        }
    },
    components: {
        datetimepicker
    }
}
</script>
<style scoped lang="scss"></style>
