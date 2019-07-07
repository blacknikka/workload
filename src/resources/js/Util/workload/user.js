import axios from '../axios/axios';

class user {
  async getUserDataFromServer() {
    const result = await axios.get('api/auth/me');
    return result;
  }
}

export default new user();
