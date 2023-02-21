import * as constants from '../constants';

export default {
    page: {
        default: 'dashboard',
        notFound: 'error.not-found',
        login: 'auth.login'
    },
    selected: 'main',// main, tablet, mobile
    layoutsList: ['main', 'empty', 'centered'],
    defaultLayout: 'main',// main, empty, centered

    isDark: false,
    isMini: false,
    title: 'Admin Panel',

    footerInset: true,

    snackbar: {
        absolute: false,
        bottom: true,
        color: constants.SNACKBAR_COLORS.info,
        left: false,
        "multi-line": false,
        right: false,
        timeout: 6000,
        top: false,
        vertical: false,
        showButton: true
    },

    main: {
        /*navigationMini: false,
        navigationIsOpened: true,
        temporary: false*/
    },
    tablet: {
        /*navigationMini: true,
        navigationIsOpened: true,
        temporary: false*/
    },
    mobile: {
        /*navigationMini: false,
        navigationIsOpened: false,
        temporary: true*/
    },
};
