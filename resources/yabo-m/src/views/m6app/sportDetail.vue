<template>
  <div class="container">
    <chatRoom v-if="!isFuncBetOpen" :key="gameId" :event-id="Number(gameId)" :event="moreGameData[0]" />
    <loading v-show="loadingStatus && !timeout" />
    <!-- <selectHeader /> -->
    <avoid v-show="showAvoid" />
    <template v-if="moreGameData.length > 0">
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
            <img src="@/icons/png/backWhite.png" class="back" @click="$router.go(-1)">
            {{ detail.HomeTeam }} v {{ detail.AwayTeam }}
          </div>
          <scoreBoard :match-info="detail" sport-id="sportId" />
          <ul class="playing">
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
          <!-- 2222 {{ isFuncBetOpen }} -->
          <div
            v-for="(moreGame, moreGameIndex) in formatPlayType(detail.MarketLines)"
            :key="moreGameIndex"
            class="sportevent"
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
                  :bet-func="clickOdds"
                  :iscombo="iscombo"
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
import chatRoom from './chatRoom'
import selectHeader from '../../layout/m6app/components/selectHeader'
import scoreBoard from '../../layout/m6app/components/scoreBoard'
import { getSelectedEventInfo, getSelectedComboEventInfo } from '@/api/m6'
import loading from '@/layout/components/loading'
import template1 from '@/layout/m6app/components/moreGame/template1'
import template2 from '@/layout/m6app/components/moreGame/template2'
import template3 from '@/layout/m6app/components/moreGame/template3'
import template4 from '@/layout/m6app/components/moreGame/template4'
import template5 from '@/layout/m6app/components/moreGame/template5'
import avoid from '@/layout/m6app/components/Avoid'

