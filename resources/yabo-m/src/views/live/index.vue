<template>
  <div class="dashboard-container">
    <loading v-show="loadingStatus && !initTimer" />
    <div class="dashboard-choose">
      <div v-for="(item,index) in sportLiveList" :key="`dashboard-choose-item-${index}`" class="dashboard-choose-item">
        <div :class="{'active' : sportId == item.orderNumber }" @click="getSportData(item.orderNumber)">
          <div class="dashboard-choose-item-icon">
            <div
              class="icon"
              :class="[
                getIcon(index),
                {
                  active: $route.params.sportId === index
                }
              ]"
            />
          </div>
          <div class="dashboard-choose-item-text">{{ item.sportName }}</div>
          <div class="dashboard-choose-item-text">({{ item.liveCount }})</div>
        </div>
      </div>
    </div>
    <div v-if="sortedEvent && sortedEvent.length === 0 && initTimer">
      <div class="dashboard-scoreListArea">
        <div class="scoreList">
          <div class="sorryArea">
            {{ $t('S_SORRY') }}
          </div>
        </div>
      </div>
    </div>
    <div v-if="sortedEvent && sortedEvent.length > 0">
      <div class="dashboard-scoreListArea">
        <div class="scoreList">
          <div class="item" style="position: relative;">
            <div class="logo" @click="back()">
              <font-awesome-icon class="icon" :icon="['fas','arrow-left']" />
            </div>
            <div class="title">
              <div>{{ ballData.SportName }}</div>
              <div>{{ sportDetail.period }}</div>
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
          <div v-for="(games,item) in sortedEvent" :key="item" class="text">
            <div class="oddComponent">
              <div v-if="checkFirst(item)" class="text-title" @click="show(games.Competition.CompetitionId)">
                <span>
                  <font-awesome-icon
                    class="icon"
                    :icon="[
                      'fas',
                      showcode[games.Competition.CompetitionId] ? 'angle-up' : 'angle-down'
                    ]"
                  />
                </span>
                <span>{{ games.Competition.CompetitionName }}</span>
              </div>
              <div v-show="!showcode[games.Competition.CompetitionId] === true && showWay === 1 " class="oddHide">
                <div>
                  <div class="header">
                    <div class="header-left">
                      <div>
                        <real-time :time="games.RBTime" :sport-id="+(sportId)" :time-status="games.RBTimeStatus" />
                        &nbsp;&nbsp;
                        <template v-if="(games.HomeScore && games.AwayScore) || (games.HomeScore === 0 || games.AwayScore === 0)">
                          {{ games.HomeScore }} - {{ games.AwayScore }}
                        </template>
                      </div>
                    </div>
                    <div class="header-right">
                      <div class="count" @click="moreGame(games)">
                        <p>{{ games.TotalMarketLineCount }}></p>
                        <!-- <div class="tick" /> -->
                      </div>
                      <p>{{ $t('S_HANDICAP') }}</p>
                      <p>{{ $t('S_OVER_UNDER') }}</p>
                    </div>
                  </div>
                  <div class="mean">
                    <div class="team">
                      <div class="team-left">
                        <div class="team">
                          <span>{{ games.HomeTeam }}</span>
                          <span v-show="games.GroundTypeId == 0" class="spaceLeft">({{ $t('S_NEUTRAL_GROUND') }})</span>
                          <div v-show="games.HomeRedCard != 0 && games.HomeRedCard != null" class="spaceLeft"><span class="redCard">{{ games.HomeRedCard }}</span></div>
                        </div>
                        <div class="team">
                          <span>{{ games.AwayTeam }}</span>
                          <div v-show="games.AwayRedCard != 0 && games.AwayRedCard != null" class="spaceLeft"><span class="redCard">{{ games.AwayRedCard }}</span></div>
                        </div>
                      </div>
                      <div class="team-right">
                        <div class="oddBox-area">
                          <div @click="clickOdds(1, 1, games, sportId)">
                            <odds
                              v-if="getMarketLineId(games.MarketLines, 1)"
                              :odds="getBetTypeData(games.MarketLines, 1, 1)"
                              :marketline-status="getMarketLineId(games.MarketLines, 1)"
                            />
                          </div>

                          <div @click="clickOdds(1, 2, games, sportId)">
                            <odds
                              v-if="getMarketLineId(games.MarketLines, 1)"
                              :odds="getBetTypeData(games.MarketLines, 1, 2)"
                              :marketline-status="getMarketLineId(games.MarketLines, 1)"
                            />
                          </div>
                        </div>
                        <div class="oddBox-area">
                          <div @click="clickOdds(2, 3, games, sportId)">
                            <odds
                              v-if="getMarketLineId(games.MarketLines, 2)"
                              :odds="getBetTypeData(games.MarketLines, 2, 3)"
                              :marketline-status="getMarketLineId(games.MarketLines, 2)"
                            />
                          </div>
                          <div @click="clickOdds(2, 4, games, sportId)">
                            <odds
                              v-if="getMarketLineId(games.MarketLines, 2)"
                              :odds="getBetTypeData(games.MarketLines, 2, 4)"
                              :marketline-status="getMarketLineId(games.MarketLines, 2)"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-show="!showcode[games.Competition.CompetitionId] === true && showWay === 2 " class="oddHide">
                <!-- v-for="(oddsList,gameKey) in gameList" :key="gameKey" -->
                <div>
                  <div class="header2">
                    <div class="header2-left">
                      <div>
                        <real-time :time="games.RBTime" :sport-id="+(sportId)" :time-status="games.RBTimeStatus" />
                        &nbsp;&nbsp;
                        <template v-if="(games.HomeScore && games.AwayScore) || (games.HomeScore === 0 || games.AwayScore === 0)">
                          {{ games.HomeScore }} - {{ games.AwayScore }}
                        </template>
                      </div>
                    </div>
                    <div class="header2-right">
                      <div class="count" @click="moreGame(games)">
                        <p>{{ games.TotalMarketLineCount }}></p>
                      </div>
                    </div>
                  </div>
                  <div class="mean">
                    <!-- 1 X 2  -->
                    <div class="team2">
                      <div class="team2-left">
                        <!-- <div class="pointer">0</div> -->
                        <div class="team">{{ games.AwayTeam }}</div>
                        <div class="team">{{ games.HomeTeam }}</div>
                      </div>
                      <div class="team2-right">
                        <div v-if="getMarketLineId(games.MarketLines, 3)" class="oddBox-area">
                          <!-- (data, betType, selectionId)  -->
                          <!-- 主 -->
                          <div @click="clickOdds(3, 5, games, sportId)">
                            <odds1X2
                              :odds="getBetTypeData(games.MarketLines, 3, 5)"
                              :marketline-status="getMarketLineId(games.MarketLines, 3)"
                            />
                          </div>
                          <!-- 和 -->
                          <div @click="clickOdds(3, 7, games, sportId)">
                            <odds1X2
                              :odds="getBetTypeData(games.MarketLines, 3, 7)"
                              :marketline-status="getMarketLineId(games.MarketLines, 3)"
                            />
                          </div>
                          <!-- 客 -->
                          <div @click="clickOdds(3, 6, games, sportId)">
                            <odds1X2
                              :odds="getBetTypeData(games.MarketLines, 3, 6)"
                              :marketline-status="getMarketLineId(games.MarketLines, 3)"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-show="!showcode[games.EventId] === true && showWay === 3 " class="oddHide">
                <div>
                  <div class="header">
                    <div class="solo-left">
                      <div>
                        <real-time :time="games.RBTime" :sport-id="+(sportId)" :time-status="games.RBTimeStatus" />
                        &nbsp;&nbsp;
                        <template v-if="(games.HomeScore && games.AwayScore) || (games.HomeScore === 0 || games.AwayScore === 0)">
                          {{ games.HomeScore }} - {{ games.AwayScore }}
                        </template>
                      </div>
                    </div>
                    <div class="solo-right">
                      <div class="count" @click="moreGame(games)">
                        <p>{{ games.TotalMarketLineCount }}></p>
                      <!-- <div class="tick" /> -->
                      </div>
                      <p>{{ $t('S_SOLOWIN') }}</p>
                    </div>
                  </div>
                  <div class="mean">
                    <div class="team">
                      <div class="solo-content-left">
                        <div class="team">{{ games.HomeTeam }}</div>
                        <div class="team">{{ games.AwayTeam }}</div>
                      </div>
                      <div class="solo-content-right">
                        <div class="oddBox-area">
                          <div @click="clickOdds(4, 8, games, sportId)">
                            <odds
                              v-if="getMarketLineId(games.MarketLines, 4) && getBetTypeData(games.MarketLines, 4, 8)"
                              :odds="getBetTypeData(games.MarketLines, 4, 8)"
                              :marketline-status="getMarketLineId(games.MarketLines, 4)"
                            />
                          </div>
                          <div @click="clickOdds(4, 9, games, sportId)">
                            <odds
                              v-if="getMarketLineId(games.MarketLines, 4) && getBetTypeData(games.MarketLines, 4, 9)"
                              :odds="getBetTypeData(games.MarketLines, 4, 9)"
                              :marketline-status="getMarketLineId(games.MarketLines, 4)"
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
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getLiveBasicData, getSportKind } from '@/utils/getBallData'
import { mapActions, mapGetters } from 'vuex'
import odds from '@/layout/components/odds/layoutOdds'
import odds1X2 from '@/layout/components/odds/layoutOdds1X2'
import loading from '@/layout/components/loading'
import icon from '@/utils/sportIconList'
import realTime from '@/layout/components/realTimeClock'

