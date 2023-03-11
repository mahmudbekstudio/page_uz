<template>
    <div>
        <label class="field-label v-label theme--light">{{params.label}}</label>
        <editor
            v-bind="params"
            v-on="events"
            v-model="dataValue"
            :disabled="disabled"
        ></editor>
    </div>
</template>
<script>
import mixins from '../../../mixin';
import editor from '../../editor';

export default {
    mixins: [mixins.get('formField')],
    props: {
        //
    },
    components: {
        editor
    }
}
</script>
<style scoped lang="scss"></style>
