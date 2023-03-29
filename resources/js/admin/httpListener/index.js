import navigationListener from "./navigationListener";
import testListener from "./testListener";

export default {
    request: [
        testListener,
    ],
    response: [
        navigationListener,
    ]
}
