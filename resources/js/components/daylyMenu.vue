<template>
  <div class="container">
    <div class="row">
      <div class="col menu">
        <h2 class="menu__title">{{ displayDate }}の献立</h2>
        <div class="menu__body">
          <p v-for="menu in menus" :key="menu.id">{{ menu.name }}/{{ menu.price }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment';
import { OK } from '../util';

export default {
  data() {
    return{
      date: this.$route.params.date,
      menus: []
    }
  },
  computed: {
    displayDate() {
      return moment(this.date).format('M[月]D[日]');
    }
  },
  async created() {
    const response = await axios.get('/api/menu/' + this.date);
    if(response.status === OK) {
      this.menus = response.data.menu;
    }
  },
}
</script>

<style scoped lang="scss">
  .menu {
    display: flex;
    flex-direction: column;
    align-items: center;
    &__title {
      max-width: 900px;
      width: 100%;
      padding: 1rem;
      text-align: center;
    }
    &__body {
      max-width: 900px;
      width: 100%;
    }
  }
</style>