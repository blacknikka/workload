<template>
  <v-container
    fluid
    fill-height
  >
    <v-layout
      align-center
      justify-center
    >
      <v-flex
        xs12
        sm8
        md8
      >
        <v-card class="login-card-container">
          <v-form
            id="app"
            @submit="login"
            method="post"
          >
            <v-card-text>
              <v-text-field
                prepend-icon="person"
                v-model="email"
                label="E-mail"
              ></v-text-field>

              <v-text-field
                prepend-icon="lock"
                v-model="password"
                :append-icon="show1 ? 'visibility' : 'visibility_off'"
                :type="show1 ? 'text' : 'password'"
                name="input-10-1"
                label="password"
                counter
              ></v-text-field>
            </v-card-text>

            <v-btn
              color="primary"
              flat
              type="submit"
            >Login</v-btn>
          </v-form>

          <v-card-text>
            <router-link :to="{name: 'register'}">登録する</router-link>
            <div
              class="message-text"
              v-if="isShownMessage"
            >{{ message }}</div>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import axios from '../../Util/axios/axios';
const goto = 'splash';

export default {
  data() {
    return {
      email: '',
      password: '',
      message: '',
      show1: ''
    };
  },
  computed: {
    isShownMessage() {
      return this.message !== '';
    }
  },
  methods: {
    async login(e) {
      e.preventDefault();

      // login
      const result = await axios
        .post('api/auth/authenticate', {
          email: this.email,
          password: this.password
        })
        .then(response => {
          if (response.status !== 200) {
            throw new Error(response.status);
          }

          // 成功
          console.log('login done');
          this.$store.commit('setLoggedIn', {
            loggedIn: true,
            token: response.data.token
          });

          this.$router.replace({
            name: goto
          });
        })
        .catch(error => {
          // something error
          console.log(error);

          console.log('login error');
          this.message = 'login error';
        });
    }
  },
  async mounted() {
    if (this.$store.getters.loggedIn === true) {
      const result = await axios.auth();
      if (result === true) {
        console.log('auth ok.');
        this.$router.replace({ name: goto });
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.message-text {
  color: red;
  font-size: 2em;
  text-align: center;
}

.login-card-container {
  max-width: 500px;
  min-height: 300px;
  margin: auto;
}
</style>
