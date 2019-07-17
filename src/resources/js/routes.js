import VueRouter from 'vue-router';

import Top from './views/layout/Top';
import Home from './views/layout/Home';
import Report from './views/layout/Report';
import Login from './views/layout/Login';
import Register from './views/layout/Register';

import axios from './Util/axios/axios';
import store from './store';

const routes = [
  {path: '/', component: Login, name: 'top'},
  {
    path: '/home',
    component: Home,
    name: 'home',
    meta: {requiresAuth: true},
  },
  {
    path: '/report',
    component: Report,
    name: 'report',
    meta: {requiresAuth: true},
  },
  {
    path: '/top',
    component: Top,
    name: 'splash',
    meta: {requiresAuth: true},
  },
  {path: '/login', component: Login, name: 'login'},
  {
    path: '/register',
    component: Register,
    name: 'register',
    meta: {requiresAuth: true},
  },
  {path: '*', redirect: '/'},
];

const router = new VueRouter({
  routes,
  linkActiveClass: 'active',
  mode: 'history',
});

router.beforeEach(async (to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // このルートはログインされているかどうか認証が必要です。
    // もしされていないならば、ログインページにリダイレクトします。
    if (store.getters.loggedIn === false) {
      // login状態でなければ問答無用でlogin画面を出す
      next({path: '/login'});
    } else {
      // authを通す
      const result = await axios.auth();
      if (result === true) {
        next();
      } else {
        next({path: '/login'});
      }
    }
  } else {
    next();
  }
});

export default router;
