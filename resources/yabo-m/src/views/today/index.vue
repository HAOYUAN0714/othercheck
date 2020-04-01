<template>
  <div class="dashboard-container">
    <loading v-show="loadingStatus" />
    <div v-if="!ballData">
      <div class="dashboard-scoreListArea">
        <div class="scoreList">
          <div class="item">
            {{ $t('S_SORRY') }}
          </div>
        </div>
      </div>
    </div>
    <div v-if="ballData">

      <div class="dashboard-scoreListArea">
        <div class="scoreList">
          <div class="item">
            <div class="logo" @click="back()">
              <font-awesome-icon class="icon" :icon="['fas','arrow-left']" />
            </div>
            <div class="title">
              <div>{{ ballData.SportName }}</div>
              <div class="sub-text">{{ sportDetail.period }}</div>
            </div>
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
            <!-- <button :class="{buttonActive: showWay == 1 }" class="button-left" @click="disply(1)">{{ $t('S_HANDICAP_AND_OVER_UNDER') }}</button>
            <button :class="{buttonActive: showWay == 2 }" class="button-right" @click="disply(2)">1 X 2</button> -->
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
          <div v-for="(teams,item) in sortedEvent" :key="item" class="text">
            <div class="oddComponent">
              <div v-if="checkFirst(item)" class="text-title" @click="show(teams.Competition.CompetitionId)">
                <span>
                  <font-awesome-icon
                    class="icon"
                    :icon="[
                      'fas',
                      showcode[teams.EventId] ? 'angle-up' : 'angle-down'
                    ]"
                  />
                </span>
                <span>{{ teams.Competition.CompetitionName }}</span>
              </div>
              <div v-show="!showcode[teams.EventId] === true && showWay === 1 " class="oddHide">
                <div>
                  <div class="header">
                    <div class="header-left">
                      <div class="time">{{ formateTime(teams.EventDate) }}</div>
                    </div>
                    <div class="header-right">
                      <div class="count" @click="moreGame(teams)">
                        <p>{{ teams.TotalMarketLineCount }}></p>
                      </div>
                      <p>{{ $t('S_HANDICAP') }}</p>
                      <p>{{ $t('S_OVER_UNDER') }}</p>
                    </div>
                  </div>
                  <div class="mean">
                    <div class="team">
                      <div class="team-left">

                        <div class="team">{{ teams.AwayTeam }}</div>
                        <div class="team">{{ teams.HomeTeam }}</div>
                      </div>
                      <div class="team-right">
                        <div class="oddBox-area">
                          <div @click="clickOdds(1, 1, teams, sportId)">
                            <odds
                              v-if="getMarketLineId(teams.MarketLines, 1)"
                              :odds="getBetTypeData(teams.MarketLines, 1, 1)"
                              :marketline-status="getMarketLineId(teams.MarketLines, 1)"
                            />
                          </div>
                          <div @click="clickOdds(1, 2, teams, sportId)">
                            <odds
                              v-if="getMarketLineId(teams.MarketLines, 1)"
                              :odds="getBetTypeData(teams.MarketLines, 1, 2)"
                              :marketline-status="getMarketLineId(teams.MarketLines, 1)"
                            />
                          </div>
                        </div>
                        <div class="oddBox-area">
                          <div @click="clickOdds(2, 3, teams, sportId)">
                            <odds
                              v-if="getMarketLineId(teams.MarketLines, 2)"
                              :odds="getBetTypeData(teams.MarketLines, 2, 3)"
                              :marketline-status="getMarketLineId(teams.MarketLines, 2)"
                            />
                          </div>
                          <div @click="clickOdds(2, 4, teams, sportId)">
                            <odds
                              v-if="getMarketLineId(teams.MarketLines, 2)"
                              :odds="getBetTypeData(teams.MarketLines, 2, 4)"
                              :marketline-status="getMarketLineId(teams.MarketLines, 2)"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-show="!showcode[teams.Competition.CompetitionId] === true && showWay === 2 " class="oddHide">
                <!-- v-for="(oddsList,gameKey) in gameList" :key="gameKey" -->
                <div>
                  <div class="header2">
                    <div class="header2-left">
                      <div>{{ teams.EventDate.substring(5,10) }}&nbsp;&nbsp;</div>
                      <div class="time">{{ teams.EventDate.substring(11,15) }}</div>
                    </div>
                    <div class="header2-right">
                      <div class="count" @click="moreGame(teams)">
                        <p>{{ teams.TotalMarketLineCount }}></p>
                      </div>
                    </div>
                  </div>
                  <div class="mean">
                    <!-- 1 X 2  -->
                    <div class="team2">
                      <div class="team2-left">
                        <!-- <div class="pointer">0</div> -->
                        <div class="team">{{ teams.AwayTeam }}</div>
                        <div class="team">{{ teams.HomeTeam }}</div>
                      </div>
                      <div class="team2-right">
                        <div v-if="getBetTypeData(teams.MarketLines, 3, 5).Odds" class="oddBox-area">
                          <!-- (data, betType, selectionId)  -->
                          <!-- 主 -->
                          <div @click="clickOdds(3, 5, teams, sportId)">
                            <odds1X2
                              :odds="getBetTypeData(teams.MarketLines, 3, 5)"
                              :marketline-status="getMarketLineId(teams.MarketLines, 3)"
                            />
                          </div>
                          <!-- 和 -->
                          <div @click="clickOdds(3, 7, teams, sportId)">
                            <odds1X2
                              :odds="getBetTypeData(teams.MarketLines, 3, 7)"
                              :marketline-status="getMarketLineId(teams.MarketLines, 3)"
                            />
                          </div>
                          <!-- 客 -->
                          <div @click="clickOdds(3, 6, teams, sportId)">
                            <odds1X2
                              :odds="getBetTypeData(teams.MarketLines, 3, 6)"
                              :marketline-status="getMarketLineId(teams.MarketLines, 3)"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-show="!showcode[teams.Competition.CompetitionId] === true && showWay === 3 " class="oddHide">
                <div>
                  <div class="header">
                    <div class="solo-left">
                      <div class="time">{{ formateTime(teams.EventDate) }}</div>
                    </div>
                    <div class="solo-right">
                      <div class="count" @click="moreGame(teams)">
                        <p>{{ teams.TotalMarketLineCount }}></p>
                      <!-- <div class="tick" /> -->
                      </div>
                      <p>{{ $t('S_SOLOWIN') }}</p>
                    </div>
                  </div>
                  <div class="mean">
                    <div class="team">
                      <div class="solo-content-left">
                        <div class="team">{{ teams.HomeTeam }}</div>
                        <div class="team">{{ teams.AwayTeam }}</div>
                      </div>
                      <div class="solo-content-right">
                        <div class="oddBox-area">
                          <div @click="clickOdds(4, 8, teams, sportId)">
                            <odds
                              v-if="getMarketLineId(teams.MarketLines, 4)"
                              :odds="getBetTypeData(teams.MarketLines, 4, 8)"
                              :marketline-status="getMarketLineId(teams.MarketLines, 4)"
                            />
                          </div>
                          <div @click="clickOdds(4, 9, teams, sportId)">
                            <odds
                              v-if="getMarketLineId(teams.MarketLines, 4)"
                              :odds="getBetTypeData(teams.MarketLines, 4, 9)"
                              :marketline-status="getMarketLineId(teams.MarketLines, 4)"
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
import { mapGetters, mapActions } from 'vuex'
import { getToday } from '@/utils/getBallData'
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
      sportId: this.$route.params.sportId,
      sportData: {},
      sportDetail: {
        title: '足球',
        period: this.$t('S_TODAY_MATCH')
      },
      showcode: {},
      showWay: 1,
      sportIcon: [
        {
          icon: 'futbol',
          name: '足球',
          nu: '14',
          path: '1'
        },
        {
          icon: 'baseball-ball',
          name: '籃球',
          nu: '6',
          path: '2'
        },
        {
          icon: 'volleyball-ball',
          name: '排球',
          nu: '1',
          path: '3'
        },
        {
          icon: 'gamepad',
          name: '電競',
          nu: '1',
          path: '4'
        }
      ],
      timeout: '',
      initTimer: false,
      loadingStatus: false,
      currentPage: 1,
      pagesize: 1,
      totalPage: 0
    }
  },
  computed: {
    ...mapGetters([
      'betSlip'
    ]),
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
  mounted() {
    this.getSportData(this.sportId)
  },
  beforeDestroy() {
    clearInterval(this.timeout)
  },
  methods: {
    ...mapActions({
      setBet: 'ball/actionBet'
    }),
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
    getSportData(id, currentPage) {
      if (!this.initTimer) {
        this.loadingStatus = true
      }
      getToday(id, currentPage).then((res) => {
        this.sportData = res
        this.totalPage = res.PageSize
        this.loadingStatus = false
        if (!this.initTimer) {
          this.timeout = setInterval(() => {
            this.initTimer = true
            this.getSportData(this.sportId, this.currentPage)
          }, 30000)
        }
      }).catch((res) => {
        this.loadingStatus = false
      })
      this.$forceUpdate()
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
      const { sportId } = this.$route.params
      this.$router.push(`/detail/${sportId}/${game.EventId}`)
    },
    back() {
      this.$router.go(-1) // 返回上一层
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
      return moment(val).utcOffset(-4).format('HH:mm')
    },
    changePage(currentPage) {
      this.currentPage = currentPage
      this.sportData = {}
      this.updateSportData(this.sportId, this.currentPage)
    },
    updateSportData(id, currentPage) {
      this.loadingStatus = true
      getToday(id, currentPage).then((res) => {
        this.sportData = res
        this.totalPage = res.PageSize
        this.loadingStatus = false
      })
    }
  }
}
</script>

<style lang='scss' scoped  src="@/styles/dashboard.scss"></style>
<style lang='scss' scoped  src="@/styles/score.scss"></style>
