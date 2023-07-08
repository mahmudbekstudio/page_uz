<template>
    <div class="sort-list-component">
        <draggable
            v-model="list"
            v-bind="dragOptions"
            :class="{'list-wrap': !isSubList, 'is-sub-list': isSubList}"
            @add="addElement({event: $event, indexes: [...indexes, $event.newIndex]})"
        >
            <transition-group
                type="transition"
                class="transition-group"
                :class="{'list-is-empty': !list.length, 'is-sortable-list': isSortableList}"
            >
                <div
                    v-for="(item, index) in list"
                    :key="item.key"
                    class="list-group"
                >
                    <div class="list-item" :class="{'list-item-active': item.isActive}">
                        <v-input>
                            <v-icon v-if="!item.notSort" slot="prepend" class="list-item-handle">mdi-menu</v-icon>
                            <v-btn-toggle mandatory slot="append">
                                <slot name="actions" :item="item" :index="index" :indexes="[...indexes, index]" />
                            </v-btn-toggle>
                            <slot name="title" :item="item">{{ $t(item.title) }}</slot>
                        </v-input>
                    </div>
                    <sort-list
                        disabled
                        v-if="item.canHasChild || item.children.length"
                        v-model="item.children"
                        is-sub-list
                        @add="addElement({...$event})"
                        :indexes="[...indexes, index, 'children']"
                    >
                        <template v-slot:actions="{item, index, indexes}">
                            <slot name="actions" :item="item" :index="index" :indexes="[...indexes]"></slot>
                        </template>
                        <template v-slot:append-item="{value, indexes}">
                            <slot name="append-item" :value="value" :indexes="[...indexes]"></slot>
                        </template>
                    </sort-list>
                </div>
            </transition-group>
        </draggable>
        <div :class="{'is-sub-list': isSubList}">
            <slot name="append-item" :value="value" :indexes="[...indexes]"></slot>
        </div>
    </div>
</template>
<script>
import draggable from 'vuedraggable';
//import TransitionGroup from "vue/src/platforms/web/runtime/components/transition-group";
export default {
    name: 'sort-list',
    data() {
        return {
            //
        }
    },
    props: {
        value: {
            type: Array,
            default () {
                return [];
            }
        },
        isSubList: {
            type: Boolean,
            default () {
                return false;
            }
        },
        indexes: {
            type: Array,
            default () {
                return [];
            }
        }
    },
    computed: {
        dragOptions() {
            return {
                animation: 200,
                group: 'menu-list',
                disabled: false,
                ghostClass: "ghost",
                handle: '.list-item-handle',
                move: e => this.moveElement(e)
            };
        },
        isSortableList() {
            if (!this.list.length) {
                return true;
            }
            for (const item of this.list) {
                if (!item.notSort) {
                    return true;
                }
            }
            return false;
        },
        list: {
            get() {
                let k = 0;
                let changed = false;
                const list = this.value.map(item => {
                    k++;
                    if (!item.key) {
                        item = {...item};
                        item.key = (new Date()).getTime() + k;
                        changed = true;
                    }

                    if (!item.children) {
                        item = {...item};
                        item.children = [];
                        changed = true;
                    }

                    return item;
                });

                if (changed) {
                    this.$emit('input', list);
                }

                return list;
            },
            set(value) {
                this.$emit('input', value);
            }
        }
    },
    methods: {
        addElement(e) {
            this.$emit('add', e);
        },
        moveElement(e) {
            return e.to.classList.contains('is-sortable-list');
        },
    },
    components: {
        draggable,
    }
}
</script>
<style lang="scss">
.sort-list-component {
    .v-messages {
        display: none;
    }

    .list-item {
        box-shadow: 0 3px 1px -2px rgba(0,0,0,.2), 0 2px 2px 0 rgba(0,0,0,.14), 0 1px 5px 0 rgba(0,0,0,.12);
        border-radius: 4px;
        padding: 4px 10px;
        margin: 5px 0 0 0;

        &.list-item-active {
            outline: 2px solid #068eef;
        }

        .v-input__slot {
            margin: 4px 0;
        }

        .list-item-handle {
            cursor: all-scroll;
        }
    }

    .list-is-empty {
        height: 40px;
        background-color: #fcf9f9;
        display: block;
    }

    .list-wrap {
        padding: 5px;
        border: 1px dashed #000;
    }

    .is-sub-list {
        padding-left: 45px;
        position: relative;

        .list-is-empty {
            min-height: 10px;
            height: auto;
            border-top: 5px solid #AAA;
            background-color: transparent;
            display: block;
            margin: 0;
        }
    }
}
</style>
