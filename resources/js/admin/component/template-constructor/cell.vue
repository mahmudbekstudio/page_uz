<template>
    <v-col
        class="constructor-col"
        cols="12"
        :md="col.size"
        :class="{dragging}"
        v-if="col"
    >
        <cell-action
            v-if="row.children.length > 1"
            :col="col"
            :row="row"
            @actionClick="cellActionClick"
        />
        <draggable
            class="constructor-element"
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
                :class="{'cell-empty': !col.children.length}"
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
import app from '../../service/app';
import { Element } from '../template/classes/template';

export default {
    data () {
        return {
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
        row: null,
        dragging: {
            type: Boolean,
            default () {
                return false;
            }
        },
    },
    methods: {
        cellActionClick (actionName) {
            if (actionName === 'edit') {
                ///
            }
        },
        endDrag (e) {
            this.$emit('dragging', false);
        },
        startDrag (e) {
            this.$emit('dragging', true);
        },
        changeList (item) {
            if (item.added && !this.dragging) {
                const newItem = new Element({..._.cloneDeep(item.added.element.json), isConstructor: this.col.isConstructor});
                newItem.newId();
                this.col.children[item.added.newIndex] = newItem;
                this.col.children = [...this.col.children];
                //this.col.children = this.col.children.slice();
                this.$emit('add', {item: this.col.children[item.added.newIndex], col: this.col});
            }
        },
        elementEdit (item) {
            this.$emit('edit', {item, col: this.col});
        },
        elementCopy (item) {
            /*const itemIndex = this.col.children.indexOf(item)
            const newItem = _.cloneDeep(item);
            delete newItem.id;
            this.col.children.splice(itemIndex, 0, new Element(newItem));*/
        },
        elementDelete (item) {
            app.openConfirm(this.$t('words.do_you_really_want_to_delete'), () => {
                const itemIndex = this.col.children.indexOf(item);
                this.col.children.splice(itemIndex, 1);
            })
        },
    },
    components: {
        cellAction,
        elementRender,
        draggable,
    }
}
</script>
