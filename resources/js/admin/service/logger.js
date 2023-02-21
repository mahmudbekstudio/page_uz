import mainConfig from '../config/main';

class Logger {
    install(Vue, options) {
        Vue.prototype.$logger = this
    }

    clear() {
        this.message('clear', arguments)
    }

    table() {
        this.message('table', arguments)
    }

    time() {
        this.message('time', arguments)
    }

    timeEnd() {
        this.message('timeEnd', arguments)
    }

    trace() {
        this.message('trace', arguments)
    }

    warn() {
        this.message('warn', arguments)
    }

    count() {
        this.message('count', arguments)
    }

    countReset() {
        this.message('countReset', arguments)
    }

    debug() {
        this.message('debug', arguments)
    }

    error() {
        this.message('error', arguments)
    }

    group() {
        this.message('group', arguments)
    }

    groupCollapsed() {
        this.message('groupCollapsed', arguments)
    }

    groupEnd() {
        this.message('groupEnd', arguments)
    }

    log() {
        this.message('log', arguments)
    }

    info() {
        this.message('info', arguments)
    }

    message(type, args) {
        const types = [
            'clear',
            'table',
            'time',
            'timeEnd',
            'trace',
            'warn',
            'count',
            'countReset',
            'debug',
            'error',
            'group',
            'groupCollapsed',
            'groupEnd',
            'log',
            'info'
        ];
        if (mainConfig.app.debug && types.indexOf(type) > -1) {
            console[type].apply(null, args);
        }
    }
}

export default new Logger();