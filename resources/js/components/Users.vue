<template>
  <div class="container">
    <div class="row">
      <div class="col user">
        <div class="user__head">
          <h2 class="user__head__title">社員データ一覧</h2>
          <button @click="add" class="user__head__button btn btn-secondary">新規</button>
        </div>
        <div class="user__body">
          <table class="table table-bordered">
            <thead class="thead-light">
              <tr>
                <th scope="col" style="width: 5%">#</th>
                <th scope="col" style="width: 15%">社員ナンバー</th>
                <th scope="col" style="width: 25%">名前</th>
                <th scope="col" style="width: 25%">パスワード</th>
                <th scope="col" style="width: 30%">操作</th>
              </tr>
            </thead>
            <transition-group name="list-move" tag="tbody">
              <UserRow v-for="(user, index) in users"
                :key="user.key"
                :user="user"
                @change-props="change(index, $event)"
                @delete="remove(index)"
                class="list-move-item"
              >
              </UserRow>
            </transition-group>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import UserRow from './UserRow.vue';
import { OK } from '../util';

export default {
  data() {
    return{
      users: [],
      newId: 1
    };
  },
  methods: {
    add() {
      //新規作成
      let user = {
        id: 'n' + this.newId,
        name: '',
        price: '',
        image_file: '',
        isUpdate: true,
        isNew: true,
        key: 'n' + this.newId
      };
      this.users.splice(0, 0, user);
      this.newId++;
    },
    change(index, data) {
      this.users.splice(index, 1, data);
    },
    remove(index) {
      //削除
      this.users.splice(index, 1);
    }
  },
  components: {
    UserRow
  },
  async created() {
    const response = await axios.get('/api/user');
    if(response.status === OK) {
      this.users = response.data.users.reverse();
      //isUpdate,isNew,keyプロパティ追加
      this.users = this.users.map(function(o){
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
  .user__head {
    display: flex;
    justify-content: space-between;
    padding: 1rem 0;
  }
  .user__body {
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