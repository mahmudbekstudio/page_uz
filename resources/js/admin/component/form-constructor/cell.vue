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
import app from '../../service/app';
import { Field } from '../form/classes/form';

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
        },
        startDrag (e) {
            this.dragging = true;
        },
        changeList (item) {
            if (item.added) {
                const newItem = new Field({..._.cloneDeep(item.added.element.json), isConstructor: this.col.isConstructor});
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
            this.col.children.splice(itemIndex, 0, new Field(newItem));*/
        },
        elementDelete (item) {
            app.openConfirm('Do you really want to delete?', () => {
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
