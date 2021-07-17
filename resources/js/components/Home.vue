<template>
  <div class="container">
    <div class="row">
      <div class="col calendar">
        <h2 class="calendar__title">{{ displayMonth }}</h2>
        <div class="calendar__selector">
          <div @click="prevMonth" class="calendar__selector--prev" :class="{hidden: isHidden}">
            <span><i class="fas fa-angle-double-left"></i></span>
            前の月
          </div>
          <div @click="nextMonth" class="calendar__selector--next">
            次の月
            <span><i class="fas fa-angle-double-right"></i></span>
          </div>
        </div>
        <div class="calendar__body">
          <div class="calendar__body__week">
            <div v-for="n in 7" :key="n" v-color="n" class="calendar__body__cell--title">
              {{ getWeek(n-1) }}
            </div>
          </div>
          <div v-for="(week, index) in calendars" :key="index" class="calendar__body__week">
            <router-link
              v-for="(day, index) in week"
              :key="index"
              :to="{name:'daylymenu', params: {date: `${day.month}-${day.day}`}}"
              class="calendar__body__cell"
              :class="{outside: isOutside(day)}">
              <span class="calendar__body__cell--text">{{ Number(day.day) }}</span>
              <pre v-if="day.message" class="calendar__body__cell--message">受付終了</pre>
            </router-link>
            <router-link
              :to="{name:'weeklymenu', params: {start: `${week[0].month}-${week[0].day}`, end: `${week[6].month}-${week[6].day}`}}"
              class="weeklymenu">
              <i class="fas fa-calendar-week"></i><span>週間<br>メニュー</span>
            </router-link>
          </div>
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
    return {
      //表示月
      currentDate: moment(),
      //当日(アクセスした日)
      today: moment().clone(),
      //メニュー
      monthMenu: [],
      //前月へのリンク
      isHidden: true
    };
  },
  methods: {
    getStartDate() {
      //表示月の最初の日にち(前月分含む)
      let date = this.currentDate.clone();
      date.startOf("month");
      const dayOfWeek = date.day();
      return date.subtract(dayOfWeek, "days");
    },
    getEndDate() {
      //表示月の最後の日にち(次月分含む)
      let date = this.currentDate.clone();
      date.endOf("month");
      const dayOfWeek = date.day();
      return date.add(6 - dayOfWeek, "days");
    },
    getCalendar() {
      const startDate = this.getStartDate();
      const endDate = this.getEndDate();
      //表示月の週の数
      const weeks = Math.ceil(endDate.diff(startDate,'days') / 7);
      let calendars = [];
      let calendarDate = this.getStartDate();

      for(let week = 0; week < weeks; week++) {
        let weekRow = [];
        for (let day = 0; day < 7; day++) {
          weekRow.push({
            day: calendarDate.format("DD"),
            month: calendarDate.format("YYYY-MM"),
            dayOfWeek: calendarDate.day(),
            timeStamp: calendarDate.valueOf(),
            //"受付終了"文字の表示判定
            message: false
          });
          calendarDate.add(1,'days');
        }
        calendars.push(weekRow);
      }
      return calendars;
    },
    nextMonth() {
      this.currentDate = moment(this.currentDate).add(1, "month");
    },
    prevMonth() {
      this.currentDate = moment(this.currentDate).subtract(1, "month");
    },
    getWeek(dayIndex) {
      const week = ["日", "月", "火", "水", "木", "金", "土"];
      return week[dayIndex];
    },
    dispatchChangeMonth() {
      //vuexのmonthプロパティにカレンダーの表示月を代入する
      this.$store.dispatch('changeMonth', this.currentDate.month() + 1);
    },
    async getMonthMenu() {
      //カレンダーに表示されている月のメニューを取得
      const response = await axios.get('/api/monthmenu/' + this.currentDate.format("YYYY-MM"));
      if(response.status === OK) {
        if(response.data.monthmenu) {
          this.monthMenu = response.data.monthmenu;
        }
      }
    },
    returnTodayMonth() {
      //カレンダーを現在の月に戻す
      this.currentDate = this.today;
    }
  },
  computed: {
    calendars() {
      return this.getCalendar();
    },
    displayMonth() {
      return this.currentDate.format('YYYY[年]M[月]')
    },
    currentMonth() {
      return this.currentDate.format('YYYY-MM');
    },
    isOutside() {
      //リンクを無効にする
      return function(day) {
        let todayStartValue = this.today.clone().startOf('day').valueOf();
        //条件1.表示月以外の日にち
        if( this.currentMonth !== day.month ) {
          return true;
        //条件2.日曜日
        }else if(day.dayOfWeek === 0){
          return true;
        //条件3.前日以前
        }else if(day.timeStamp < todayStartValue){
          day.message = true;
          return true;
        //条件4.当日の場合
        }else if(day.timeStamp === todayStartValue){
          //9時以降またはメニューが無い
          if((this.today.hour() >= 9) | (!this.monthMenu.includes(`${day.month}-${day.day}`))){
            day.message = true;
            return true;
          }
        //条件5.メニューが無い
        }else if(!this.monthMenu.includes(`${day.month}-${day.day}`)){
          return true;
        }else{
          return false;
        }
      }
    },
  },
  directives: {
    //日=>赤,土=>青
    color(el, binding) {
      if(binding.value === 1) {
        el.style.color = 'red';
      }else if(binding.value === 7) {
        el.style.color = 'blue';
      }
    }
  },
  watch: {
    currentDate() {
      this.getMonthMenu();
      this.dispatchChangeMonth();
      //表示月が現在の場合、前月へのリンクを隠す
      if(this.currentDate.format('YYYY-MM') !== this.today.format('YYYY-MM')){
        this.isHidden = false;
      }else{
        this.isHidden = true;
      }
    }
  },
  mounted() {
    this.getMonthMenu();
    this.dispatchChangeMonth();
    //Header.vueのHomeボタンを押した際に現在の月に戻る
    this.$root.$on('today-month', ()=> {
      this.returnTodayMonth();
    });
  }
}
</script>

