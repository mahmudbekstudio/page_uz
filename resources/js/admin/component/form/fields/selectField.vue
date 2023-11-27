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
    import mainConfig from '../../../config/main';

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

                    if(typeof item === 'object' && !this.objectIsTranslation(item)) {
                        if(typeof item.text !== 'undefined' && typeof item.value !== 'undefined') {
                            const prefix = item.prefix ? item.prefix + ' ' : '' ;
                            result.push({text: prefix + this.$t(item.text), value: this.valueByType(item.value)});
                        } else {
                            if(!isFirst) {
                                result.push({divider: true});
                            }

                            isFirst = false;
                            result.push({header: this.$t(key)});

                            for(let subKey in item) {
                                result.push({text: this.$t(item[subKey]), value: this.valueByType(subKey)});
                            }
                        }
                    } else {
                        result.push({text: this.$t(item), value: this.valueByType(key)});
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

                    return this.valueByType(this.value);
                },
                set: function (newValue) {
                    this.$emit('input', newValue);
                }
            }
        },
        methods: {
            objectIsTranslation(item) {
                const keys = Object.keys(item);

                for (const key of keys) {
                    if (mainConfig.lang.list.indexOf(key) === -1) {
                        return false;
                    }
                }

                return true;
            },
            valueByType(value) {
                if (value === null) {
                    return null;
                }

                if (this.params.multiple) {
                    return value;
                }

                if (this.params.valueType === 'int') {
                    return parseInt(value);
                }

                if (this.params.valueType === 'string') {
                    return String(value);
                }

                return value;
            },
            clear() {
                this.$nextTick(() => {
                    this.dataValue = this.params.defaultObject.value;
                });
            },
        }
    }
</script>
