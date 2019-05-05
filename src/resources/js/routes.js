import VueRouter from 'vue-router';
import store from './store.js';

import Top from './views/layout/Top';
import Home from './views/layout/Home';

const routes = [
    {path: '/', component: Top, name: 'top'},
    {path: '/home', component: Home, name: 'home'},
];

const router = new VueRouter({
    routes,
    linkActiveClass: 'active',
    mode: 'history',
});

/** requiresAuth check **/

router.beforeEach((to, from, next) => {
    // requiresAuthがtrueのページはJWT認証の確認を行う
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        return axios.post('/api/auth/check')
            .then((response) => {
                return !!response.data.authenticated;
            }).catch((/* error */) => {
                // return response.data.authenticated;
                return false;
            }).then((authenticated) => {
                if (!authenticated) {
                    return next({path: '/login'});
                }

                // 認証が通ったらユーザを取得する
                // TODO
                // 取得済なら取得を省略する
                store.dispatch('fetchUser')
                    .then(() => {
                        // console.log('fetched user in route middleware layer.');
                    })
                    .finally(() => {
                        return next();
                    })
            });
    }

    return next();
});

/** gateCanRegisterProjects check **/
router.beforeEach((to, from, next) => {
    // RegisterProjectsの認可が必要なページは確認を行う
    if (to.matched.some((record) => record.meta.gateCanRegisterProjects)) {
        // console.log(store.state.user);

        // 認可が通らなければ/homeに飛ばす
        if (!store.state.user.canRegisterProjects) {
            return next({path: '/home'});
        }
    }

    return next();
});

export default router;
