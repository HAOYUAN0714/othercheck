<template>
  <div class="dashboard-container">
    <loading v-show="loadingStatus" />
    <homeHeader />
    <navbar />
    <div class="dashboard-choose">
      <div v-for="(item,iconIndex) in (allEventCount.Today && allEventCount.Today.Sport)" :key="iconIndex" class="dashboard-choose-item">
        <div :class="{'active' : sportId == item.SportId }" @click="getSportData(item.SportId, 1, true)">
          <div class="dashboard-choose-item-text">{{ item.TodayCount }} {{ item.SportName }}</div>
        </div>
      </div>
    </div>
    <div v-if="sortedEvent && sortedEvent.length === 0 && init">
      <div class="dashboard-scoreListArea">
        <div class="scoreList">
          <div class="item sorryArea">
            {{ $t('S_SORRY') }}
          </div>
        </div>
      </div>
    </div>
    <matchInfo :sorted-event="sortedEvent" :sport-id="sportId" />
    <div class="live" @click="toMoreMatch">
      滾球test<br>
      {{ liveLen }}
      <!-- {{liveData.Sports[0].Events.length}} -->
      <!-- liveData{{liveData}} -->
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { getTodayGame, getLiveBasicData } from '@/api/m6'
import loading from '@/layout/components/loading'
import icon from '@/utils/sportIconList'
import matchInfo from '@/layout/yabolive/components/homeMatchInfo'
import homeHeader from '@/layout/yabolive/components/header'
import navbar from '@/layout/yabolive/components/navbar/homeNavbar'

