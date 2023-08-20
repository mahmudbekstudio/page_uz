<template>
    <div
        :id="params.id"
        :class="params.class"
        :title="$t(params.title)"
        v-html="$t(params.content)"
    />
</template>
<script>
import mixins from '../../../../mixin';

export default {
    mixins: [mixins.get('templateElement')],
}
</script>
<style scoped lang="scss"></style>
