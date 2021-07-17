<template>
  <tr>
    <!-- id -->
    <th scope="row" class="id">{{ id }}</th>
    <!-- name -->
    <transition name="fade" mode="out-in">
      <td v-if="isUpdate" key="update" class="name">
        <span>{{ message.name }}</span>
        <input type="text" v-model.lazy="name">
        <span class="comment">必須</span>
      </td>
      <td v-else key="not-update" class="name">{{ name }}</td>
    </transition>
    <!-- price -->
    <transition name="fade" mode="out-in">
      <td v-if="isUpdate" key="update" class="price">
        <span>{{ message.price }}</span>
        <input type="number" v-model.lazy.number="price">
        <span class="comment">必須</span>
      </td>
      <td v-else key="not-update" class="price">{{ price.toLocaleString() }}</td>
    </transition>
    <!-- image -->
    <transition name="fade" mode="out-in">
      <td v-if="isUpdate" key="update" class="image">
        <img v-if="imageReady" :src="confirmedImage">
        <template v-else>
          <span>{{ message.image }}</span>
          <label :for="fileUpload">ファイルを選択して下さい
            <input type="file" :id="fileUpload" @change.stop="confirmImage">
          </label>
          <span class="comment">任意</span>
        </template>
      </td>
      <td v-else key="not-update" class="image"><img :src="'storage/' + image"></td>
    </transition>
    <!-- button -->
    <transition name="fade" mode="out-in">
      <td v-if="isUpdate" key="update" class="btn-space">
        <button @click="reset" class="m-2 mr-4 btn btn-secondary">戻る</button>
        <button @click="upload" class="m-2 ml-4 btn btn-primary">登録</button>
      </td>
      <td v-else key="not-update" class="btn-space">
        <button @click="isUpdate=true" class="m-2 mr-4 btn btn-secondary">変更</button>
        <button @click="deleteSelf" class="m-2 ml-4 btn btn-danger">削除</button>
      </td>
    </transition>
  </tr>
</template>
 
<script>
import { OK, UNPROCESSABLE_ENTITY } from '../util';

export default {
  data(){
    return {
      id: this.food.id,
      fileUpload: 'file-upload' + this.food.id,//ファイル選択用inputのid属性
      name: this.food.name,
      price: this.food.price,
      image: this.food.image_file,
      isUpdate: this.food.isUpdate,
      isNew: this.food.isNew,
      file: '',
      message: {'name': '', 'price': '', 'image': ''},
      imageReady: false,//アップロード用imgとinputの切替
      confirmedImage: '',
    };
  },
  props: {
    food: Object,
  },
  methods: {
    async getData(id) {
      const response = await axios.get(`api/food/${id}`);
      if(response.status === OK) {
        const newProps = response.data.food;
        newProps.isUpdate= false;
        newProps.isNew = false;
        newProps.key = newProps.id + Date.now();
        this.$emit('change-props', newProps);
        this.file = '';
        this.message = {'name': '', 'price': '', 'image': ''};
        this.imageReady = false;
        this.confirmedImage = '';
      }
    },
    reset() {
      //戻るボタン(データの初期化)
      this.name = this.food.name;
      this.price = this.food.price;
      this.image = this.food.image_file;
      this.isUpdate = false;
      this.file = '';
      this.message = {'name': '', 'price': '', 'image': ''};
      this.imageReady = false;
      this.confirmedImage = '';
    },
    confirmImage(e) {
      this.$set(this.message, 'image', '');
      this.file = e.target.files[0];
      if (!this.file.type.match("image.*")) {
        this.$set(this.message, 'image', '画像ファイルを選択して下さい');
        this.file = '';
        return;
      }
      const reader = new FileReader();
      reader.readAsDataURL(this.file);
      reader.onload = e => {
        this.confirmedImage = e.target.result;
        this.imageReady = true;
      };
    },
    validate() {
      this.message = {'name': '', 'price': '', 'image': ''};
      let flag = false;
      if(this.name === '') {
        this.$set(this.message, 'name', '名前を入力して下さい');
        flag = true;
      }
      if(this.price === '') {
        this.$set(this.message, 'price', '金額を入力して下さい');
        flag = true;
      }else if(!Number.isInteger(this.price)) {
        this.$set(this.message, 'price', '整数を入力して下さい');
        flag = true;
      }
      return flag;
    },
    async upload() {
      //登録ボタン
      
      if(this.validate()) {
        return;
      }

      const data = new FormData();
      data.append('id', this.id);
      data.append('name', this.name);
      data.append('price', this.price);
      data.append('file', this.file);

      let config;
      if(this.isNew) {
        //新規時
        config = {
          method: 'POST',
          url: '/api/food',
          data: data
        };
      } else {
        //変更時
        data.append('_method', 'PATCH');
        config = {
          method: 'POST',
          url: `/api/food/${this.id}`,
          data: data
        };
      }

      const response = await axios(config);
      if(response.status === OK) {
        alert(response.data.success.message);
        this.getData(response.data.success.id);
      }else if(response.status === UNPROCESSABLE_ENTITY) {
        if(err.response.data.errors.name) {
          this.message.name = err.response.data.errors.name[0];
        }
        if(err.response.data.errors.price) {
          this.message.price = err.response.data.errors.price[0];
        }
        if(err.response.data.errors.file) {
          this.message.image = err.response.data.errors.file[0];
        }
      }

    },
    async deleteSelf() {
      //削除ボタン
      if(window.confirm('削除して宜しいですか？')) {
        if(this.isNew) {
          //新規時
          this.$emit('delete');
        } else {
          //変更時
          const response = await axios.delete(`/api/food/${this.id}`);
          if(response.status === OK) {
            if(response.data.success) {
              alert(response.data.success.message);
              this.$emit('delete');
            }else {
              alert(response.data.failure.message);
            }
          }
        }
      }
    }
  }
}
</script>

<style scoped lang="scss">
@import 'resources/sass/app.scss';

  img {
    width: 60%;
    height: auto;
    max-height: 88px;
    @include lg {
      max-height: 51px;
    }
    object-fit: cover;
  }
  th, td {
    vertical-align: middle!important;
    height: 113px;
    @include lg {
      height: 73px;
    }
  }
  .id {
    width: 5%;
    min-width: 30px;
    text-align: center;
  }
  .name {
    width: 25%;
    min-width: 150px;
    position: relative;
  }
  .price {
    width: 15%;
    min-width: 90px;
    text-align: end;
    position: relative;
    span:not(.comment){
      font-size: .8rem;
      @include lg {
        left: -5px;
        font-size: .5rem;
      }
    }
  }
  .image {
    width: 25%;
    min-width: 150px;
    text-align: center;
    position: relative;
    label {
      width: 100%;
      @include lg {
        font-size: .5rem;
      }
      padding: 5px;
      margin-bottom: 0;
      background-color:#eee;
      cursor: pointer;
    }
    input {
      display:none;
    }
  }
  span:not(.comment) {
    color: red;
    position: absolute;
    top: 3px;
    @include lg {
      font-size: .5rem;
    }
  }
  .comment {
    color: blue;
    position: absolute;
    top: 100%;
    left: 50%;
    transform:translate(-50%, -100%);
  }
  .btn-space {
    width: 30%;
    min-width: 180px;
    text-align: center;
  }
  input {
    width: 100%;
    padding: 5px;
    background-color:#eee;
    &:focus {
      background:#d1d1d1;
    }
  }
</style>