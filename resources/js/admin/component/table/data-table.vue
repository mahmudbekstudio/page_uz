<template>
    <div class="data-table">
        <v-data-table
            :value="params.selected"
            :loading="params.loading"
            :headers="params.headers"
            :items="params.items"
            :items-per-page="params.perPage"
            :options.sync="params.options"
            :server-items-length="params.totalCount"
            :show-select="params.showSelect"
            :item-key="params.itemKey"
            :page="params.page"
            :footer-props="{itemsPerPageOptions}"
            @input="inputChange($event)"
            @click:row="rowClick($event)"
            @dblclick:row="rowDblclick($event)"
            class="elevation-3"
            :class="{'row-clickable': rowClickable}"
        >
            <template v-slot:top>
                <slot name="filter" v-bind="params" />
            </template>
            <template v-for="col in params.headers" v-slot:[getSlotName(col)]="props">
                <slot :name="getSlotName(col)" v-bind="props">{{props.value}}</slot>
            </template>
        </v-data-table>
    </div>
</template>
<script>
import app from "../../service/app";
import http from "../../service/http";
import route from "../../api/route";
import viewSettings from '../../config/view';

export default {
    name: 'data-table',
    data() {
        return {
            tableCreated: false,
            itemsPerPageOptions: viewSettings.dataTable.itemsPerPageOptions,
            params: {
                route: '',
                routeNeedToken: false,
                options: {filter: {}},
                selected: [],
                loading: false,
                totalCount: -1,
                headers: [],
                items: [],
                perPage: viewSettings.dataTable.defaultItemsPerPage,
                showSelect: false,
                itemKey: 'id',
                page: 1,
            }
        }
    },
    watch: {
        'params.options': {
            handler (val) {
                this.tableCreated && this.$emit('update:options', val);
                this.loadItems();
            },
            deep: true,
        },
        route (val) {
            this.params.route = val;
            this.$nextTick(() => this.loadItems());
        },
        routeNeedToken (val) {
            this.params.routeNeedToken = val;
        },
        options: {
            handler (val) {
                this.params.options = val;
            },
            deep: true,
        },
        filter: {
            handler (val) {
                this.params.options.filter = val;
            },
            deep: true,
        },
        selected(val) {
            this.params.selected = val;
        },
        loading(val) {
            this.params.loading = val;
        },
        totalCount(val) {
            this.params.totalCount = val;
        },
        headers(val) {
            this.params.headers = val;
        },
        items(val) {
            this.params.items = val;
        },
        perPage(val) {
            this.params.perPage = val;
        },
        showSelect(val) {
            this.params.showSelect = val;
        },
        itemKey(val) {
            this.params.itemKey = val;
        },
        page(val) {
            this.params.page = val;
        }
    },
    created() {
        this.params = {
            route: this.route,
            routeNeedToken: this.routeNeedToken,
            options: this.options,
            selected: this.selected,
            loading: this.loading,
            totalCount: this.totalCount,
            headers: this.headers,
            items: this.items,
            perPage: this.perPage,
            showSelect: this.showSelect,
            itemKey: this.itemKey,
            page: this.page,
        };

        this.$nextTick(() => this.tableCreated = true);
        this.$emit('reloadCallback', () => this.loadItems());
    },
    props: {
        rowClickable: {
            type: Boolean,
            default () {
                return false;
            }
        },
        route: {
            type: String,
            default() {
                return '';
            }
        },
        routeNeedToken: {
            type: Boolean,
            default() {
                return false;
            }
        },
        options: {
            type: Object,
            default() {
                return {filter: {}};
            }
        },
        filter: {
            type: Object,
            default() {
                return {};
            }
        },
        selected: {
            type: Array,
            default() {
                return [];
            }
        },
        loading: {
            type: Boolean,
            default() {
                return false
            }
        },
        totalCount: {
            type: Number,
            default() {
                return -1
            }
        },
        headers: {
            type: Array,
            default() {
                return [];
            }
        },
        items: {
            type: Array,
            default() {
                return [];
            }
        },
        perPage: {
            type: Number,
            default() {
                return viewSettings.dataTable.defaultItemsPerPage;
            }
        },
        showSelect: {
            type: Boolean,
            default() {
                return false;
            }
        },
        itemKey: {
            type: String,
            default() {
                return 'id';
            }
        },
        page: {
            type: Number,
            default() {
                return 1
            }
        },
    },
    methods: {
        getSlotName (col) {
            return 'item.' + col.value
        },
        inputChange(val) {
            this.params.selected = val;
            this.$emit('input', val);
        },
        rowClick(val) {
            this.$emit('click:row', val);
        },
        rowDblclick(val) {
            this.$emit('dblclick:row', val);
        },
        loadItems () {
            if (this.params.loading) {
                return true;
            }

            this.params.loading = true;
            this.params.items = [];
            const apiRoute = {
                ...route.get(this.params.route),
                callback: function(data) {
                    this.params(data);
                },
                token: this.params.routeNeedToken
            };
            http(apiRoute)
                .callback(this.params.options)
                .send()
                .then(response => {
                    this.params.items = response.data.data.data;
                    this.params.totalCount = response.data.data.total;
                })
                .catch(error => {
                    this.$logger.error('data-table route:', this.params.route, error);
                    app.errorMessage('Error');
                }).then(() => this.params.loading = false);
        }
    }
}
</script>
<style lang="scss">
.data-table {
    .row-clickable {
        table tbody tr {
            cursor: pointer;
        }
    }
}
</style>
