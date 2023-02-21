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
    export default {
        data() {
            return {
                formValue: [],
                actions: []
            }
        },
        created() {
            this.actions.push(getPageBoxAction(this.$t('words.back'), '', {color: 'default'}, {click: () => this.$router.push({name: 'type.list'})}));
            this.actions.push(getPageBoxAction(this.$t('words.save'), '', {color: 'primary'}, {click: () => console.log('save clicked')}));
            this.formValue = new FormClass();
        },
        components: {
            pageBox,
            formConstructor
        }
    }
</script>