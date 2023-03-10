<template>
  <div class="element-render" :class="{'active': isActive}">
    <div class="element-render-actions">
      <span @click="actionClicked('copy')" class="action-ico"><icon-copy /></span>
      <span @click="actionClicked('edit')" class="action-ico"><icon-pen></icon-pen></span>
      <span @click="actionClicked('delete')" class="action-ico"><icon-delete></icon-delete></span>
    </div>
    <div class="element-render-cover" />
      <field-component
          :type="element.type"
          :value="element.value"
          :params="element.params"
      /></div>
</template>
<script>
//import helpers from '../../helpers'
import {
  iconDelete,
  iconPen,
  iconCopy
} from '../../icons';
import fieldComponent from "../../form/field-component";

export default {
  data () {
    return {
      //
    }
  },
  props: {
    element: {
      type: Object,
      default () {
        return {};//helpers.createElement()
      }
    }
  },
  methods: {
    elementEditClicked () {
      if (this.isActive) {
        this.$store.commit('setElement', null)
      } else {
        this.$store.commit('setElement', null)
        this.$nextTick(() => {
          this.$store.commit('setElement', this.element)
        })
      }
    },
    actionClicked (actionName) {
      if (actionName === 'edit') {
        this.elementEditClicked()
        this.$emit('actionEdit', this.element)
      } else if (actionName === 'copy') {
        this.$emit('actionCopy', this.element)
      } else if (actionName === 'delete') {
        this.$emit('actionDelete', this.element)
      }
    }
  },
  computed: {
    isActive () {
      return this.$store.state.element === this.element
    },
    elementName () {
      return this.element.name || 'Element'
    }
  },
  components: {
      iconDelete,
      iconPen,
      iconCopy,
      fieldComponent,
  }
}
</script>
