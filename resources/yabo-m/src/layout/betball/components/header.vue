<template>
  <div>
    <div class="sport-header">
      <div class="hLeft" @click="$router.go(-1)">
        <img src="@/icons/png/back.png" class="back">
      </div>
      <div class="hLeft">
        <div
          class="hTxt"
          :class="navHeader === 'live' ? 'chgColor': ''"
          @click="selectNavFilter('live')"
        >
          <span>{{ $t('S_LIVE_2') }}</span>
        </div>
        <div
          class="hTxt"
          :class="navHeader === 'today' ? 'chgColor': ''"
          @click="selectNavFilter('today')"
        >
          <span>{{ $t('S_NOT_START') }}</span>
        </div>
      </div>
      <div class="hRight">
        <div class="dline"><span>¥{{ Balance }}</span></div>
        <div class="dline"><img src="../../../icons/png/clipboard.png" class="hImg" @click="showBet()"></div>
      </div>
    </div>
    <div class="sport-navbar">
      <div class="nLeft">
        <div class="flow-x">
          <div
            v-for="(count, sportId) in curSportCount"
            :key="`count-${sportId}`"
            class="navLeft"
          >
            <div
              :class="[
                'sport-btn',
                {
                  chgColor: curSportId === sportId
                }
              ]"
              @click="selectSport(sportId)"
            >
              {{ sportList[sportId] ? sportList[sportId].sportName : '' }}
            </div>
          </div>
        </div>
      </div>
      <div class="nRight">
        <div :class="['dline', { chgFuncColor: selectFunc === 1 }]" @click="selFunction(1)">
          <div v-if="selectFunc===1"><img src="@/icons/png/all(betball)Blue.png" class="nImg"></div>
          <div v-else><img src="@/icons/png/all(betball).png" class="nImg"></div>
          <div><span>{{ $t('S_ALL_FUNCTION') }}</span></div>
        </div>
        <div :class="['dline', { chgFuncColor: selectFunc === 2 }]" @click="selFunction(2)">
          <div v-if="selectFunc===2"> <img src="@/icons/png/prefer(betball)Blue.png" class="nImg"></div>
          <div v-else> <img src="@/icons/png/prefer(betball).png" class="nImg"></div>
          <div><span>{{ $t('S_PREFER_FUNCTION') }}</span></div>
        </div>
      </div>
    </div>
    <transition>
      <myWager :class="{ hasOpenWager: isShowBet }" @closeBet="closeBet" />
    </transition>
    <transition>
      <funcAll
        v-show="isShowFuncAll"
        :sport-count="sportCount"
        :sport-list="sportList"
        @closeFuncAll="closeFuncAll"
      />
    </transition>
    <transition>
      <funcPrefer v-show="isShowFuncPrefer" @closeFuncPre="closeFuncPre" />
    </transition>
  </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'
import myWager from '@/layout/betball/components/myWager.vue'
import funcAll from '@/layout/betball/components/funcAll.vue'
import funcPrefer from '@/layout/betball/components/funcPrefer.vue'
import { getAllSportCount, getSportKind, getBalance } from '@/api/betball'

