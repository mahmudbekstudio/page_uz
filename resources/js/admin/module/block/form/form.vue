<script>
import PostForm from '../../post/form/form.vue';

export default {
    extends: PostForm,
    methods: {
        back() {
            this.$router.push({name: 'block.list', params: {typeId: this.$options.service.typeId}});
        }
    }
}
</script>
