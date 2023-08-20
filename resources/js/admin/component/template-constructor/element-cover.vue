<template>
  <div class="element-cover">
    <div class="element-cover-wrapper">
      <div class="element-cover-name">{{$t('words.components.tag_' + elementTag, {name: elementName})}}</div>
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
    elementTag () {
      return this.element.tag || 'unknown';
    },
      elementName () {
        return this.element.name || '';
      }
  },
}
</script>
