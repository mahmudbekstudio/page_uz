<template>
    <page-box
        class="module-post-list"
        :actions="actions"
    >
        <form-component
            v-if="formValue"
            :value="formValue"
        ></form-component>
    </page-box>
</template>
<script>
import pageBox from '../../../view/partial/page-box';
import {getPageBoxAction} from "../../../helper";
import Service from "./service";
import formComponent from '../../../component/form/form-component.vue';
import { Form as FormClass } from '../../../component/form/classes/form';

export default {
    service: new Service(),
    data() {
        return {
            actions: [],
            formValue: null,
        }
    },
    created() {
        this.init();
    },
    computed: {
        typeId() {
            return this.$route.params.typeId;
        },
        id() {
            return this.$route.params?.id;
        },
        isEdit() {
            return !!this.id;
        }
    },
    watch: {
        typeId() {
            this.init();
        }
    },
    methods: {
        init() {
            this.actions = [];
            this.formValue = null;
            this.actions.push(getPageBoxAction(this.$t('words.back'), '', {color: 'default'}, {
                click: this.back
            }));
            this.actions.push(getPageBoxAction(this.$t('words.' + (this.isEdit ? 'update' : 'create')), '', {color: 'primary'}, {
                click: this.save
            }));

            this.loadType();
        },
        loadType() {
            this.$options.service.get(this.typeId, response => {
                this.formValue = new FormClass(response.data.type.structure);
            })
        },
        save() {
            console.log(this.formValue.getFieldValues())
        },
        back() {
            this.$router.push({name: 'post.list', params: {typeId: this.typeId}});
        }
    },
    components: {
        pageBox,
        formComponent,
    }
}
</script>
