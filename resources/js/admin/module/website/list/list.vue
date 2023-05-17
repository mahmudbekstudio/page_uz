<template>
    <page-box>
        <data-table
            :headers="headers"
            route="admin.website.list"
            route-need-token
            row-clickable
            :filter="filter"
            @click:row="clickRow"
            @reloadCallback="listReload($event)"
        >
            <template v-slot:item.status="props">
                <v-chip
                    :color="getStatusColor(props.value)"
                    dark
                >
                    {{ $t('words.' + mainConfig.app.status.website[props.value]) }}
                </v-chip>
            </template>
        </data-table>
    </page-box>
</template>
<script>
import pageBox from '../../../view/partial/page-box';
import { getPageBoxAction } from "../../../helper";
import dataTable from '../../../component/table/data-table';
import mainConfig from "../../../config/main";

export default {
    data () {
        return {
            mainConfig: mainConfig,
            actionsList: [],
            headers: [],
            filter: {},
            listReloadCallback: null,
        }
    },
    created() {
        if(this.$route.query.userid) {
            /*this.$options.service.userById(this.$route.query.userid, response => {
                this.openDialog(response);
            });*/
        }
        const saveButton = getPageBoxAction('words.create', '', {color: 'primary'}, {
            click: () => {
                //this.openDialog();
            }
        });
        this.actionsList.push(saveButton);

        this.headers = [
            { text: 'Id', value: 'id' },
            { text: 'words.name', value: 'name' },
            { text: 'words.status', value: 'status' },
            { text: 'words.domain', value: 'domain' },
            { text: 'words.created', value: 'created_at' },
        ];
    },
    methods: {
        clickRow (row) {
            console.log(row);
        },
        listReload (listReloadCallback) {
            this.listReloadCallback = listReloadCallback;
        },
        getStatusColor (statusId) {
            const colors = [
                'silver',
                'green',
                'red',
                'silver',
                'silver',
                'red'
            ];
            return colors[statusId];
        },
    },
    components: {
        pageBox,
        dataTable,
    }
}
</script>
