<template>
    <page-box
        class="module-type-list"
        :actions="actions"
    >
        <data-table
            :headers="headers"
            route="admin.type.list"
            route-need-token
            row-clickable
            :filter="filter"
            @click:row="clickRow"
            @reloadCallback="listReload($event)"
        >
            <template v-slot:filter="props">
                <v-container class="grey lighten-5">
                    <v-row no-gutters>
                        <v-col
                            cols="12"
                            sm="4"
                        >
                            <v-select
                                :items="statusOptions"
                                v-model="filter.status.value"
                                label="Status"
                            ></v-select>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="4"
                        >
                            <v-select
                                :items="typeOptions"
                                v-model="filter.type.value"
                                label="Type"
                            ></v-select>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="4"
                        >
                            <v-select
                                :items="parentOptions"
                                v-model="filter.has_parent.value"
                                label="Has parent"
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
            <template v-slot:item.status="props">
                <v-chip
                    :color="props.value ? 'green' : 'red'"
                    dark
                >
                    {{ $t('words.' + (props.value ? 'yes' : 'no')) }}
                </v-chip>
            </template>
            <template v-slot:item.has_parent="props">
                {{ $t('words.' + (props.value ? 'yes' : 'no')) }}
            </template>
            <template v-slot:item.child_of="props">
                {{ props.value || '-' }}
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
                filter: {
                    status: {condition: '=', value: ''},
                    type: {condition: '=', value: ''},
                    has_parent: {condition: '=', value: ''},
                },
                listReloadCallback: null,
                mainConfig: mainConfig,
            }
        },
        created() {
            this.actions.push(getPageBoxAction(this.$t('words.create_category'), '', {color: 'primary'}, {
                click: () => this.$router.push({name: 'type.create', params: {type: 'category'}})
            }));
            this.actions.push(getPageBoxAction(this.$t('words.create_post'), '', {color: 'primary'}, {
                click: () => this.$router.push({name: 'type.create', params: {type: 'post'}})
            }));

            this.headers = [
                { text: 'Id', value: 'id' },
                { text: 'Status', value: 'status' },
                { text: 'Name', value: 'name' },
                { text: 'Type', value: 'type' },
                { text: 'Has parent', value: 'has_parent' },
                { text: 'Child of', value: 'child_of' },
                { text: 'Created', value: 'created_at' },
                { text: 'Actions', value: 'actions' },
            ];
        },
        computed: {
            statusOptions () {
                return [
                    {text: 'Show all status', value: ''},
                    {text: this.$t('words.active'), value: 1},
                    {text: this.$t('words.not_active'), value: 0}
                ];
            },
            parentOptions () {
                return [
                    {text: 'Show all', value: ''},
                    {text: this.$t('words.yes'), value: 1},
                    {text: this.$t('words.no'), value: 0}
                ];
            },
            typeOptions () {
                return [
                    {text: 'Show all', value: ''},
                    {text: this.$t('words.post'), value: 'post'},
                    {text: this.$t('words.category'), value: 'category'}
                ];
            }
        },
        methods: {
            clickRow (row) {
                this.$router.push({name: 'type.edit', params: {id: row.id}})
            },
            listReload (listReloadCallback) {
                this.listReloadCallback = listReloadCallback;
            },
            clickDelete (item) {
                app.openConfirm('Do you really want to delete type "' + item.name + '"', () => {
                    this.$options.service.delete(item.id, response => {
                        if (response.result) {
                            this.listReloadCallback();
                            app.successMessage('Deleted');
                        } else {
                            app.errorMessage('Error');
                        }
                    });
                });
            }
        },
        components: {
            pageBox,
            dataTable,
        }
    }
</script>
