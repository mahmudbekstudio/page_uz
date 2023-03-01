<template>
    <div class="table-component">
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
                @input="inputChange($event)"
                @click:row="rowClick($event)"
                @dblclick:row="rowDblclick($event)"
                class="elevation-3"
        >
        </v-data-table>
    </div>
</template>
<script>
    export default {
        name: 'table-component',
        data() {
            return {
                tableCreated: false,
                params: {
                    options: {},
                    selected: [],
                    loading: false,
                    totalCount: -1,
                    headers: [],
                    items: [],
                    perPage: 10,
                    showSelect: false,
                    itemKey: 'id',
                    page: 1,
                }
            }
        },
        watch: {
            'params.options': {
                handler (val) {
                    this.tableCreated && this.$emit('change:options', val);
                },
                deep: true,
            },
            options: {
                handler (val) {
                    this.params.options = val;
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

            this.$nextTick(function () {
                this.tableCreated = true;
            })
        },
        props: {
            options: {
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
                    return 10;
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
            inputChange(val) {
                this.params.selected = val;
                this.$emit('input', val);
            },
            rowClick(val) {
                this.$emit('click:row', val);
            },
            rowDblclick(val) {
                this.$emit('dblclick:row', val);
            }
        }
    }
</script>
