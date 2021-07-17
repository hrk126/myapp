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

      <div class="login">
        <form class="login__form" @submit.prevent="login">
          <div class="login__form__item">
            <span><i class="far fa-user"></i></span>
            <input type="text" class="form__item--user" id="user" placeholder="Username" v-model="loginForm.email">
          </div>
          <div class="login__form__item">
            <span><i class="fas fa-unlock-alt"></i></span>
            <input type="password" class="form__item--password" id="password" placeholder="Password" v-model="loginForm.password">
          </div>
          <div class="login__form__item">
            <button type="submit" class="button button--inverse">login</button>
          </div>
        </form>
      </div>

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
      //ユーザー情報が返った来たらhomeへリダイレクトする
      if(this.$store.getters.check){
        this.$router.push('/home');
      }
    },
  },
  created () {
    //ログインページを表示するタイミングでエラーメッセージをクリアしておく
    this.$store.commit('setLoginErrorMessages', null)
  }
}
</script>

<style scoped lang="scss">
@import 'resources/sass/app.scss';

  .panel {
    padding: 80px 0 30px;
    position: relative;
  }
  .errors {
    position: absolute;
    top: 0px;
    left: 50%;
    transform: translate(-50%);
    color: red;
  }
  .login {
    &__form {
      margin: auto;
      padding: 22px;
      width: 50%;
      min-width: 280px;
      max-width: 520px;
      border-radius: 5px;
      background: #fff;
      border-top: 3px solid #a9a9a9;
      border-bottom: 3px solid #a9a9a9;
      &__item {
        display: flex;
        width: 80%;
        margin: 0 auto;
        span {
          flex: 0 0 50px;
          border-width: 3px 0 3px 3px;
          border-style: solid;
          border-color: #a9a9a9;
          border-radius: 3px 0px 0px 3px;
          color: #606468;
          display: block;
          line-height: 50px;
          text-align: center;
          height: 50px;
        }
        input {
          flex: 1 1 250px;
          border-width: 3px 3px 3px 0;
          border-style: solid;
          border-color: #a9a9a9;
          border-radius: 0px 3px 3px 0px;
          color: #606468;
          margin-bottom: 1.5em;
          padding: 0 8px;
          height: 50px;
        }
      }
      button {
        background: #FF7052;
        border: 0;
        width: 100%;
        height: 40px;
        border-radius: 3px;
        text-align: center;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
        &:hover {
          background: #16aa56;
        }
      }
    }
  }






</style>