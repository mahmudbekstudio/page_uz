<template>
    <page-box
            class="module-type-form"
            :actions="actions"
            :footer-actions="actions"
    >
        <formComponent
            :value="typeForm"
            :disabled="isLoading"
            @validate="formValidateFunc($event)"
            @valid="formValid($event)"
        />
        <form-constructor
            :disabled="isLoading"
            v-model="formValue"
            :advanced="advanced"
            :required="required"
        />
    </page-box>
</template>
<script>
    import Service from './service';
    import pageBox from '../../../view/partial/page-box';
    import { getPageBoxAction } from '../../../helper';
    import formConstructor from '../../../component/form-constructor/form-constructor';
    import { Form as FormClass, Field } from '../../../component/form/classes/form';
    import { mapActions, mapGetters } from 'vuex';
    import app from "../../../service/app";
    import formComponent from '../../../component/form/form-component';

    export default {
        service: new Service(),
        data() {
            return {
                typeForm: null,
                formValidate: null,
                formIsValidValue: true,
                type: null,
                id: null,
                formValue: [],
                actions: [],
                advanced: {},
                required: {}
            }
        },
        created() {
            this.type = this.$route.params?.type;
            this.id = this.$route.params?.id;

            if (this.type) {
                this.changeTitle(this.$t('words.create_' + this.type));
            }

            this.advanced = {
                parent: new Field({type: 'advancedParent'}),
            };
            this.required = {
                title: new Field({type: 'requiredTitle'}),
                routeName: new Field({type: 'requiredRouteName'}),
                status: new Field({type: 'requiredStatus'}),
                template: new Field({type: 'requiredTemplate'}),
                seoKeyword: new Field({type: 'requiredSeoKeyword'}),
                seoDescription: new Field({type: 'requiredSeoDescription'}),
                publishEnd: new Field({type: 'requiredPublishEnd'}),
                publishStart: new Field({type: 'requiredPublishStart'}),
            };

            this.initAdvanced();

            this.actions.push(getPageBoxAction(this.$t('words.back'), '', {color: 'default'}, {click: this.backClick}));
            this.actions.push(getPageBoxAction(this.$t('words.' + (this.id ? 'update' : 'create')), '', {color: 'primary'}, {click: this.saveForm}));

            if (this.id) {
                this.$options.service.get(
                    this.id,
                    response => {
                        this.formValue = new FormClass(response.data.type.structure, true);
                        this.type = response.data.type.type;
                        this.initAdvanced();
                        this.createTypeForm(response.data.type);
                    }
                );
            } else {
                this.formValue = new FormClass({}, true);
                const mainTab = this.formValue.getTab();
                mainTab.addField({type: 'requiredTitle'});
                mainTab.addField({type: 'requiredRouteName'});
                mainTab.addField({type: 'requiredStatus'});
                const seoTab = this.formValue.addTab({title: 'SEO'});
                seoTab.addField({type: 'requiredSeoKeyword'});
                seoTab.addField({type: 'requiredSeoDescription'});
                const advancedTab = this.formValue.addTab({title: 'Advanced'});
                advancedTab.addField({type: 'requiredTemplate'});
                advancedTab.addField({type: 'requiredPublishEnd'});
                advancedTab.addField({type: 'requiredPublishStart'});

                this.createTypeForm();
            }
        },
        computed: {
            ...mapGetters({
                isLoading: 'type-form/isLoading'
            }),
            formIsValid () {
                let result = true;
                const values = this.formValue.getFieldValues();

                for (const fieldKey in this.required) {
                    if (typeof values[fieldKey] === 'undefined') {
                        result = false;
                        break;
                    }
                }

                return result;
            }
        },
        watch: {
            formValue: {
                handler(newVal, oldVal) {
                    //
                },
                deep: true
            }
        },
        methods: {
            ...mapActions({
                changeTitle: 'view/changeTitle',
            }),
            initAdvanced() {
                if (this.type === 'post') {
                    this.advanced['childOf'] = new Field({type: 'advancedChildOf'});
                }
            },
            backClick () {
                this.$router.push({name: 'type.list'});
            },
            createTypeForm (values) {
                this.typeForm = new FormClass();
                const routeName = this.typeForm.addField({type: 'requiredRouteName', name: 'name', value: values?.name || '', params: {label: 'Type name'}});
                const status = this.typeForm.addField({type: 'switch', name: 'status', value: (typeof values?.status === 'undefined' ? true : values?.status), params: {label: 'Status'}});
            },
            saveForm() {
                this.formValidate();
                if (!this.formIsValidValue) {
                    app.errorMessage('Please, fill all required fields');
                    return false;
                }

                if (!this.formIsValid) {
                    app.errorMessage('Please, add all required fields from required tab');
                    return false;
                }

                const formValues = this.formValue.getFieldValues();
                const fields = this.formValue.getFields().map(item => item.json);
                const form = {
                    ...this.typeForm.getFieldValues(),
                    type: this.type,
                    has_parent: typeof formValues['parent'] !== 'undefined',
                    child_of: this.getChildOfValue(fields),
                    structure: this.formValue.json,
                    fields: fields,
                };

                this.$options.service.submit(this.id, form, response => {
                    if (!this.id) {
                        this.backClick();
                    }
                    app.successMessage('Saved');
                });
            },
            formValidateFunc(formValidate) {
                this.formValidate = formValidate;
            },
            formValid(valid) {
                this.formIsValidValue = valid;
            },
            getChildOfValue(fields) {
                let result = 0;

                for (const field of fields) {
                    if (field.name === 'childOf') {
                        result = parseInt(field.params.child_of);
                        break;
                    }
                }

                return result;
            }
        },
        components: {
            pageBox,
            formConstructor,
            formComponent,
        }
    }
</script>
