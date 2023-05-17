<template>
    <div class="confirm-component">
        <dialog-component
                :title="title || $t('words.confirmation')"
                :actions="actions"
                v-model="show"
                size="small"
        >
            <div class="confirmation-question">{{question || $t('words.do_you_really_want')}}</div>
        </dialog-component>
    </div>
</template>
<script>
    import dialogComponent from './dialog-component';
    export default {
        name: 'confirm-component',
        data() {
            return {
                show: false,
                actions: []
            }
        },
        props: {
            value: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            title: {
                type: String,
                default() {
                    return '';
                }
            },
            question: {
                type: String,
                default() {
                    return '';
                }
            }
        },
        created() {
            this.show = this.value;
            this.actions.push({
                color: 'default',
                text: this.$t('words.no'),
                click: () => this.$emit('onNoClick')
            });
            this.actions.push({
                color: 'default',
                text: this.$t('words.yes'),
                click: () => this.$emit('onYesClick')
            });
        },
        watch: {
            show(val) {
                this.$emit('input', val);
                if(!val) {
                    this.$emit('onNoClick');
                }
            },
            value(val) {
                this.show = val;
            },
        },
        components: {
            dialogComponent
        }
    }
</script>
<style scoped lang="scss">
    .confirmation-question {
        padding-top: 20px;
    }
</style>
