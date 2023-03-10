<template>
    <v-radio-group
        v-bind="params"
        v-on="events"
        v-model="dataValue"
        :disabled="disabled"
    >
        <v-radio
            v-for="(item, key) in list"
            :key="key"
            :label="item"
            :value="key"
        ></v-radio>
    </v-radio-group>
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
