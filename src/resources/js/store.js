import Vue from 'vue';
import Vuex from 'vuex';

import User from './model/user';
import Category from './model/project/category';
import Project from './model/project/project';

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    loginToken: '',
    workload: [],
    userInfo: null,
    project: [],
    category: [],

    // 現在選択中の日付
    picked: '',
  },
  mutations: {
    setWorkload(state, workload) {
      workload.map((data) => {
        state.workload.push(data);
      })
    },
    clearWorkload(state) {
      state.workload.length = 0;
    },
    setWorkloadToBeUploaded(state, {index, id}) {
      state.workload[index].setThisHasBeenOld();
      state.workload[index].id = id;
    },
    addWorklaod(state, workload) {
      state.workload.push(workload);
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
    },
    setProjectData(state, project) {
      project.forEach((item) => {
        state.project.push(item);
      });
    },
    setCategoryData(state, category) {
      category.forEach((item) => {
        state.category.push(item);
      });
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
    },
    getProjectFromId: (state) => (id) => {
      return state.project.find((item) => {
        return id === item.id;
      });
    },
    getCategoryFromId: (state) => (id) => {
      return state.category.find((item) => {
        return id === item.id;
      });
    },
  },
  plugins: [],
});

export default store;