<style scoped lang="scss">
@import 'resources/sass/app.scss';

  .calendar {
    display: flex;
    flex-direction: column;
    align-items: center;
    &__title {
      padding: 1rem;
      width: 100%;
      text-align: center;
      font-size: 2.3rem;
      @include md{
        font-size: 2rem;
      };
      color: white;
      text-shadow: 2px  2px 10px #777, -2px  2px 10px #777, 2px -2px 10px #777, -2px -2px 10px #777;
    }
    &__selector {
      display: flex;
      width: 100%;
      height: 2rem;
      max-width:900px;
      margin: .5rem 0;
      justify-content: space-between;
      align-items: center;
      position: relative;
      &--next {
        position: relative;
        text-align: end;
        color: white;
        cursor: pointer;
        font-weight: 600;
        text-shadow: 2px  2px 10px #485461, -2px  2px 10px #485461, 2px -2px 10px #485461, -2px -2px 10px #485461;
        span {
          position: absolute;
          top: 80%;
          left: 0;
          animation: pathmove linear 1.5s infinite;
          opacity:0;
        }
      }
      &--prev {
        position: relative;
        text-align:start;
        color: white;
        cursor: pointer;
        font-weight: 600;
        text-shadow: 2px  2px 10px #485461, -2px  2px 10px #485461, 2px -2px 10px #485461, -2px -2px 10px #485461;
        span {
          position: absolute;
          top: 80%;
          left: 100%;
          animation: pathmove linear 1.5s infinite reverse;
          opacity:0;
        }
      }
    }
    &__body {
      display: block;
      width: 100%;
      max-width:900px;
      margin:2rem auto;
      border-top:1px solid #E0E0E0;
      font-size:1rem;
      &__week {
        display: flex;
        width: 100%;
        border-left:1px solid #E0E0E0;
        position:relative;
      }
      &__cell {
        position: relative;
        flex: 1;
        text-align: center;
        color: var(--secondary);
        background-color: white;
        border-right:1px solid #E0E0E0;
        border-bottom:1px solid #E0E0E0;
        transition: all .5s;
        &:before {
          content: "";
          display: block;
          padding-top: 100%;
        }
        &--text {
          position: absolute;
          top: 0;
          left: 0;
          bottom: 0;
          right: 0;
          font-size: 1.3rem;
          @include xl {
            font-size: 1rem;
          };
          @include md {
            font-size: .9rem;
          };
        }
        &--message {
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          font-size: 1rem;
          color: red;
          @include xl {
            font-size: .8rem;
          };
          @include md {
            font-size: 0px;
            &:after {
              content:'終了';
              font-size: 1px;
            }
          };
        }
        &:hover {
          text-decoration: none;
          background-color: #afcfef;
        }
        &--title {
          flex:1;
          border-right:1px solid #E0E0E0;
          border-bottom:1px solid #E0E0E0;
          text-align:center;
          font-weight: 600;
          background-color: rgba(255, 255, 255, 0.3);
          backdrop-filter: blur(5px);
        }
      }
    }
  }
  .outside {
    background-color: #d1d1d1;
    pointer-events:none;
  }
  .hidden {
    visibility:hidden;
    pointer-events: none;
  }
  .weeklymenu {
    padding-left: 5px;
    position: absolute;
    top: 50%;
    right: 0;
    transform: translate(100%, -50%);
    color: white;
    text-shadow: 1px  1px 5px #777, -1px  1px 5px #777, 1px -1px 5px #777, -1px -1px 5px #777;
    &:hover {
      text-decoration: none;
      color: #d1d1d1;
    }
    span {
      padding-left: 5px;
      @include md {
        display:none;
      }
    }
  }

  @keyframes pathmove{
    0%{
      left:0;
      opacity: 0;
    }
    30%{
      opacity: 1;
    }
    100%{
      left:calc(100% - 10px);
      opacity: 0;
    }
  }

</style>