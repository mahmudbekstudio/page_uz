import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from '../module/route'
import app from '../service/app';
import auth from '../service/auth';

Vue.use(VueRouter);
const routersList = new VueRouter({
    mode: 'history',// "hash" | "history" | "abstract"
    routes
});

routersList.beforeEach((to, from, next) => {
    app.routeInit(to, from, next);
    app.settings(() => {
        if(to.meta.requiresAuth) {
            auth.check(to, from, next);
        } else {
            next();
        }
    }, to);
});

export default routersList;
