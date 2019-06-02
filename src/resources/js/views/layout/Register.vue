<template>
    <div class="register-container">
        <form id="app" @submit="register" method="post">
            <div class="register-text">Input your data.</div>
            <div class="register-input">
                name: <input v-model="name" type="text" placeholder="name">
            </div>

            <div class="register-input">
                e-mail: <input v-model="email" type="text" placeholder="e-mail">
            </div>

            <div class="register-input">
                password: <input v-model="password" type="password" placeholder="password">
            </div>

            <div class="register-input">
                password (confirm): <input v-model="passowrd_confirmed" type="password" placeholder="password (confirmed)">
            </div>

            <input
                type="submit"
                value="Register"
                :disabled="IsEnabled"
            >
        </form>
    </div>
</template>

<script>
import axios from '../../Util/axios/axios';

export default {
    methods: {
        register() {
            axios.post('api/register',
            {
                name: this.name,
                email: this.email,
                password: this.passowrd,
            });
        },
    },
    data() {
        return {
            name: '',
            email: '',
            password: '',
            passowrd_confirmed: '',
        };
    },
    computed: {
        IsEnabled() {
            let result = false;

            if (this.name === '' || this.email === '' || this.password === '' || this.passowrd_confirmed === '') {
                result = 'disabled';
            }

            if (this.password !== this.passowrd_confirmed) {
                result = 'disabled';
            }
            return result;
        },
    },
}
</script>

<style lang="scss" scoped>
.register-container {
    margin-top: 20vh;
    width: 100vw;
    height: 100vh;
    background-color: honeydew;

    .register-text {
        font-size: 1.5em;
        text-align: center;
        padding: 10px;
    }

    .register-input {
        text-align: center;
        width: 80%;
        height: 10%;
        background-color: greenyellow;
    }
}
</style>
