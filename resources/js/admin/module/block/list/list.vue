<script>
import PostList from '../../post/list/list.vue';
import app from "../../../service/app";
import {getPageBoxAction} from "../../../helper";
import {mapGetters} from "vuex";

export default {
    extends: PostList,
    data () {
        return {
            //
        };
    },
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
            this.actions = [];
            this.actions.push(getPageBoxAction('words.create', '', {color: 'primary'}, {
                click: () => this.$router.push({name: 'block.create', params: {typeId: this.typeId}})
            }));

            this.headers = [
                { text: 'Id', value: 'id' },
                { text: 'words.title', value: 'title' },
                { text: 'words.status', value: 'status' },
                { text: 'words.created', value: 'created_at' },
                { text: 'words.actions', value: 'actions' },
            ];

            this.listReloadCallback && this.listReloadCallback();
        },
        clickDelete (item) {
            app.openConfirm(this.$t('words.do_you_really_want_to_delete_block') + ' "' + item.title + '"', () => {
                this.$options.service.delete(item.id, response => {
                    if (response.result) {
                        this.listReloadCallback();
                        app.successMessage(this.$t('words.deleted'));
                    } else {
                        app.errorMessage(this.$t('words.error'));
                    }
                });
            });
        },
        clickRow (row) {
            this.$router.push({name: 'block.edit', params: {typeId: this.typeId, id: row.id}})
        },
    }
}
</script>
