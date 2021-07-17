require('./bootstrap');

window.Vue = require('vue').default;

import App from './App.vue';
import router from './router';
import store from './store';

const createApp = async () => {

    //(再)読込時にログイン(user)情報を取得
    await store.dispatch('getUser');

    new Vue({
        router,
        store,
        render: h => h(App),
    }).$mount('#app');

};

createApp();