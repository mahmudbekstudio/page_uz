<template>
    <v-select
        :items="listItems"
        v-bind="params"
        v-on="events"
        v-model="dataValue"
        :disabled="disabled"
        :clearable="clearable"
        @click:clear="clear"
        :hint="params?.hint ? $t(params.hint) : null"
        :label="labelText"
        :placeholder="params?.placeholder ? $t(params.placeholder) : null"
        :no-data-text="$t('words.no-data-available')"
    ></v-select>
</template>
<script>
    import mixins from '../../../mixin';

    export default {
        mixins: [mixins.get('formField')],
        computed: {
            clearable () {
                return typeof this.params.clearable !== 'undefined' ? !!this.params.clearable : true;
            },
            listItems() {
                const result = [];
                let isFirst = true;
                for(let key in this.optionsParam) {
                    const item = this.optionsParam[key];

                    if(typeof item === 'object') {
                        if(typeof item.text !== 'undefined' && typeof item.value !== 'undefined') {
                            result.push({text: this.$t(item.text), value: item.value});
                        } else {
                            if(!isFirst) {
                                result.push({divider: true});
                            }

                            isFirst = false;
                            result.push({header: this.$t(key)});

                            for(let subKey in item) {
                                result.push({text: this.$t(item[subKey]), value: subKey});
                            }
                        }
                    } else {
                        result.push({text: this.$t(item), value: key});
                    }
                }
                return result;
            },
            optionsParam () {
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
            dataValue: {
                get: function () {
                    if (this.params.multiple && typeof this.value === 'string') {
                        return this.value.split(',').map(item => item.trim());
                    }

                    return this.value !== null && !this.params.multiple ? String(this.value) : this.value;
                },
                set: function (newValue) {
                    this.$emit('input', newValue);
                }
            }
        },
        methods: {
            clear() {
                this.$nextTick(() => {
                    this.dataValue = this.params.defaultObject.value;
                });
            },
        }
    }
</script>
