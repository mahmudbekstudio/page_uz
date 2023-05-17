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
import formConstructorHelper from '../../../helper/form-constructor'
// import elementIcon from './element-icon'

export default {
  props: {
    element: {
      type: Object,
      default () {
        return {};//formConstructorHelper.createElement()
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
