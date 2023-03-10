<template>
    <page-box
            class="module-type-form"
            :actions="actions"
            :footer-actions="actions"
    >
        <form-constructor v-model="formValue"></form-constructor>
    </page-box>
</template>
<script>
    import pageBox from '../../../view/partial/page-box';
    import { getPageBoxAction } from '../../../helper';
    import formConstructor from '../../../component/form-constructor/form-constructor';
    import { Form as FormClass } from '../../../component/form/classes/form';
    import { mapActions } from 'vuex';

    export default {
        data() {
            return {
                type: null,
                id: null,
                formValue: [],
                actions: []
            }
        },
        created() {
            this.type = this.$route.params?.type;
            this.id = this.$route.params?.id;
            if (this.type) {
                this.changeTitle(this.$t('words.create_' + this.type));
            }

            this.actions.push(getPageBoxAction(this.$t('words.back'), '', {color: 'default'}, {click: () => this.$router.push({name: 'type.list'})}));
            this.actions.push(getPageBoxAction(this.$t('words.' + (this.id ? 'update' : 'create')), '', {color: 'primary'}, {click: () => console.log('save clicked')}));
            this.formValue = new FormClass();
            this.formValue.addField({type: 'text', params: {label: 'Test1'}});
            this.formValue.addField({type: 'text', label: 'Test2'});
        },
        methods: {
            ...mapActions({
                changeTitle: 'view/changeTitle',
            })
        },
        components: {
            pageBox,
            formConstructor
        }
    }
</script>
