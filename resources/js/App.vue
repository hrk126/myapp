<template>
  <div style="min-height: 100vh; position: relative">
    <transition name="fade" mode="out-in">
      <img v-if="isHome" :src="backImage" :key="backImage" class="background" alt="back-image">
    </transition>
    <router-view name="header"></router-view>
    <main class="container">
      <div class="row">
        <div class="col-md-11 mx-auto">
          <transition name="fade" mode="out-in" @before-enter="beforeEnter">
            <router-view></router-view>
          </transition>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isHome: false
    }
  },
  methods: {
    beforeEnter() {
      this.$root.$emit('triggerScroll');
    }
  },
  computed: {
    backImage() {
      //homeの場合は画像を表示
      let month = this.$store.getters.getMonth;
      return `images/${month}.jpg`;
    }
  },
  watch: {
    $route() {
      if(this.$route.path.match(/^\/home$/)) {
        this.isHome = true;
      }else {
        this.isHome = false;
        //home以外はvuexのmonthプロパティを-1にして画像を表示させない
        this.$store.dispatch('changeMonth', -1 );
      }
    }
  },
}
</script>

<style lang="scss">
@import 'resources/sass/app.scss';

  .background {
    object-fit: cover;
    position: fixed;
    width: 100%;
    height:100%;
  }
  main {
    padding-top: 5rem;
    padding-bottom: 5rem;
  }

  .fade- {
    &move {
      transition: transform 1s;
    }
    &enter {
      opacity: 0;
      &-to {
        opacity: 1;
      }
      &-active {
        transition: all 1s;
      }
    }
    &leave {
      opacity: 1;
      &-to {
        opacity: 0;
      }
      &-active {
        transition: all 1s;
      }
    }
  }
</style>