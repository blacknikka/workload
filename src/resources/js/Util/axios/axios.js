import axios from 'axios';
import store from '../../store';

class myAxios {
  async get(url) {
    const result = await axios.get(url);
    return result;
  }

  async getWithJwt(url) {
    const token = store.getters.loginToken;

    const result = await axios.get(url, { headers: { Authorization: `Bearer ${token}` } })
      .then((response) => {
        return response;
      })
      .catch((error) => {
        console.log(error);
        return false;
      });

    return result;
  }

  async post(url, data) {
    const result = await axios.post(url, data);
    return result;
  }

  async postWithJwt(url, data) {
    const token = store.getters.loginToken;

    const result = await axios.post(url, data, {
      headers: { Authorization: `Bearer ${token}` },
    })
      .then((response) => {
        return response;
      })
      .catch((error) => {
        console.log(error);
        return false;
      });

    return result;
  }

  async auth() {
    const token = store.getters.loginToken;

    if (token === '') {
      return false;
    }

    let result;
    await axios.post('api/auth/confirm', {}, { headers: { Authorization: `Bearer ${token}` } })
      .then((response) => {
        if (response.status !== 200) {
          throw new Error(response.status)
        }

        if (response.data.auth === true) {
          store.commit('setLoggedIn', { loggedIn: true });
          result = true;
        } else {
          store.commit('setLoggedIn', { loggedIn: false });
          result = false;
        }
      })
      .catch((error) => {
        // something error
        console.log(error);

        store.commit('setLoggedIn', { loggedIn: false });
        result = false;
      });

    return result;
  }
}

export default new myAxios();
