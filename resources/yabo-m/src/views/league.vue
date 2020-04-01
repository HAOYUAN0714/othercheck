<template>
  <div class="dashboard-container">
    <loading v-show="loadingStatus && !initTimer" />
    <div class="dashboard-scoreListArea">
      <div class="scoreList">
        <div class="item">
          <div class="logo" @click="back()">
            <font-awesome-icon class="icon" :icon="['fas','arrow-left']" />
          </div>
          <div class="title">
            <div>{{ getSportName(sportId) }}</div>
            <div class="sub-text">{{ $t('S_MATCHES') }}</div>
            <div class="bottom-choose">
              <div v-if="showMode(sportId) === 1" class="choose-item ">
                <button :class="{buttonActive: showWay == 1 }" class="button-left" @click="disply(1)">{{ $t('S_HANDICAP_AND_OVER_UNDER') }}</button>
                <button :class="{buttonActive: showWay == 2 }" class="button-right" @click="disply(2)">1 X 2</button>
              </div>
              <div v-else class="choose-item ">
                <button :class="{buttonActive: showWay == 1 }" class="button-left" @click="disply(1)">{{ $t('S_HANDICAP_AND_OVER_UNDER') }}</button>
                <button :class="{buttonActive: showWay == 3 }" class="button-right" @click="disply(3)">{{ $t('S_SOLOWIN') }}</button>
              </div>
              <!-- <div v-if="showMode(sportId) === 0" class="choose-item ">
                <button :class="{buttonActive: showMode(sportId) === 0 }" class="button-left">{{ $t('S_HANDICAP_AND_OVER_UNDER') }}</button>
              </div> -->
            </div>
          </div>
        </div>
        <div class="text">
          <div v-if="leagueData && Object.keys(leagueData).length > 0">
            <div v-for="(league,leagueKey) in leagueData" :key="leagueKey" class="oddComponent">
              <div class="text-title" @click="show(leagueKey)">
                <span>
                  <font-awesome-icon
                    class="icon"
                    :icon="[
                      'fas',
                      showcode[leagueKey] ? 'angle-up' : 'angle-down'
                    ]"
                  />
                </span>
                <span>{{ league.CompetitionName }}</span>
              </div>
              <div v-show="!showcode[leagueKey] === true && showWay === 1" class="oddHide">
                <div v-for="(gameList,gameListIndex) in final[league.CompetitionId]" :key="gameListIndex">
                  <div class="header">
                    <div class="header-left">
                      <div class="time">{{ formateTime(gameList.EventDate) }}</div>
                    </div>
                    <div class="header-right">
                      <div class="count" @click="moreGame(gameList)">
                        <p>{{ gameList.TotalMarketLineCount }}></p>
                      </div>
                      <p>{{ $t('S_HANDICAP') }}</p>
                      <p>{{ $t('S_OVER_UNDER') }}</p>
                    </div>
                  </div>
                  <div class="mean">
                    <div class="team">
                      <div class="team-left">
                        <div class="team">
                          <span>{{ gameList.HomeTeam }}</span>
                          <span v-show="gameList.GroundTypeId == 0" class="spaceLeft">({{ $t('S_NEUTRAL_GROUND') }})</span>
                        </div>
                        <div class="team">{{ gameList.AwayTeam }}</div>
                      </div>
                      <div class="team-right">
                        <div class="oddBox-area">
                          <div @click="clickOdds(1, 1, gameList, sportId)">
                            <odds
                              v-if="getMarketLineId(gameList.MarketLines, 1)"
                              :odds="getBetTypeData(gameList.MarketLines, 1, 1)"
                              :marketline-status="getMarketLineId(gameList.MarketLines, 1)"
                            />
                          </div>
                          <div @click="clickOdds(1, 2, gameList, sportId)">
                            <odds
                              v-if="getMarketLineId(gameList.MarketLines, 1)"
                              :odds="getBetTypeData(gameList.MarketLines, 1, 2)"
                              :marketline-status="getMarketLineId(gameList.MarketLines, 1)"
                            />
                          </div>
                        </div>
                        <div class="oddBox-area">
                          <div @click="clickOdds(2, 3, gameList, sportId)">
                            <odds
                              v-if="getMarketLineId(gameList.MarketLines, 2)"
                              :odds="getBetTypeData(gameList.MarketLines, 2, 3)"
                              :marketline-status="getMarketLineId(gameList.MarketLines, 2)"
                            />
                          </div>
                          <div @click="clickOdds(2, 4, gameList, sportId)">
                            <odds
                              v-if="getMarketLineId(gameList.MarketLines, 2)"
                              :odds="getBetTypeData(gameList.MarketLines, 2, 4)"
                              :marketline-status="getMarketLineId(gameList.MarketLines, 2)"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-show="!showcode[leagueKey] === true && showWay === 3" class="oddHide">
                <div v-for="(gameList,gameListIndex) in final[league.CompetitionId]" :key="gameListIndex">
                  <div class="header">
                    <div class="solo-left">
                      <div class="time">{{ formateTime(gameList.EventDate) }}</div>
                    </div>
                    <div class="solo-right">
                      <div class="count" @click="moreGame(gameList)">
                        <p>{{ gameList.TotalMarketLineCount }}></p>
                      </div>
                      <p>{{ $t('S_SOLOWIN') }}</p>
                    </div>
                  </div>
                  <div class="mean">
                    <div class="team">
                      <div class="solo-content-left">
                        <div class="team">{{ gameList.HomeTeam }}</div>
                        <div class="team">{{ gameList.AwayTeam }}</div>
                      </div>
                      <div class="solo-content-right">
                        <div class="oddBox-area">
                          <div @click="clickOdds(4, 8, gameList, sportId)">
                            <odds
                              v-if="getMarketLineId(gameList.MarketLines, 4) && getBetTypeData(gameList.MarketLines, 4, 8)"
                              :odds="getBetTypeData(gameList.MarketLines, 4, 8)"
                              :marketline-status="getMarketLineId(gameList.MarketLines, 4)"
                            />
                          </div>
                          <div @click="clickOdds(4, 9, gameList, sportId)">
                            <odds
                              v-if="getMarketLineId(gameList.MarketLines, 4) && getBetTypeData(gameList.MarketLines, 4, 9)"
                              :odds="getBetTypeData(gameList.MarketLines, 4, 9)"
                              :marketline-status="getMarketLineId(gameList.MarketLines, 4)"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-show="!showcode[leagueKey] === true && showWay === 2 " class="oddHide">
                <div v-for="(gameList,gameListIndex) in final[league.CompetitionId]" :key="gameListIndex">
                  <div class="header2">
                    <div class="header2-left">
                      <div class="time">{{ formateTime(gameList.EventDate) }}</div>
                    </div>
                    <div class="header2-right">
                      <div class="count" @click="moreGame(gameList)">
                        <p>{{ gameList.TotalMarketLineCount }}></p>
                      </div>
                    </div>
                  </div>
                  <div class="mean">
                    <div class="team2">
                      <div class="team2-left">
                        <div class="team">{{ gameList.HomeTeam }}</div>
                        <div class="team">{{ gameList.AwayTeam }}</div>
                      </div>
                      <div class="team2-right">
                        <div v-if="getMarketLineId(gameList.MarketLines, 3)" class="oddBox-area">
                          <!-- (data, betType, selectionId)  -->
                          <!-- 主 -->
                          <div @click="clickOdds(3, 5, gameList, sportId)">
                            <odds1X2
                              :odds="getBetTypeData(gameList.MarketLines, 3, 5)"
                              :marketline-status="getMarketLineId(gameList.MarketLines, 3)"
                            />
                          </div>
                          <!-- 和 -->
                          <div @click="clickOdds(3, 7, gameList, sportId)">
                            <odds1X2
                              :odds="getBetTypeData(gameList.MarketLines, 3, 7)"
                              :marketline-status="getMarketLineId(gameList.MarketLines, 3)"
                            />
                          </div>
                          <!-- 客 -->
                          <div @click="clickOdds(3, 6, gameList, sportId)">
                            <odds1X2
                              :odds="getBetTypeData(gameList.MarketLines, 3, 6)"
                              :marketline-status="getMarketLineId(gameList.MarketLines, 3)"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div v-else>
            <div class="score-item">
              <div class="score-title">{{ $t('S_SORRY') }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getLeagueBasicData } from '@/utils/getBallData'
