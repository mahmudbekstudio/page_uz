<template>
    <page-box class="module-type-list" :actions="actions" :footer-actions="actions">
        <table-component
                :headers="headers"
                :items="items"
                show-select
        ></table-component>
    </page-box>
</template>
<script>
    import pageBox from '../../../view/partial/page-box';
    import tableComponent from '../../../component/table/table-component';
    import { getPageBoxAction } from '../../../helper';
    export default {
        data() {
            return {
                actions: [],
                headers: [
                    { text: 'Column 1', value: 'col1' },
                    { text: 'Column 2', value: 'col2' },
                    { text: 'Column 3', value: 'col3' },
                    { text: 'Column 4', value: 'col4' },
                ],
                items: [
                    { id: '1', col1: 'A1', col2: 'B', col3: 'C', col4: 'D' },
                    { id: '2', col1: 'A2', col2: 'B', col3: 'C', col4: 'D' },
                    { id: '3', col1: 'A3', col2: 'B', col3: 'C', col4: 'D' },
                    { id: '4', col1: 'A4', col2: 'B', col3: 'C', col4: 'D' },
                    { id: '5', col1: 'A5', col2: 'B', col3: 'C', col4: 'D' },
                    { id: '6', col1: 'A6', col2: 'B', col3: 'C', col4: 'D' },
                    { id: '7', col1: 'A7', col2: 'B', col3: 'C', col4: 'D' },
                    { id: '8', col1: 'A8', col2: 'B', col3: 'C', col4: 'D' },
                    { id: '9', col1: 'A9', col2: 'B', col3: 'C', col4: 'D' },
                    { id: 10, col1: 'A10', col2: 'B', col3: 'C', col4: 'D' },
                    { id: 11, col1: 'A11', col2: 'B', col3: 'C', col4: 'D' },
                    { id: 12, col1: 'A12', col2: 'B', col3: 'C', col4: 'D' },
                ]
            }
        },
        created() {
            //this.$logger.error('testing');
            this.actions.push(getPageBoxAction(this.$t('words.create'), '', {color: 'primary'}, {
                click: () => this.$router.push({name: 'type.create'})
            }));
        },
        components: {
            tableComponent,
            pageBox
        }
    }
</script>
