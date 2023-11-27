import ParentRoute from '../../view/parent-route';
import MainSetting from './main/main-setting';
import DomainSetting from './domain/domain-setting';
import SettingForm from './form/form.vue';

const route = {
    path: 'setting',
    component: ParentRoute,
    children: [
        {path: '', redirect: {name: 'setting.main'}},
        {
            path: 'main',
            name: 'setting.main',
            component: MainSetting,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.main_settings'
            }
        },
        {
            path: 'domain',
            name: 'setting.domain',
            component: DomainSetting,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.domain_setting'
            }
        },
        {
            path: 'edit/:typeId/:id?',
            name: 'setting.edit',
            component: SettingForm,
            meta: {
                layout: 'main',
                requiresAuth: true,
                title: 'words.setting_edit'
            }
        },
    ]
};

export default route;
