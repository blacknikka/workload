import VueRouter from 'vue-router';

import Top from './views/layout/Top';
import Home from './views/layout/Home';
import Login from './views/layout/Login';

const routes = [
    {path: '/', component: Top, name: 'top'},
    {path: '/home', component: Home, name: 'home'},
    {path: '/login', component: Login, name: 'login'},
    {path: '*', redirect: '/' },
];

const router = new VueRouter({
    routes,
    linkActiveClass: 'active',
    mode: 'history',
});

export default router;
