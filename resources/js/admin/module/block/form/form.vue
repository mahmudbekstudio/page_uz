<script>
import PostForm from '../../post/form/form.vue';
import {mapGetters} from "vuex";

export default {
    extends: PostForm,
    computed: {
        ...mapGetters({
            viewTitle: "view/title",
        }),
        headerTitle () {
            return this.$t(this.viewTitle) + ': ' + this.$t(this.activeNavigation.text);
        },
    },
    methods: {
        back() {
            this.$router.push({name: 'block.list', params: {typeId: this.$options.service.typeId}});
        }
    }
}
</script>
