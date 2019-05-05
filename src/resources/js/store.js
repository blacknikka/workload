import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        workload : [],
    },
    mutations: {
        setWorkload(state, workload) {
            state.workload = workload;
        }
    },
    actions: {
    },
    getters: {
        workload({workload}) {
            return workload;
        }
    },
    plugins: [
    ],
});

export default store;
