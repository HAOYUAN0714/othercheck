<template>
  <div class="container">
    <Header />
    <div class="clear" />
    <div class="filter">
      <div class="filterL">
        <span><i class="fa fa-angle-up" aria-hidden="true" /></span><span>收起</span>
      </div>
      <div class="filterR">
        <span><i class="fa fa-filter" aria-hidden="true" /></span>聯賽 / 時間<span />
      </div>
    </div>
    <!-- {{event}} -->
    <div class="content-wrap">
      <div v-for="(sport, leagueId) in totalGameList" :key="leagueId" class="sport-content">
        <div class="sportevent">
          <div class="label" @click="showLeague(leagueId)">
            <div class="slope">
              <div class="icon" />
              <div class="league-name">{{ sport.leagueName }}</div>
            </div>
            <img src="../../icons/png/down.png" :class="['arrow', { down: leagueOpenList[leagueId] }]">
          </div>
          <div :style="{ height: !leagueOpenList[leagueId] && sport.matchList ? `${sport.matchList.length * 140}px` : `0px` }" class="odds-Content clearfix">
            <div
              v-for="(matchInfo, subIndex) in sport.matchList"
              :key="`matchInfo-${subIndex}`"
              class="match-wrap clearfix"
            >
              <div class="oddsL tips">
                <div class="mar10">
                  <div>{{ formatEventTime(matchInfo.EventDate) }}</div>
                </div>
                <div class="total" @click="showSportDetails(1, matchInfo.matchId)">
                  {{ matchInfo.moreNum }}
                  <i class="fa fa-caret-right" aria-hidden="true" />
                </div>
                <div class="betName">
                  <div>{{ matchInfo.letName }}</div>
                  <div>{{ matchInfo.ballName }}</div>
                </div>
              </div>

              <div class="odds-Teams">
                <div class="teams">
                  <div class="oddsL">
                    <div class="mar10">
                      <div class="teamName">
                        <img src="../../assets/img/Fav.png" alt="">
                        {{ matchInfo.homeName }}
                      </div>
                      <div v-if="matchInfo.homeScore === null" class="teamScore">{{ '-' }}</div>
                      <div class="teamScore">{{ matchInfo.homeScore }}</div>
                      <div class="htxtb" @click="betting(1, 1, matchInfo.MarketLines[0], sport.sportId, subIndex, sport.leagueName, matchInfo.homeName, matchInfo.awayName, matchInfo.matchId, matchInfo.Market)">
                        <div class="handi">
                          {{ formatLet(matchInfo.letNameHomeHandicapCap) }}
                        </div>
                        <div class="odds">{{ matchInfo.letNameHomeOdds }}</div>
                      </div>
                      <div class="htxt">{{ matchInfo.ballHomeHomeSelectionName }}</div>
                      <div class="htxtb" @click="betting(2, 3, matchInfo.MarketLines[1], sport.sportId, subIndex, sport.leagueName, matchInfo.homeName, matchInfo.awayName, matchInfo.matchId, matchInfo.Market)">
                        <div class="handi">
                          {{ formatBall(matchInfo.ballHomeHomeHandicapCap) }}
                        </div>
                        <div class="odds">
                          {{ matchInfo.ballHomeOdds }}
                        </div>
                      </div>
                    </div>
                    <div class="mar10">
                      <div class="teamName">
                        <img src="../../assets/img/Fav.png" alt="">
                        {{ matchInfo.awayName }}
                      </div>
                      <div v-if="matchInfo.awayScore === null" class="teamScore">{{ '-' }}</div>
                      <div class="teamScore">{{ matchInfo.awayScore }}</div>
                      <div class="htxtb" @click="betting(1, 2, matchInfo.MarketLines[0], sport.sportId, subIndex, sport.leagueName, matchInfo.homeName, matchInfo.awayName, matchInfo.matchId, matchInfo.Market)">
                        <div class="handi">
                          {{ formatLet(matchInfo.letNameAwayHandicapCap) }}
                        </div>
                        <div class="odds">
                          {{ matchInfo.letNameAwayOdds }}
                        </div>
                      </div>
                      <div class="htxt">{{ matchInfo.ballAwaySelectionName }}</div>
                      <div class="htxtb" @click="betting(2, 4, matchInfo.MarketLines[1], sport.sportId, subIndex, sport.leagueName, matchInfo.homeName, matchInfo.awayName, matchInfo.matchId, matchInfo.Market)">
                        <div class="handi">
                          {{ formatBall(matchInfo.ballAwayHandicapCap) }}
                        </div>
                        <div class="odds">
                          {{ matchInfo.ballAwayOdds }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="Object.keys(totalGameList).length === 0" class="empty-reminder-box">
        <div class="empty-reminder" />
        {{ $t('S_NO_DATA') }}
      </div>
    </div>
    <div v-if="this.navHeader==='today' || this.navHeader==='future'" class="pageFooter">
      <ul class="pagination modal-1">
        <li><a href="#" class="prev" @click="pagePrev(-1)">&laquo;</a></li>
        <li><a href="#">{{ getPage }}</a></li>
        <!-- <li><a href="#">{{getPageSize}}</a></li> -->
        <li><a v-if="getPage <=pageSize" href="#" class="next" @click="pageNext(+1)">&raquo;</a></li>
      </ul>
    </div>
    <div v-if="this.navHeader==='combo'" class="pageFooter">
      <ul class="pagination modal-1">
        <li><a href="#" class="prev" @click="pagePrev(-1)">&laquo;</a></li>
        <li><a href="#">{{ getComboPage }}---</a></li>
        <li><a href="#" class="next" @click="pageNext(+1)">&raquo;</a></li>
      </ul>
    </div>
    <transition>
      <funcAll
        v-show="isShowFuncAll"
        :sport-count="sportCount"
        :sport-list="sportList"
        @closeFuncAll="closeFuncAll"
      />
    </transition>
  </div>
</template>

<script>
import moment from 'moment'
import { mapGetters, mapActions } from 'vuex'
import Header from '@/layout/m6app/components/header'
import funcAll from '@/layout/m6app/components/funcAll'
import { getEventInfoByPage, getSelectEventInfo } from '@/api/m6'

export default {
  name: 'MoreSport',
  components: {
    Header,
    funcAll
  },
  data() {
    return {
      moment,
      leagueOpenList: {},
      navTable: {
        live: 3,
        today: 2,
        future: 1,
        combo: null
      },
      hasSentAPI: false,
      pageSize: 0,
      isShowFuncAll: false,
      sportList: {},
      event: [],
      showcode: {},
      curBetSlip: []
    }
  },
  computed: {
    ...mapGetters({
      curSportId: 'm6app/getCurSportId',
      navHeader: 'm6app/getNavHeader',
      isCombo: 'm6app/getIsCombo',
      totalGameList: 'm6app/getTotalGameList',
      sportCount: 'm6app/getSportCount',
      getPreferPlayGroup: 'm6app/getPreferPlayGroup',
      getPage: 'm6app/getPage',
      getPageSize: 'm6app/getPageSize',
      getcomboMarket: 'm6app/getcomboMarket',
      getComboPage: 'm6app/getComboPage',
      getComboPageSize: 'm6app/getComboPageSize'
    })
  },

  watch: {
    curSportId(val) {
      if (this.isCombo === true) {
        this.getGameList({ SportId: +val, Market: 3, IsCombo: this.isCombo, page: 1 })
      } else {
        this.getGameList({ SportId: +val, Market: this.navTable[this.navHeader], IsCombo: this.isCombo, page: 1 })
      }
    },
    navHeader(val) {
      if (this.isCombo === true) {
        this.getGameList({ SportId: +Object.keys(this.sportCount[this.navHeader])[0], Market: this.getcomboMarket, IsCombo: this.isCombo, page: 1 })
      } else {
        this.getGameList({ SportId: +Object.keys(this.sportCount[this.navHeader])[0], Market: this.navTable[val], IsCombo: this.isCombo, page: 1 })
      }
    },
    getPage(val) { // 非串關 page
      if (this.isCombo === true) {
        // console.log()
      } else {
        this.getGameList({ SportId: this.curSportId, Market: this.navTable[this.navHeader], IsCombo: this.isCombo, page: val })
      }
    },
    getComboPage(val) { // 串關 page
      if (this.isCombo === true) {
        // console.log(Number(this.curSportId), '串關 getComboPag curSportId')
        // this.getGameList({ SportId: +Object.keys(this.sportCount[this.navHeader])[0], Market: this.getcomboMarket, IsCombo: this.isCombo, page: val })
        this.getGameList({ SportId: Number(this.curSportId), Market: this.getcomboMarket, IsCombo: this.isCombo, page: val })
      }
    },
    getcomboMarket(val) {
      this.getGameList({ SportId: Number(this.curSportId), Market: val, IsCombo: this.isCombo, page: 1 })
    }
  },
  created() {
  },
  mounted() {
    // console.log('mouted', this.totalGameList)
  },
  methods: {
    ...mapActions({
      actionSetTotalGameList: 'm6app/actionSetTotalGameList',
      actionSetIsLoading: 'm6app/actionSetIsLoading',
      actionSetPage: 'm6app/actionSetPage',
      actionSetCombopage: 'm6app/actionSetCombopage',
      actionSetComboMarket: 'm6app/actionSetComboMarket',
      actionSetComboPageSize: 'm6app/actionSetComboPageSize',
      actionSetPageSize: 'm6app/actionSetPageSize',
      actionSetBetInfo: 'm6app/actionSetBetInfo',
      setBet: 'm6app/actionBet',
      actionSetMoreGameInfo: 'm6app/actionSetMoreGameInfo'
    }),
    formatLet(val) {
      const mod = val % 0.5
      if (mod === 0) {
        return val * -1
      }
      if (val > 0) {
        return `-${Math.abs(val - mod)} / ${Math.abs(val + mod)}`
      }
      return `+${Math.abs(val - mod)} / ${Math.abs(val + mod)}`
      return val
    },
    formatBall(val) {
      const mod = val % 0.5
      if (mod === 0) {
        return val
      }
      if (val > 0) {
        return `+${Math.abs(val - mod)} / ${Math.abs(val + mod)}`
      }
      return `-${Math.abs(val - mod)} / ${Math.abs(val + mod)}`
      return val
    },
    showLeague(id) {
      this.leagueOpenList = {
        ...this.leagueOpenList,
        [id]: !this.leagueOpenList[id]
      }
    },
    formatBetTime(val) {
      return moment(val).format('DD/MM YY hh:mm:ss')
    },
    formatEventTime(val) {
      return moment(val).format('DD/MM hh:mm')
    },
    pagePrev(val) {
      // console.log(val, '--------')
      if (this.isCombo === true) {
        // this.actionSetCombopage(val)
        this.actionSetPage(val)
      } else {
        this.actionSetPage(val)
      }
    },
    pageNext(val) {
      // console.log(val, 'pageNextxxxxxxx')
      if (this.isCombo === true) {
        this.actionSetPage(val)
      } else {
        this.actionSetPage(val)
      }
    },
    betting(playId, betId, games, sportId, subIndex, leagueName, homeName, awayName, EventId, market) {
      // playId=1 讓球 playId=2 大小
      // betId = 1 letHome / 2 letAway / 3 ballHome / 4 ballAway
      // console.log(homeName, 'homeName', awayName, 'awayName', EventId, 'EventId')
      // console.log(games, 'games', betId, 'betId', playId, 'playId', subIndex, 'subIndex')
      this.getBetTypeData(games, betId)
      // ----
      this.setBet({
        bet: this.getBetTypeData(games, betId), // WagerSelections inner
        playType: games, // WagerSelections outer
        games,
        sportId: +(sportId),
        leagueName,
        homeName,
        awayName,
        EventId,
        betId,
        market
      })
      // ----
      this.isShowFuncPrefer = false
      this.isShowFuncAll = !this.isShowFuncAll
    },

    getBetTypeData(games, betId) {
      if (betId === 1 || betId === 3) {
        // console.log('home', games.WagerSelections[0])
        return games.WagerSelections[0]
      } else if (betId === 2 || betId === 4) {
        // console.log('away', games.WagerSelections[1])
        return games.WagerSelections[1]
      }
    },
    show(id) {
      if (!this.showcode[id]) {
        this.showcode[id] = true
      } else {
        delete this.showcode[id]
      }
      this.$forceUpdate()
    },

    closeFuncAll(val) {
      this.isShowFuncAll = val
    },
    showSportDetails(sport, matchId) {
      this.actionSetIsLoading(true)
      getSelectEventInfo(+sport, matchId).then((res) => {
        // console.log(res, 'showSportsDetail res')
        // const item = res[0].Events[0]
        // const playGroupList = item.MarketLines.reduce((prev, curr) => {
        //   const keyName = `${curr.BetTypeId}_${curr.PeriodId}`
        //   if (prev[keyName]) {
        //     return {
        //       ...prev,
        //       [keyName]: {
        //         ...prev[keyName],
        //         WagerSelections: [
        //           ...prev[keyName].WagerSelections,
        //           ...curr.WagerSelections
        //         ]
        //       }
        //     }
        //   }
        //   return {
        //     ...prev,
        //     [keyName]: { ...curr }
        //   }
        // }, {})
        // const gameInfo = {
        //   leagueName: res[0].Events[0].Competition.CompetitionName,
        //   awayName: item.AwayTeam,
        //   homeName: item.HomeTeam,
        //   awayScore: item.AwayScore,
        //   homeScore: item.HomeScore,
        //   time: item.EventDate,
        //   RbTimeStatus: item.RbTimeStatus,
        //   RBTime: item.RBTime,
        //   EventDate: item.EventDate,
        //   playGroupList,
        //   matchId,
        //   BREventId: item.BREventId,
        //   IsLive: item.IsLive
        // }

        // this.actionSetMoreGameInfo(gameInfo)
      }).finally(() => {
        this.actionSetIsLoading(false)
        this.$router.push({ name: 'sportDetail', path: '/sportDetail' })
      })
    },
    getGameList({ SportId, Market, IsCombo, page }) {
      // console.log(SportId, 'SportId', Market, 'Market', IsCombo, 'IsCombo', page, 'page')
      // 避免 curSportId & navHeader 同時觸發更新
      if (this.hasSentAPI) {
        return
      }
      const gameList = {}
      // if (!SportId) {
      //   this.actionSetTotalGameList(gameList)
      //   return
      // }
      this.hasSentAPI = true
      this.actionSetIsLoading(true)
      getEventInfoByPage({
        SportId: SportId,
        Market: Market,
        IsCombo: IsCombo,
        page: page
      }).then((res) => {
        if (res.Sports.length < 1) {
          // console.log('========')
          this.actionSetComboMarket(this.getcomboMarket - 1)
          // this.actionSetComboMarket(this.getcomboMarket)
          this.getGameList({ SportId: Number(this.curSportId), Market: this.getcomboMarket, IsCombo: this.isCombo, page: 1 })
        } else {
          if (res.Sports[0]) {
            // OutrightTeamId: 0
            // PeriodId: 1 ---
            // console.log('00000')
            // console.log(res.PageSize, 'res.PageSize')
            this.pageSize = res.PageSize
            this.actionSetPageSize(res.PageSize)
            this.actionSetComboPageSize(res.PageSize)
            // console.log('111111')
            res.Sports[0].Events.forEach((item, index) => {
              // console.log('222222')
              // console.log(item.MarketLines.filter(item => item.BetTypeId === 1).forEach((item)=> {console.log(item.MarketlineId)}), 'item.MarketLines.filter(item => item.BetTypeId === 1)')
              this.match = item.EventId
              const letName = item.MarketLines.filter(item => item.BetTypeId === 1)[0].BetTypeName
              const letNameHome = item.MarketLines.filter(item => item.BetTypeId === 1).sort((a, b) => a.MarketLineLevel - b.MarketLineLevel)[0].WagerSelections.filter(item => item.SelectionId === 1)[0]
              const letNameAway = item.MarketLines.filter(item => item.BetTypeId === 1).sort((a, b) => a.MarketLineLevel - b.MarketLineLevel)[0].WagerSelections.filter(item => item.SelectionId === 2)[0]
              const letMarketlineId = item.MarketLines.filter(item => item.BetTypeId === 1)[0].MarketlineId
              const letMarketlineStatusId = item.MarketLines.filter(item => item.BetTypeId === 1)[0].MarketlineStatusId
              const letPeriodId = item.MarketLines.filter(item => item.BetTypeId === 1)[0].PeriodId
              // console.log(item.MarketLines.filter(item => item.BetTypeId === 1).forEach((item,index)=>{
              //   console.log(item.WagerSelections, 'WagerSelections')
              // }), 'item.MarketLines.')
              const ballName = item.MarketLines.filter(item => item.BetTypeId === 2)[0].BetTypeName
              const ballHome = item.MarketLines.filter(item => item.BetTypeId === 2).sort((a, b) => a.MarketLineLevel - b.MarketLineLevel)[0].WagerSelections.filter(item => item.SelectionId === 3)[0]
              const ballAway = item.MarketLines.filter(item => item.BetTypeId === 2).sort((a, b) => a.MarketLineLevel - b.MarketLineLevel)[0].WagerSelections.filter(item => item.SelectionId === 4)[0]
              const ballMarketlineId = item.MarketLines.filter(item => item.BetTypeId === 2)[0].MarketlineId
              const ballMarketlineStatusId = item.MarketLines.filter(item => item.BetTypeId === 2)[0].MarketlineStatusId
              const ballPeriodId = item.MarketLines.filter(item => item.BetTypeId === 2)[0].PeriodId

              const homeInfo = item.MarketLines
                .sort((a, b) => a.MarketLineLevel - b.MarketLineLevel)[0]
                .WagerSelections
                .filter(item => item.SelectionId === 1)[0]
                // console.log(homeInfo, 'homeInfo')
              const awayInfo = item.MarketLines
                .sort((a, b) => a.MarketLineLevel - b.MarketLineLevel)[0]
                .WagerSelections
                .filter(item => item.SelectionId === 2)[0]
              const BetTypeName = item.MarketLines.sort((a, b) => a.MarketLineLevel - b.MarketLineLevel)[0].BetTypeName
              // console.log(item.MarketLines.filter(item => item.BetTypeId === 1).sort((a, b) => a.MarketLineLevel - b.MarketLineLevel)[0].WagerSelections.filter(item => item.SelectionId === 1)[0], 'test------')
              if (gameList[item.Competition.CompetitionId]) {
                gameList[item.Competition.CompetitionId] = {
                  ...gameList[item.Competition.CompetitionId],
                  matchList: [
                    ...gameList[item.Competition.CompetitionId].matchList,
                    {
                      homeName: item.HomeTeam,
                      awayName: item.AwayTeam,
                      homeScore: item.HomeScore,
                      awayScore: item.AwayScore,
                      matchId: item.EventId,
                      Market: item.Market,
                      MarketLines: item.MarketLines,
                      test: item.MarketLines.filter(item => item.BetTypeId === 1),
                      letMarketlineId: letMarketlineId,
                      letMarketlineStatusId: letMarketlineStatusId,
                      letPeriodId: letPeriodId,
                      ballMarketlineId: ballMarketlineId,
                      ballMarketlineStatusId: ballMarketlineStatusId,
                      ballPeriodId: ballPeriodId,
                      time: item.EventDate,
                      EventDate: item.EventDate,
                      RBTime: item.RBTime,
                      RbTimeStatus: item.RbTimeStatus,
                      moreNum: item.TotalMarketLineCount,
                      letBetTypeId: 1,
                      balBetTypeId: 2,
                      letName: letName,
                      letNameHomeOdds: letNameHome.Odds,
                      letNameHomeOddsType: letNameHome.OddsType,
                      letNameHomeHandicapCap: letNameHome.Handicap,
                      letNameHomeSelectionName: letNameHome.SelectionName,
                      letNameAwayOdds: letNameAway.Odds,
                      letNameAwayOddsType: letNameAway.OddsType,
                      letNameAwayHandicapCap: letNameAway.Handicap,
                      letNameAwaySelectionName: letNameAway.SelectionName,
                      ballName: ballName,
                      ballHomeOdds: ballHome.Odds,
                      ballHomeOddsType: ballHome.OddsType,
                      ballHomeHomeHandicapCap: ballHome.Handicap,
                      ballHomeHomeSelectionName: ballHome.SelectionName,
                      ballAwayOdds: ballAway.Odds,
                      ballAwayOddsType: ballAway.OddsType,
                      ballAwayHandicapCap: ballAway.Handicap,
                      ballAwaySelectionName: ballAway.SelectionName,
                      homeMain: homeInfo.SelectionName,
                      awayMain: awayInfo.SelectionName,
                      ballHomeWagerSelectionId: ballHome.WagerSelectionId,
                      ballAwayWagerSelectionId: ballAway.WagerSelectionId,
                      letHomeWagerSelectionId: letNameHome.WagerSelectionId,
                      letAwayWagerSelectionId: letNameAway.WagerSelectionId
                    }
                  ]
                }
                return
              }

              gameList[item.Competition.CompetitionId] = {
                sportId: SportId,
                leagueName: item.Competition.CompetitionName,
                matchList: [
                  {
                    homeName: item.HomeTeam,
                    awayName: item.AwayTeam,
                    homeScore: item.HomeScore,
                    awayScore: item.AwayScore,
                    matchId: item.EventId,
                    Market: item.Market,
                    MarketLines: item.MarketLines,
                    test: item.MarketLines.filter(item => item.BetTypeId === 1),
                    letMarketlineId: letMarketlineId,
                    letMarketlineStatusId: letMarketlineStatusId,
                    letPeriodId: letPeriodId,
                    ballMarketlineId: ballMarketlineId,
                    ballMarketlineStatusId: ballMarketlineStatusId,
                    ballPeriodId: ballPeriodId,
                    time: item.EventDate,
                    EventDate: item.EventDate,
                    RBTime: item.RBTime,
                    RbTimeStatus: item.RbTimeStatus,
                    moreNum: item.TotalMarketLineCount,
                    letBetTypeId: 1,
                    balBetTypeId: 2,
                    letName: letName,
                    letNameHomeOdds: letNameHome.Odds,
                    letNameHomeOddsType: letNameHome.OddsType,
                    letNameHomeHandicapCap: letNameHome.Handicap,
                    letNameHomeSelectionName: letNameHome.SelectionName,
                    letNameAwayOdds: letNameAway.Odds,
                    letNameAwayOddsType: letNameAway.OddsType,
                    letNameAwayHandicapCap: letNameAway.Handicap,
                    letNameAwaySelectionName: letNameAway.SelectionName,
                    ballName: ballName,
                    ballHomeOdds: ballHome.Odds,
                    ballHomeOddsType: ballHome.OddsType,
                    ballHomeHomeHandicapCap: ballHome.Handicap,
                    ballHomeHomeSelectionName: ballHome.SelectionName,
                    ballAwayOdds: ballAway.Odds,
                    ballAwayOddsType: ballAway.OddsType,
                    ballAwayHandicapCap: ballAway.Handicap,
                    ballAwaySelectionName: ballAway.SelectionName,
                    homeMain: homeInfo.SelectionName,
                    awayMain: awayInfo.SelectionName,
                    ballHomeWagerSelectionId: ballHome.WagerSelectionId,
                    ballAwayWagerSelectionId: ballAway.WagerSelectionId,
                    letHomeWagerSelectionId: letNameHome.WagerSelectionId,
                    letAwayWagerSelectionId: letNameAway.WagerSelectionId
                  }
                ]
              }
              // console.log(gameList, 'gameList nowwwwww')
            })
          }
        }
      }).catch((val) => {
        // console.log(val)
      }).finally(() => {
        // console.log(gameList)
        this.actionSetTotalGameList(gameList)
        this.actionSetIsLoading(false)
        this.hasSentAPI = false
      })
    }
  }
}
</script>
<style lang="scss" scoped>
.container {
    transition: margin-left .5s;
    background: #fff;
    min-width: 250px;
    .filter {
        clear: both;
        height: 40px;
        padding-top: 7px;
        // background-color: pink;
        .filterL {
            float: left;
            svg {
                width: 15px;
                border-radius: 50px;
                background-color: #000;
                color: #e6a23c;
                margin: 0px 10px;
            }
        }
        .filterR {
            float: right;
            margin-right :10px;
            svg {
                width: 15px;
                border-radius: 50px;
                background-color: #000;
                color: #e6a23c;
                margin: 0px 10px;
            }
        }
    }
    .content-wrap {
        background-color: #f5f7fa;
        height: calc(100vh - 185px);
        overflow-y: scroll;
    }

    .sport {
    &-content {
        .sportevent {
            margin: 10px 0px 0px 0px;
            background-color: #ffffff;
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
                float: right;
                margin-right: 10px;
                margin-top: 5px;
                transition: transform .3s;
                &.down {
                    transform: rotate(180deg);
                }
            }
        }
        .tips {
          width: 100%;
          font-weight: bold;
          padding-left: 0px;
          .mar10 {
            float: left;
            width: 40%;
            color: #4c4c4c;
            display: inline-block;
            background: linear-gradient(120deg, #e4e7ed 86%, transparent 0);
            div {
              padding: 5px;
              font-size: 12px;
              width: 100px;
            }
            .title {
              text-align: center;
              background: linear-gradient(120deg, #e6a23c 86%, transparent 0);
            }
          }
          .tv {
            float: left;
            font-size: 12px;
            width: 10px;
            vertical-align: super;
          }
          .total {
            // background-color: green;
            width: 15%;
            float: left;
            color:#c0c4cc;
            padding: 5px;
          }
          .betName {
            width: 45%;
            float: left;
            font-size: 12px;
            font-weight: 100;
            color:#c0c4cc;
            div {
              padding: 5px 20px;
              float: left;
            }
          }
        }

        .odds {
        &-Content {
            height: 0;
            overflow: hidden;
            transition: all .4s ease-in-out;
            &.open {
                height: 140px;
            }
        }
        &-Teams {
            width: 100%;
            .teams {
                float: left;
                width: 100%;
                .oddsL {
                    float: left;
                    padding-left: 5px;
                    width: calc(100% - 0px);
                    .mar10 {
                        font-size: 15px;
                        padding-right: 5px;
                        color: #4c4c4c;
                        white-space: nowrap;
                        overflow: scroll;
                        line-height: 28px;

                        div {
                          // display: inline-block;
                        }
                        .teamName {
                          img {
                            width: 20px;
                          }
                          font-weight: bold;
                          width: 40%;
                          overflow-x: scroll;
                          float: left;
                        }
                        .teamScore {
                          float: left;
                          width: 8%;
                          font-weight: bold;
                          text-align: center;
                          .fR {
                            div {

                              padding: 0px 0px;
                            }
                          }
                        }
                        .htxt {
                          width: 5%;
                          float: left;
                          font-size: 12px;
                          // border: 1px solid;
                          text-align: center;
                          color: gray;
                        }
                        .htxtb {
                          // width: 16%;
                          float: left;
                          font-size: 12px;
                          // border: 1px solid;
                          text-align: center;
                          color: gray;
                          .handi {
                            float: left;
                          }
                          .odds {
                            float: left;
                            font-weight: bold;
                            color: #000;
                            margin-left: 2px;
                            margin-right: 2px;
                          }
                        }
                        // .odds {
                        //   width: 10%;
                        //   font-size: 12px;
                        //   float: left;
                        //   font-weight: bold;
                        //   text-align: center;
                        // }
                    }
                }
            }
          }
        }
        .status {
            clear: both;
            font-size: 12px;
            .scoreL {
                float: left;
                padding: 5px 10px;
                span {
                    &:last-child{
                        color: red;
                    }
                }
            }
            .scoreR {
                float:right;
                padding: 5px 10px;
                span {
                    &:last-child{
                        color: red;
                    }
                }
            }
        }
    }
    }
    .pageFooter {
      background-color: #f4f4f5;;
      width: 100vw;
      // height: 8vh;
      position: fixed;
      bottom: 0;
      .pagination {
        list-style: none;
        display: inline-block;
        padding: 0;
        // margin-top: 10px;
        margin: 0;
        li {
          display: inline;
          text-align: center;
          a {
            float: left;
            display: block;
            font-size: 14px;
            text-decoration: none;
            // padding: 5px 12px;
            color: #fff;
            margin-left: -1px;
            border: 1px solid transparent;
            line-height: 1.5;
            &:active {
              outline: none;
            }
          }
          .active {
            cursor: default;
          }
        }
      }
      .modal-1 {
        li {
          &:first-child {
            a {
                -moz-border-radius: 6px 0 0 6px;
                -webkit-border-radius: 6px;
                border-radius: 6px 0 0 6px;
                padding: 5px 12px;
            }
          }
          &:last-child {
            a {
                -moz-border-radius: 0 6px 6px 0;
                -webkit-border-radius: 0;
                border-radius: 0 6px 6px 0;
                padding: 5px 12px;
            }
          }
          a {
            border-color: #ddd;
            color: #4285F4;
            background: #fff;
            padding: 5px 40px;
            &:hover {
              background: #eee;
            }
            // &:active {
            //   border-color: #4285F4;
            //   background: #4285F4;
            //   color: #fff;
            // }
          }
          .active {
            border-color: #4285F4;
            background: #4285F4;
            color: #fff;
          }
        }
      }
    }
.empty-reminder-box {
  height: 200PX;
  margin: 0 auto;
  padding-top: 35px;
  text-align: center;
}
.empty-reminder {
  width: 250px;
  height: 165px;
  margin: 0 auto;
  background: url('~@/assets/betball/empty-reminder.png') no-repeat center/contain;
}
}
</style>
