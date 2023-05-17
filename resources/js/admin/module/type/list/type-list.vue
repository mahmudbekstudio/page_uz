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
            <template v-for="col in headers" v-slot:[getHeaderSlotName(col)]="prop">
                {{$t(prop.text)}}
            </template>
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
                                :label="$t('words.status')"
                            ></v-select>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="4"
                        >
                            <v-select
                                :items="typeOptions"
                                v-model="filter.type.value"
                                :label="$t('words.type')"
                            ></v-select>
                        </v-col>
                        <v-col
                            cols="12"
                            sm="4"
                        >
                            <v-select
                                :items="parentOptions"
                                v-model="filter.has_parent.value"
                                :label="$t('words.has_parent')"
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-container>
            </template>
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
            this.actions.push(getPageBoxAction('words.create_category', '', {color: 'primary'}, {
                click: () => this.$router.push({name: 'type.create', params: {type: 'category'}})
            }));
            this.actions.push(getPageBoxAction('words.create_post', '', {color: 'primary'}, {
                click: () => this.$router.push({name: 'type.create', params: {type: 'post'}})
            }));

            this.headers = [
                { text: 'Id', value: 'id' },
                { text: 'words.title', value: 'title' },
                { text: 'words.status', value: 'status' },
                { text: 'words.name', value: 'name' },
                { text: 'words.type', value: 'type' },
                { text: 'words.has_parent', value: 'has_parent' },
                { text: 'words.child_of', value: 'child_of' },
                { text: 'words.created', value: 'created_at' },
                { text: 'words.actions', value: 'actions' },
            ];
        },
        computed: {
            statusOptions () {
                return [
                    {text: this.$t('words.show_all'), value: ''},
                    {text: this.$t('words.active'), value: 1},
                    {text: this.$t('words.not_active'), value: 0}
                ];
            },
            parentOptions () {
                return [
                    {text: this.$t('words.show_all'), value: ''},
                    {text: this.$t('words.yes'), value: 1},
                    {text: this.$t('words.no'), value: 0}
                ];
            },
            typeOptions () {
                return [
                    {text: this.$t('words.show_all'), value: ''},
                    {text: this.$t('words.post'), value: 'post'},
                    {text: this.$t('words.category'), value: 'category'}
                ];
            }
        },
        methods: {
            getHeaderSlotName(item) {
                return 'header.' + item.value;
            },
            clickRow (row) {
                this.$router.push({name: 'type.edit', params: {id: row.id}})
            },
            listReload (listReloadCallback) {
                this.listReloadCallback = listReloadCallback;
            },
            clickDelete (item) {
                app.openConfirm(this.$t('words.do_you_really_want_to_delete_type') + ' "' + this.$t(item.title) + '"', () => {
                    this.$options.service.delete(item.id, response => {
                        if (response.result) {
                            this.listReloadCallback();
                            app.successMessage(this.$t('words.deleted'));
                        } else {
                            app.errorMessage(this.$t('words.error'));
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
