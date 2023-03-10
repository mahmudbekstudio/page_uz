<template>
    <div>
        <v-checkbox
            v-for="(item, key) in list"
            :key="key"
            v-bind="params"
            v-on="events"
            v-model="dataValue"
            :disabled="disabled"
            :label="item"
            :value="key"
            hide-details
        ></v-checkbox>
    </div>
</template>
<script>
import mixins from '../../../mixin';

export default {
    mixins: [mixins.get('formField')],
    props: {
        //
    },
    computed: {
        list () {
            return this.params['options'];
        }
    }
}
</script>
<style scoped lang="scss"></style>
