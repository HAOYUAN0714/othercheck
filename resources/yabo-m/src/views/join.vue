<template>
  <div class="dashboard-container">
    <loading v-show="isFetchApi" />
    <div v-if="!(sortedEvent && sortedEvent.length > 0)">
      <div class="dashboard-scoreListArea">
        <div class="scoreList">
          <div class="item">
            <div class="logo" @click="back()">
              <font-awesome-icon class="icon" :icon="['fas','arrow-left']" />
            </div>
            <div class="title">
              <span>{{ ballData?ballData.SportName:'' }}</span>
              <span class="sub-text">{{ $t('S_MATCHES') }}</span>
            </div>
            <button :class="{buttonActive: showWay == 1 }" class="button-left" @click="disply(1)">{{ $t('S_HANDICAP_AND_OVER_UNDER') }}</button>
            <button :class="{buttonActive: showWay == 2 }" class="button-right" @click="disply(2)">1 X 2</button>
          </div>
          <div class="text-title">
            {{ $t('S_SORRY') }}
          </div>
        </div>
      </div>
    </div>
    <div v-if="sortedEvent && sortedEvent.length > 0">

      <div class="dashboard-scoreListArea">
        <div class="scoreList">
          <div class="item">
            <div class="logo" @click="back()">
              <font-awesome-icon class="icon" :icon="['fas','arrow-left']" />
            </div>
            <div class="title">
              <span>{{ ballData.SportName }}</span>
              <span style="color:#fff;">{{ $t('S_MATCHES') }}</span>
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
          <div class="title">
            <el-pagination
              background
              layout="prev, pager, next"
              :total="totalPage"
              :page-size="1"
              :current-page.sync="currentPage"
              @current-change="changePage"
            />
          </div>
          <div v-for="(games,item) in sortedEvent" :key="item" class="text">
            <div class="oddComponent">
              <div v-if="checkFirst(item)" class="text-title" @click="show(games.Competition.CompetitionId)">
                <span>
                  <font-awesome-icon class="icon" :icon="['fas','angle-down']" />
                </span>
                <span>{{ games.Competition.CompetitionName }}</span>
              </div>
              <div v-show="!showcode[games.Competition.CompetitionId] === true && showWay === 1 " class="oddHide">
                <div>
                  <div class="header">
                    <div class="header-left">
                      <!-- <div>{{ setClass(games.RBTime) }}&nbsp;&nbsp;</div> -->
                      <div class="time">{{ formateTime(games.EventDate) }}</div>
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
                        </div>
                        <div class="team">{{ games.AwayTeam }}</div>
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
            </div>
            <div v-show="!showcode[games.Competition.CompetitionId] === true && showWay === 2 " class="oddHide">
              <!-- v-for="(oddsList,gameKey) in gameList" :key="gameKey" -->
              <div>
                <div class="header2">
                  <div class="header2-left">
                    <!-- <div>{{ games.EventDate.substring(5,10) }}&nbsp;&nbsp;</div> -->
                    <div class="time">{{ formateTime(games.EventDate) }}</div>
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
                      <div class="team">{{ games.HomeTeam }}</div>
                      <div class="team">{{ games.AwayTeam }}</div>
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
            <div v-show="!showcode[games.Competition.CompetitionId] === true && showWay === 3 " class="oddHide">
              <div>
                <div class="header">
                  <div class="solo-left">
                    <!-- <div>{{ setClass(games.RBTime) }}&nbsp;&nbsp;</div> -->
                    <div class="time">{{ formateTime(games.EventDate) }}</div>
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
                            v-if="getMarketLineId(games.MarketLines, 4)"
                            :odds="getBetTypeData(games.MarketLines, 4, 8)"
                            :marketline-status="getMarketLineId(games.MarketLines, 4)"
                          />
                        </div>
                        <div @click="clickOdds(4, 9, games, sportId)">
                          <odds
                            v-if="getMarketLineId(games.MarketLines, 4)"
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
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { getToday, getOneFutureDay } from '@/utils/getBallData'
import odds from '@/layout/components/odds/layoutOdds'
import odds1X2 from '@/layout/components/odds/layoutOdds1X2'
import moment from 'moment'
import loading from '@/layout/components/loading'

