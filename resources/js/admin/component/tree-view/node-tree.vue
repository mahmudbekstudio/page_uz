<template>
    <li class="node-tree">
        <span class="label" @click="clicked" :class="{'active-node': node.active}">
            <v-icon v-show="!node.loading">{{ getIcon(node) }}</v-icon>
            <v-progress-circular v-show="node.loading" indeterminate size="16" :width="2"></v-progress-circular>
            {{ $t(node.label) }}
        </span>
        <ul class="sub-tree-view" v-if="node.children && node.children.length && node.opened">
            <node v-for="(child, key) in node.children" :key="key" :node="child" @onClick="subItemClicked" :default-icon="defaultIcon" :opened-icon="openedIcon"></node>
        </ul>
    </li>
</template>
<script>
    export default {
        name: 'node',
        data: function() {
            return {
                //
            }
        },
        props: {
            node: {
                type: Object,
                default: {
                    label: '',
                    active: false,
                    loading: false,
                    opened: false,
                    children: []
                }
            },
            defaultIcon: {
                type: String,
                default: ''
            },
            openedIcon: {
                type: String,
                default: ''
            },
        },
        methods: {
            subItemClicked(node) {
                this.$emit('onClick', node);
            },
            clicked() {
                /*if(!(this.node.opened && !this.node.active) && this.node.children && this.node.children.length) {
                    this.node.opened = !this.node.opened;
                }*/
                this.$emit('onClick', this.node);
            },
            getIcon(node) {
                let icon = this.defaultIcon;
                if(this.node.opened && this.openedIcon && node.children && node.children.length) {
                    icon = this.openedIcon;
                }
                return icon;
            }
        }
    }
</script>
<style scoped lang="scss">
    .node-tree {
        list-style: none;
        padding: 0;
        .label {
            cursor: pointer;
            display: block;
            font-size: 15px;
            &.active-node,
            &:hover {
                background-color: #f5f5f5;
            }
            &.active-node {
                font-weight: bold;
            }
        }
    }
</style>
