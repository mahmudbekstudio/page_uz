<template>
    <hr
        :id="params.id"
        :class="params.class"
        :title="$t(params.title)"
    />
</template>
<script>
import mixins from '../../../mixin';

export default {
    mixins: [mixins.get('templateElement')],
}
</script>
<style scoped lang="scss"></style>
