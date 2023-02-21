<template>
    <emptyLayout>
        <template slot="header">
            <navigationPartial></navigationPartial>
            <headerPartial></headerPartial>
        </template>
        <v-row
                align="start"
                justify="start"
        >
            <slot></slot>
        </v-row>
        <template slot="footer">
            <footerPartial></footerPartial>
        </template>
    </emptyLayout>
</template>
<script>
    import {mapActions} from 'vuex';
    import emptyLayout from './empty'
    import navigationPartial from '../partial/navigation';
    import headerPartial from '../partial/header';
    import footerPartial from '../partial/footer';

    export default {
        created() {
            this.changeContainerFillHeight(false);
        },
        methods: {
            ...mapActions({
                changeContainerFillHeight: 'view/changeContainerFillHeight'
            })
        },
        components: {
            emptyLayout,
            navigationPartial,
            headerPartial,
            footerPartial,
        }
    }
</script>
<style scoped lang="scss"></style>
