<template>
    <span class="icon-up-plus">
        <icon-plus class="icon-up-plus-p" />
        <icon-up class="icon-up-plus-u" />
    </span>
</template>

<script>
import IconPlus from './icon-plus'
import IconUp from './icon-up'

export default {
  components: {
    IconPlus,
    IconUp
  }
}
</script>

<style lang="scss" scoped>
    .icon-up-plus {
        position: relative;
        text-align: center;
        display: block;

        .icon-up-plus-p {
            width: 11px;
            position: absolute;
            margin-left: 5px;
            top: 0;
        }

        .icon-up-plus-u {
            width: 21px;
            margin-top: 6px;
        }
    }
</style>
