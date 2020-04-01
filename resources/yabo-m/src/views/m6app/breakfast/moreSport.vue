<template>
  <div class="dashboard-container">
    <loading v-show="loadingStatus" />
    <navbar />
    <div class="dashboard-choose">
      <div v-for="(item,iconIndex) in (allEventCount.Early && allEventCount.Early.Sport)" :key="iconIndex" class="dashboard-choose-item">
        <div :class="{'active' : sportId == item.SportId }" @click="getSportData(item.SportId, 1, true)">
          <div class="dashboard-choose-item-text">{{ item.EarlyCount }} {{ item.SportName }}</div>
        </div>
      </div>
    </div>
    <div class="dashboard-feature">
      <ul class="feature">
        <li>
          <span
            :class="[collapseAllMatch ? 'show-all-match' : 'hide-all-match']"
            @click="collapseAllLeague"
          />
        </li>
        <li @click="changeFilterMatchType">
          <span
            :class="[filterMatchStatus === 1 ? 'time' : 'league']"
          />
        </li>
      </ul>
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
    <div v-if="sortedEvent && sortedEvent.length > 0">
      <div class="dashboard-scoreListArea">
        <div class="scoreList">
          <div class="title">
            <el-pagination
              background
              layout="prev, pager, next"
              :total="total"
              :page-size="1"
              :current-page.sync="currentPage"
              @current-change="changedPage"
            /></div>
          <div v-for="(games,item) in sortedEvent" :key="item" class="text">
            <div class="oddComponent">
              <div v-if="checkFirst(item)" class="text-title" @click="show(games.Competition.CompetitionId)">
                <div class="camp">
                  <div class="league-name">
                    <img
                      :src="`/exsport/img/CompetitionImageFile/${games.Competition.CompetitionId}.png`"
                      class="league-img"
                      @error="setDefaultLogo"
                    >
                    <div class="name">{{ games.Competition.CompetitionName }}</div>
                  </div>
                </div>
                <div class="arraw">
                  <font-awesome-icon
                    class="icon"
                    :icon="[
                      'fas',
                      showcode[games.Competition.CompetitionId] ? 'angle-up' : 'angle-down'
                    ]"
                  />
                </div>
              </div>
              <div v-show="!showcode[games.Competition.CompetitionId] === true && showWay === 1 " class="oddHide">
                <div>
                  <div class="header">
                    <div class="time">{{ formateTime(games.EventDate) }}</div>
                    <div class="count" @click="moreGame(games)">
                      {{ games.TotalMarketLineCount }}>
                    </div>
                    <div>{{ $t('S_HANDICAP') }}</div>
                    <div>{{ $t('S_OVER_UNDER') }}</div>
                  </div>
                  <div class="mean">
                    <div class="team">
                      <div class="team-left">
                        <div class="team">
                          <img
                            :src="`/exsport/img/TeamImageFile/${games.HomeTeamId}.png`"
                            class="team-img"
                            @error="setDefaultLogo"
                          >
                          <div class="teamName">{{ games.HomeTeam }}</div>
                        </div>
                        <div class="team">
                          <img
                            :src="`/exsport/img/TeamImageFile/${games.AwayTeamId}.png`"
                            class="team-img"
                            @error="setDefaultLogo"
                          >
                          <div class="teamName">{{ games.AwayTeam }}</div>
                        </div>
                      </div>
                      <div class="team-score">
                        <div class="score">{{ games.HomeScore }}</div>
                        <div class="score">{{ games.AwayScore }}</div>
                      </div>
                      <div class="team-right">
                        <div class="oddBox-area">
                          <div @click="clickOdds(1, 1, games, sportId)">
                            <div
                              v-if="getMarketLineId(games.MarketLines, 1)"
                              :class="{
                                active: curBetSlip.some(item => item.odds_id === getBetTypeData(games.MarketLines, 1, 1).WagerSelectionId),
                                disable: !getBetTypeData(games.MarketLines, 1, 1) || +(getMarketLineId(games.MarketLines, 1).MarketlineStatusId) === 2 || +(getBetTypeData(games.MarketLines, 1, 1).Odds) === 0,
                                up: oddsStatus === 'up',
                                down: oddsStatus === 'down'
                              }"
                              class="oddBox"
                            >
                              <div v-if="!getBetTypeData(games.MarketLines, 1, 1) || +(getMarketLineId(games.MarketLines, 1).MarketlineStatusId) === 2 || +(getBetTypeData(games.MarketLines, 1, 1).Odds) === 0 || !getMarketLineId(games.MarketLines, 1) || getMarketLineId(games.MarketLines, 1).IsLocked" class="lock">
                                <i class="fas fa-lock" />
                              </div>
                              <div v-else class="unlock">
                                <div class="cap">
                                  {{ formatCap(getBetTypeData(games.MarketLines, 1, 1), getMarketLineId(games.MarketLines, 1)) }}
                                </div>
                                <div
                                  :class="{
                                    change: oddsStatus === 'up' || oddsStatus === 'down',
                                    'no-cap': !getBetTypeData(games.MarketLines, 1, 1).Handicap && getBetTypeData(games.MarketLines, 1, 1).Handicap!== 0
                                  }"
                                  class="black"
                                >
                                  {{ parseOdds(getBetTypeData(games.MarketLines, 1, 1)) }}
                                </div>
                              </div>
                            </div>
                            <div v-else class="oddBox">
                              <div class="unlock">
                                -
                              </div>
                            </div>
                            <!-- <odds
                              v-if="getMarketLineId(games.MarketLines, 1)"
                              class="odds"
                              :odds="getBetTypeData(games.MarketLines, 1, 1)"
                              :marketline-status="getMarketLineId(games.MarketLines, 1)"
                              :nav="nav"
                            /> -->
                          </div>
                          <div @click="clickOdds(1, 2, games, sportId)">
                            <div
                              v-if="getMarketLineId(games.MarketLines, 1)"
                              :class="{
                                active: curBetSlip.some(item => item.odds_id === getBetTypeData(games.MarketLines, 1, 2).WagerSelectionId),
                                disable: !getBetTypeData(games.MarketLines, 1, 2) || +(getMarketLineId(games.MarketLines, 1).MarketlineStatusId) === 2 || +(getBetTypeData(games.MarketLines, 1, 2).Odds) === 0,
                                up: oddsStatus === 'up',
                                down: oddsStatus === 'down'
                              }"
                              class="oddBox"
                            >
                              <div v-if="!getBetTypeData(games.MarketLines, 1, 2) || +(getMarketLineId(games.MarketLines, 1).MarketlineStatusId) === 2 || +(getBetTypeData(games.MarketLines, 1, 2).Odds) === 0 || !getMarketLineId(games.MarketLines, 1) || getMarketLineId(games.MarketLines, 1).IsLocked" class="lock">
                                <i class="fas fa-lock" />
                              </div>
                              <div v-else class="unlock">
                                <div class="cap">
                                  {{ formatCap(getBetTypeData(games.MarketLines, 1, 2), getMarketLineId(games.MarketLines, 1)) }}
                                </div>
                                <div
                                  :class="{
                                    change: oddsStatus === 'up' || oddsStatus === 'down',
                                    'no-cap': !getBetTypeData(games.MarketLines, 1, 2).Handicap && getBetTypeData(games.MarketLines, 1, 2).Handicap!== 0
                                  }"
                                  class="black"
                                >
                                  {{ parseOdds(getBetTypeData(games.MarketLines, 1, 2)) }}
                                </div>
                              </div>
                            </div>
                            <div v-else class="oddBox">
                              <div class="unlock">
                                -
                              </div>
                            </div>
                            <!-- <odds
                              v-if="getMarketLineId(games.MarketLines, 1)"
                              class="odds"
                              :odds="getBetTypeData(games.MarketLines, 1, 2)"
                              :marketline-status="getMarketLineId(games.MarketLines, 1)"
                              :nav="nav"
                            /> -->
                          </div>
                        </div>
                        <div class="oddBox-area">
                          <div @click="clickOdds(2, 3, games, sportId)">
                            <div
                              v-if="getMarketLineId(games.MarketLines, 2)"
                              :class="{
                                active: curBetSlip.some(item => item.odds_id === getBetTypeData(games.MarketLines, 2, 3).WagerSelectionId),
                                disable: !getBetTypeData(games.MarketLines, 2, 3) || +(getMarketLineId(games.MarketLines, 2).MarketlineStatusId) === 2 || +(getBetTypeData(games.MarketLines, 2, 3).Odds) === 0,
                                up: oddsStatus === 'up',
                                down: oddsStatus === 'down'
                              }"
                              class="oddBox"
                            >
                              <div v-if="!getBetTypeData(games.MarketLines, 2, 3) || +(getMarketLineId(games.MarketLines, 2).MarketlineStatusId) === 2 || +(getBetTypeData(games.MarketLines, 2, 3).Odds) === 0 || !getMarketLineId(games.MarketLines, 2) || getMarketLineId(games.MarketLines, 2).IsLocked" class="lock">
                                <i class="fas fa-lock" />
                              </div>
                              <div v-else class="unlock">
                                <div class="cap">
                                  {{ formatCap(getBetTypeData(games.MarketLines, 2, 3), getMarketLineId(games.MarketLines, 2)) }}
                                </div>
                                <div
                                  :class="{
                                    change: oddsStatus === 'up' || oddsStatus === 'down',
                                    'no-cap': !getBetTypeData(games.MarketLines, 2, 3).Handicap && getBetTypeData(games.MarketLines, 2, 3).Handicap!== 0
                                  }"
                                  class="black"
                                >
                                  {{ parseOdds(getBetTypeData(games.MarketLines, 2, 3)) }}
                                </div>
                              </div>
                            </div>
                            <div v-else class="oddBox">
                              <div class="unlock">
                                -
                              </div>
                            </div>
                            <!-- <odds
                              v-if="getMarketLineId(games.MarketLines, 2)"
                              class="odds"
                              :odds="getBetTypeData(games.MarketLines, 2, 3)"
                              :marketline-status="getMarketLineId(games.MarketLines, 2)"
                              :nav="nav"
                            /> -->
                          </div>
                          <div @click="clickOdds(2, 4, games, sportId)">
                            <div
                              v-if="getMarketLineId(games.MarketLines, 2)"
                              :class="{
                                active: curBetSlip.some(item => item.odds_id === getBetTypeData(games.MarketLines, 2, 4).WagerSelectionId),
                                disable: !getBetTypeData(games.MarketLines, 2, 4) || +(getMarketLineId(games.MarketLines, 2).MarketlineStatusId) === 2 || +(getBetTypeData(games.MarketLines, 2, 4).Odds) === 0,
                                up: oddsStatus === 'up',
                                down: oddsStatus === 'down'
                              }"
                              class="oddBox"
                            >
                              <div v-if="!getBetTypeData(games.MarketLines, 2, 4) || +(getMarketLineId(games.MarketLines, 2).MarketlineStatusId) === 2 || +(getBetTypeData(games.MarketLines, 2, 4).Odds) === 0 || !getMarketLineId(games.MarketLines, 2) || getMarketLineId(games.MarketLines, 2).IsLocked" class="lock">
                                <i class="fas fa-lock" />
                              </div>
                              <div v-else class="unlock">
                                <div class="cap">
                                  {{ formatCap(getBetTypeData(games.MarketLines, 2, 4), getMarketLineId(games.MarketLines, 2)) }}
                                </div>
                                <div
                                  :class="{
                                    change: oddsStatus === 'up' || oddsStatus === 'down',
                                    'no-cap': !getBetTypeData(games.MarketLines, 2, 4).Handicap && getBetTypeData(games.MarketLines, 2, 4).Handicap!== 0
                                  }"
                                  class="black"
                                >
                                  {{ parseOdds(getBetTypeData(games.MarketLines, 2, 4)) }}
                                </div>
                              </div>
                            </div>
                            <div v-else class="oddBox">
                              <div class="unlock">
                                -
                              </div>
                            </div>
                            <!-- <odds
                              v-if="getMarketLineId(games.MarketLines, 2)"
                              class="odds"
                              :odds="getBetTypeData(games.MarketLines, 2, 4)"
                              :marketline-status="getMarketLineId(games.MarketLines, 2)"
                              :nav="nav"
                            /> -->
                          </div>
                        </div>
                      </div>
                      <div class="clear" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <transition>
      <funcAll
        v-if="isShowFuncAll"
        :open-bet-slip="isShowFuncAll"
        @closeFuncAll="closeFuncAll"
      />
    </transition>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
