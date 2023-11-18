<template>
    <data-table
        :headers="headers"
        route="admin.template.list"
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
</template>
<script>
import dataTable from '../../../component/table/data-table';
import mainConfig from '../../../config/main';
import app from "../../../service/app";
import Service from "../js/service";

export default {
    service: new Service(),
    data () {
        return {
            headers: [],
            mainConfig: mainConfig,
            listReloadCallback: null,
        }
    },
    created() {
        this.headers = [
            { text: 'Id', value: 'id' },
            { text: 'words.name', value: 'name' },
            { text: 'words.type', value: 'type' },
            { text: 'words.created', value: 'created_at' },
            { text: 'words.actions', value: 'actions' },
        ];
    },
    methods: {
        clickRow (row) {
            this.$router.push({name: 'template.edit', params: {id: row.id}});
        },
        clickDelete (item) {
            app.openConfirm(this.$t('words.do_you_really_want_to_delete_template') + ' "' + this.$t(item.name) + '"', () => {
                this.$options.service.delete(item.id, response => {
                    if (response.result) {
                        this.listReloadCallback();
                        app.successMessage(this.$t('words.deleted'));
                    } else {
                        app.errorMessage([this.$t('words.error'), ...response.message]);
                    }
                });
            });
        },
    },
    components: {
        dataTable
    }
}
</script>
