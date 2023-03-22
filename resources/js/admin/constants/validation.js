import mainConfig from '../config/main';
export const VALIDATION_MIN_PASSWORD_LENGTH = mainConfig.app.min_password_length;
export const VALIDATION_EMAIL = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
export const VALIDATION_ROUTE_NAME = new RegExp('^' + mainConfig.app.routeRules.name + '$');
