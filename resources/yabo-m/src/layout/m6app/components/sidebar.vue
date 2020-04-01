<template>
  <div class="sidebar-wrap">
    <div class="logo" />
    <div class="home-page clearfix" @click="closeSidebar">
      <div class="match-img" />
      <div class="text">赛事</div>
      <div class="icon" />
    </div>
    <div class="champion-btn">
      <div class="container clearfix" @click="toChampion">
        <div class="match-img" />
        <div class="text">冠军投注</div>
        <div class="icon" />
      </div>
    </div>
    <div class="odds-setting">
      <div class="title">盘口设置</div>
      <div class="odds-btn-wrap">
        <div class="odds-btn">
          <label for="eu2">
            <input id="eu2" v-model="selectHandicap" type="radio" name="radio2" value="3">
            <span>欧洲盘</span>
          </label>
          <!-- <hr> -->
          <!-- <input id="eu" v-model="selectHandicap" type="radio" value="3" name="radio">
          <label for="eu">欧洲盘</label> -->
        </div>
        <div class="odds-btn">
          <label for="eu">
            <input id="hongkong2" v-model="selectHandicap" type="radio" name="radio2" value="2">
            <span>香港盘</span>
          </label>
          <!-- <hr> -->
          <!-- <input id="hongkong" v-model="selectHandicap" type="radio" value="2" name="radio">
          <label for="eu">香港盘</label> -->
        </div>
      </div>
    </div>
    <div class="match-setting">
      <div class="title">赛事排序</div>
      <div class="match-btn-wrap">
        <div class="match-btn">
          <label for="eu">
            <input id="eu" v-model="selectMatch" type="radio" value="1">
            <span>按时间排序</span>
          </label>
          <!-- <input id="eu" v-model="selectMatch" type="radio" value="1">
          <label for="eu">按时间排序</label> -->
        </div>
        <div class="match-btn">
          <label for="eu">
            <input id="hongkong" v-model="selectMatch" type="radio" value="2">
            <span>按联赛排序</span>
          </label>
          <!-- <input id="hongkong" v-model="selectMatch" type="radio" value="2">
          <label for="eu">按联赛排序</label> -->
        </div>
      </div>
    </div>
    <div class="odds-change">
      <div class="title">自动接收赔率</div>
      单注：
      <div class="odds-selct">
        <label for="eu">
          <input id="eu" v-model="selectOddsSingle" type="radio" value="自动接收较佳赔率">
          <span>自动接收较佳赔率</span>
        </label>
      </div>
      串关：
      <div class="odds-selct">
        <label for="eu">
          <input id="eu" v-model="selectOddsCombo" type="radio" value="自动接收任何赔率">
          <span>自动接收任何赔率</span>
        </label>
      </div>
      <!-- <div class="odds-selct">
        <label for="eu">
          <input id="eu" v-model="selectOdds" type="radio" value="自动接收任何赔率">
          <span>自动接收任何赔率</span>
        </label>
        <input id="eu" v-model="selectOdds" type="radio" value="自动接收任何赔率">
        <label for="eu">自动接收任何赔率</label>
      </div>
      <div class="odds-selct">
        <label for="eu">
          <input id="eu" v-model="selectOdds" type="radio" value="不接受任何赔率变动">
          <span>不接受任何赔率变动</span>
        </label>
        <input id="eu" v-model="selectOdds" type="radio" value="不接受任何赔率变动">
        <label for="eu">不接受任何赔率变动</label>
      </div> -->
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  data() {
    return {
      selectHandicap: this.$cookie.get('odds_type'),
      selectMatch: this.$cookie.get('filterMatch'),
      selectOddsSingle: '自动接收较佳赔率',
      selectOddsCombo: '自动接收任何赔率',
      firstChampionSport: ''
    }
  },
  computed: {
    ...mapGetters({
      allEventCount: 'getAllEventCount'
    })
  },
  watch: {
    allEventCount() {
      this.firstChampionSport = this.allEventCount.Outright.Sport
    },
    selectHandicap(val) {
      this.$cookie.set('odds_type', val)
      location.reload()
    },
    selectMatch(val) {
      this.$cookie.set('filterMatch', val)
      location.reload()
    }
  },
  methods: {
    ...mapActions({
      actonSetSidebar: 'm6app/actonSetSidebar'
    }),
    closeSidebar() {
      this.actonSetSidebar(false)
    },
    toChampion() {
      this.firstChampionSport = this.allEventCount.Outright.Sport[0].SportId
      this.actonSetSidebar(false)
      this.$router.push(`/champion/${this.firstChampionSport}`)
    }
  }
}
</script>

