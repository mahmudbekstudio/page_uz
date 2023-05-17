<template>
    <div class="editor-field">
        <label class="field-label v-label theme--light">{{labelText}}</label>
        <editor
            v-bind="params"
            v-on="events"
            v-model="dataValue"
            :disabled="disabled"
            :class="className"
        ></editor>
    </div>
</template>
<script>
import mixins from '../../../mixin';
import editor from '../../editor';

export default {
    mixins: [mixins.get('formField')],
    props: {
        className: {
            type: String,
            default() {
                return '';
            }
        }
    },
    components: {
        editor
    }
}
</script>
<style scoped lang="scss"></style>
