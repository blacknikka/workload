import axios from 'axios';
import store from '../../store';

class myAxios {
  async get(url) {
    const result = await axios.get(url);
    return result;
  }

  async post(url, data) {
    const result = await axios.post(url, data);
    return result;
  }

  async auth() {
    const token = store.getters.loginToken;

    let result;
    if (token === '') {
      return false;
    }

    await axios.post('api/auth/confirm', {}, { headers: { Authorization: `Bearer ${token}` } })
      .then((response) => {
        if (response.status !== 200) {
          throw new Error(response.status)
        }

        if (result.data.auth === true) {
          store.commit('setLoggedIn', { loggedIn: true });
          return true;
        } else {
          store.commit('setLoggedIn', { loggedIn: false });
          return false;
        }
      })
      .catch((error) => {
        // something error
        console.log(error);

        store.commit('setLoggedIn', { loggedIn: false });
      });
  }
}

export default new myAxios();
