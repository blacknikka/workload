<template>
  <div class="login-container">
    <form id="app" @submit="login" method="post">
      <div class="login-input">
        e-mail:
        <input v-model="email" type="text" placeholder="e-mail" />
      </div>

      <div class="login-input">
        password:
        <input v-model="password" type="password" placeholder="password" />
      </div>

      <input type="submit" value="Login" />
    </form>
    <div class="message-text" v-if="isShownMessage">{{message}}</div>
    <router-link :to="{name: 'register'}">Register</router-link>
  </div>
</template>

<script>
import axios from "../../Util/axios/axios";

export default {
  data() {
    return {
      email: "",
      password: "",
      message: ""
    };
  },
  computed: {
    isShownMessage() {
      return this.message !== "";
    }
  },
  methods: {
    async login(e) {
      e.preventDefault();

      // login
      const result = await axios.post("api/auth/authenticate", {
        email: this.email,
        password: this.password
      });

      if (result.status === 200) {
        // 成功
        console.log('login done');
        this.$store.commit("setLoggedIn", {
          loggedIn: true,
          token: result.data.token
        });

        this.$router.replace({
          name: 'home',
        });
      } else {
        console.log('login error');
        this.message = 'login error';
      }
    }
  },
  mounted() {
    if (this.$store.getters.loggedIn === true) {
      const result = axios.auth();
      if (result === true) {
        this.$route.replace({ name: "home" });
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.login-container {
  margin-top: 20vh;
  width: 100vw;
  height: 100vh;
  background-color: honeydew;

  .login-input {
    text-align: center;
    width: 80%;
    height: 10%;
    background-color: greenyellow;
  }
}
.message-text {
  color: red;
  font-size: 2em;
  text-align: center;
}
</style>
