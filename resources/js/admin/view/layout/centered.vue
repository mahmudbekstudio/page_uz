<template>
    <emptyLayout>
        <v-row
                align="center"
                justify="center"
        >
            <slot></slot>
        </v-row>
    </emptyLayout>
</template>
<script>
    import emptyLayout from './empty'
    import {mapActions} from 'vuex';

    export default {
        created() {
            this.changeContainerFillHeight(true);
        },
        methods: {
            ...mapActions({
                changeContainerFillHeight: 'view/changeContainerFillHeight'
            })
        },
        components: {
            emptyLayout,
        }
    }
</script>
<style scoped lang="scss"></style>
