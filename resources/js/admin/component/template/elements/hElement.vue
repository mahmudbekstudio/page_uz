<template>
    <component
        v-if="params.number"
        :is="tag"
        :id="params.id"
        :class="params.class"
        :title="$t(params.title)"
        v-html="$t(params.content)"
    />
</template>
<script>
import mixins from '../../../mixin';

export default {
    mixins: [mixins.get('templateElement')],
    computed: {
        tag() {
            return 'h' + this.params.number;
        }
    }
}
</script>
<style scoped lang="scss"></style>
