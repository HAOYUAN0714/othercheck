<template>
  <div class="container">
    <loading v-show="loadingStatus && !timeout" />
    <avoid v-show="showAvoid" />
    <template v-if="moreGameData && moreGameData.length > 0">
      <div
        v-for="(item, index) in moreGameData"
        :key="`content-wrap-${index}`"
        class="content-wrap"
      >
        <div
          v-for="(detail, detailKey) in item.Events"
          :key="detailKey"
          class="sport-content"
        >
          <div class="sport-header">
            <!-- <img src="@/icons/png/backWhite.png" class="back" @click="$router.go(-1)"> -->
            <img src="@/icons/png/backWhite.png" class="back" @click="goBack()">
            <span class="teanName">{{ detail.HomeTeam }} v {{ detail.AwayTeam }}</span>
          </div>
          <scoreBoard v-if="$route.params.routername==='moreSportToday'" :match-info="detail" sport-id="sportId" />
          <ul
            class="playing"
            :class="{
              today: $route.params.routername === 'moreSportToday'
            }"
          >
            <li
              v-for="(list) in gameList"
              :key="list.id"
              :class="{
                active: list.id === curPlayTypeId
              }"
              @click="changePlayType(list.id)"
            >
              <span>{{ list.name }}</span>
            </li>
          </ul>
          <div v-if="$route.params.routername === 'moreSportToday'" class="todayevent" />
          <div
            v-for="(moreGame, moreGameIndex) in formatPlayType(detail.MarketLines)"
            :key="moreGameIndex"
            class="sportevent"
            :class="{
              // todayevent: $route.params.routername === 'moreSportToday'
            }"
          >
            <div
              v-for="(playType, playTypeIndex) in filterPlayType(moreGame)"
              :key="playTypeIndex"
            >
              <div class="label" @click="showPlayType(playType[0])">
                <div class="slope">
                  <div class="league-name">
                    {{ playType[0].BetTypeNames }} - {{ playType[0].PeriodName }}
                  </div>
                </div>
                <img
                  src="../../icons/png/down.png"
                  class="arrow"
                  :class="{'down': !hidePlayTypeData[`${playType[0].BetTypeId}_${playType[0].PeriodId}`]}"
                >
              </div>
              <el-collapse-transition>
                <Component
                  :is="getTableId(playType[0].BetTypeId)"
                  v-if="!hidePlayTypeData[`${playType[0].BetTypeId}_${playType[0].PeriodId}`]"
                  :odds-data="playType"
                  :team-name="getTeamName(detail.HomeTeam, detail.AwayTeam)"
                  :bet-type-id="playType[0].BetTypeId"
                  :games="detail"
                  :sport-id="sportId"
                />
              </el-collapse-transition>
            </div>
          </div>
        </div>
      </div>
    </template>
    <template v-else>
      <div class="sport-header">
        <img src="@/icons/png/backWhite.png" class="back" @click="$router.go(-1)">
      </div>
      <div class="content">
        暫無資料
      </div>
    </template>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import scoreBoard from '../../layout/bblivem/components/scoreBoard'
import { getSelectedEventInfo } from '@/api/bblivem'
import loading from '@/layout/components/liveloading'
import template1 from '@/layout/bblivem/components/moreGame/template1'
import template2 from '@/layout/bblivem/components/moreGame/template2'
import template3 from '@/layout/bblivem/components/moreGame/template3'
import template4 from '@/layout/bblivem/components/moreGame/template4'
import template5 from '@/layout/bblivem/components/moreGame/template5'
import avoid from '@/layout/bblivem/components/Avoid'

