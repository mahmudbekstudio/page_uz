<template>
    <page-box
        class="module-post-list"
        :actions="actions"
    ></page-box>
</template>
<script>
import pageBox from '../../../view/partial/page-box';
import {getPageBoxAction} from "../../../helper";

export default {
    data() {
        return {
            actions: [],
        }
    },
    created() {
        this.actions.push(getPageBoxAction(this.$t('words.create'), '', {color: 'primary'}, {
            click: () => this.$router.push({name: 'type.create', params: {type: 'post'}})
        }));
    },
    mounted() {
        console.log('post listed');
    },
    components: {
        pageBox
    }
}
</script>
