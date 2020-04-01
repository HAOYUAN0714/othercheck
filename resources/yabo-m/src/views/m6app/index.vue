<template>
  <div class="container">
    <AppHeader />
    <div class="liveBall">
      <router-link to="moreSport">
        <div>滾球</div>
        <!-- 這個還沒接api -->
        <div>7</div>
      </router-link>
    </div>
    <div class="clear" />
    <ul class="main">
      <li v-for="(list,index) in main" :key="list.index" :class="{active: index === nowIndex}" @click="selectMinE(list.path,index,mainSelect)">
        <div>
          <strong>{{ list.total }}</strong>
        </div>
        <hr>
        <div>{{ list.name }}</div>
      </li>
    </ul>
    <ul class="second">
      <li v-for="(list,index) in getSports" :key="list.index" :class="{active: index === sportindex}" @click="selectSport(list.SportId,index)">
        <!-- {{list.TodayFECount}} -->
        <span v-if="mainSelect === 'today'">{{ list.TodayFECount }}</span>
        <span v-if="mainSelect === 'future'">{{ list.EarlyFECount }}</span>
        <span v-if="mainSelect === 'combo'">{{ list.Count }}</span>
        <span>{{ list.SportName }}</span>
      </li>
    </ul>
    <ul class="feature">
      <li>
        <i class="fa fa-angle-up" aria-hidden="true" />
        <span>收起</span>
      </li>
      <li>
        <i class="fa fa-filter" aria-hidden="true" />
        <span><strong>聯賽</strong> / 時間</span>
      </li>
      <li>
        <router-link to="moreSport">
          <span>more更多賽事</span>
          <i class="fa fa-chevron-right" aria-hidden="true" />
        </router-link>
      </li>
    </ul>
    <div class="clear" />
    <div class="page">
      <div v-for="(list) in getTotalGameList" :key="list.index" class="sport-content">
        <div class="sportevent">
          <div class="label" @click="showLeague(list.index)">
            <div class="icon" />
            <div class="league-name">{{ list.leagueName }}</div>
            <img src="" :class="['arrow', { down: leagueOpenList[list.index] }]">
          </div>
          <router-link to="sportDetail">
            <div :style="{ height: !leagueOpenList[list.index] && list.matchList ? `${list.matchList.length * 77}px` : `0px` }" class="odds-Content clearfix">
              <div
                v-for="(matchInfo, subIndex) in list.matchList"
                :key="`matchInfo-${subIndex}`"
                class="match-wrap clearfix ulList"
              >
                <div>
                  <img src="@/assets/betball/basketball.png" alt="">
                </div>
                <div class="detail">
                  <div class="time">{{ moment(matchInfo.time).format('DD/MM:HH:mm') }}</div>
                  <div class="team">{{ matchInfo.homeName }} <strong> vs </strong> {{ matchInfo.awayName }}</div>
                  <div class="state">
                    <span>未開始</span>
                    <span><i class="fa fa-television" aria-hidden="true" /></span>
                  </div>
                </div>
                <div>
                  <img src="@/assets/betball/basketball.png" alt="">
                </div>
              </div>
            </div>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment'
import { mapGetters, mapActions } from 'vuex'
import AppHeader from '@/layout/m6app/components/AppHeader'
// import SportList from '@/layout/m6app/components/SportList'
import { getSportKind, getTodayGame, getAllSportCount, getEarlyGame, getComboGame, getLiveBasicData, getEventInfoByPage } from '@/api/m6'