// import { getToday } from '@/utils/getBallData'
import { getEarlyGame } from '@/api/m6'
// import odds from '@/layout/components/odds/layoutOdds'
// import odds1X2 from '@/layout/components/odds/layoutOdds1X2'
import loading from '@/layout/components/loading'
import moment from 'moment'
import icon from '@/utils/sportIconList'
import funcAll from '@/layout/m6app/components/funcAll'
import defaultImg from '@/assets/m6app/ic_team_logo_default.png'
import navbar from '@/layout/m6app/components/navbar/moreGameNavbar'

export default {
  name: 'Dashboard',
  components: {
    // odds,
    // odds1X2,
    loading,
    funcAll,
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
      isShowFuncAll: false,
      nav: 'breakfast',
      curData: [],
      // comboStatus 0初始 1取資訊 2可下過關（可能騙你）3準備過關 4以過關
      comboStatus: 0,
      checkRepeat: 0,
      comboOdd: 0,
      // pre=實際數字 input=顯示
      errorAllert_combo: '',
      collisionCheck: [],
      oddsStatus: '',
      collapseAllMatch: false,
      filterMatchStatus: +(this.$cookie.get('filterMatch')),
      finalInfo: [],
      liveLen: 0
    }
  },
  computed: {
    ...mapGetters({
      navHeader: 'm6app/getNavHeader',
      curGameCount: 'curGameCount',
      allEventCount: 'getAllEventCount',
      curBetSlip: 'getBetSlip'
    }),
    navPath() {
      return this.$route.path
    },
    ballData() {
      if (!this.sportData.Sports) {
        return this.sportData
      }
      return this.sportData.Sports[0]
    },
    sortedEvent() {
      if (!this.ballData || !this.ballData.Events) {
        return []
      }
      const sortData = [...this.ballData.Events]
      sortData.sort((a, b) => {
        return b.Competition.CompetitionId - a.Competition.CompetitionId
      })
      return sortData
    }
  },
  watch: {
    allEventCount() {
      this.firstUpdate()
    },
    odds(newOdds, oldOdds) {
      if (newOdds.Odds > oldOdds.Odds) {
        this.oddsStatus = 'up'
      }
      if (newOdds.Odds < oldOdds.Odds) {
        this.oddsStatus = 'down'
      }

      this.timeout = setTimeout(() => {
        this.oddsStatus = ''
      }, 5000)
    },
    curBetSlip(val) {
      this.finalInfo = []
      // const collision = {}
      this.comboStatus = 0
      this.errorMsg = ''
      val.forEach((data) => {
        const base = data.betinfo.apiInput
        // 計算串了幾場
        if (this.comboStatus === 0 || this.comboStatus === 1) {
          if (base.WagerSelectionInfos.OddsType === 3 && data.openParlay === true) {
            this.collisionCheck.push(data.collisionCheck)
            this.collisionCheck = this.collisionCheck.filter((element, index, arr) => { return arr.indexOf(element) === index })
            // console.log(this.collisionCheck, 'this.collisionCheck')
            // this.comboInfo = '串關處理中'
          } else {
            // this.comboInfo = '含有非歐洲盤賠率 無法串關'
            this.comboStatus = -1
            this.finalInfo = []
          }
        }
      })
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
      actionSetSportCount: 'm6app/actionSetSportCount',
      actionSetNavHeader: 'm6app/actionSetNavHeader'
    }),
    firstUpdate() {
      if (!this.allEventCount || !this.allEventCount.Early) {
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
    NavRouter(page) {
      console.log(page, 'page')
      this.$router.push(page)
      this.ItemClass = page
    //   this.actionSetNavHeader(page)
    },
    formatCap(val, marketLine) {
      if (!val.Handicap && val.Handicap !== 0) {
        return
      }
      const mod = val.Handicap % 0.5
      const betTypeId = +(marketLine.BetTypeId)
      const selectionId = +(val.SelectionId)
      if (mod === 0) {
        if (betTypeId === 1 && selectionId === 1) {
          if (val.Handicap > 0) {
            return `-${val.Handicap}`
          }
          return `${val.Handicap === 0 ? 0 : +Math.abs(val.Handicap)}`
        }
        if (betTypeId === 1 && selectionId === 2) {
          if (val.Handicap > 0) {
            return `+${val.Handicap}`
          }
          return `${val.Handicap === 0 ? 0 : -Math.abs(val.Handicap)}`
        }
        return val.Handicap
      }

      if (betTypeId === 1 && selectionId === 1) {
        if (val.Handicap > 0) {
          return `-${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
        }
        return `+${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
      }
      if (betTypeId === 1 && selectionId === 2) {
        if (val.Handicap > 0) {
          return `+${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
        }
        return `-${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
      }
      if (betTypeId === 2) {
        return `${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
      }
      return val.Handicap
    },
    checkFirst(index) {
      if (index - 1 < 0) {
        return true
      }
      const current = this.sortedEvent[index].Competition.CompetitionId
      const before = this.sortedEvent[index - 1].Competition.CompetitionId
      return current !== before
    },
    clickOdds(playId, betId, games, sportId) {
      // console.log(playId, betId, games, sportId)
      const betTypeId = games.MarketLines.find((item) => { return item.BetTypeId === playId })
      const SelectionId = betTypeId.WagerSelections.find((item) => { return item.SelectionId === betId })
      this.setBet({
        bet: this.getBetTypeData(games.MarketLines, playId, betId),
        playType: this.getMarketLineId(games.MarketLines, playId),
        games,
        sportId: +(sportId)
      })
      if (betTypeId.MarketlineStatusId === 2 || SelectionId.Odds === 0 || betTypeId.IsLocked === true) {
        this.isShowFuncAll = false
      } else {
        this.isShowFuncAll = !this.isShowFuncAll
      }
      // this.isShowFuncPrefer = false
      // this.isShowFuncAll = !this.isShowFuncAll
    },
    closeFuncAll(val) {
      this.isShowFuncAll = val
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
      getEarlyGame(id, page).then((res) => {
        this.sportData = res
        this.sportId = id
        this.total = res.PageSize
        this.showWay = 1
      })
    },
    getSportData(id, page, changeSport = false) {
      if (changeSport) {
        this.currentPage = 1
      }
      this.sportData = {}
      this.loadingStatus = true
      getEarlyGame(id, page).then((res) => {
        this.loadingStatus = false
        this.sportData = res
        this.sportId = id
        this.total = res.PageSize
        this.showWay = 1
      }).catch((res) => {
        this.loadingStatus = false
      })
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
    show(id) {
      if (!this.showcode[id]) {
        this.showcode[id] = true
      } else {
        delete this.showcode[id]
      }
      this.$forceUpdate()
    },
    disply(way) {
      this.showWay = way
    },
    moreGame(game) {
      if (+(game.TotalMarketLineCount) === 0) {
        return
      }
      this.$router.push(`/detail/${this.sportId}/${game.EventId}/${this.$route.name}`)
    },
    back() {
      this.$router.go(-1) // 返回上一层
    },
    formateTime(val) {
      return moment(val).utcOffset(-4).format('MM/DD HH:mm')
    },
    collapseAllLeague() {
      if (!this.collapseAllMatch) {
        this.sortedEvent.forEach((item) => {
          this.showcode[item.Competition.CompetitionId] = true
          this.showcode = {
            ...this.showcode,
            ...item.Competition.CompetitionId
          }
        })
        this.collapseAllMatch = true
        return
      }
      this.showcode = {}
      this.collapseAllMatch = false
    },
    changeFilterMatchType() {
      if (+(this.$cookie.get('filterMatch')) === 1) {
        this.$cookie.set('filterMatch', 2)
        location.reload()
        return
      }
      this.$cookie.set('filterMatch', 1)
      location.reload()
    },
    setDefaultLogo(e) {
      e.target.src = defaultImg
    }
  }
}
</script>

<style lang="scss" scoped>

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
  &-feature {
      background-color: #f5f7fa;;
      ul {
        font-size: 14px;
        list-style: none;
        padding: 0;
        margin: 0;
        li {
          padding: 10px;
          display: inline-block;
        }
        .hide-all-match,
        .show-all-match {
          width: 53px;
          height: 25px;
          display: inline-block;
        }
        .league,
        .time {
          display: inline-block;
          width: 78px;
          height: 25px;
        }
        .hide-all-match {
          background: url('~@/assets/m6app/ic_home_icon_expand.png') 50% 5px no-repeat;
          background-size: contain;
        }
        .show-all-match {
          background: url('~@/assets/m6app/ic_home_icon_open.png') 50% 5px no-repeat;
          background-size: contain;
        }
        .league {
          background: url('~@/assets/m6app/ic_filter_league.png') 50% 5px no-repeat;
          background-size: contain;
        }
        .time {
          background: url('~@/assets/m6app/ic_filter_time.png') 50% 5px no-repeat;
          background-size: contain;
        }
      }
  }
  &-scoreListArea {
    background-color: #F9F9F9;
    & .scoreList {
      border-radius: 5px;
      .title {
        >>>.el-pagination {
            .el-pager {
              .active {
                background-color: #e6a23c;
                color: #222;
              }
            }
          }
      }
      & .item {
        display: flex;
        height: 50px;
        border-radius: 5px;
        background-color: var(--title1_bg);
        border-top: 1px solid #000000a1;
        padding: 6px 10px;
        color: var(--title1_text);
        align-items: center;
        & .title {
          font-size: 14px;
          // font-weight: bold;
          margin: 0;
          padding: 10px 1px;
          width: 91%;

        }
        & .sub-text {
          color: var(--title1_sub_text);
        }
        & .count {
          float: right;
          font-weight: 500;
          color: rgb(4, 20, 110);
          margin: 0;
          padding: 10px 1px;
        }
      }
      & .text {
        margin-bottom: 5px;
        margin-top: 5px;
        &-title {
          font-size: 14px;
          flex: 6;
          margin: 0;
          div {
            display: inline-block;
          }
          .camp {
            width: 75vw;
            // padding: 0px 5px;
            background: linear-gradient(130deg, #E8E8E8 86%, transparent 0);
            color: #737373;
            text-align: left;
            div {
              display: inline-block;
              width: calc(100% - 50px);
              height: 30px;
              line-height: 30px;
              white-space: nowrap;
              overflow-x: scroll;
              .league-img {
                // background: url(/exsport/img/CompetitionImageFile/230.png) 0px 0px no-repeat;
                background-repeat: no-repeat;
                width: 30px;
                height: 30px;
                margin-bottom: 0;
                background-size: 100%;
              }
              &::-webkit-scrollbar {
              width: 5px;
              }
              &::-webkit-scrollbar:horizontal {
                height: 5px;
              }
              &::-webkit-scrollbar-track {
                background-color: transparentize(#E8E8E8, 0.7);
              }
              &::-webkit-scrollbar-thumb {
                border-radius: 5px;
                background: transparentize(#E8E8E8, 0.5);
                box-shadow: inset 0 0 6px #E8E8E8;
              }
            }
          }
          .arraw {
            width: 15vw;
            text-align: right;
            // background: linear-gradient(-55deg, #e4e7ed 86%, transparent 0);
            height: 26px;
            vertical-align: top;
          }
        }
        .oddHide {
          div {
            // background-color: #fff;

            // padding-bottom: 10px;
            // position: relative;
          }
          .header {
            font-size: 12px;
            color: #727477;
            background-color: #fff;
            div {
                min-width: 24.5%;
                overflow: hidden;
                display: inline-block;
                text-align: center;
            }
            .time {
                // color: #d89e43;
                min-width: 35%;
                text-align: left;
                background: linear-gradient(130deg, #EFEFEF 83%, transparent 0);
                padding: 5px;
            }
            .live {
                min-width: 35%;
                text-align: center;
                padding: 3px;
                background: linear-gradient(130deg, #e6a23c 83%, transparent 0);
            }
            .count {
                min-width: 12%;
            }
          }
          .mean {
            .team {
              background-color: #fff;
                .team-left {
                    width: 35%;
                    float: left;
                    text-align: center;
                    padding: 0px 5px;
                    font-size: 14px;
                    font-weight: bold;
                    overflow: hidden;
                    // display: -webkit-box;
                    // overflow: scroll;
                    // width: 35vw;
                    .team {
                        padding-top: 5px;
                        text-align: left;
                      .team-img {
                        background-repeat: no-repeat;
                        width: 25px;
                        height: 25px;
                        margin-bottom: 0;
                        display: inline-block;
                      }
                      .teamName {
                        display: -webkit-box;
                        overflow-x: scroll;
                        width: 70%;
                        display: inline-block;
                        white-space: nowrap;
                        &::-webkit-scrollbar {
                        width: 5px;
                        }
                        &::-webkit-scrollbar:horizontal {
                          height: 5px;
                        }
                        &::-webkit-scrollbar-track {
                          background-color: transparentize(#fff, 0.7);
                        }
                        &::-webkit-scrollbar-thumb {
                          border-radius: 5px;
                          background: transparentize(#fff, 0.5);
                          box-shadow: inset 0 0 6px #fff;
                        }
                      }
                    }
                }
                .team-score {
                  width: 12%;
                  height: 70px;
                  float: left;
                  text-align: center;
                  // border: 1px solid;
                  .score {
                    height: 33px;
                    line-height: 29px;
                    font-weight: bold;
                  }
                }
                .team-right {
                width: 50%;
                // border: 1px solid;
                float: right;
                .oddBox-area {
                  width: 24vw;
                  display: inline-block;
                  float: left;
                  & .oddBox {
                    position: relative;
                    text-align: center;
                    //display: grid;
                    //background-color:red;
                    width: 100%;
                    // margin-right: 5px;
                    // margin-bottom: 7px;
                    // margin: 5px 2px;
                    height: inherit;
                    line-height: 25px;
                    // padding: 2px;
                    // padding-bottom: 5px;
                    background-color: #FBFBFB;
                    border: solid #dcdfe626 1px;
                    // border-radius: 5px;
                    justify-content: center;
                    font-size: 12px;
                    .unlock {
                      width: 100%;
                      height: 30px;
                      .cap {
                        width: 45%;
                        color: #909399;
                        display: inline-block;
                      }
                      .black {
                        font-size: 14px;
                        width: 40%;
                        display: inline-block;
                        margin: 2px 0px;
                      }
                    }
                    .lock {
                      height: 30px;
                      width: 100%;
                    }
                    & .no-cap {
                      height: 50px;
                      line-height: 50px;
                    }
                    & .black {
                      color: var(--content_odds_text);
                    }
                    & .change {
                      color: #FFF;
                    }
                    & .grey {
                      color: #d67116;
                    }
                    & .active {
                      color: #222;
                    }
                    & .fa-lock {
                      position: absolute;
                      top: 9px;
                      left: 50%;
                      margin-left: -12%;
                      color: #8c8c8c;
                    }
                  }
                  & .active {
                    background-color: #ffcb46;
                    color: #222;
                    // border-color: #502a08;
                  }
                  & .disable {
                    background-color: purple;
                    color: #BBBABA;
                    height: 60px;
                  }
                  & .up {
                    background-color: #44C38A;
                    color: #FFF;
                    border-color: #44C38A;
                    transition: background-color .3s linear;
                  }
                  & .down {
                    background-color: #E54242;
                    color: #FFF;
                    border-color: #E54242;
                    transition: background-color .3s linear;
                  }
                }
              }
                .clear {
                    clear: both;
                }
            }
          }

        }
      }
    }
  }
}
.combo {
  position: fixed;
  right: 0;
  top: 80vh;
  background-color: #000;
  color: #fff;
  padding: 10px 20px 10px 10px;
  border-radius: 10px 0px 0px 10px;
  .tips {
    background-color: red;
    position: fixed;
    right: 5px;
    top: 81vh;
    border-radius: 50px;
    padding: 2px;
  }
}
</style>