<style lang="scss" scoped>
.sidebar-wrap {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #FFF;
  z-index: 2;
  width: 70%;
  .logo {
    background: url('~@/assets/m6app/bitbet_logo.png') 50% 0 no-repeat;
    background-size: contain;
    width: 60%;
    height: 93px;
    margin: 30px 0 0 26px;
  }
  .home-page {
    padding: 10px 20px;
    .match-img {
      float: left;
      background: url('~@/assets/m6app/ic_side_event.png') 0 0 no-repeat;
      background-size: contain;
      width: 22px;
      height: 22px;
    }
    .text {
      float: left;
      margin-left: 20px;
    }
    .icon {
      float: right;
      background: url('~@/assets/m6app/event_arrow_black.png') 0 5px no-repeat;
      background-size: contain;
      width: 15px;
      height: 20px;
    }
  }
  .champion-btn {
    padding: 0 20px;
    .container {
      padding: 20px 0;
      border-bottom: 5px solid #F3F3F3;
      .match-img {
        float: left;
        background: url('~@/assets/m6app/ic_side_championship_betting.png') 0 0 no-repeat;
        background-size: contain;
        width: 22px;
        height: 22px;
      }
      .text {
        float: left;
        margin-left: 20px;
      }
      .icon {
        float: right;
        background: url('~@/assets/m6app/event_arrow_black.png') 0 5px no-repeat;
        background-size: contain;
        width: 15px;
        height: 20px;
      }
    }
  }
  .odds-setting {
    padding: 20px 20px 0;
    font-size: 12px;
    .title {
      margin-bottom: 12px;
      color: #000;
      font-size: 14px;
      font-weight: bold;
    }
    .odds-btn-wrap {
      padding-bottom: 20px;
      border-bottom: 3px solid #F3F3F3;
      .odds-btn {
        display: inline-block;
        width: 49%;
        label {
          font-weight: normal;
        }
      }
    }
  }
  .match-setting {
    padding: 20px 20px 0;
    font-size: 12px;
    .title {
      margin-bottom: 12px;
      color: #000;
      font-size: 14px;
      font-weight: bold;
    }
    .match-btn-wrap {
      padding-bottom: 20px;
      border-bottom: 3px solid #F3F3F3;
      .match-btn {
        display: inline-block;
        width: 49%;
        label {
          font-weight: normal;
        }
      }
    }
  }
  .odds-change {
    padding: 20px 20px 0;
    font-size: 12px;
    .title {
      margin-bottom: 12px;
      color: #000;
      font-size: 14px;
      font-weight: bold;
    }
    .odds-selct {
      margin: 5px 0px 10px 0px;
      label {
        font-weight: normal;
      }
    }
  }
}
input[type="radio"] {
  position: absolute;
  opacity: 0;
  margin: 4px 0px;
  -ms-margin: 4px 0px;
  -webkit-margin: 4px 0px;
  -o-margin: 4px 0px;
  -moz-margin: 4px 0px;
  // clip: rect(0, 0, 0, 0);
  }
input[type="radio"] + span::before {
  content: "\a0";		/*不换行空格*/
  display: inline-block;
  vertical-align: middle;
  font-size: 16px;
  width: 12px;
  height: 12px;
  margin-right: 10px;
  border: 1px solid #ddd;
  border-radius: 50%;
  text-indent: 5px;
  line-height: 1;
}
input[type="radio"]:checked + span::before {
  width: 12px;
  height: 12px;
  background-color: #e6a23c;
  background-clip: content-box;
  padding: 2px;
  border: 1px solid #e6a23c;
}
</style>
