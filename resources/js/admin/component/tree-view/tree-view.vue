<template>
    <div class="tree-view">
        <ul class="tree-list">
            <TreeNode v-for="(item, key) in items" :node="item" :key="key" @onClick="clicked" :default-icon="defaultIcon" :opened-icon="openedIcon"></TreeNode>
        </ul>
    </div>
</template>
<script>
    import TreeNode from './node-tree';
    export default {
        data: function() {
            return {
                //
            }
        },
        props: {
            items: {
                type: Array,
                default: []
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
            clicked(node) {
                this.$emit('onClick', node);
            }
        },
        components: {
            TreeNode
        }
    }
</script>
<style scoped lang="scss">
    .tree-view {
        padding: 10px;
        .tree-list {
            padding: 0;
        }
    }
</style>