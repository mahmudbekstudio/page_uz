<script>
import baseElement from "./type/baseElement.vue";

export default {
    extends: baseElement,
    computed: {
        innerText () {
            return this.$t(this.params.content.value);
        },
    },
}
</script>
<style scoped lang="scss"></style>
