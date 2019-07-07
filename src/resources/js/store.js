import Vue from 'vue';
import Vuex from 'vuex';

import User from './model/user';

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    loginToken: '',
    workload: [],
    userInfo: null,
  },
  mutations: {
    setWorkload(state, workload) {
      state.workload = workload;
    },
    setLoggedIn(state, { loggedIn, token }) {
      if (loggedIn === true) {
        if (token) {
          state.loginToken = token;
          localStorage.auth_token = token;
        }
      } else {
        state.loginToken = '';
        localStorage.auth_token = '';
      }
    },
    setLoginToken(state, token) {
      state.loginToken = token;

      localStorage.auth_token = token;
    },
    setUserInfo(state, user) {
      state.userInfo = new User(
        user.id,
        user.name,
        user.email,
        user.department
      );
    },
  },
  actions: {},
  getters: {
    workload({ workload }) {
      return workload;
    },
    loggedIn(state) {
      const token = localStorage.auth_token;
      if (token) {
        console.log(token);
        state.loginToken = token;
      }

      return state.loginToken !== '';
    },
    loginToken({ loginToken }) {
      return loginToken;
    },
    userInfo({ userInfo }) {
      return userInfo;
    },
  },
  plugins: [],
});

export default store;