export default {
  name: 'Dashboard',
  components: {
    loading,
    matchInfo,
    homeHeader,
    navbar
  },
  data() {
    return {
      total: 0,
      pagesize: 1,
      currentPage: 1,
      icon,
      sportId: '',
      sportData: {},
      sportDetail: {
        title: '足球',
        period: this.$t('S_COMMING_MATCH')
      },
      showcode: {},
      showWay: 1,
      timeout: '',
      loadingStatus: false,
      init: false,
      sportCount: {
        live: {},
        today: {},
        future: {},
        combo: {},
        faver: {}
      },
      liveData: {},
      liveLen: 0
    }
  },
  computed: {
    ...mapGetters({
      navHeader: 'm6app/getNavHeader',
      allEventCount: 'getAllEventCount'
    }),
    ballData() {
      if (!this.sportData.Sports) {
        return this.sportData
      }
      return this.sportData.Sports[0]
    },
    sortedEvent() {
      // console.log(this.liveData, 'this.liveData')
      // console.log(this.liveData.Sports, 'this.liveData.Events')
      var newData = []
      var sortData = []
      if (!this.ballData || !this.ballData.Events) {
        return []
      }
      if (!this.liveData.Sports) {
        this.liveLen = 0
        sortData = [...this.ballData.Events]
      } else if (this.liveData.Sports.length === 0) {
        this.liveLen = 0
        sortData = [...this.ballData.Events]
      } else {
        this.liveLen = this.liveData.Sports[0].Events.length
        // const newData = []
        newData.push(this.liveData.Sports[0].Events)
        newData.push(this.sportData.Sports[0].Events)
        sortData = [...newData[0], ...newData[1]]
      }

      return sortData
        .sort((a, b) => +(b.Market) - +(a.Market))
    }
    // ballData() {
    //   if (!this.sportData.Sports) {
    //     return this.sportData
    //   }
    //   if (!this.addLive.Sports) {
    //     return this.addLive
    //   }
    //   return this.addLive.Sports[0]
    // },
    // sortedEvent() {
    //   if (!this.ballData || !this.ballData.Events) {
    //     return []
    //   }
    //   // return this.ballData.Events
    //   // const sortData = [...this.ballData.Events, ...this.addLive.Sports[0].Events]
    //   console.log([...this.addLive.Events], 'sortData')
    //   // sortData.sort((a, b) => {
    //   //   return b.Competition.CompetitionId - a.Competition.CompetitionId
    //   // })
    //   return sortData
    // }
  },
  watch: {
    allEventCount() {
      this.firstUpdate()
    }
  },

  mounted() {
    this.firstUpdate()
  },
  beforeDestroy() {
    clearInterval(this.timeout)
  },
  methods: {
    ...mapActions({
      setBet: 'ball/actionBet',
      actionSetSportCount: 'm6app/actionSetSportCount'
    }),
    toMoreMatch() {
      const routeName = this.$route.name
      console.log(this.$route.name, 'this.$route.name')
      switch (routeName) {
        case 'today':
          console.log('1')
          this.$router.push('/moreSportToday')
          break
        case 'breakfast':
          this.$router.push('/moreSportFuture')
          break
        case 'combo':
          this.$router.push('/moreSportCombo')
          break
        case 'faver':
          this.$router.push('/moreSportFaver')
          break
      }
    },
    firstUpdate() {
      if (!this.allEventCount || !this.allEventCount.Today) {
        return {}
      } else {
        if (this.init === false) {
          this.sportId = this.allEventCount.Today.Sport[0].SportId
          this.getSportData(this.sportId, 1)
          // 先預設60s call一次api
          this.timeout = setInterval(() => {
            this.updateSportData(this.sportId, this.currentPage)
          }, 30000)
          this.init = true
        }
      }
    },
    clickOdds(playId, betId, games, sportId) {
      // console.log(playId, betId, games, sportId)
      this.setBet({
        bet: this.getBetTypeData(games.MarketLines, playId, betId),
        playType: this.getMarketLineId(games.MarketLines, playId),
        games,
        sportId: +(sportId)
      })
    },
    showMode(sportId) {
      // 1x2:1
      // 獨贏:2
      const setting = {
        1: 1,
        2: 2,
        3: 0
      }
      if (setting[sportId]) {
        return setting[sportId]
      } else {
        return 0
      }
    },
    changedPage(currentPage) {
      this.currentPage = currentPage
      this.sportData = {}
      this.getSportData(this.sportId, this.currentPage)
    },
    getIcon(sportId) {
      if (this.icon.includes(sportId)) {
        return `icon-${sportId}`
      } else {
        return 'icon-1'
      }
    },
    updateSportData(id, page) {
      getTodayGame(id, page).then((res) => {
        this.sportData = res
        this.sportId = id
        this.total = res.PageSize
        this.showWay = 1
      })
      getLiveBasicData(id, page).then((res) => {
        this.liveData = res
      })
      // ///
      // getAllSportCount(false).then((res) => { // is combo === false
      // this.loadingStatus = false
      // // console.log(res, 'res')
      //   res.forEach((item) => {
      //     console.log(item, 'item')
      //     if (item.EarlyFECount) { // 早盤 count
      //       if (this.sportCount.future[item.SportId]) {
      //         this.sportCount.future[item.SportId] += item.EarlyFECount
      //       } else {
      //         this.sportCount.future[item.SportId] = item.EarlyFECount
      //       }
      //     }
      //     if (item.TodayFECount) { // 今日 count
      //       if (this.sportCount.today[item.SportId]) {
      //         this.sportCount.today[item.SportId] += item.TodayFECount
      //       } else {
      //         this.sportCount.today[item.SportId] = item.TodayFECount
      //       }
      //     }
      //     if (item.RBFECount) { // 今日 count & 滾球count
      //       this.sportCount.live[item.SportId] = item.RBFECount
      //       this.sportCount.today[item.SportId] = item.RBFECount
      //     }
      //     console.log(this.sportCount, 'this.sportCount in more header')
      //   })
      // });
      // //
    },
    getSportData(id, page, changeSport = false) {
      if (changeSport) {
        this.currentPage = 1
      }
      this.sportData = {}
      this.loadingStatus = true
      getLiveBasicData(id, page).then((res) => {
        this.liveData = res
      }).then(() => {
        getTodayGame(id, page).then((res) => {
          this.loadingStatus = false
          this.sportData = res
          this.sportId = id
          this.total = res.PageSize
          this.showWay = 1
        })
      }).catch((res) => {
        this.loadingStatus = false
      })
      // getAllSportCount(false).then((res) => { // is combo === false
      // this.loadingStatus = false
      // // console.log(res, 'res')
      //   res.forEach((item) => {
      //     console.log(item, 'item')
      //     if (item.EarlyFECount) { // 早盤 count
      //       if (this.sportCount.future[item.SportId]) {
      //         this.sportCount.future[item.SportId] += item.EarlyFECount
      //       } else {
      //         this.sportCount.future[item.SportId] = item.EarlyFECount
      //       }
      //     }
      //     if (item.TodayFECount) { // 今日 count
      //       if (this.sportCount.today[item.SportId]) {
      //         this.sportCount.today[item.SportId] += item.TodayFECount
      //       } else {
      //         this.sportCount.today[item.SportId] = item.TodayFECount
      //       }
      //     }
      //     if (item.RBFECount) { // 今日 count & 滾球count
      //       this.sportCount.live[item.SportId] = item.RBFECount
      //       this.sportCount.today[item.SportId] = item.RBFECount
      //     }
      //     // console.log(this.sportCount, 'this.sportCount in more header')
      //   })
      // }).catch((res) => {
      //   this.loadingStatus = false
      // })
    },
    getBetTypeData(data, betType, selectionId) {
      const work = data.filter(
        val => val.BetTypeId === betType && val.MarketLineLevel === 1
      )[0]
      if (work) {
        return work.WagerSelections.filter(val => val.SelectionId === selectionId)[0]
      } else {
        return {}
      }
    },
    getMarketLineId(data, betType) {
      return data.filter(val => val.BetTypeId === betType && val.MarketLineLevel === 1)[0]
    },
    disply(way) {
      this.showWay = way
    },
    moreGame(game) {
      if (+(game.TotalMarketLineCount) === 0) {
        return
      }
      this.$router.push(`/detail/${this.sportId}/${game.EventId}`)
    },
    back() {
      this.$router.go(-1) // 返回上一层
    }
  }
}
</script>
<style lang='scss' scoped  src="@/styles/score.scss"></style>
<style lang="scss" scoped>
// @import "~@/styles/mixin.scss";
.live {
  width: 20vw;
  position: fixed;
  top: 70%;
  right: 0;
  background-color: #e6a23c;
  padding: 5px 0px;
  text-align: center;
  border-radius: 20px 0px 0px 20px;
}
.dashboard {
  &-container {
    margin: 0px;
  }
  &-choose {
    display: -webkit-box;
    overflow-x: scroll;
    width: 100%;
    background-color: #000;
    height: 45px;
    // position: fixed;
    // top: 12vh;
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
      box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
    }
    &-item {
      text-align: center;
      color: #fff;
      margin-right: 15px;
      min-width: 35px;
      margin: 5px;
      &-text {
        display: inline-block;
        font-size: .8em;
        font-weight: 600;
        padding: 5px;
      }
    }
    .active {
      background-color: #d89e43;
      color: #000;
      border-radius: 5px;
    }
  }
  // &-scoreListArea {
  //   padding: 0;
  //   position: fixed;
  //   top: 47vw;
  //   width: 100vw;
  // }
  // .scroll-inner {
  //   background-color: #d89e43;
  // }
  // &-feature {
  //     background-color: #f5f7fa;;
  //     ul {
  //       font-size: 14px;
  //       list-style: none;
  //       padding: 0;
  //       margin: 0;
  //       li {
  //         padding: 10px;
  //         display: inline-block;
  //       }
  //     }
  //   }
  // &-scoreListArea {
  //   background-color: #e8e8e8;
  //   & .scoreList {
  //     border-radius: 5px;
  //     & .item {
  //       display: flex;
  //       height: 50px;
  //       border-radius: 5px;
  //       background-color: var(--title1_bg);
  //       border-top: 1px solid #000000a1;
  //       padding: 6px 10px;
  //       color: var(--title1_text);
  //       align-items: center;
  //       & .title { // ------
  //         font-size: 14px;
  //         // font-weight: bold;
  //         margin: 0;
  //         padding: 10px 1px;
  //         width: 91%;

  //       }
  //       & .sub-text {
  //         color: var(--title1_sub_text);
  //       }
  //       & .count {
  //         float: right;
  //         font-weight: 500;
  //         color: rgb(4, 20, 110);
  //         margin: 0;
  //         padding: 10px 1px;
  //       }
  //     }
  //     & .text {
  //       margin-bottom: 5px;
  //       &-title {
  //         font-size: 14px;
  //         flex: 6;
  //         margin: 0;
  //         div {
  //           display: inline-block;
  //         }
  //         .camp {
  //           width: 75vw;
  //           padding: 5px;
  //           background: linear-gradient(130deg, #c0c4cce0 86%, transparent 0);
  //         }
  //         .arraw {
  //           width: 20vw;
  //           text-align: right;
  //           background: linear-gradient(-55deg, #e4e7ed 86%, transparent 0);
  //           height: 26px;
  //           vertical-align: top;
  //         }
  //       }
  //       .oddHide {
  //         div {
  //           background-color: #fff;
  //           padding-bottom: 10px;
  //           position: relative;
  //           .header {
  //             padding: 5px;
  //             .time {
  //               text-align: center;
  //             }
  //           }
  //           .vs {
  //             top: 30%;
  //             left: 45%;
  //             font-size: 20px;
  //             font-weight: bold;
  //             position: absolute;
  //           }
  //           .mean {
  //             .team {
  //               font-size: 12px;
  //               text-align: center;
  //               height: 30px;
  //               .team-left {
  //                 width: 40%;
  //                 float: left;
  //                 overflow-x: scroll;
  //               }
  //               .team-right {
  //                 width: 40%;
  //                 float: right;
  //                 overflow-x: scroll;
  //               }
  //             }

  //           }
  //           .clear {
  //               clear: both;
  //             }
  //           .status {
  //             width: 35vw;
  //             margin: 0 auto;
  //             background-color: #e4e7ed;
  //             padding-bottom: 0;
  //             border-radius: 20px;
  //             div {
  //               display: inline-block;
  //             }
  //             .statusLeft {
  //               color: #fff;
  //               min-width: 90px;
  //               font-size: 12px;
  //               background-color: #c5c9d0;
  //               padding: 5px 10px;
  //               border-radius: 20px 0px 0px 20px;
  //               background: linear-gradient(120deg, #c0c4cce0 70%, transparent 0);
  //             }
  //             .statusRight {
  //               color: blue;
  //               padding: 5px;
  //             }
  //           }
  //           .star {
  //             position: absolute;
  //             bottom: 4px;
  //             right: 10px;
  //             color: #DDD;
  //           }
  //         }
  //       }
  //     }
  //   }
  // }
}
</style>
