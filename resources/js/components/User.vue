<template>
<div>
  <h2>User</h2>
  <transition-group name="down" mode="out-in" tag="ul">
    <li v-for="user in users" :key="user.id">{{ user.id }} : {{ user.name }}</li>
  </transition-group>
  <button @click="shuffle">shuffle</button>
  <div class="long"></div>
  <p>something</p>
</div>
</template>

<script>
import { OK } from '../util';

export default {
  data() {
    return{
      users:[]
    }
  },
  methods: {
    shuffle() {
      this.users = _.shuffle(this.users);
    }
  },
  async created() {
    const response = await axios.get('/api/user');
    if(response.status === OK) {
      this.users = response.data.users;
    }
  },
}
</script>

<style scoped>
  ul {
    list-style: none;
    padding: 0;
  }
  li {
    margin: 0;
    background-color: aqua;
  }
  .long {
    height: 500px;
    background-color: dimgrey;
  }
  .down {
    transition: all 1s;
  }

  .down-move {
    /* transition-groupのみ */
    transition: transform 1s;
  }
  .down-enter,
  .down-leave-to {
    opacity: 0;
    line-height: 0;
  }
  .down-enter-active,
  .down-leave-active {
    transition: all 1s;
  }
  .down-enter-to,
  .down-leave {
    opacity: 1;
    line-height: 1.6;
  }
</style>
