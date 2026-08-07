<template>
    <div class="page-box-wrapper">
        <page-header :title="headerTitle" :actions="actions">
            <template #actions><slot name="actions"></slot></template>
        </page-header>
        <div class="page-box">
            <slot></slot>
        </div>
        <page-footer :actions="footerActions"><span v-html="footerText"></span></page-footer>
    </div>
</template>
<script>
    import pageHeader from './page-header';
    import pageFooter from './page-footer';

    export default {
        props: {
            headerTitle: {
                type: String,
                default: () => null
            },
            actions: {
                type: Array,
                default: () => []
            },
            footerActions: {
                type: Array,
                default: () => []
            },
            footerText: {
                type: String,
                default: ''
            }
        },
        components: {
            pageHeader,
            pageFooter
        }
    }
</script>
