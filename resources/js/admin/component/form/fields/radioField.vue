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
            v-for="item of list"
            :key="item.value"
            :label="$t(item.text)"
            :value="item.value"
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
            const options = this.params['options'];
            if (typeof options === 'string') {
                const optionsList = options.split("\n");
                const result = {};

                for (let item of optionsList) {
                    item = item.trim().split(':').map(item => item.trim());
                    if (item.length >= 2 && item[0] && item[1]) {
                        result[item[0]] = item[1];
                    }
                }

                return result;
            } else if(Array.isArray(options) && options.length && !options[0].text && !options[0].value) {
                const result = [];

                for (const optionKey in options) {
                    result.push({
                        value: optionKey,
                        text: options[optionKey]
                    });
                }

                return result;
            }

            return this.params['options'];
        }
    }
}
</script>
<style scoped lang="scss"></style>
