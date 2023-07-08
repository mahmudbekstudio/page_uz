<template>
  <div class="element-cover">
    <div class="element-cover-wrapper">
      <div class="element-cover-name">{{$t('words.components.tag_' + elementName)}}</div>
    </div>
  </div>
</template>
<script>

export default {
  props: {
    element: {
      type: Object,
      default () {
        return {};
      }
    }
  },
  computed: {
    elementName () {
      return this.element.tag || 'unknown'
    }
  },
}
</script>
