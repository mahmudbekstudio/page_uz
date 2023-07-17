<template>
    <div class="module-template-post">
        <formComponent
            :value="templateForm"
            :disabled="isLoading"
            @input="formChanged($event)"
            @validate="formValidateFunc($event)"
        />
    </div>
</template>
<script>
import formComponent from "../../../component/form/form-component.vue";
import {mapGetters} from "vuex";
import {Form as FormClass} from "../../../component/form/classes/form";
import validation from "../../../config/validation";

export default {
    data () {
        return {
            templateForm: null,
            formValidate: null,
        };
    },
    created () {
        //console.log('test', this.$route.currentRoute.params.id)
        this.templateForm = new FormClass();
        const titleField = this.templateForm.addField({type: 'text'});
        titleField.setParams('label', 'words.title');
        titleField.setParams('rules', [validation.required('words.title')]);
        titleField.name = 'title';
        //titleField.value = this.form.first_name;
    },
    computed: {
        ...mapGetters({
            isLoading: 'view/loading'
        })
    },
    methods: {
        formChanged (e) {
            console.log('e.getFieldValues()', e.getFieldValues());
        },
        formValidateFunc (e) {
            this.formValidate = e;
        },
    },
    components: {
        formComponent
    }
}
</script>