export default {
  components: {
    AppHeader
    // SportList
  },
  data() {
    return {
      moment,
      leagueOpenList: {},
      selperiod: 0,
      main: [
        {
          name: '今日',
          total: 0,
          path: 'today',
          type: 'today'
        },
        {
          name: '早盤',
          total: 0,
          path: 'future',
          type: 'future'
        },
        {
          name: '串關',
          total: 0,
          path: 'combo',
          type: 'combo'
        },
        {
          name: '關注',
          total: 0,
          path: 'faver',
          type: 'faver'
        }
      ],
      events: [],
      liveEvents: [],
      liveCount: 0,
      mainSelect: '',
      nowIndex: 0,
      sportindex: 0,
      timeout: '',
      sportId: 1,
      todayCount: 0,
      count: 0,
      liveCombo: [],
      todayCombo: [],
      futureCombo: [],
      sportList: {},
      sportCount: {
        live: {},
        today: {},
        future: {},
        combo: {},
        faver: {}
      }
    }
  },
  computed: {
    ...mapGetters({
      getSports: 'm6app/getSports',
      getCurSportId: 'm6app/getCurSportId',
      getTotalGameList: 'm6app/getTotalGameList'
    })
  },
  created() {
    this.getGame() // defult today
  },
  mounted() {
    this.$router.push({ path: 'focus', query: { main: 'today', sport: 1 }})
  },
  methods: {
    ...mapActions({
      actionSetTotalGameList: 'm6app/actionSetTotalGameList',
      actionSetSports: 'm6app/actionSetSports',
      actionSetCurSportId: 'm6app/actionSetCurSportId',
      actionSetNavHeader: 'm6app/actionSetNavHeader',
      actionSetIsLoading: 'm6app/actionSetIsLoading',
      actionSetSportCount: 'm6app/actionSetSportCount'
    }),
    showLeague(id) {
      this.leagueOpenList = {
        ...this.leagueOpenList,
        [id]: !this.leagueOpenList[id]
      }
    },
    selectMinE(navItem, index, mainSelect) {
      this.nowIndex = index
      this.mainSelect = navItem
      this.$router.push({ path: 'focus', query: { main: `${this.mainSelect}`, sport: 1 }})
      this.actionSetNavHeader(this.mainSelect)
    },
    selectSport(navItem, index) {
      this.sportindex = index
      this.$router.push({ path: 'focus', query: { main: `${this.mainSelect}`, sport: navItem }})
      this.sportId = this.$router.currentRoute.query.sport
      // console.log(this.getSports, 'getSports');
      this.actionSetCurSportId(navItem)
      switch (this.$router.currentRoute.query.main) {
        case 'today':
          getLiveBasicData(this.sportId).then((res) => {
            if (res.Sports.length === 0) {
              this.liveEvents = []
            } else {
              const gameList = {}
              this.liveEvents = res.Sports[0].Events
              this.liveCount = res.Sports[0].Events.length
            }
          })
          return getTodayGame(this.sportId).then((res) => {
            if (res.Sports.length === 0) {
              this.events = []
              const gameList = {}
              this.actionSetTotalGameList(gameList)
            } else {
              const gameList = {}
              this.events = res.Sports[0].Events.concat(this.liveEvents)
              this.events
                .forEach((item, index) => {
                  if (gameList[item.Competition.CompetitionId]) {
                    gameList[item.Competition.CompetitionId] = {
                      ...gameList[item.Competition.CompetitionId],
                      matchList: [
                        ...gameList[item.Competition.CompetitionId].matchList,
                        {
                          homeName: item.HomeTeam,
                          awayName: item.AwayTeam,
                          matchId: item.EventId,
                          time: item.EventDate,
                          EventDate: item.EventDate,
                          RBTime: item.RBTime,
                          RbTimeStatus: item.RbTimeStatus,
                          moreNum: item.TotalMarketLineCount
                        }
                      ]
                    }
                    return
                  }
                  gameList[item.Competition.CompetitionId] = {
                    sportId: this.sportId,
                    leagueName: item.Competition.CompetitionName,
                    matchList: [
                      {
                        homeName: item.HomeTeam,
                        awayName: item.AwayTeam,
                        matchId: item.EventId,
                        time: item.EventDate,
                        EventDate: item.EventDate,
                        RBTime: item.RBTime,
                        RbTimeStatus: item.RbTimeStatus,
                        moreNum: item.TotalMarketLineCount
                      }
                    ]
                  }
                  this.actionSetTotalGameList(gameList)
                })
            }
          })
          break
        case 'future':
          // console.log('future')
          return getEarlyGame(this.sportId).then((res) => {
            // console.log('getEarlyGame')
            if (res.Sports.length === 0) {
              this.events = []
              const gameList = {}
              this.actionSetTotalGameList(gameList)
            } else {
              const gameList = {}
              this.events = res.Sports[0].Events
              this.events
                .forEach((item, index) => {
                  if (gameList[item.Competition.CompetitionId]) {
                    gameList[item.Competition.CompetitionId] = {
                      ...gameList[item.Competition.CompetitionId],
                      matchList: [
                        ...gameList[item.Competition.CompetitionId].matchList,
                        {
                          homeName: item.HomeTeam,
                          awayName: item.AwayTeam,
                          matchId: item.EventId,
                          time: item.EventDate,
                          EventDate: item.EventDate,
                          RBTime: item.RBTime,
                          RbTimeStatus: item.RbTimeStatus,
                          moreNum: item.TotalMarketLineCount
                        }
                      ]
                    }
                    return
                  }
                  gameList[item.Competition.CompetitionId] = {
                    sportId: this.sportId,
                    leagueName: item.Competition.CompetitionName,
                    matchList: [
                      {
                        homeName: item.HomeTeam,
                        awayName: item.AwayTeam,
                        matchId: item.EventId,
                        time: item.EventDate,
                        EventDate: item.EventDate,
                        RBTime: item.RBTime,
                        RbTimeStatus: item.RbTimeStatus,
                        moreNum: item.TotalMarketLineCount
                      }
                    ]
                  }
                  this.actionSetTotalGameList(gameList)
                })
            }
          })
          break
        case 'combo':
          // console.log('combo')
          // getComboGame(this.sportId,1).then((res) => {  // futureCombo
          //     if(res.Sports.length === 0 ) {
          //       this.futureCombo = []
          //     } else {
          //       const gameList = {}
          //       this.futureCombo = res.Sports[0].Events;
          //       this.events = this.events.concat(this.futureCombo)
          //       this.events = this.events.concat(this.liveCombo)
          //       this.events = this.events.concat(this.todayCombo)
          //       console.log(this.events, 'eventsevents')
          //     this.events
          //       .forEach((item, index) => {
          //         if (gameList[item.Competition.CompetitionId]) {
          //           gameList[item.Competition.CompetitionId] = {
          //             ...gameList[item.Competition.CompetitionId],
          //             matchList: [
          //               ...gameList[item.Competition.CompetitionId].matchList,
          //               {
          //                 homeName: item.HomeTeam,
          //                 awayName: item.AwayTeam,
          //                 matchId: item.EventId,
          //                 time: item.EventDate,
          //                 EventDate: item.EventDate,
          //                 RBTime: item.RBTime,
          //                 RbTimeStatus: item.RbTimeStatus,
          //                 moreNum: item.TotalMarketLineCount,
          //               }
          //             ]
          //           }
          //           return
          //         }
          //         gameList[item.Competition.CompetitionId] = {
          //           sportId: this.sportId,
          //           leagueName: item.Competition.CompetitionName,
          //           matchList: [
          //             {
          //               homeName: item.HomeTeam,
          //               awayName: item.AwayTeam,
          //               matchId: item.EventId,
          //               time: item.EventDate,
          //               EventDate: item.EventDate,
          //               RBTime: item.RBTime,
          //               RbTimeStatus: item.RbTimeStatus,
          //               moreNum: item.TotalMarketLineCount,
          //             }
          //           ]
          //         }
          //   // console.log('gameList', gameList)
          //   this.actionSetTotalGameList(gameList)
          // })
          // console.log('gameList', gameList)
          //   }
          // });
          // getComboGame(this.sportId,2).then((res) => { // todayCombo
          //   this.todayCombo = []
          //     if(res.Sports.length === 0 ) {
          //       this.todayCombo = []
          //     } else {
          //       const gameList = {}
          //       this.todayCombo = res.Sports[0].Events;
          //       console.log(this.todayCombo, 'todayCombo')
          //     }
          //   });
          // getComboGame(this.sportId,3).then((res) => { // liveCombo
          //     if(res.Sports.length === 0 ) {
          //       this.liveCombo = []
          //       const gameList = {}
          //       this.actionSetTotalGameList(gameList)
          //     } else {
          //       const gameList = {}
          //       this.liveCombo = res.Sports[0].Events;
          //     }
          //   });
          return getEarlyGame(this.sportId).then((res) => {
            // console.log('getEarlyGame')
            if (res.Sports.length === 0) {
              this.events = []
              const gameList = {}
              this.actionSetTotalGameList(gameList)
            } else {
              const gameList = {}
              this.events = res.Sports[0].Events
              this.events
                .forEach((item, index) => {
                  if (gameList[item.Competition.CompetitionId]) {
                    gameList[item.Competition.CompetitionId] = {
                      ...gameList[item.Competition.CompetitionId],
                      matchList: [
                        ...gameList[item.Competition.CompetitionId].matchList,
                        {
                          homeName: item.HomeTeam,
                          awayName: item.AwayTeam,
                          matchId: item.EventId,
                          time: item.EventDate,
                          EventDate: item.EventDate,
                          RBTime: item.RBTime,
                          RbTimeStatus: item.RbTimeStatus,
                          moreNum: item.TotalMarketLineCount
                        }
                      ]
                    }
                    return
                  }
                  gameList[item.Competition.CompetitionId] = {
                    sportId: this.sportId,
                    leagueName: item.Competition.CompetitionName,
                    matchList: [
                      {
                        homeName: item.HomeTeam,
                        awayName: item.AwayTeam,
                        matchId: item.EventId,
                        time: item.EventDate,
                        EventDate: item.EventDate,
                        RBTime: item.RBTime,
                        RbTimeStatus: item.RbTimeStatus,
                        moreNum: item.TotalMarketLineCount
                      }
                    ]
                  }
                  this.actionSetTotalGameList(gameList)
                })
            }
          })
          break
        case 'faver':
          // console.log('faver')
          // const gameList = {}
          // this.actionSetTotalGameList(gameList)
          return 'faver'
          break
        default:
          return 'today'
          break
      }
    },
    getGame() { // defult today
      this.actionSetNavHeader('today')
      getAllSportCount(false).then((res) => { // is combo === false
        this.actionSetSports(res)
        this.getSports.forEach((item) => {
          this.main[0].total = this.main[0].total + item.TodayFECount // today
          this.main[1].total = this.main[1].total + item.EarlyFECount // feature
          // console.log(item, 'item')
          if (item.EarlyFECount) { // 早盤 count
            if (this.sportCount.future[item.SportId]) {
              this.sportCount.future[item.SportId] += item.EarlyFECount
            } else {
              this.sportCount.future[item.SportId] = item.EarlyFECount
            }
          }
          if (item.TodayFECount) { // 今日 count
            if (this.sportCount.today[item.SportId]) {
              this.sportCount.today[item.SportId] += item.TodayFECount
            } else {
              this.sportCount.today[item.SportId] = item.TodayFECount
            }
          }
          if (item.RBFECount) { // 今日 count & 滾球count
            this.sportCount.live[item.SportId] = item.RBFECount
            this.sportCount.today[item.SportId] = item.RBFECount
          }
        })
        // console.log(this.sportCount, 'this.sportCount')
      })
      getAllSportCount(true).then((res) => { // is combo === true
        this.actionSetSports(res)
        this.getSports.forEach((item) => {
          this.main[2].total = this.main[2].total + item.Count // combo
        })
      })
      // this.getGameList()
      this.actionSetSportCount(this.sportCount)
      // this.actionSetCurSportId(Object.keys(this.curSportCount)[0])
    }
  }
}
</script>

