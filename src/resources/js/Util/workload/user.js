import axios from '../axios/axios';
import store from '../../store';

class user {
  async getUserDataFromServer() {
    const result = await axios.get('api/auth/me');
    return result;
  }
}

export default new user();
