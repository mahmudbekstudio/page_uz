import TypeService from '../../type/form/service';

export default class Service {
    get(id, successCallback, errorCallback) {
        return (new TypeService()).get(id, successCallback, errorCallback);
    }
}
