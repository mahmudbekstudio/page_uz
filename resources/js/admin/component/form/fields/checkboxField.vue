<template>
    <div>
        <label class="field-label v-label theme--light">{{labelText}}</label>
        <v-checkbox
            v-for="item of list"
            :key="item.value"
            v-bind="params"
            v-on="events"
            v-model="checkboxValues"
            :disabled="disabled"
            :label="$t(item.text)"
            :value="item.value"
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
    created() {
        this.initDataValue(this.dataValue);
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

            return options;
        },
        checkboxValues: {
            get: function () {
                if (typeof this.dataValue === 'string') {
                    return this.dataValue.split(',').map(item => item.trim()).filter(item => !!item);
                }
                return this.dataValue;
            },
            set: function (newValue) {
                this.dataValue = newValue;
            }
        },
    },
    methods: {
        initDataValue(value) {
            if (typeof value === 'string') {
                this.dataValue = value.split(',').map(item => item.trim()).filter(item => !!item);
            }
        }
    },
    watch: {
        dataValue(value) {
            this.initDataValue(value);
        }
    }
}
</script>
<style scoped lang="scss">
.v-input--selection-controls {
    margin-top: 4px;
}
</style>
