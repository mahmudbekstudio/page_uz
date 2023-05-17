<template>
    <v-text-field
            v-bind="params"
            v-on="events"
            v-model="dataValue"
            :disabled="disabled"
            :hint="params?.hint ? $t(params.hint) : null"
            :label="labelText"
            :placeholder="params?.placeholder ? $t(params.placeholder) : null"
            :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
            :type="showPassword ? 'text' : 'password'"
            @click:append="showPassword = !showPassword"
    ></v-text-field>
</template>
<script>
    import mixins from '../../../mixin';

    export default {
        mixins: [mixins.get('formField')],
        data() {
            return {
                showPassword: false
            };
        },
        props: {
            //
        }
    }
</script>
<style scoped lang="scss"></style>