export default {
  name: 'SportDetail',
  components: {
    // selectHeader,
    chatRoom,
    selectHeader,
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
      isFuncBetOpen: 'm6app/getFuncBetOpen'
    }),
    showAvoid() {
      return this.$store.state.ball.showAvoid
    }
  },
  watch: {
    curBetSlip(val) {
      // this.curdata = val
      // console.log(val, 'in detail')
      // let finalInfo = []
      // const collision = {}
      // this.comboStatus = 0
      // this.errorMsg = ''
      // val.forEach((data) => {
      //   const base = data.betinfo.apiInput
      //   // 計算串了幾場
      //   if (this.comboStatus === 0 || this.comboStatus === 1) {
      //     if (base.WagerSelectionInfos.OddsType === 3 && data.openParlay === true) {
      //       this.collisionCheck.push(data.collisionCheck)
      //       this.collisionCheck = this.collisionCheck.filter((element, index, arr) => { return arr.indexOf(element) === index; })
      //       // console.log(this.collisionCheck, 'this.collisionCheck')
      //       // this.comboInfo = '串關處理中'
      //     } else {
      //       // this.comboInfo = '含有非歐洲盤賠率 無法串關'
      //       this.comboStatus = -1
      //       finalInfo = []
      //     }
      //   }
      // })
    }
  },
  created() {
    this.gameId = this.$route.params.gameId
    this.sportId = this.$route.params.sportId
    // this.getMoreGameApi()
  },
  mounted() {
    // console.log(this.$route.params.routername, 'find routername')
    if (this.$route.params.routername === 'moreSportCombo') {
      // console.log('1123')
      this.iscombo = true
    } else if (this.$route.params.routername === 'moreSportToday') {
      this.iscombo = false
    } else if (this.$route.params.routername === 'moreSportFuture') {
      this.iscombo = false
    }
    this.loadingStatus = true
    this.getMoreGameApi()
    // switch (this.$route.params.routername) {
    //   case 'moreSportToday':
    //     this.iscombo = false
    //     break
    //   case 'moreSportFuture':
    //     this.iscombo = false
    //     break
    //   case 'moreSportCombo':
    //     this.iscombo = true
    //     break
    // }
  },
  beforeDestroy() {
    clearInterval(this.timeout)
  },
  methods: {
    ...mapActions({
      setBet: 'ball/actionBet'
    }),
    getMoreGameApi() {
      this.loadingStatus = true
      if (this.iscombo === true) {
        // console.log('iscombo === true')
        getSelectedComboEventInfo(this.gameId, this.sportId).then((res) => {
          this.moreGameData = res
          this.loadingStatus = false
          const curMarketId = this.moreGameData[0].Events[0].Market
          // 串關提示串了幾關
          this.finalInfo = []
          this.comboStatus = 0
          this.curBetSlip.forEach((data) => {
            if (this.comboStatus === 0 || this.comboStatus === 1) {
              if (data.betinfo.apiInput.WagerSelectionInfos.OddsType === 3 && data.openParlay === true) {
                this.collisionCheck.push(data.collisionCheck)
                this.collisionCheck = this.collisionCheck.filter((element, index, arr) => { return arr.indexOf(element) === index })
                // this.comboInfo = '串關處理中'
              } else {
              // this.comboInfo = '含有非歐洲盤賠率 無法串關'
                this.comboStatus = -1
                this.finalInfo = []
              }
            }
          })
          // this.iscombo = this.moreGameData[0].Events[0].OpenParlay
          // console.log(this.moreGameData[0].Events[0].OpenParlay, 'curMarketId = this.moreGameData[0].Events[0]')
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
      } else {
        // console.log('iscombo === false')
        getSelectedEventInfo(this.gameId, this.sportId).then((res) => {
          this.moreGameData = res
          this.loadingStatus = false
          const curMarketId = this.moreGameData[0].Events[0].Market
          // this.iscombo = this.moreGameData[0].Events[0].OpenParlay
          // console.log(this.moreGameData[0].Events[0].OpenParlay, 'curMarketId = this.moreGameData[0].Events[0]')
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
      }
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
        // console.log(final[item.BetTypeId][item.PeriodId].push(item), 'final[item.BetTypeId][item.PeriodId].push(item)')
        // console.log(final, 'final')
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
      // console.log(this.curPlayTypeId, 'this.curPlayTypeId')
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
    },
    clickOdds(playType, oddsObj) {
      // console.log(playType, oddsObj, 'playType, oddsObj')
      const sportId = this.$route.params.sportId
      const games = this.moreGameData[0].Events[0]
      // console.log('playType:', playType, 'oddsObj:', oddsObj)
      if (!this.iscombo) {
        this.setBet({
          bet: oddsObj,
          playType: playType,
          games,
          sportId: +(sportId)
        })
      } else {
        this.setBet({
          bet: oddsObj,
          playType: playType,
          games,
          sportId: +(sportId),
          combo: this.combo
        })
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
    background-color: #000;
    color: #fff;
    // font-size: 12px;
    margin: 0px;
    padding: 0px;
    list-style: none;
    &::-webkit-scrollbar {
    width: 5px;
    }
    &::-webkit-scrollbar:horizontal {
      height: 5px;
    }
    &::-webkit-scrollbar-track {
      background-color: transparentize(#ccc, 0.7);
    }
    &::-webkit-scrollbar-thumb {
      border-radius: 5px;
      background: transparentize(#ccc, 0.5);
      box-shadow: inset 0 0 6px #c0c4cce0;
    }
    li {
      margin: 0px 10px;
      padding: 0 10px;
      justify-content: center;
      white-space: nowrap;
      height: 40px;
      line-height: 40px;
      font-size: 14px;
    }
    .active {
      border-bottom: 3px solid #e6a23c;
      color: #e6a23c;
      font-weight: bold;
    }
  }
  .content-wrap {
        background-color: #f5f7fa;
        min-height: calc(100vh - 185px);
        // overflow-y: scroll;
    }

    .sport {
    &-content {
        .sport-header {
          height: 45px;
          line-height: 45px;
          color: #fff;
          z-index: 1;
          position: fixed;
          width: 100%;
          background-color: #000;
          text-align: center;
          position: fixed;
          top: 0;
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
        }
        .sportevent {
          background-color: #fff;
            // background-color: #f1f1f1;
        }
        .label {
            height: 30px;
            border-bottom: 1px solid #eee;
            overflow: hidden;
            font-size: 14px;
            div {
                background: linear-gradient(120deg, #e4e7ed 86%, transparent 0);
                width: 95vw;
                .icon {
                    background: url('~@/assets/betball/basketball.png') no-repeat center/cover;
                    height: 28px;
                    float: left;
                    width: 28px;
                }
                .league-name {
                    color: #323233;
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
