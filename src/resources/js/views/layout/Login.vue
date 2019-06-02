<template>
    <div class="login-container">
        <form id="app" @submit="login" method="post">
            <div class="login-container">
                e-mail: <input v-model="email" type="text" placeholder="e-mail">
            </div>

            <div class="login-container">
                password: <input v-model="password" type="password" placeholder="password">
            </div>

            <input
                type="submit"
                value="Login"
            >
        </form>
    </div>
</template>

<script>
import axios from '../../Util/axios/axios';

export default {
    data() {
        return {
            email: '',
            password: '',
        };
    },
    methods: {
        login(e) {
            e.preventDefault();

            // if (this.email != '' && this.password != '') {
            //     axios.post('api/logout', {});
            // }
        },
    },
}
</script>

<style lang="scss" scoped>
.login-container {
    margin-top: 20vh;
    width: 100vw;
    height: 100vh;
    background-color: honeydew;

    .login-container {
        text-align: center;
        width: 80%;
        height: 10%;
        background-color: greenyellow;
    }
}
</style>
