<template>
    <span class="icon-right-plus">
        <icon-plus class="icon-right-plus-p" />
        <icon-right class="icon-right-plus-u" />
    </span>
</template>

<script>
import iconPlus from './icon-plus'
import iconRight from './icon-right'

export default {
  components: {
      iconPlus,
      iconRight
  }
}
</script>

<style lang="scss" scoped>
    .icon-right-plus {
        position: relative;
        text-align: center;
        display: block;

        .icon-right-plus-p {
            width: 11px;
            position: absolute;
            top: 5px;
            right: 0;
        }

        .icon-right-plus-u {
            width: 21px;
            margin-right: 8px;
        }
    }
</style>