export default {
  name: 'Dashboard',
  components: {
    odds,
    odds1X2,
    loading,
    realTime
  },
  data() {
    return {
      sportId: '',
      sportData: {},
      API: {},
      sportDetail: {
        title: '足球',
        period: this.$t('S_RUN_BALL')
      },
      showcode: {},
      showWay: 1,
      sportLiveList: {},
      icon,
      timeout: '',
      initTimer: false,
      loadingStatus: false
    }
  },
  computed: {
    ...mapGetters([
      'getBetSlip'
    ]),
    ballData() {
      // console.log(this.sportData[0])
      if (!this.sportData.Sports) {
        return []
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
  mounted() {
    getSportKind(false, true).then((res) => {
      this.sportLiveList = res
    })
    this.getRunBallData(this.$route.params.sportId)

    this.getSportData(this.$route.params.sportId)
    this.sportId = this.$route.params.sportId
  },
  beforeDestroy() {
    clearInterval(this.timeout)
  },
  methods: {
    ...mapActions({
      setBet: 'ball/actionBet'
    }),
    getRunBallData(sportId) {
      this.loadingStatus = true
      getLiveBasicData(sportId).then((res) => {
        this.sportData = res
        this.loadingStatus = false
        if (!this.initTimer) {
          this.timeout = setInterval(() => {
            this.getRunBallData(this.$route.params.sportId)
          }, 5000)
          this.initTimer = true
        }
      }).catch((res) => {
        this.loadingStatus = false
      })
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
      this.setBet({
        bet: this.getBetTypeData(games.MarketLines, playId, betId),
        playType: this.getMarketLineId(games.MarketLines, playId),
        games,
        sportId: +(sportId)
      })
    },
    getIcon(sportId) {
      if (this.icon.includes(sportId)) {
        return `icon-${sportId}`
      } else {
        return 'icon-1'
      }
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
    getSportData(id) {
      if (+(id) === +(this.$route.params.sportId)) {
        return
      }
      this.sportId = id
      this.$router.push(`/live/${id}`)
    },
    getBetTypeData(data, betType, selectionId) {
      const work = data.filter(
        val => val.BetTypeId === betType && val.MarketLineLevel === 1
      )[0]
      // console.log(work)
      if (work) {
        return work.WagerSelections.filter(val => val.SelectionId === selectionId)[0]
      } else {
        return {}
      }
    },
    getMarketLineId(data, betType) {
      return data.filter(val => val.BetTypeId === betType && val.MarketLineLevel === 1)[0]
    },
    setClass(val) {
      if (!val) {
        return
      }

      return val.replace('Q1', this.$t('S_FIRST_QUAR'))
        .replace('Q2', this.$t('S_SECOND_QUAR'))
        .replace('Q3', this.$t('S_THIRD_QUAR'))
        .replace('Q4', this.$t('S_FOURTH_QUAR'))
        .replace('1H', this.$t('S_FIRST_HALF'))
        .replace('2H', this.$t('S_SECOND_HALF'))
        .replace('!LIVE', this.$t('S_LIVE'))
        .replace('HT', this.$t('S_HT'))
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
      const { sportId } = this.$route.params
      this.$router.push(`/detail/${sportId}/${game.EventId}`)
    },
    back() {
      this.$router.go(-1) // 返回上一层
    }
  }
}
</script>

<style lang='scss' scoped  src="@/styles/dashboard.scss"></style>
<style lang='scss' scoped  src="@/styles/score.scss"></style>
