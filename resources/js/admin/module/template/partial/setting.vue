<template>
    <div class="template-settings">
        <form-component
            :value="settingsForm"
            :disabled="isLoading"
            @input="formChanged($event)"
            @validate="settingsValidation = $event"
        />
    </div>
</template>
<script>
import formComponent from "../../../component/form/form-component.vue";
import {Form as FormClass} from "../../../component/form/classes/form";
import validation from "../../../config/validation";
import mainConfig from '../../../config/main';
import Service from "../js/service";
import { mapGetters } from "vuex";

export default {
    service: new Service(),
    data () {
        return {
            settingsForm: null,
            settingsValidation: null,
        }
    },
    created() {
        this.$options.service.settings(response => console.log('response', response));
        this.initForm();
    },
    computed: {
        ...mapGetters({
            isLoading: 'view/loading',
        }),
    },
    methods: {
        initForm () {
            this.settingsForm = new FormClass();
        },
        formChanged (e) {
            console.log('formChanged', e);
        }
    },
    components: {
        formComponent
    }
}
</script>
