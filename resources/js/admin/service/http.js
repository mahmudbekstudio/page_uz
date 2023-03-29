import axios from 'axios';
import viewConfig from '../config/view';
import {get as getValue, set as setValue} from 'lodash';
import router from '../plugin/route';
import auth from "./auth";
import api from '../api';
import logger from './logger';
import httpListener from "../httpListener";

class Http {
    constructor(apiName, axios) {
        this.axios = axios;
        this.cancelToken = axios.CancelToken;
        this.requestDefault = {
            url: '',
            method: 'get',
            baseURL: '',
            headers: {},
            params: {},
            data: {},
            responseType: 'json',
            responseEncoding: 'utf8',
            token: null,
            callback: null
        };
        this.source = null;
        this.requestParams = Object.assign({}, this.requestDefault);
        this.api(typeof apiName === 'object' ? apiName : getValue(api, apiName, {}));
    }

    callback() {
        if (typeof this.requestParams.callback === 'function') {
            this.requestParams.callback.apply(this, arguments);
        }

        return this;
    }

    api(api) {
        for (let key in api) {
            if (api.hasOwnProperty(key)) {
                if (typeof api[key] === 'object') {
                    for (let innerKey in api[key]) {
                        if (api[key].hasOwnProperty(innerKey)) {
                            this.requestParams[key][innerKey] = api[key][innerKey];
                        }
                    }
                } else {
                    this.requestParams[key] = api[key];
                }
            }
        }

        return this;
    }

    url(newUrl) {
        this.requestParams.url = newUrl;
        return this;
    }

    urlParam(param, val) {
        this.requestParams.url = this.requestParams.url.replace(param, val);
        return this;
    }

    method(newMethod) {
        this.requestParams.method = newMethod;
        return this;
    }

    baseUrl(newBaseUrl) {
        this.requestParams.baseURL = newBaseUrl;
        return this;
    }

    headers(headerObj) {
        if (typeof headerObj === 'object') {
            for (let key in headerObj) {
                setValue(this.requestParams, 'headers.' + key, headerObj[key]);
            }
        }

        return this;
    }

    params(paramObj) {
        if (typeof paramObj === 'object') {
            for (let key in paramObj) {
                setValue(this.requestParams, 'params.' + key, paramObj[key]);
            }
        }

        return this;
    }

    setData(dataObj) {
        this.requestParams.data = dataObj;
    }

    setFormData(dataObj) {
        let formData = new FormData();
        for (let key in dataObj) {
            if (dataObj.hasOwnProperty(key)) {
                formData.append(key, dataObj[key]);
            }
        }
        this.setData(formData);
    }

    data(dataObj) {
        if (typeof dataObj === 'object') {
            for (let key in dataObj) {
                setValue(this.requestParams, 'data.' + key, dataObj[key]);
            }
        }

        return this;
    }

    responseType(newResponseType) {
        this.requestParams.responseType = newResponseType;
        return this;
    }

    responseEncoding(newResponseEncoding) {
        this.requestParams.responseEncoding = newResponseEncoding;
        return this;
    }

    setToken(val = true) {
        this.requestParams.token = val;

        return this;
    }

    send(initCallback = null) {
        if (this.requestParams.token) {
            return new Promise((resolve, reject) => {
                if(this.requestParams.checkExcept) {
                    this.initHeader();
                    resolve(this.sendRequest(initCallback));
                } else {
                    auth.checkAccess(
                        () => {
                            this.initHeader();
                            resolve(this.sendRequest(initCallback));
                        },
                        () => {
                            auth.logout(false);
                            reject({result: false})
                        }
                    );
                }
            });
        }

        return this.sendRequest(initCallback);
    }

    sendRequest(initCallback = null) {
        typeof initCallback === 'function' && initCallback(this);
        this.source = this.cancelToken.source();
        let params = {
            url: this.requestParams.url,
            method: this.requestParams.method,
            //baseURL: this.requestParams.baseURL || config.get('baseUrl'),
            /*
            transformRequest: [function (data, headers) {
                // Do whatever you want to transform the data

                return data;
            }],
            */
            // `transformResponse` allows changes to the response data to be made before
            // it is passed to then/catch
            transformResponse: [data => {
                data = JSON.parse(data);

                for (const listener of httpListener.response) {
                    if(listener.check(data, params,this)) {
                        data = listener.callback(data, params,this);
                    }
                }

                return data;
            }],

            headers: this.requestParams.headers,
            params: this.requestParams.params,
            /*paramsSerializer: function (params) {
                return Qs.stringify(params, {arrayFormat: 'brackets'})
            },*/
            data: this.requestParams.data,
            //timeout: 1000, // default is `0` (no timeout)
            //withCredentials: false, // default
            /*
            adapter: function (config) {
                //
            },

            auth: {
                username: 'janedoe',
                password: 's00pers3cret'
            },*/
            responseType: this.requestParams.responseType, // default
            responseEncoding: this.requestParams.responseEncoding, // default
            /*xsrfCookieName: 'XSRF-TOKEN', // default
            xsrfHeaderName: 'X-XSRF-TOKEN', // default

            onUploadProgress: function (progressEvent) {
                // Do whatever you want with the native progress event
            },

            onDownloadProgress: function (progressEvent) {
                // Do whatever you want with the native progress event
            },

            validateStatus: function (status) {
                return status >= 200 && status < 300; // default
            },
            maxRedirects: 5, // default
            socketPath: null, // default
            httpAgent: new http.Agent({ keepAlive: true }),
            httpsAgent: new https.Agent({ keepAlive: true }),

            proxy: {
                host: '127.0.0.1',
                port: 9000,
                auth: {
                    username: 'mikeymike',
                    password: 'rapunz3l'
                }
            },*/
            cancelToken: this.source.token
        };

        this.requestParams = Object.assign({}, this.requestDefault);
        for (const listener of httpListener.request) {
            if(listener.check(params, this)) {
                params = listener.callback(params, this);
            }
        }
        return this.axios(params);
    }

    cancel(msg = 'Operation canceled') {
        this.source || this.source.cancel(msg);
    }

    initHeader(isAccessToken = true) {
        let tokenField = auth.getTokenField();

        if (!tokenField) return false;

        if (!this.requestParams.headers[tokenField]) {
            this.requestParams.headers[tokenField] = isAccessToken ? auth.getAccessToken() : auth.getRefreshToken();
        }
    }
}

export default (apiName = null) => new Http(apiName, axios)
