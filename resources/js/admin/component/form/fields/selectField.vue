<template>
    <v-select
        :items="listItems"
        v-bind="params"
        v-on="events"
        v-model="selectedValues"
        :disabled="disabled"
    ></v-select>
</template>
<script>
    import mixins from '../../../mixin';

    export default {
        mixins: [mixins.get('formField')],
        computed: {
            listItems() {
                const result = [];
                let isFirst = true;
                for(let key in this.optionsParam) {
                    const item = this.optionsParam[key];

                    if(typeof item === 'object') {
                        if(!isFirst) {
                            result.push({divider: true});
                        }

                        isFirst = false;
                        result.push({header: key});

                        for(let subKey in item) {
                            result.push({text: item[subKey], value: subKey});
                        }
                    } else {
                        result.push({text: item, value: key});
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
            selectedValues: {
                get: function () {
                    if (this.params.multiple && typeof this.dataValue === 'string') {
                        return this.dataValue.split(',').map(item => item.trim());
                    }
                    return this.dataValue;
                },
                set: function (newValue) {
                    this.dataValue = newValue;
                }
            }
        },
    }
</script>
