import app from "./app";
import http from "./http";
import logger from "./logger";

export default class BaseService {
    callbackObj = [];
    callback(...args) {
        this.callbackObj = args;
        return this;
    }
    request(api, successCallback, errorCallback, callbackObj = []) {
        this.loading(true);
        let request = http(api);
        callbackObj = [
            ...this.callbackObj,
            ...callbackObj,
        ];

        if (Array.isArray(callbackObj) && callbackObj.length) {
            request = request.callback(...callbackObj);
        }

        request
            .send()
            .then(response => {
                if (typeof successCallback === 'function') {
                    successCallback(response.data);
                }
            })
            .catch(error => {
                if (typeof errorCallback === 'function') {
                    errorCallback(error);
                } else {
                    logger.error(error);
                    app.errors(error);
                }
            })
            .then(() => {
                this.loading(false);
            })
    }

    loading(isStart) {
        app.loading(isStart);
    }
}
