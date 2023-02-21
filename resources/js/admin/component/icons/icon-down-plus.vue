<template>
    <span class="icon-down-plus">
        <icon-plus class="icon-up-plus-p" />
        <icon-down class="icon-up-plus-u" />
    </span>
</template>

<script>
import IconPlus from './icon-plus'
import IconDown from './icon-down'

export default {
  components: {
    IconPlus,
    IconDown
  }
}
</script>

<style lang="scss" scoped>
    .icon-down-plus {
        position: relative;
        text-align: center;
        display: block;

        .icon-up-plus-p {
            width: 11px;
            position: absolute;
            margin-left: 5px;
            bottom: 3px;
        }

        .icon-up-plus-u {
            width: 21px;
            margin-bottom: 2px;
        }
    }
</style>
