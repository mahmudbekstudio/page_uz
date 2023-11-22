<template>
    <page-box>
        <list v-if="type === 'list'"></list>
    </page-box>
</template>
<script>
import pageBox from "../../view/partial/page-box.vue";
import list from './partial/list.vue';

export default {
    data () {
        return {
            type: null,
        }
    },
    created() {
        this.init();
    },
    methods: {
        init() {
            this.type = this.$route.params.type || 'list';
        }
    },
    watch: {
        '$route.fullPath'() {
            this.$nextTick(this.init);
        },
    },
    components: {
        list,
        pageBox
    }
}
</script>
