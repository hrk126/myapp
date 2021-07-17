import Vue from 'vue';
import Router from 'vue-router';
import store from './store';

Vue.use(Router);

const Login = () => import(/* webpackChunkName: "Login" */ './components/Login.vue');
const Header = () => import(/* webpackChunkName: "Header" */ './components/Header.vue');
const NotFound = () => import(/* webpackChunkName: "NotFound" */ './components/NotFound.vue');
const Home = () => import(/* webpackChunkName: "Home" */ './components/Home.vue');
const User = () => import(/* webpackChunkName: "User" */ './components/User.vue');
const Food = () => import(/* webpackChunkName: "Food" */ './components/Food.vue');
const Users = () => import(/* webpackChunkName: "Users" */ './components/Users.vue');
const Menu = () => import(/* webpackChunkName: "Menu" */ './components/Menu.vue');
const daylyMenu = () => import(/* webpackChunkName: "daylyMenu" */ './components/daylyMenu.vue');
const WeeklyMenu = () => import(/* webpackChunkName: "WeeklyMenu" */ './components/WeeklyMenu.vue');


export default new Router({
  mode: 'history',
  routes: [
    {
      path: '*',
      name: 'notfound',
      components: {
        default: NotFound,
        header: Header
      }
    },
    {
      path: '/',
      name: 'login',
      component: Login,
      beforeEnter(to, from, next) {
        if(store.getters.check) {
          next('/home');
        } else {
          next();
        }
      }
    },
    {
      path: '/home',
      name: 'home',
      components: {
        default: Home,
        header: Header
      },
      beforeEnter(to, from, next) {
        if(store.getters.check) {
          next();
        } else {
          next('/');
        }
      }
    },
    {
      path: '/user',
      name: 'user',
      components: {
        default: User,
        header: Header
      },
      beforeEnter(to, from, next) {
        if(store.getters.check) {
          next();
        } else {
          next('/');
        }
      }
    },
    {
      path: '/food',
      name: 'food',
      components: {
        default: Food,
        header: Header
      },
      beforeEnter(to, from, next) {
        if(store.getters.check) {
          next();
        } else {
          next('/');
        }
      }
    },
    {
      path: '/users',
      name: 'users',
      components: {
        default: Users,
        header: Header
      },
      beforeEnter(to, from, next) {
        if(store.getters.check) {
          next();
        } else {
          next('/');
        }
      }
    },
    {
      path: '/menu',
      name: 'menu',
      components: {
        default: Menu,
        header: Header
      },
      beforeEnter(to, from, next) {
        if(store.getters.check) {
          next();
        } else {
          next('/');
        }
      }
    },
    {
      path: '/daylymenu/:date',
      name: 'daylymenu',
      components: {
        default: daylyMenu,
        header: Header
      },
      beforeEnter(to, from, next) {
        if(store.getters.check) {
          next();
        } else {
          next('/');
        }
      }
    },
    {
      path: '/weeklymenu/:start/:end',
      name: 'weeklymenu',
      components: {
        default:WeeklyMenu,
        header: Header
      },
      beforeEnter(to, from, next) {
        if(store.getters.check) {
          next();
        } else {
          next('/');
        }
      }
    },
  ],
  scrollBehavior(to, from, savedPosition) {
    //画面遷移後のスクロール制御
    return new Promise(resolve => {
      this.app.$root.$once('triggerScroll', ()=> {
        let position = { x: 0, y: 0 };
        if(savedPosition) {
          position = savedPosition;
        }
        if(to.hash) {
          position = {
            selector: to.hash
          };
        }
        resolve(position);
      });
    });
  }
});