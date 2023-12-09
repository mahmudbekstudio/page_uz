<template>
    <page-box
            class="module-type-form"
            :actions="actions"
            :footer-actions="actions"
    >
        <formComponent
            :value="typeForm"
            @input="typeFormChanged"
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
    import {getPageBoxAction, translationObject} from '../../../helper';
    import formConstructor from '../../../component/form-constructor/form-constructor';
    import { Form as FormClass, Field } from '../../../component/form/classes/form';
    import { mapActions, mapGetters } from 'vuex';
    import app from "../../../service/app";
    import formComponent from '../../../component/form/form-component';
    import validation from "../../../config/validation";
    import mainConfig from '../../../config/main';
    import i18n from "../../../plugin/i18n";

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
                required: {},
                typeFormValues: null,
            }
        },
        created() {
            this.type = this.$route.params?.type;
            this.id = this.$route.params?.id;

            if (!this.id && mainConfig.app.type.all.indexOf(this.type) === -1) {
                this.backClick();
            }

            if (this.id) {
                this.$options.service.get(
                    this.id,
                    response => {
                        this.formValue = new FormClass(response.data.type.structure, true);
                        this.type = response.data.type.type;
                        this.initComponents();
                        this.initAdvanced();
                        this.typeFormValues = response.data.type;
                        this.createTypeForm();
                    }
                );
            } else {
                this.initComponents();
                this.initAdvanced();
                this.formValue = new FormClass({}, true);
                const mainTab = this.formValue.getTab();

                if (this.type !== 'setting') {
                    mainTab.addField({type: 'requiredTitle'});
                    mainTab.addField({type: 'requiredStatus'});
                }

                if (this.isPageType) {
                    mainTab.addField({type: 'requiredRouteName'});

                    const seoTab = this.formValue.addTab({title: translationObject('words.seo', i18n)});
                    seoTab.addField({type: 'requiredSeoKeyword'});
                    seoTab.addField({type: 'requiredSeoDescription'});

                    const advancedTab = this.formValue.addTab({title: translationObject('words.advanced', i18n)});
                    advancedTab.addField({type: 'requiredTemplate'});
                    advancedTab.addField({type: 'requiredPublishEnd'});
                    advancedTab.addField({type: 'requiredPublishStart'});
                }
            }

            if (this.type) {
                this.changeTitle('words.create_' + this.type);
            }

            this.actions.push(getPageBoxAction('words.back', '', {color: 'default'}, {click: this.backClick}));
            this.actions.push(getPageBoxAction('words.' + (this.id ? 'update' : 'create'), '', {color: 'primary'}, {click: this.saveForm}));

            this.currentLangChanged();
        },
        computed: {
            ...mapGetters({
                isLoading: 'type-form/isLoading',
                language: 'view/language',
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
            },
            isPageType () {
                return mainConfig.app.type.page.indexOf(this.type) > -1
            },
        },
        watch: {
            formValue: {
                handler(newVal, oldVal) {
                    //
                },
                deep: true
            },
            language(newLang, oldLang) {
                this.currentLangChanged(newLang, oldLang);
            },
        },
        methods: {
            ...mapActions({
                changeTitle: 'view/changeTitle',
            }),
            typeFormChanged(form) {
                this.typeFormValues = {...this.typeFormValues, ...form.getFieldValues()};
            },
            currentLangChanged() {
                this.createTypeForm();

                if (this.formValue && this.formValue.json) {
                    this.formValue = new FormClass(this.formValue.json, true);
                }
            },
            initComponents() {
                if (this.isPageType) {
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
                } else {
                    this.advanced = {};

                    if (this.type !== 'setting') {
                        this.required = {
                            title: new Field({type: 'requiredTitle'}),
                            status: new Field({type: 'requiredStatus'}),
                        };
                    } else {
                        this.required = {};
                    }

                }
            },
            initAdvanced() {
                if (this.type === 'post') {
                    this.advanced['childOf'] = new Field({type: 'advancedChildOf'});
                }
            },
            backClick () {
                this.$router.push({name: 'type.list'});
            },
            createTypeForm () {
                this.typeForm = new FormClass();
                const typeTitle = this.typeForm.addField({type: 'text', name: 'title', value: this.typeFormValues?.title || '', params: {label: 'words.title', rules: [validation.required('words.title')]}});

                if (this.isPageType) {
                    const routeName = this.typeForm.addField({type: 'requiredRouteName', name: 'name', value: this.typeFormValues?.name || '', params: {label: 'words.type_name'}});
                }

                const status = this.typeForm.addField({type: 'switch', name: 'status', value: (typeof this.typeFormValues?.status === 'undefined' ? true : this.typeFormValues?.status), params: {label: 'words.status'}});
            },
            saveForm() {
                if (!this.formValidate() || !this.formIsValidValue) {
                    app.errorMessage(this.$t('words.please_fill_all_required_fields'));
                    return false;
                }

                if (!this.formIsValid) {
                    app.errorMessage(this.$t('words.please_add_all_required_field'));
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
                    app.successMessage(this.$t('words.saved'));
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
