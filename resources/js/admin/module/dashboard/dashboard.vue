<template>
    <page-box class="module-dashboard">
        <file-select v-model="files"></file-select>
        <v-btn
                text
                @click="showDialog = true"
        >
            Open
        </v-btn>
        <dialogComponent
                title="Testing dialog"
                v-model="showDialog"
                size="large"
                :actions="dialogActions"
                fullscreen
        >
            <div>Test1</div>
            <div>Test2</div>
            <div>Test3</div>
            <div>Test4</div>
            <div>Test5</div>
            <div>Test6</div>
            <div>Test7</div>
            <div>Test8</div>
            <div>Test9</div>
            <div>Test0</div>
            <div>Test1</div>
            <div>Test2</div>
            <div>Test3</div>
        </dialogComponent>
    </page-box>
</template>
<script>
    import dialogComponent from '../../component/dialog-component';
    import fileSelect from '../../component/file-select';
    import pageBox from '../../view/partial/page-box';
    export default {
        data() {
            return {
                files: [],
                showDialog: false,
                dialogActions: [
                    {
                        color: 'default',
                        text: 'words.cancel',
                        click: () => this.showDialog = false
                    },
                    {
                        color: 'primary',
                        text: 'words.save',
                        click: () => this.showDialog = false
                    },
                ]
            }
        },
        created() {
            //this.$logger.error('testing');
        },
        components: {
            dialogComponent,
            fileSelect,
            pageBox
        }
    }
</script>
