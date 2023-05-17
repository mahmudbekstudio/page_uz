<template>
    <v-radio-group
        v-bind="params"
        v-on="events"
        v-model="dataValue"
        :disabled="disabled"
        :hint="params?.hint ? $t(params.hint) : null"
        :label="labelText"
    >
        <v-radio
            v-for="(item, key) in list"
            :key="key"
            :label="$t(item)"
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
            if (typeof this.params['options'] === 'string') {
                const options = this.params['options'].split("\n");
                const result = {};

                for (let item of options) {
                    item = item.trim().split(':').map(item => item.trim());
                    if (item.length >= 2 && item[0] && item[1]) {
                        result[item[0]] = item[1];
                    }
                }

                return result;
            }

            return this.params['options'];
        }
    }
}
</script>
<style scoped lang="scss"></style>
