<template>
    <page-box
        class="module-post-list"
        :actions="actions"
    >
        <data-table
            :headers="headers"
            route="admin.post.list"
            :routeCallback="dataTableRouteCallback"
            route-need-token
            row-clickable
            :filter="filter"
            @click:row="clickRow"
            @reloadCallback="listReloadCallback = $event"
        >
            <template v-slot:item.title="props">
                {{ $t(props.value) }}
            </template>
            <template v-slot:item.status="props">
                <v-chip
                    :color="props.value ? 'green' : 'red'"
                    dark
                >
                    {{ $t('words.' + (props.value ? 'yes' : 'no')) }}
                </v-chip>
            </template>
            <template v-slot:item.parent="props">
                {{ $t(props.value) || '-' }}
            </template>
            <template v-slot:item.category="props">
                {{ $t(props.value) || '-' }}
            </template>
            <template v-slot:item.created_at="props">{{ $moment(props.value).format(mainConfig.app.timeFormat.full) }}</template>
            <template v-slot:item.actions="props">
                <v-btn
                    depressed
                    color="error"
                    @click.stop="clickDelete(props.item)"
                    v-if="canDelete(props.item)"
                >
                    {{ $t('words.delete') }}
                </v-btn>
            </template>
        </data-table>
    </page-box>
</template>
<script>
import pageBox from '../../../view/partial/page-box';
import {getPageBoxAction} from "../../../helper";
import dataTable from '../../../component/table/data-table';
import {mapGetters} from "vuex";
import mainConfig from '../../../config/main';
import app from "../../../service/app";
import Service from '../form/service';

export default {
    service: new Service(),
    data() {
        return {
            actions: [],
            headers: [],
            mainConfig: mainConfig,
            listReloadCallback: null,
            filter: {
                status: {condition: '=', value: ''},
            },
        }
    },
    created() {
        this.init();
    },
    computed: {
        ...mapGetters({
            typeNavigation: "view/typeNavigation",
            website: 'view/website',
        }),
        typeId() {
            return parseInt(this.$route.params.typeId);
        },
        cannotDeleteIds() {
            return [this.website.metas.page404, this.website.metas.pageHome];
        }
    },
    watch: {
        typeId() {
            this.init();
        },
    },
    methods: {
        init() {
            this.actions = [];
            this.actions.push(getPageBoxAction('words.create', '', {color: 'primary'}, {
                click: () => this.$router.push({name: 'post.create', params: {typeId: this.typeId}})
            }));

            this.headers = [
                { text: 'Id', value: 'id' },
                { text: 'words.title', value: 'title' },
                { text: 'words.status', value: 'status' },
                { text: 'words.parent', value: 'parent' },
                { text: 'words.category', value: 'category' },
                { text: 'words.template', value: 'template' },
                { text: 'words.created', value: 'created_at' },
                { text: 'words.actions', value: 'actions' },
            ].filter(item => this.hasCategory() || item.value !== 'category');

            this.listReloadCallback && this.listReloadCallback();
        },
        hasCategory() {
            for (const item of this.typeNavigation.filter(item => !!item.child_of)) {
                for (const childItem of item.children) {
                    if (parseInt(childItem.route.params.typeId) === this.typeId) {
                        return true;
                    }
                }
            }
            return false;
        },
        clickDelete (item) {
            app.openConfirm(this.$t('words.do_you_really_want_to_delete_post') + ' "' + item.title + '"', () => {
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
        dataTableRouteCallback(route) {
            route.urlParam('{type}', this.typeId);
        },
        clickRow (row) {
            this.$router.push({name: 'post.edit', params: {typeId: this.typeId, id: row.id}})
        },
        canDelete(item) {
            return this.cannotDeleteIds.indexOf(parseInt(item.id)) === -1
        }
    },
    components: {
        dataTable,
        pageBox
    }
}
</script>
