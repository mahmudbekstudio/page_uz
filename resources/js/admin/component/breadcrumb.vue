<template>
    <div class="breadcrumbs">
        <template v-for="(item, ind) in items">
            <div class="breadcrumbs__item" @click.prevent="(items.length > (ind + 1)) && itemClicked(item)" :class="{'breadcrumbs__item--last': items.length === (ind + 1)}">{{ item.title }}</div>
            <div class="breadcrumbs__divider" v-show="items.length > (ind + 1)">{{ divider }}</div>
        </template>
    </div>
</template>
<script>
    export default {
        data: function() {
            return {}
        },
        props: {
            items: {
                type: Array,
                default: []
            },
            divider: {
                type: String,
                default: '/'
            }
        },
        methods: {
            itemClicked(item) {
                this.$emit('click', item);
            }
        }
    }
</script>
<style scoped lang="scss">
    .breadcrumbs {
        &__item {
            display: inline-block;
            cursor: pointer;
            color: #1867c0;
            padding: 10px 0;
            &--last {
                cursor: default;
                color: rgba(0,0,0,0.38);
            }
        }
        &__divider {
            display: inline-block;
            padding: 6px;
        }
    }
</style>