<template>
  <div>
    <div v-if="isCollapsedNavmenuActive" class="back-panel"></div>
    <nav class="navbar navbar-expand-lg fixed-top">
      <router-link
        to="/home"
        class="navbar-brand text-secondary font-weight-bold"
        @click.native="collapsedNavmenuDeactivate(); returnTodayMonth()"
      >App</router-link>
      <button
        class="navbar-toggler navbar__button"
        type="button"
        @click="isCollapsedNavmenuActive = !isCollapsedNavmenuActive"
        data-toggle="collapse"
        data-target="#navbar"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <div class="navbar__button__openbtn" :class="{active: isCollapsedNavmenuActive}">
          <div class="openbtn-area">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </button>
      <div class="collapse navbar-collapse" id="navbar">
        <div class="navbar-nav">
          <router-link
            to="/home"
            class="nav-item nav-link text-secondary mx-2"
            active-class="link--active"
            data-toggle="collapse"
            exact
            @click.native="collapsedNavmenuDeactivate(); returnTodayMonth()"
          ><i class="fas fa-home"></i></router-link>
          <a
            class="nav-item dropdown text-secondary mx-2"
            id ="userlink"
            data-toggle="collapse"
          >
            <span class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-user"></i>
            </span>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <router-link to="/user" class="dropdown-item" active-class="link--active" @click.native="collapsedNavmenuDeactivate">{{ username }}</router-link>
              <button class="dropdown-item" @click="collapsedNavmenuDeactivate(); logout()">Logout</button>
            </div>
          </a>
          <router-link
            to="/food"
            class="nav-item nav-link text-secondary mx-2"
            active-class="link--active"
            data-toggle="collapse"
            @click.native="collapsedNavmenuDeactivate"
          >Food</router-link>
          <router-link
            to="/users"
            class="nav-item nav-link text-secondary mx-2"
            active-class="link--active"
            data-toggle="collapse"
            @click.native="collapsedNavmenuDeactivate"
          >Usres</router-link>
          <router-link
            to="/menu"
            class="nav-item nav-link text-secondary mx-2"
            active-class="link--active"
            data-toggle="collapse"
            @click.native="collapsedNavmenuDeactivate"
          >Menu</router-link>
        </div>
        <div class="ml-lg-auto navbar__button__searchbtn">
          <div @click="isSearchActive = !isSearchActive" class="openbtn text-secondary">
            <i v-if="isSearchActive" class="fas fa-times"></i>
            <i v-else class="fas fa-search"></i>
          </div>
          <div class="search-wrap" :class="{active: isSearchActive}">
            <form class="form-inline">
              <input class="form-control" type="search" placeholder="Search..." aria-label="Search">
              <button class="btn bg-light text-secondary" type="button" @click="collapsedNavmenuDeactivate(); SearchDeactivate()">
                <i class="fas fa-search"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isCollapsedNavmenuActive: false,
      isSearchActive: false
    }
  },
  computed: {
    username() {
      return this.$store.getters.username;
    }
  },
  methods: {
    collapsedNavmenuDeactivate() {
      this.isCollapsedNavmenuActive = false;
      $('#navbar').removeClass('show');
    },
    SearchDeactivate() {
      this.isSearchActive = false;
    },
    returnTodayMonth() {
      //カレンダーの表示月を現在の月に戻す
      this.$root.$emit('today-month');
    },
    async logout () {
      await this.$store.dispatch('logout');
      if(!this.$store.getters.check){
        this.$router.push('/');
      }
    },
  },
  watch: {
    $route(to, from) {
      if(to.path === '/user') {
        $('#userlink').addClass('link--active');
      }else {
        $('#userlink').removeClass('link--active');
      }
    }
  }
}
</script>

<style scoped lang="scss">
@import 'resources/sass/app.scss';

  .back-panel {
    position:absolute;
    top:0;
    left: 0;
    right:0;
    bottom: 0;
    background-color: black;
    opacity: .8;
    z-index: 1000;
  }
  nav {
    background-color: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(5px);
  }
  .navbar__button__openbtn {
    position: relative;
    background: transparent;
    cursor: pointer;
    width: 40px;
    height: 40px;
    &:hover {
      span {
        background: var(--light);
      }
    }
    .openbtn-area{
      transition: all .4s;
      span {
        display: inline-block;
        transition: all .4s;
        position: absolute;
        left: 12px;
        height: 3px;
        border-radius: 2px;
        background: var(--dark);
        width: 45%;
        &:nth-of-type(1) {
          top:10px;
        }
        &:nth-of-type(2) {
          top:18px;
        }
        &:nth-of-type(3) {
          top:26px;
        }
      }
    }
    &.active {
      background: white;
      border-radius: 50%;
      transform: rotateY(-360deg);
      span {
        &:nth-of-type(1) {
          top: 12px;
          left: 13px;
          transform: translateY(6px) rotate(-135deg);
          width: 40%;
        }
        &:nth-of-type(2) {
          opacity: 0;
        }
        &:nth-of-type(3) {
          top: 24px;
          left: 13px;
          transform: translateY(-6px) rotate(135deg);
          width: 40%;
        }
      }
    }
  }
  #navbar {
    transition: all .2s;
    &.collapsing {
      opacity: 0!important;
      height: 0!important;
      display: none!important;
    }
    &.show {
      opacity: 1;
      display: flex;
      justify-content: center;
      flex-direction: column;
      margin: 10px;
      background-color: white;
      border-radius: 5px;
      .navbar-nav {
        width: 30%;
        align-items: center;
        .nav-item {
          margin-top: 5px;
          margin-bottom: 5px;
          width: 100%;
          text-align: center;
          border-radius: 5px;
          transition: all .5s;
          text-decoration: none;
          &.link--active , &:hover{
            color: white !important;
            background: var(--secondary);
          }
          .dropdown-menu.show{
            text-align: center;
          }
        }
      }
    }
    .nav-item {
      position: relative;
      line-height: 1.5rem;
      i {
        font-size: 1.5rem;
      }
      &:after {
        content: '';
        position: absolute;
        z-index: -1;
        bottom: 0;
        left: 0;
        width: 0;
        height:100%;
        border-radius: 5px;
        background: var(--secondary);
        transition: all .5s;
        opacity: 0;
      }
      &.link--active {
        color: white !important;
        &:after {
          width: 100%;
          opacity: 1;
        }
      }
      &:hover {
        color: white !important;
        &:after {
          width: 100%;
          opacity: 1;
        }
      }
    }
  }
  .navbar__button__searchbtn {
    position: relative;
    .openbtn{
      width: 50px;
      line-height: 3rem;
      font-size: 1.5rem;
      vertical-align: middle;
      text-align: center;
      cursor: pointer;
    }
    .search-wrap{
      position:absolute;
      top:50%;
      right:50%;
      @include md {
        transform: translateX(50%);
      };
      z-index: -1;
      opacity: 0;
      width:0;
      transition: top 0.4s, opacity 0.4s;
      border-radius: 5px;
      form {
        display: none;
        input{
          -webkit-appearance:none;
          outline: none;
          color: #666;
          width: 180px;
          border: none;
          border-radius: 0;
          transition: all 0.5s;
          letter-spacing: 0.05em;
          &:focus {
            background:#eee;
          }
        }
        button {
          position: absolute;
          right: 20px;
        }
      }
      &.active{
        opacity: 1;
        z-index: 2000;
        width:280px;
        padding:20px;
        top:100%;
        background: #9d9ba7;
        box-shadow: 0 0 8px gray;
        form {
          display: flex;
        }
      }
    }
  }
</style>