export default {
  name: 'Dashboard',
  components: {
    odds,
    odds1X2,
    loading
  },
  data() {
    return {
      sportId: 1,
      sportData: {},
      showcode: {},
      showWay: 1,
      timeout: '',
      initTimer: false,
      isFetchApi: false,
      currentPage: 1,
      pagesize: 1,
      totalPage: 0,
      comboNew: 'all'
    }
  },
  computed: {
    ...mapGetters([
      'betSlip'
    ]),
    ballData() {
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
    this.comboNew = this.$route.params.isComboNew
    this.sportId = this.$route.params.sportid
    this.date = this.$route.params.date
    this.getSportData(this.sportId, this.date)

    if (!this.initTimer) {
      this.timeout = setInterval(() => {
        this.initTimer = true
        this.getSportData(this.sportId, this.date)
      }, 30000)
    }
  },
  beforeDestroy() {
    clearInterval(this.timeout)
  },
  methods: {
    ...mapActions({
      setBet: 'ball/actionBet',
      setBetSlip: 'ball/actionSetBetSlip',
      removeBetSlip: 'ball/actionRemoveBetSlip'
    }),
    clickOdds(playId, betId, games, sportId) {
      // console.log(playId, betId, games, sportId)
      this.setBet({
        bet: this.getBetTypeData(games.MarketLines, playId, betId),
        playType: this.getMarketLineId(games.MarketLines, playId),
        games,
        sportId: +(sportId)
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
    getSportData(id, date) {
      const comboParam = (this.comboNew === 'combo')

      if (!this.initTimer) {
        this.isFetchApi = true
      }
      if (date === 'today') {
        // 今日
        getToday(id, this.currentPage, comboParam).then((res) => {
          this.sportData = res
          this.totalPage = res.PageSize
          this.isFetchApi = false
        })
      } else {
        // 明日
        getOneFutureDay(id, date, this.currentPage, comboParam).then((res) => {
          this.sportData = res
          this.totalPage = res.PageSize
          this.isFetchApi = false
        })
      }
      this.sportId = id
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
    setClass(time) {
      if (!time) {
        return ''
      } else {
        if (time.substring(0, 2) === 'Q1') {
          return this.$t('S_FIRST_QUAR')
        }
        if (time.substring(0, 2) === 'Q2') {
          return this.$t('S_SECOND_QUAR')
        }
        if (time.substring(0, 2) === 'Q3') {
          return this.$t('S_THIRD_QUAR')
        }
        if (time.substring(0, 2) === 'Q4') {
          return this.$t('S_FOURTH_QUAR')
        }
      }
    },
    show(id) {
      // console.log('testid', id)
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
      const { sportid } = this.$route.params
      this.$router.push(`/detail/${sportid}/${game.EventId}`)
    },
    back() {
      this.$router.go(-1) // 返回上一层
    },
    formateTime(val) {
      return moment(val).utcOffset(-4).format('MM-DD HH:mm')
    },
    changePage(currentPage) {
      this.currentPage = currentPage
      this.sportData = {}
      this.updateSportData(this.sportId, this.currentPage)
    },
    updateSportData(id, currentPage) {
      const date = this.$route.params.date
      this.isFetchApi = true
      if (date === 'today') {
        getToday(id, currentPage).then((res) => {
          this.sportData = res
          this.totalPage = res.PageSize
          this.isFetchApi = false
        })
      } else {
        // 明日
        getOneFutureDay(id, date, this.currentPage).then((res) => {
          this.sportData = res
          this.totalPage = res.PageSize
          this.isFetchApi = false
        })
      }
    }
  }
}
</script>

<style lang='scss' scoped  src="@/styles/dashboard.scss"></style>
<style lang='scss' scoped  src="@/styles/score.scss"></style>
