<template>
  <div class="panel">

    <div v-if="loginErrors" class="errors">
      <ul v-if="loginErrors.email">
        <li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li>
      </ul>
      <ul v-if="loginErrors.password">
        <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
      </ul>
    </div>

    <form class="form" @submit.prevent="login">
      <label for="login-email">Email</label>
      <input type="text" class="form__item" id="login-email" v-model="loginForm.email">
      <label for="login-password">Password</label>
      <input type="password" class="form__item" id="login-password" v-model="loginForm.password">
      <div class="form__button">
        <button type="submit" class="button button--inverse">login</button>
      </div>
    </form>
    
  </div>
</template>

<script>
export default {
  data () {
    return {
      loginForm: {
        email: '',
        password: ''
      },
    }
  },
  computed: {
    loginErrors() {
      return this.$store.getters.getloginErrorMessages;
    }
  },
  methods: {
    async login () {
      await this.$store.dispatch('login', this.loginForm);
      if(this.$store.getters.check){
        this.$router.push('/home');
      }
    },
  },
  created () {
    this.$store.commit('setLoginErrorMessages', null)
  }
}
</script>