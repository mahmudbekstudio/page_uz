<template>
    <v-select
            :items="listItems"
            v-bind="params"
            v-on="events"
            v-model="dataValue"
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
                for(let key in this.params['options']) {
                    const item = this.params['options'][key];

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
            }
        }
    }
</script>