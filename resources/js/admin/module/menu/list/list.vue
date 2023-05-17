<template>
    <page-box
        class="module-menu-list"
        :actions="actions"
    >
        <data-table
            :headers="headers"
            route="admin.menu.list"
            route-need-token
            row-clickable
            @click:row="clickRow"
            @reloadCallback="listReloadCallback=$event"
        >
            <template v-slot:item.created_at="props">{{ $moment(props.value).format(mainConfig.app.timeFormat.full) }}</template>
            <template v-slot:item.actions="props">
                <v-btn
                    depressed
                    color="error"
                    @click.stop="clickDelete(props.item)"
                >
                    {{ $t('words.delete') }}
                </v-btn>
            </template>
        </data-table>
    </page-box>
</template>
<script>
import pageBox from '../../../view/partial/page-box';
import { getPageBoxAction } from '../../../helper';
import dataTable from '../../../component/table/data-table';
import mainConfig from '../../../config/main';
import Service from "../form/service";
import app from '../../../service/app';

export default {
    service: new Service(),
    data() {
        return {
            actions: [],
            headers: [],
            listReloadCallback: null,
            mainConfig: mainConfig,
        }
    },
    created() {
        this.actions.push(getPageBoxAction('words.create', '', {color: 'primary'}, {
            click: () => this.$router.push({name: 'menu.create'})
        }));

        this.headers = [
            { text: 'Id', value: 'id' },
            { text: 'words.name', value: 'name' },
            { text: 'words.created', value: 'created_at' },
            { text: 'words.actions', value: 'actions' },
        ];
    },
    methods: {
        clickRow (row) {
            this.$router.push({name: 'menu.edit', params: {menu: row.id}})
        },
        clickDelete (item) {
            app.openConfirm(this.$('words.do_you_really_want_to_delete_menu') + ' "' + item.name + '"', () => {
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
    },
    components: {
        pageBox,
        dataTable,
    }
}
</script>