<style lang="scss" scoped>
.container {
  transition: margin-left .5s;
  background: #fff;
  min-width: 250px;
  .liveBall {
    width: 70px;
    height: 50px;
    background-color: #e0a44a;
    border-radius: 50px 0px 0px 50px;
    position: fixed;
    right: 0;
    top: 60%;
    text-align: center;
    div {
      font-weight: bold;
    }
    // vertical-align: middle;
  }
  ul {
    list-style: none;
    padding: 0;
    margin: 0;
    li {
      display: inline-block;
    }
  }
  .main {
    font-size: 14px;
    margin: 0 auto;
    margin-top: 10px;
    text-align: center;
    min-width: 320px;
    li {
      padding: 5px 20px;
      background-color: #fff;
      color: gray;
      font-weight: bold;
      border-radius: 5px 5px 0px 0px;
      div {
        &:last-child {
          font-size: 12px;
        }
        strong {
          font-size: 18px;
        }
      }
      hr {
        margin: 0px;
      }
    }
    .active {
      background-color: #000;
      color: #d89e48;
    }
  }
  .second {
    display: flex;
    flex: 1;
    align-items: center;
    height: 40px;
    width: 100vw;
    overflow-x: scroll;
    padding: 6px 0px;
    background-color: #000;
    color: #fff;
    font-size: 12px;
    li {
      margin: 0px 10px;
      padding: 5px 10px;
      justify-content: center;
      white-space: nowrap;
      border-radius: 5px;
    }
    .active {
      background-color: #e6a23c;
      color: #000;
    }
  }
  .feature {
    min-width: 320px;
    li {
      font-size:14px;
      padding: 10px 5px;
      border-right: 1px solid #e4e4e4d9;
      svg {
        color: #d89e48;
        background-color: #000;
        border-radius: 12px;
        width: 15px;
      }
      &:last-child {
        width: 50vw;
        text-align: right;
        border-right: none;
      }
      &:nth-child(2) {
        font-size: 10px;
        color: #909399b8;
        strong {
          color: #d89e48;
          font-size: 14px;
        }
      }
    }
  }
  .clear {
    clear: both;
  }
    .page {
      margin-top: 10px;
      color: #808080;
      .sport {
        &-content {
        .sportevent {
          margin: 0px 5px 0px 5px;
          border-radius: 10px;
          background-color: #ffffff;
        }
        .label {
          height: 45px;
          padding: 6px 12px;
          // border-bottom: 1px solid #eee;
          overflow: hidden;
          font-size: 14px;

          .icon {
            background: url('~@/assets/betball/basketball.png') no-repeat center/cover;
            height: 28px;
            float: left;
            width: 28px;
          }

          // .logoSize{
          //     height: 20px;
          //     width: auto;
          //     padding: 6px 5px;
          //     float: left;
          // }

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
            background: linear-gradient(135deg, #e4e7ed 86%, transparent 0);
          }

          .arrow {
            height: 18px;
            width: auto;
            padding: 5px;
            float: right;
            transition: transform .3s;

            &.down {
              transform: rotate(180deg);
            }
          }
        }
        .odds {
          &-Content {
            height: 0;
            overflow: hidden;
            transition: all .4s ease-in-out;
            // padding: 0px 10px;
            &.open {
              // height: 75px;
            }
            .ulList {
              padding: 0px 20px;
              // margin: 10px 0px;
              // border-bottom: 1px solid #80808030;
              div {
                // border-bottom: 1px solid #eee;
                display: inline-block;
                img {
                    width: 30px;
                }
              }
              .detail {
                text-align: center;
                width: 60vw;
                .time {
                    font-size: 12px;
                }
                .team {
                    font-weight: bold;
                    font-size: 12px;
                    strong {
                        font-size: 20px;
                        margin: 0px 15px;
                    }
                }
                .state {
                    font-size: 12px;
                    span {
                        &:first-child {
                            border-radius: 10px 0px 0px 10px;
                            width: 70vw;
                            padding: 0px 10px;
                            background: linear-gradient(135deg, #737477 86%, transparent 0);
                            color: #fff;
                        }
                            border-radius: 0px 10px 10px 0px;
                            padding: 0px 10px;
                            background: linear-gradient(-45deg, rgba(228,231,237,0.870588) 75%, transparent 0);
                    }
                }
              }
            }
          }

        }
  }
}

  }
}

</style>
