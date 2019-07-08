import Vue from 'vue';
import Vuex from 'vuex';

import User from './model/user';

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    loginToken: '',
    workload: [],
    userInfo: null,

    // 現在選択中の日付
    picked: '',
  },
  mutations: {
    setWorkload(state, workload) {
      workload.map((data) => {
        state.workload.push(data);
      })
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
    setPickedDate(state, picked) {
      state.picked = picked;
    }
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
    getPickedDate({picked}) {
      return picked;
    }
  },
  plugins: [],
});

export default store;
