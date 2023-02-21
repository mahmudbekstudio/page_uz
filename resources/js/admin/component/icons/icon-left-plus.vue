<template>
    <span class="icon-left-plus">
        <icon-plus class="icon-left-plus-p" />
        <icon-left class="icon-left-plus-u" />
    </span>
</template>

<script>
import iconPlus from './icon-plus'
import iconLeft from './icon-left'

export default {
  components: {
      iconPlus,
      iconLeft
  }
}
</script>

<style lang="scss" scoped>
    .icon-left-plus {
        position: relative;
        text-align: center;
        display: block;

        .icon-left-plus-p {
            width: 11px;
            position: absolute;
            top: 5px;
        }

        .icon-left-plus-u {
            width: 21px;
            margin-left: 8px;
        }
    }
</style>
