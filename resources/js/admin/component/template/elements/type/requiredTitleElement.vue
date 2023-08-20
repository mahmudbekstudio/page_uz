<template>
    <div v-text="'{ $' + name + ' }'"></div>
</template>
<script>
import mixins from '../../../../mixin';

export default {
    mixins: [mixins.get('templateElement')],
}
</script>
<style scoped lang="scss"></style>
