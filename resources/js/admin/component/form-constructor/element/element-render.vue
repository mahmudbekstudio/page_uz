<template>
  <div class="element-render">
    <div class="element-render-actions">
      <!--span @click="actionClicked('copy')" class="action-ico"><icon-copy /></span-->
      <span v-if="element.field.hasFillable" @click="actionClicked('edit')" class="action-ico"><icon-pen></icon-pen></span>
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
        return {};
      }
    }
  },
  methods: {
    actionClicked (actionName) {
      if (actionName === 'edit') {
        this.$emit('actionEdit', this.element)
      } else if (actionName === 'copy') {
        this.$emit('actionCopy', this.element)
      } else if (actionName === 'delete') {
        this.$emit('actionDelete', this.element)
      }
    }
  },
  computed: {
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
