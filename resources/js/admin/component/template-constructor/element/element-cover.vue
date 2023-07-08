<template>
  <div class="element-cover">
    <div class="element-cover-wrapper">
      <!--<div class="element-cover-icon">
        <elementIcon :name="element.icon" />
      </div>-->
      <div class="element-cover-name">{{$t('words.components.' + elementName)}}</div>
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
      return this.element.title || 'element'
    }
  },
  components: {
    // elementIcon
  }
}
</script>
