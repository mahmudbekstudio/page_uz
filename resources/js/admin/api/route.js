import apiRoutes from 'Static/routes';

const api = {};
api.routes = apiRoutes;
api.baseUrl = '/';

class RouteClass {
    get(name, method = false) {
        let result = {
            method: '',
            url: ''
        };

        if (api.routes[name]) {
            if (!method || api.routes[name].methods.indexOf(method) === -1) {
                result.method = api.routes[name].methods[0];
            } else {
                result.method = method;
            }

            result.url = api.baseUrl + api.routes[name].uri;
        }

        return result;
    }

    admin(name, method = false) {
        return this.get('admin.' + name, method);
    }

    ajax(name, method = false) {
        return this.get('ajax.' + name, method);
    }

    api(name, method = false) {
        return this.get('api.' + name, method);
    }

    file(name, method = false) {
        return this.get('file.' + name, method);
    }

    web(name, method = false) {
        return this.get('web.' + name, method);
    }
}

export default new RouteClass();