export default {
  components: {
    myWager,
    funcAll,
    funcPrefer
  },
  data() {
    return {
      event: false,
      isShowBet: false,
      isShowFuncAll: false,
      isShowFuncPrefer: false,
      selectFunc: '',
      sportList: {},
      Balance: {}
    }
  },
  computed: {
    ...mapGetters({
      navHeader: 'betball/getNavHeader',
      curSportId: 'betball/getCurSportId',
      sportCount: 'betball/getSportCount'
    }),
    curSportCount() {
      return this.sportCount[this.navHeader]
    }
  },
  created() {
    this.actionSetIsLoading(true)
    Promise
      .all([getBalance(), getAllSportCount(), getSportKind()])
      .then(([Balance, AllSportCount, sportList]) => {
        this.Balance = Balance
        this.sportList = { ...sportList }

        const sportCount = {
          live: {},
          today: {}
        }

        AllSportCount.forEach((item) => {
          if (item.EarlyFECount) {
            if (sportCount.today[item.SportId]) {
              sportCount.today[item.SportId] += item.EarlyFECount
            } else {
              sportCount.today[item.SportId] = item.EarlyFECount
            }
          }

          if (item.TodayFECount) {
            if (sportCount.today[item.SportId]) {
              sportCount.today[item.SportId] += item.TodayFECount
            } else {
              sportCount.today[item.SportId] = item.TodayFECount
            }
          }

          if (item.RBFECount) {
            sportCount.live[item.SportId] = item.RBFECount
          }
        })

        this.actionSetSportCount(sportCount)
        this.actionSetCurSportId(Object.keys(this.curSportCount)[0])
      })
      .finally(() => {
        this.actionSetIsLoading(false)
      })
  },
  methods: {
    ...mapActions({
      actionSetNavHeader: 'betball/actionSetNavHeader',
      actionSetCurSportId: 'betball/actionSetCurSportId',
      actionSetSportCount: 'betball/actionSetSportCount',
      actionSetIsLoading: 'betball/actionSetIsLoading'
    }),
    selectNavFilter(val) {
      this.actionSetNavHeader(val)
      this.actionSetCurSportId(Object.keys(this.curSportCount)[0])
    },
    selectSport(val) {
      this.actionSetCurSportId(val)
    },
    showBet() {
      this.isShowBet = true
    },
    closeBet(val) {
      this.isShowBet = val
    },
    closeFuncAll(val) {
      this.isShowFuncAll = val
      this.selectFunc = ''
    },
    closeFuncPre(val) {
      this.isShowFuncPrefer = val
      this.selectFunc = ''
    },
    selFunction(val) {
      this.selectFunc = val

      if (val === 1) {
        this.isShowFuncPrefer = false
        this.isShowFuncAll = !this.isShowFuncAll
      } else {
        this.isShowFuncAll = false
        this.isShowFuncPrefer = !this.isShowFuncPrefer
      }
    }
  }
}
</script>
<style lang="scss" scoped>
.sport {
  &-header {
    height: 45px;
    background-color: #fff;
    border-bottom: 1px solid #eee;
    .back {
      height: 14px;
      margin: 14px 6px;
    }

    .hLeft {
      float: left;
      height: 45px;
    }

    .hTxt {
      line-height: 40px;
      display: inline;
      font-size: 18px;
      color: #5d6c76;
      font-weight: bold;
      padding: 10px;
      margin-right: 20px;
      &.chgColor{
        color: #0149ff;
      }
    }
    .hRight {
      float: right;
      height: 45px;

      .dline{
        float: left;
        margin-right: 10px;
        span{
          line-height: 30px;
          font-size: 22px;
          font-weight: 500;
          float: left;
          padding: 7px 0;
        }
      }
      .hImg {
        height: 30px;
        padding-top: 8px;

      }
    }
  }
  &-navbar {
    height: 50px;
    background-color: #FFF;
    border-bottom: 1px solid #EEE;

    .nLeft {
      float: left;
      width: 70%;
      height: 50px;
    }
    .flow-x {
      padding: 10px 5px 8px;
      height: 50px;
      overflow-y: hidden;
      overflow-x: auto;
    }
    .navLeft {
      margin: 5px 10px 5px 0;
      display: table-cell;
    }
    .sport-btn {
      background-color: #fff;
      padding: 6px 15px;
      font-size: 12px;
      border: 1px solid #979797;
      border-radius: 15px;
      margin-right: 15px;
      outline: none;
      color: #333;
      white-space: nowrap;
    }
    .chgColor{
      background-color: #0149ff;
      color: #fff;
    }
    .nRight {
      // box-shadow: -10px 0 5px -5px #d8d8d8;
      float: left;
      width: 30%;
      height: 50px;

      .dline{
        float: left;
        width: 50%;
        text-align: center;

        .nImg {
          height: 22px;
          padding-top: 5px;
          display: block;
          margin: auto;
        }

        span{
          font-size: 12px;
        }
      }
      .chgFuncColor{
          color: #0149ff;
        }
    }
  }
}

.hasOpenWager {
  transform: translateX(0);
  z-index: 2;
}
</style>
