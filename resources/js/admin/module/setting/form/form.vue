<script>
import PostForm from '../../post/form/form.vue';
import route from "../../../plugin/route";
import {getPageBoxAction} from "../../../helper";
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
        init() {
            this.formValue = null;
            this.actions = [];
            this.actions.push(getPageBoxAction('words.update', '', {color: 'primary', disabled: false}, {
                click: this.save
            }));
            this.loadType();
        },
        back(response = {}) {
            const params = {typeId: this.$options.service.typeId};
            if (response?.data?.post?.id) {
                params['id'] = response.data.post.id;

                const typeNavigation = this.$store.getters['view/typeNavigation'];
                for (const item of typeNavigation) {
                    if (item.childrenOf && item.childrenOf === 'settings' && !item.route.params.id && item.route.params.typeId === this.$options.service.typeId) {
                        item.route.params.id = response.data.post.id;
                    }
                }

                this.$store.dispatch('view/changeTypeNavigation', typeNavigation);
            }
            this.$router.push({name: 'setting.edit', params: params});
        }
    }
}
</script>
