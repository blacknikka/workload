import axios from 'axios';

class myAxios
{
    async get(url) {
        const result = await axios.get(url);
        return result;
    }

    async post(url, data) {
        const result = await axios.post(url, data);
        return result;
    }
}

export default new myAxios();
