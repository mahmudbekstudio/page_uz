<template>
    <v-col
        class="constructor-col"
        cols="12"
        :md="col.size"
        :class="{dragging}"
        v-if="col"
    >
        <cell-action
            :col="col"
            :row="row"
        />
        <draggable
            class="constructor-field"
            v-model="col.children"
            v-bind="dragOptions"
            group="cell-element"
            @change="changeList"
            @start="startDrag($event)"
            @end="endDrag($event)"
        >
            <transition-group
                type="transition"
                class="transition-group"
            >
                <element-render
                    v-for="(element, index) in col.children"
                    :key="'element-render-' + element.id"
                    :element="element"
                    @actionEdit="elementEdit"
                    @actionCopy="elementCopy"
                    @actionDelete="elementDelete"
                    :style="element.style"
                />
            </transition-group>
        </draggable>
    </v-col>
</template>
<script>
import cellAction from "./cell-action";
import elementRender from "./element/element-render";
import draggable from 'vuedraggable';
import * as _ from 'lodash';

export default {
    data () {
        return {
            dragging: false,
            dragOptions: {
                animation: 200,
                group: 'description',
                disabled: false,
                ghostClass: 'ghost'
            },
        }
    },
    props: {
        col: null,
        row: null
    },
    methods: {
        endDrag (e) {
            this.dragging = false;
            console.log('endDrag', e);
        },
        startDrag (e) {
            this.dragging = true;
            console.log('dragStart', e);
        },
        changeList (item) {
            console.log('changeList', item);
            if (item.added) {
                this.col.children[item.added.newIndex] = _.cloneDeep(item.added.element);
                this.col.children = this.col.children.slice()
            }
        },
        elementEdit (item) {
            console.log('elementEdit item', item)
        },
        elementCopy (item) {
            console.log('elementCopy', item);
            /*const itemIndex = this.item.children.indexOf(item)
            this.item.children.splice(itemIndex, 0, Helpers.deepClone(item))*/
        },
        elementDelete (item) {
            console.log('elementDelete', item);
            /*if (confirm('Do you really want to delete?')) {
                const itemIndex = this.item.children.indexOf(item)
                this.item.children.splice(itemIndex, 1)
                this.$store.commit('setElement', null)
            }*/
        },
    },
    components: {
        cellAction,
        elementRender,
        draggable,
    }
}
</script>
