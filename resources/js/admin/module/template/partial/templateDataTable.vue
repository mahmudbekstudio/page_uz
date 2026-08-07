<template>
    <data-table
        :headers="headers"
        :route="route"
        route-need-token
        row-clickable
        @click:row="clickRow"
        @reloadCallback="listReloadCallback=$event"
    >
        <template v-slot:filter="props">
            <v-toolbar
                flat
                class="toolbar-header"
            >
                <v-toolbar-title>{{ title }}</v-toolbar-title>
                <v-divider
                    class="mx-4"
                    inset
                    vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-btn
                    depressed
                    color="primary"
                    @click="$router.push({name: 'template.create', params: {type}})"
                >
                    {{$t('words.create')}}
                </v-btn>
            </v-toolbar>
        </template>
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
import app from "../../../service/app";
import Service from "../js/service";
import mainConfig from '../../../config/main';

export default {
    service: new Service(),
    data () {
        return {
            listReloadCallback: null,
            mainConfig: mainConfig,
        };
    },
    props: {
        type: {
            type: String,
            default: () => 'post',
        },
        title: {
            type: String,
            default: () => 'Template',
        },
        route: {
            type: String,
            default: () => 'admin.template.list',
        },
        headers: {
            type: Array,
            default: () => [
                { text: 'Id', value: 'id', width: 80 },
                { text: 'words.name', value: 'name' },
                { text: 'words.created', value: 'created_at' },
                { text: '', value: 'actions', align: 'right' },
            ],
        }
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