import { mapGetters, mapActions } from 'vuex'
import odds from '@/layout/components/odds/layoutOdds'
import odds1X2 from '@/layout/components/odds/layoutOdds1X2'
import loading from '@/layout/components/loading'
import moment from 'moment'

export default {
  name: 'Dashboard',
  components: {
    odds,
    odds1X2,
    loading
  },
  data() {
    return {
      SportName: {},
      sportId: 1,
      leagueData: {},
      final: {},
      sportData: {},
      showcode: {},
      showWay: 1,
      timeout: '',
      initTimer: false,
      loadingStatus: false
    }
  },
  computed: {
    ...mapGetters([
      'curGameCount'
    ])
  },
  mounted() {
    this.CompetitionId = this.$route.params.leagueid
    this.sportId = this.$route.params.sportid
    this.market = this.$route.params.market
    const date = this.$route.params.date
    this.combo = this.$route.params.isCombo
    this.getSportData(this.CompetitionId, this.sportId, this.market, date, this.combo)
  },
  beforeDestroy() {
    clearInterval(this.timeout)
  },
  methods: {
    ...mapActions({
      setBet: 'ball/actionBet'
    }),
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
    getSportName(id) {
      if (this.curGameCount && this.curGameCount.sportMapping && this.curGameCount.sportMapping[id]) {
        return this.curGameCount.sportMapping[id].sportName
      } else {
        return ''
      }
    },
    // market: 1=早盤 ２=今日
    getSportData(id, sportId, market, date, IsCombo) {
      // console.log('IsCombo:', IsCombo)
      if (IsCombo === 'isCombo') {
        this.loadingStatus = true
        getLeagueBasicData(+(id), sportId, market, date, 1, true).then((res) => {
          this.sportData = res
          this.loadingStatus = false
          this.ballData()
          if (!this.initTimer) {
            this.timeout = setInterval(() => {
              this.initTimer = true
              this.getSportData(this.CompetitionId, this.sportId, this.market, date, IsCombo)
            }, 30000)
          }
        }).catch((res) => {
          this.loadingStatus = false
        })
      } else {
        this.loadingStatus = true
        getLeagueBasicData(+(id), sportId, market, date).then((res) => {
          this.sportData = res
          this.loadingStatus = false
          this.ballData()
          if (!this.initTimer) {
            this.timeout = setInterval(() => {
              this.initTimer = true
              this.getSportData(this.CompetitionId, this.sportId, this.market, date)
            }, 30000)
          }
        }).catch((res) => {
          this.loadingStatus = false
        })
      }
    },
    ballData() {
      if (!this.sportData.Sports || this.sportData.Sports.length === 0) {
        return []
      }

      const leagueData = {}
      const final = {}
      const sortFinal = {}
      const temp = this.sportData.Sports[0].Events ? this.sportData.Sports[0].Events : {}
      temp.forEach((val) => {
        const id = val.Competition.CompetitionId
        if (!final[id]) {
          leagueData[id] = val.Competition
          final[id] = []
        }
        final[id].push(val)
      })
      Object.values(final).forEach((item) => {
        Object.values(item).sort((a, b) => +(moment(a.EventDate).utcOffset(-4).format('MMDDHHmm')) - +(moment(b.EventDate).utcOffset(-4).format('MMDDHHmm')))
          .forEach((sortItem) => {
            const id = Object.keys(final)
            if (!sortFinal[id]) {
              sortFinal[id] = []
            }
            sortFinal[id].push(sortItem)
          })
      })
      this.leagueData = leagueData
      this.final = sortFinal
      return this.sportData.Sports[0]
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
    back() {
      this.$router.go(-1) // 返回上一层
    },
    moreGame(game) {
      if (+(game.TotalMarketLineCount) === 0) {
        return
      }
      const comboNew = (this.combo === 'isCombo') ? 'combo' : 'all'
      const sportId = this.$route.params.sportid
      this.$router.push(`/detail/${comboNew}/${sportId}/${game.EventId}`)
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
    formateTime(val) {
      return moment(val).utcOffset(-4).format('MM-DD HH:mm')
    }
  }
}
</script>

<style lang='scss' scoped  src="@/styles/dashboard.scss"></style>
<style lang='scss' scoped  src="@/styles/score.scss"></style>
