import Vue from 'vue';
import Vuex from 'vuex';
import { OK, UNPROCESSABLE_ENTITY, INTERNAL_SERVER_ERROR } from './util'

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    /**
     *カレンダーの表示月(1～12 | -1)を保持
     *App.vueのbackImage名の参照元
     */
    month: {
      type: Number,
    },
    //ログインユーザー情報
    user: null,
    //login時のバリデーションエラーを保持
    loginErrorMessages: null
  },
  getters: {
    getMonth: state => state.month,
    //ログインしているとuserを返す
    check: state => !! state.user,
    username: state => state.user ? state.user.name : '',
    getloginErrorMessages: state => state.loginErrorMessages
  },
  mutations: {
    changeMonth(state, newMonth) {
      state.month = newMonth;
    },
    setUser(state, user) {
      state.user = user;
    },
    setLoginErrorMessages (state, messages) {
      state.loginErrorMessages = messages;
    }
  },
  actions: {
    //カレンダーの表示月変更
    changeMonth({ commit }, newMonth) {
      commit('changeMonth', newMonth);
    },
    //ログイン
    async login({ commit }, data) {
      const response = await axios.post('/api/login', data);
      if (response.status === OK) {
        commit('setUser', response.data);
      }else if (response.status === UNPROCESSABLE_ENTITY) {
        commit('setLoginErrorMessages', response.data.errors);
      } else if (response.status === INTERNAL_SERVER_ERROR) {
        alert('サーバーエラー');
      }
    },
    //ログアウト
    async logout({ commit }) {
      const response = await axios.post('/api/logout');
      if (response.status === OK) {
        commit('setUser', null);
      }
    },
    //ユーザー情報取得
    async getUser({ commit }) {
      const response = await axios.get('/api/user');
      const user = response.data || null;
      commit('setUser', user);
    }
  },
});