export default {
  name: 'SportDetail',
  components: {
    scoreBoard,
    loading,
    template1,
    template2,
    template3,
    template4,
    template5,
    avoid
  },
  data() {
    return {
      leagueOpenList: {},
      gameList: [
        {
          name: '所有盤口',
          id: 0
        },
        {
          name: '讓球.大小球',
          id: 1
        },
        {
          name: '波膽',
          id: 2
        },
        {
          name: '進球',
          id: 3
        },
        {
          name: '半場',
          id: 4
        },
        {
          name: '特殊投注',
          id: 5
        }
      ],
      gameId: '',
      sportId: '',
      Market: '',
      currentPage: '',
      moreGameData: [],
      loadingStatus: false,
      timeout: '',
      initTimer: false,
      hidePlayTypeData: {},
      curPlayTypeId: 0,
      set1: [1, 2, 4, 31, 32, 35, 83, 85, 86, 92, 93, 94], // 讓球,大小
      set2: [6, 62], // 波膽
      set3: [2, 5, 7, 13, 14, 15, 16, 17, 18, 21, 22, 23, 31, 32, 42, 43, 46, 56, 57, 58, 59, 60, 61, 93, 94], // 進球
      set4: [2, 3], // 半場
      set5: [8, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 39, 40, 41, 44, 45, 46, 58, 59, 63, 64, 65, 78, 79, 80, 82, 85, 86, 89, 90, 82, 93, 94],
      iscombo: false,
      // curdata: [],
      collisionCheck: [],
      comboStatus: 0,
      finalInfo: []
    }
  },
  computed: {
    ...mapGetters({
      curBetSlip: 'getBetSlip',
      showAvoid: 'showAvoid',
      isFuncBetOpen: 'm6app/getFuncBetOpen',
      getLiveUrl: 'm6app/getLiveUrl'
    }),
    showAvoid() {
      return this.$store.state.ball.showAvoid
    }
  },
  watch: {
    curBetSlip(val) {
      console.log(val, 'in detail')
    }
  },
  created() {
    this.gameId = this.$route.params.gameId
    this.sportId = this.$route.params.sportId
    this.Market = this.$route.params.Market
    this.currentPage = this.$route.params.currentPage
    // console.log(this.$route.params, 'this.$route.params')
  },
  mounted() {
    if (this.$route.params.routername === 'moreSportCombo') {
      this.iscombo = true
    } else if (this.$route.params.routername === 'moreSportToday') {
      this.iscombo = false
    } else if (this.$route.params.routername === 'moreSportFuture') {
      this.iscombo = false
    }
    this.loadingStatus = true
    this.getMoreGameApi()
  },
  beforeDestroy() {
    clearInterval(this.timeout)
  },
  methods: {
    ...mapActions({
      setBet: 'ball/actionBet',
      setSportId: 'm6app/actionSetCurSportId'
    }),
    goBack() {
      // console.log(this.$route.params.routername, 'router')
      this.setSportId(this.$route.params.sportId)
      this.$router.push(`/${this.$route.params.routername}`)
    },
    getMoreGameApi() {
      this.loadingStatus = true
      getSelectedEventInfo(this.sportId, this.gameId).then((res) => {
        this.moreGameData = res.Sports
        this.loadingStatus = false
        let curMarketId = 0
        if (this.moreGameData) {
          curMarketId = this.moreGameData[0].Events[0].Market
        }

        if (!this.initTimer) {
          if (+(curMarketId) === 3) {
            this.timeout = setInterval(() => {
              this.initTimer = true
              this.getMoreGameApi()
            }, 5000)
          } else {
            this.timeout = setInterval(() => {
              this.initTimer = true
              this.getMoreGameApi()
            }, 30000)
          }
        }
      }).catch((res) => {
        this.loadingStatus = false
      })
    },
    formatPlayType(val) {
      const final = {}
      val.forEach((item) => {
        if (!final[item.BetTypeId]) {
          final[item.BetTypeId] = {}
        }
        if (!final[item.BetTypeId][item.PeriodId]) {
          final[item.BetTypeId][item.PeriodId] = []
        }
        final[item.BetTypeId][item.PeriodId].push(item)
      })
      return final
    },
    getTableId(val) {
      switch (+(val)) {
        case 1:
        case 2:
        case 4:
        case 5:
        case 7:
        case 31:
        case 32:
          return 'template2'
        case 3:
        case 8:
        case 35:
        case 86:
          return 'template3'
        case 6:
          return 'template4'
        case 62:
          return 'template5'
        default:
          return 'template1'
      }
    },
    getTeamName(homeTeam, awayTeam) {
      const team = []
      team.push(homeTeam, awayTeam)
      return team
    },
    changePlayType(val) {
      this.curPlayTypeId = val
    },
    filterPlayType(val) {
      switch (this.curPlayTypeId) {
        case 0:
          return val
        case 1:
          // console.log(Object.values(val)
          //   .filter(item => this.set1.includes(item[0].BetTypeId)), 'Object.values(val')
          return Object.values(val)
            .filter(item => this.set1.includes(item[0].BetTypeId))
        case 2:
          return Object.values(val)
            .filter(item => this.set2.includes(item[0].BetTypeId))
        case 3:
          return Object.values(val)
            .filter(item => this.set3.includes(item[0].BetTypeId))
        case 4:
          return Object.values(val)
            .filter(item => this.set4.includes(item[0].BetTypeId))
        case 5:
          return Object.values(val)
            .filter(item => this.set5.includes(item[0].BetTypeId))
      }
    },
    showPlayType(val) {
      const key = `${val.BetTypeId}_${val.PeriodId}`
      if (this.hidePlayTypeData[key]) {
        delete this.hidePlayTypeData[key]
        this.$forceUpdate()
        return
      }
      this.hidePlayTypeData = {
        ...this.hidePlayTypeData,
        [key]: true
      }
    }
  }
}

