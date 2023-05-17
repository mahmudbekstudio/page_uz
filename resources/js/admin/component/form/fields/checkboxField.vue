<template>
    <div>
        <label class="field-label v-label theme--light">{{labelText}}</label>
        <v-checkbox
            v-for="(item, key) in list"
            :key="key"
            v-bind="params"
            v-on="events"
            v-model="checkboxValues"
            :disabled="disabled"
            :label="$t(item)"
            :value="key"
            hide-details
            :hint="params?.hint ? $t(params.hint) : null"
            :placeholder="params?.placeholder ? $t(params.placeholder) : null"
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
        },
        checkboxValues: {
            get: function () {
                if (typeof this.dataValue === 'string') {
                    this.dataValue = this.dataValue.split(',').map(item => item.trim());
                }
                return this.dataValue;
            },
            set: function (newValue) {
                this.dataValue = newValue;
            }
        }
    }
}
</script>
<style scoped lang="scss">
.v-input--selection-controls {
    margin-top: 4px;
}
</style>
