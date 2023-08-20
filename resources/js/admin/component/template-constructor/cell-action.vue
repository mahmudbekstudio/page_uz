<template>
    <div class="cell-action">
        <div class="cell-actions-list">
            <span @click="actionClicked('left')" class="action-ico"><icon-left /></span>
            <!--span @click="actionClicked('right')" class="action-ico"><icon-right /></span-->
            <span @click="actionClicked('edit')" class="action-ico"><icon-pen /></span>
        </div>
    </div>
</template>
<script>
    import {
        iconLeft,
        iconRight,
        iconPen
    } from '../icons';
    export default {
        props: {
            col: null,
            row: null
        },
        methods: {
            actionClicked (actionName) {
                if (actionName === 'edit') {
                    //
                } else if(this.col && this.row) {
                    let fromIndex = this.row.children.indexOf(this.col);
                    let toIndex = actionName === 'left' ? fromIndex - 1 : fromIndex + 1;

                    if(toIndex < 0 || fromIndex >= this.row.children.length) {
                        return false;
                    }

                    this.row.children.splice(fromIndex, 1);
                    this.row.children.splice(toIndex, 0, this.col);
                }

                this.$emit('actionClick', actionName)
            }
        },
        components: {
            iconPen,
            iconLeft,
            iconRight
        }
    }
</script>