</script>

<style lang="scss" scoped>
.container {
  transition: margin-left .5s;
  background: #c0c4cc;
  min-width: 250px;
  .clear {
    clear: both;
  }

  .playing {
    display: flex;
    flex: 1;
    align-items: center;
    width: 100vw;
    overflow-x: scroll;
    padding: 6px 0px;
    background-color: #355ea9;
    color: #fff;
    border-bottom: 1px solid #355ea9;
    margin-bottom: 1px;
    margin-top: -5px;
    padding: 0px;
    list-style: none;
    z-index: 1;
    &::-webkit-scrollbar {
    width: 5px;
    }
    &::-webkit-scrollbar:horizontal {
      height: 5px;
    }
    &::-webkit-scrollbar-track {
      background-color: transparentize(#355ea9, 0.7);
    }
    &::-webkit-scrollbar-thumb {
      border-radius: 5px;
      background: transparentize(#355ea9, 0.5);
      box-shadow: inset 0 0 6px #355ea9;
    }
    li {
      margin: 0px 10px;
      padding: 0 10px;
      justify-content: center;
      white-space: nowrap;
      height: 35px;
      line-height: 40px;
      font-size: 14px;
    }
    .active {
      // border-bottom: 3px solid #e6a23c;
      color: #bdfff0;
      font-weight: bold;
    }
  }
  .today {
    position: fixed;
    z-index: 3;
  }
  .content-wrap {
        background-color: #f5f7fa;
        min-height: calc(100vh - 185px);
        // overflow-y: scroll;
    }

    .sport {
    &-content {
      margin-top: 50px;
        .sport-header {
          height: 45px;
          line-height: 45px;
          color: #f5f7fa;
          z-index: 3;
          position: fixed;
          width: 100%;
          background-color: #355ea9;
          text-align: center;
          position: fixed;
          top: 0;
          overflow-x: scroll;
          white-space: nowrap;
          // z-index: 3;
            .back {
              float: left;
              width: 35px;
              height: 30px;
              margin: 7px 0 0 10px;
            }
          .team-name {
            float: left;
          }
          .teanName {}
        }
        .sportevent {
          background-color: #fff;
        }
        .todayevent {
          margin-top: 36px;
        }
        .label {
            height: 30px;
            border-bottom: 1px solid #eee;
            background-image: linear-gradient(to right,#a2b4d8,#a2b4d8,#ccd7ee);
            overflow: hidden;
            font-size: 14px;
            div {
                // background: linear-gradient(120deg, #e4e7ed 86%, transparent 0);
                width: 95vw;
                .icon {
                    background: url('~@/assets/betball/basketball.png') no-repeat center/cover;
                    height: 28px;
                    float: left;
                    width: 28px;
                }
                .league-name {
                    color: #fff;;
                    line-height: 30px;
                    float: left;
                    width: calc(100% - 66px);
                    vertical-align: top;
                    white-space: nowrap;
                    overflow-x: scroll;
                    margin-right: 10px;
                    padding-left: 7px;
                }
            }
            .arrow {
              height: 15px;
              width: 15px;
              // float: right;
              margin-right: 10px;
              margin-top: 5px;
              transition: transform .3s;
              &.down {
                transform: rotate(180deg);
              }
            }
        }
        .odds {
          &-Content {
            .live {
              &-Letball {
                width: 95vw;
                margin: 10px auto;
                .mainList {
                  background-color: #f4f4f5;
                  // border: 1px solid #000;
                  width: 46.5vw;
                  display: inline-block;
                  text-align: center;
                  // padding: 10px;
                  div {
                    &:first-child {
                      background-color: #fff;
                      // border: 1px solid #000;
                    }
                  }
                }
                // color: red;

              }
            }
          }
        }
      }
    }
}
.sport-header {
  height: 45px;
  line-height: 45px;
  color: #fff;
  z-index: 1;
  background-color: #000;
    .back {
      width: 35px;
      height: 30px;
      margin: 7px 0 0 10px;
    }
}
.content {
  background: url('~@/assets/betball/empty-reminder.png') #FFF no-repeat center/contain;
  padding-top: 78px;
  height: 165px;
  line-height: 165px;
  text-align: center;
}
</style>
