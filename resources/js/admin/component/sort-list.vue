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
                :class="{'list-is-empty': !list.length}"
            >
                <div
                    v-for="(item, index) in list"
                    :key="item.key"
                    class="list-group"
                >
                    <div class="list-item">
                        <v-input>
                            <v-icon slot="prepend" class="list-item-handle">mdi-menu</v-icon>
                            <v-btn-toggle mandatory slot="append">
                                <slot name="actions" :item="item" :index="index" :indexes="[...indexes, index]" />
                            </v-btn-toggle>
                            <slot name="title" :item="item">{{ item.title }}</slot>
                        </v-input>
                    </div>
                    <sort-list
                        v-model="item.children"
                        is-sub-list
                        @add="addElement({...$event})"
                        :indexes="[...indexes, index, 'children']"
                    >
                        <template v-slot:actions="{item, index, indexes}">
                            <slot name="actions" :item="item" :index="index" :indexes="[...indexes]"></slot>
                        </template>
                    </sort-list>
                </div>
            </transition-group>
        </draggable>
    </div>
</template>
<script>
import draggable from 'vuedraggable';
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
                handle: '.list-item-handle'
            };
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
        }
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
