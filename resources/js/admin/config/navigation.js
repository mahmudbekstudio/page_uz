import * as constants from '../constants';
/*export default {
    admin: [
        {title: 'Dashboard', icon: 'dashboard', route: {name: 'admin.dashboard'}},
        {title: 'Users', icon: 'group', route: {name: 'admin.user'}},
        {title: 'Address', icon: 'group', route: {name: 'admin.address'}},
        {title: 'Task', icon: 'group', route: {name: 'admin.task'}},
        //{title: 'File Manager', icon: 'folder', route: {name: 'admin.fileManager'}},
        {title: 'Settings', icon: 'settings', route: {name: 'admin.settings'}}
    ],
    manager: [
        {title: 'Dashboard', icon: 'dashboard', route: {name: 'manager.dashboard'}},
        {title: 'Tasks', icon: 'group', route: {name: 'default-login'}},
        {title: 'Settings', icon: 'settings', route: {name: 'manager.settings'}}
    ],
    publisher: [
        {title: 'Dashboard', icon: 'dashboard', route: {name: 'publisher.dashboard'}},
        {title: 'Tasks', icon: 'dashboard', route: {name: 'publisher.dashboard'}},
        //{title: 'Test', icon: 'group', route: {name: 'default-login'}},
        {title: 'Settings', icon: 'settings', route: {name: 'publisher.settings'}}
    ]
};*/
export default {
    [constants.ROLES.super_admin]: [
        {text: 'Dashboard', icon: 'dashboard', route: {name: 'dashboard'}},
        {text: 'Type', icon: 'mdi-format-list-bulleted-type', route: {name: 'type.list'}, active: ['type.list', 'type.create', 'type.edit']},
        {text: 'Users', icon: 'mdi-account-group', route: {name: 'user.list'}},
        {text: 'Websites', icon: 'mdi-account-group', route: {name: 'website.list'}},
        {
            icon: 'settings',
            text: 'Setting',
            children: [
                {text: 'Main', icon: 'settings', route: {name: 'setting.main'}},
                {text: 'Domain', icon: 'settings', route: {name: 'setting.domain'}},
            ],
        },


        /*{text: 'Dashboard', icon: 'dashboard', route: {name: 'manager.dashboard'}},
        { heading: 'Labels' },
        {text: 'Dashboard1', icon: 'dashboard', route: {name: 'manager.dashboard'}},
        { divider: true },
        {text: 'Dashboard2', icon: 'dashboard', route: {name: 'manager.dashboard'}},
        {
            icon: 'dashboard',
            text: 'More',
            children: [
                { text: 'Import', icon: 'settings' },
                { text: 'Export', icon: 'settings' },
                { text: 'Print', icon: 'settings' },
                { text: 'Undo changes', icon: 'settings' },
                { text: 'Other contacts', icon: 'settings' },
            ],
        },
        {text: 'Settings', icon: 'settings', route: {name: 'manager.settings'}}*/
        /*{ divider: true },
        { heading: 'Labels' },
        {text: 'Users', icon: 'mdi-account-group-outline', route: {name: 'admin.user'}},
        {text: 'Address', icon: 'mdi-account-group-outline', route: {name: 'admin.address'}},
        {text: 'Task', icon: 'mdi-account-group-outline', route: {name: 'admin.task'}},
        //{text: 'File Manager', icon: 'folder', route: {name: 'admin.fileManager'}},
        {text: 'Settings', icon: 'mdi-settings-outline', route: {name: 'admin.settings'}},
        {
            icon: 'mdi-settings-outline',
            text: 'More',
            children: [
                { text: 'Import', icon: 'settings' },
                { text: 'Export', icon: 'settings' },
                { text: 'Print', icon: 'settings' },
                { text: 'Undo changes', icon: 'settings' },
                { text: 'Other contacts', icon: 'settings' },
            ],
        },*/
    ],
    [constants.ROLES.admin]: [
        {text: 'Dashboard', icon: 'dashboard', route: {name: 'manager.dashboard'}},
        {text: 'Settings', icon: 'settings', route: {name: 'manager.settings'}}
    ],
    [constants.ROLES.manager]: [
        {text: 'Dashboard', icon: 'dashboard', route: {name: 'manager.dashboard'}},
        {text: 'Settings', icon: 'settings', route: {name: 'manager.settings'}}
    ],
    [constants.ROLES.publisher]: [],
    [constants.ROLES.user]: [],
}
/*export default [
    {icon: 'contacts', text: 'Contacts'},
    {icon: 'history', text: 'Frequently contacted'},
    {icon: 'content_copy', text: 'Duplicates'},
    {
        icon: 'keyboard_arrow_up',
        'icon-alt': 'keyboard_arrow_down',
        text: 'Labels',
        model: true,
        children: [
            {icon: 'add', text: 'Create label'},
        ],
    },
    {
        icon: 'keyboard_arrow_up',
        'icon-alt': 'keyboard_arrow_down',
        text: 'More',
        model: false,
        children: [
            {text: 'Import'},
            {text: 'Export'},
            {text: 'Print'},
            {text: 'Undo changes'},
            {text: 'Other contacts'},
        ],
    },
    {icon: 'settings', text: 'Settings'},
    {icon: 'chat_bubble', text: 'Send feedback'},
    {icon: 'help', text: 'Help'},
    {icon: 'phonelink', text: 'App downloads'},
    {icon: 'keyboard', text: 'Go to the old version'},
];*/
