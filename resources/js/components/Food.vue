<template>
  <div class="container">
    <div class="row">
      <div class="col food">
        <div class="food__head">
          <h2 class="food__head__title">食品データ一覧</h2>
          <button @click="add" class="food__head__button btn btn-secondary">新規</button>
        </div>
        <div class="food__body">
          <table class="table table-bordered">
            <thead class="thead-light">
              <tr>
                <th scope="col" style="width: 5%">#</th>
                <th scope="col" style="width: 25%">名前</th>
                <th scope="col" style="width: 15%">金額 （円）</th>
                <th scope="col" style="width: 25%">イメージ</th>
                <th scope="col" style="width: 30%">操作</th>
              </tr>
            </thead>
            <transition-group name="list-move" tag="tbody">
              <FoodRow v-for="(food, index) in foods"
                :key="food.key"
                :food="food"
                @change-props="change(index, $event)"
                @delete="remove(index)"
                class="list-move-item"
              >
              </FoodRow>
            </transition-group>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FoodRow from './FoodRow.vue';
import { OK } from '../util';

export default {
  data() {
    return{
      foods: [],
      newId: 1
    };
  },
  methods: {
    add() {
      //新規作成
      let food = {
        id: 'n' + this.newId,
        name: '',
        price: '',
        image_file: '',
        isUpdate: true,
        isNew: true,
        key: 'n' + this.newId
      };
      this.foods.splice(0, 0, food);
      this.newId++;
    },
    change(index, data) {
      this.foods.splice(index, 1, data);
    },
    remove(index) {
      //削除
      this.foods.splice(index, 1);
    }
  },
  components: {
    FoodRow
  },
  async created() {
    const response = await axios.get('/api/food');
    if(response.status === OK) {
      this.foods = response.data.foods.reverse();
      //isUpdate,isNew,keyプロパティ追加
      this.foods = this.foods.map(function(o){
        o.isUpdate= false;
        o.isNew = false;
        o.key = o.id + Date.now();//v-forのkeyバインド用
        return o;
      });
    }
  },
}
</script>

<style scoped lang="scss">
  table {
    position: relative;
  }
  thead {
    text-align: center;
  }
  .food__head {
    display: flex;
    justify-content: space-between;
    padding: 1rem 0;
  }
  .food__body {
    overflow-x: auto;
    overflow-y: hidden;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
  }
  .list-move-item {
    transition: all 1s;
  }
  .list-move-enter, .list-move-leave-to {
    opacity: 0;
    transform: translateY(30px);
  }
  .list-move-leave-active {
    position: absolute;
    width: 100%;
  }
</style>