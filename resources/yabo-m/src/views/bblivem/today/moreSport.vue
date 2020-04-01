<template>
  <div class="dashboard-container">
    <loading v-show="loadingStatus" />
    <navbar />
    <!-- loadingStatus:: {{ loadingStatus }} -->
    <div class="tvBoard">
      <div v-if="vedioUrl==='' && isClick === true && status === false" class="tvTips">{{ `${home} vs ${away}` }}<br> 赛事无直播</div>
      <div v-if="vedioUrl==='' && isClick === true && status === true" class="tvTips">{{ `${home} vs ${away}` }}<br> 赛事尚未开始</div>
      <div v-if="isClick === false && !loadingStatus && vedioUrl===''" class="tvTips">目前皆无直播赛事</div>
      <div v-show="vedioUrl!==''" id="dplayer" />
      <div v-show="vedioUrl===''" class="noVideo" />
    </div>
    <div class="dashboard-choose">
      <div v-for="(item,iconIndex) in (allEventCount.Today && allEventCount.Today.Sport)" :key="iconIndex" class="dashboard-choose-item" :class="{'active' : sportId == item.SportId }">
        <div :class="{'active' : sportId == item.SportId }" @click="getSportData(item.SportId, 1, true)">
          <div class="dashboard-choose-item-text" :class="{'active' : sportId == item.SportId }">{{ item.TodayCount }} {{ item.SportName }}</div>
        </div>
      </div>
    </div>
    <div class="dashboard-feature">
      <ul class="feature">
        <li class="switcher">
          <!-- <span
            :class="[collapseAllMatch ? 'show-all-match' : 'hide-all-match']"
            @click="collapseAllLeague"
          /> -->
          <span class="button" @click="collapseAllLeague">{{ collapseAllMatch ? '展開': '收合' }}</span>
        </li>
        <li class="switcher" @click="changeFilterMatchType">
          <span :class="[filterMatchStatus === 1 ? 'active' : 'default']">
            時間
          </span>/
          <span :class="[filterMatchStatus === 1 ? 'default' : 'active']">
            聯賽
          </span>

        </li>
        <el-pagination
          v-if="sortedEvent && sortedEvent.length > 0"
          background
          layout="pager"
          :total="total"
          :page-size="1"
          :current-page.sync="currentPage"
          @current-change="changedPage"
        />
      </ul>
    </div>
    <div v-if="sortedEvent && sortedEvent.length === 0 && init && !loadingStatus">
      <div class="dashboard-scoreListArea">
        <div class="scoreList">
          <div class="item sorryArea">
            抱歉暂无赛事
          </div>
        </div>
      </div>
    </div>
    <div v-if="sortedEvent && sortedEvent.length > 0">
      <div class="dashboard-scoreListArea">
        <div class="scoreList">
          <div v-for="(games,item) in sortedEvent" :key="item" class="text">
            <div class="oddComponent">
              <div v-if="checkFirst(item)" class="text-title" @click="show(games.Competition.CompetitionId)">
                <div class="camp">
                  <div class="league-name">
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
                <div class="inner" :class="{'active': isClick === true && games.EventId === eventclick }">
                  <div class="header">
                    <div v-if="games.Market === 3" class="live" :class="{'active': isClick === true && games.EventId === eventclick }">
                      <realTime :sport-id="sportId" :time="games.RBTime" :time-status="games.RBTimeStatus" />
                    </div>
                    <div v-else class="time">{{ formateTime(games.EventDate) }}</div>
                    <div class="count" @click="moreGame(games.LiveStreamingMobileUrl,games)">
                      {{ games.TotalMarketLineCount }}>
                    </div>
                    <div>让球</div>
                    <div>大小</div>
                  </div>
                  <div class="mean" @click="paly(games.LiveStreamingMobileUrl, games)">
                    <div class="team">
                      <div class="team-left">
                        <div class="team">
                          <div class="teamName">{{ games.HomeTeam }}</div>
                        </div>
                        <div class="team">
                          <div class="teamName">{{ games.AwayTeam }}</div>
                        </div>
                      </div>
                      <div class="team-score">
                        <div class="score">{{ games.HomeScore }}</div>
                        <div class="score">{{ games.AwayScore }}</div>
                      </div>
                      <div class="team-right">
                        <div class="oddBox-area">
                          <div>
                            <div
                              v-if="getMarketLineId(games.MarketLines, 1)"
                              :class="{
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
                                  @click="clickOdds"
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
                          </div>
                          <div>
                            <div
                              v-if="getMarketLineId(games.MarketLines, 1)"
                              :class="{
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
                                  @click="clickOdds"
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
                          </div>
                        </div>
                        <div class="oddBox-area">
                          <div>
                            <div
                              v-if="getMarketLineId(games.MarketLines, 2)"
                              :class="{
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
                                  @click="clickOdds"
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
                          </div>
                          <div>
                            <div
                              v-if="getMarketLineId(games.MarketLines, 2)"
                              :class="{
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
                                  @click="clickOdds"
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
                          </div>
                        </div>
                      </div>
                      <div class="clear" />
                      <!-- {{ games.LiveStreamingMobile }} -->
                      <div v-if="games.LiveStreamingMobile" class="video">
                        <div
                          :class="{'active': games.LiveStreamingMobile && games.Market=== 3 }"
                        ><i class="fas fa-tv" />
                        </div>
                      </div>
                      <div v-if="games.LiveStreamingMobileUrl[0]">
                        <div :class="{'activetest': games.LiveStreamingMobileUrl[0].Url=== vedioUrl}" />
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
import { mapActions, mapGetters } from 'vuex'
import { getTodayGame, getLiveBasicData } from '@/api/yabolive'
import loading from '@/layout/components/liveloading'
import moment from 'moment-timezone'
import icon from '@/utils/sportIconList'
import yaboliveMixin from '@/utils/yaboliveMixin'
import navbar from '@/layout/bblivem/components/navbar/moreGameNavbar'
import realTime from '@/layout/components/realTimeClock'
import 'dplayer/dist/DPlayer.min.css'
import DPlayer from 'dplayer'

export default {
  name: 'Dashboard',
  components: {
    loading,
    navbar,
    realTime
  },
  mixins: [yaboliveMixin],
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
      nav: 'today',
      curData: [],
      oddsStatus: '',
      liveData: {},
      collapseAllMatch: false,
      filterMatchStatus: +(this.$cookie.get('filterMatch')),
      finalInfo: [],
      liveLen: 0,
      vedioUrl: '',
      status: false,
      isClick: false,
      eventclick: 0,
      home: '',
      away: '',
      isMobile: '',
      dp: '',
      inittest: false
    }
  },
  computed: {
    ...mapGetters({
      allEventCount: 'getAllEventCount',
      getCurSportId: 'm6app/getCurSportId'
    }),
    ballData() {
      if (!this.sportData.Sports) {
        // console.log('trrt', this.sportData)
        return this.sportData
      }
      // console.log('aaa', this.sportData)
      return this.sportData.Sports[0]
    },
    sortedEvent() {
      var newData = []
      var sortData = []
      var liveData2 = this.liveData.Sports || []
      var sportData2 = this.sportData.Sports || []

      if (this.currentPage > 1) {
        liveData2 = []
      }
      if (liveData2.length === 0 && sportData2.length === 0) {
        sortData = []
      } else if (liveData2.length > 0 && sportData2.length > 0) {
        newData.push(this.liveData.Sports[0].Events)
        newData.push(this.sportData.Sports[0].Events)
        sortData = [...newData[0], ...newData[1]]
      } else if (liveData2.length > 0) {
        sortData = [...this.liveData.Sports[0].Events]
      } else if (sportData2.length > 0) {
        sortData = [...this.sportData.Sports[0].Events]
      }

      return sortData
        .sort((a, b) => +(b.Market) - +(a.Market))
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
    }
  },
  created() {
    this.isMobile = this.$isMobile() // 分辨 pc / mb
  },
  mounted() {
    this.firstUpdate()
    // var md = new MobileDetect(window.navigator.userAgent) 分辨 ios / android
  },
  beforeDestroy() {
    clearInterval(this.timeout)
  },
  methods: {
    ...mapActions({
      actionSetLiveUrl: 'm6app/actionSetLiveUrl'
    }),
    firstUpdate() {
      if (!this.allEventCount || !this.allEventCount.Today) {
        return {}
      } else {
        if (this.init === false) {
          // this.sportId = this.allEventCount.Today.Sport[0].SportId // 在這邊取到最早的sportId
          this.sportId = Number(this.getCurSportId) || this.allEventCount.Today.Sport[0].SportId
          this.getSportData(this.sportId, 1).then(() => {
            this.findFirstEventWithStream()
          })
          // 先預設60s call一次api
          this.timeout = setInterval(() => {
            this.updateSportData(this.sportId, this.currentPage)
          }, 30000)
          this.init = true
        }
      }
    },
    findFirstEventWithStream() {
      const { sortedEvent } = this
      // if (this.isMobile) {
      if (this.inittest) {
        return
      }
      this.inittest = true
      // console.log(sortData, 'sortData test mb')
      this.vedioUrl = ''
      const item = sortedEvent.find((x) => { return x.LiveStreamingMobile === true })
      // console.log(sortData)
      if (item) {
        this.vedioUrl = item.LiveStreamingMobileUrl[0].Url
      } else {
        this.vedioUrl = ''
      }
      // this.dp.destroy()
      this.dp = new DPlayer({
        container: document.getElementById('dplayer'),
        autoplay: true,
        video: {
          url: this.vedioUrl
        }
      })
    },
    paly(val, games) {
      this.vedioUrl = ''
      // console.log(val, 'val', games, 'games')
      // eventclick
      this.home = games.HomeTeam
      this.away = games.AwayTeam
      this.eventclick = games.EventId
      // this.status = false
      if (val.length !== 0 && games.Market === 3) {
        // console.log('yess')
        this.vedioUrl = val[0].Url
        // console.log(this.vedioUrl, 'this.vedioUrl')
        this.actionSetLiveUrl(this.vedioUrl)
        this.status = false
        // this.status = true
      } else {
        if (games.LiveStreamingMobile === true) {
          this.isClick === true
          if (games.Market === 3) {
            this.status = false
          } else {
            this.status = true
          }
        } else {
          this.vedioUrl = ''
          this.actionSetLiveUrl(this.vedioUrl)
          this.isClick = true
          if (games.Market === 3) {
            this.status = false
          } else {
            this.status = true
          }
          // this.status = true
        }
      }
      // this.dp.destroy()
      this.dp = new DPlayer({
        container: document.getElementById('dplayer'),
        autoplay: true,
        video: {
          // url: 'https://d2zihajmogu5jn.cloudfront.net/bipbop-advanced/bipbop_16x9_variant.m3u8'
          url: this.vedioUrl

        }
      })
      // console.log(this.dp, 'dp')
      // this.isClick = false
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
    NavRouter(page) {
      this.$router.push(page)
      this.ItemClass = page
    },
    checkFirst(index) {
      if (index - 1 < 0) {
        return true
      }
      const current = this.sortedEvent[index].Competition.CompetitionId
      const before = this.sortedEvent[index - 1].Competition.CompetitionId
      return current !== before
    },
    changedPage(currentPage) {
      this.isClick = false
      this.currentPage = currentPage
      this.sportData = {}
      this.getSportData(this.sportId, this.currentPage)
    },
    updateSportData(id, page) {
      getTodayGame(id, page).then((res) => {
        this.sportData = res
        this.sportId = id
        this.total = res.PageSize
        this.showWay = 1
      })
      getLiveBasicData(id).then((res) => {
        this.liveData = res
      })
    },
    getSportData(id, page, changeSport = false) {
      // this.inittest = true
      this.isClick = false
      if (changeSport) {
        this.currentPage = 1
        // this.inittest = false
      }
      this.sportData = {}
      this.loadingStatus = true
      return new Promise((resolve, reject) => {
        getLiveBasicData(id).then((res) => {
          this.liveData = res
        }).then(() => {
          getTodayGame(id, page).then((res) => {
            this.loadingStatus = false
            this.sportData = res
            this.sportId = id
            this.total = res.PageSize
            this.showWay = 1
            this.inittest = false
            this.vedioUrl = ''
            return resolve()
          })
        }).catch((res) => {
          this.loadingStatus = false
        })
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
    moreGame(val, game) {
      if (+(game.TotalMarketLineCount) === 0) {
        return
      }
      if (val.length !== 0 && game.Market === 3) {
        // console.log('yess')
        this.vedioUrl = val[0].Url
        // console.log(this.vedioUrl, 'this.vedioUrl')
        this.actionSetLiveUrl(this.vedioUrl)
        // this.status = true
      } else {
        this.vedioUrl = ''
        this.actionSetLiveUrl(this.vedioUrl)
      }
      // console.log(this.vedioUrl, 'this.vedioUrl')
      // this.actionSetLiveUrl(this.vedioUrl)
      this.$router.push(`/detail/${this.sportId}/${game.EventId}/${this.$route.name}/${game.Market}/${this.currentPage}`)
    },
    formateTime(val) {
      // console.log(moment.tz(val, 'America/New_York').format('MM/DD HH:mm'), 'America')
      // console.log(moment.tz(val, 'Asia/Taipei').format('MM/DD HH:mm'), 'Taipei')
      return moment.tz(val, 'Asia/Taipei').format('MM/DD HH:mm')
      // return moment(val).utcOffset(-4).format('MM/DD HH:mm')
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
    }
  }
}
</script>

<style lang="scss" scoped>
  .tvBoard {
    height: 189px;
    position: fixed;
    width: 100%;
    z-index: 2;
    top: 40px;
    background-color: #000;
    #dplayer {
      width: 100%;
      height: 90%;
      >>>.dplayer-full-in-icon {
        display: none !important;
      }
    }
  }
  .tvTips {
    position: absolute;
    background-color: #e4e4e4bf;
    text-align: center;
    width: 80%;
    height: 30%;
    font-size: 14px;
    color: #606266;
    z-index: 1;
    padding: 10px;
    border-radius: 5px;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    margin: auto;
  }
  .noVideo {
    background-image: url('~@/assets/img/liveball.png');
    background-size: 100% 100%;
    width: 100%;
    height: 180px;
    background-color: #000;
  }
  .control-bar {
    background-color: #f4f4f5;
    position: absolute;
    bottom: 0;
    width: 100%;
    div {
      float: left;
      width: 50%;
      padding: 10px 0;
      border-right: 1px solid #ebeef5;
      font-size: 12px;
      text-align: center;
    }
  }

.dashboard {
  &-container {
    margin: 0px;
  }
  &-choose {
    display: -webkit-box;
    overflow-x: scroll;
    width: 100%;
    background-color: #FFF;
    height: 51px;
    position: fixed;
    top: 220px;
    z-index: 2;
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
      color: #a4a7ae;
      border: 2px solid #a4a7ae;
      border-radius: 3px;
      margin-right: 15px;
      min-width: 35px;
      margin: 5px;
      &.active {
          border: 2px solid #84aff4;
        }
      &-text {
        display: inline-block;
        font-size: .9em;
        font-weight: 600;
        padding: 7px;
        &.active {
          color: #84aff4;
          // border: 2px solid #abc8f7;
        }
      }
    }

  }
  &-feature {
      border-top: solid 2px rgb(237, 237, 237);
      border-bottom: solid 2px rgb(211, 211, 211);
      position: fixed;
      top: 265px;
      z-index: 2;
      background-color: #FFF;
      width: 100%;
      ul {
        font-size: 14px;
        list-style: none;
        padding: 0;
        margin: 0;
        li {
          padding: 5px;
          display: inline-block;
        }
        >>>.el-pagination {
            width: 50%;
            float: right;
            margin: 2px 0px;
            overflow-x: scroll;
            background-color: #FFF;
            &.is-background li {
              border: 2px solid #a4a7ae;
              background-color: #FFF;
              line-height: 25px;
              color: #a4a7ae;
            }
            .el-pager {
            .active {
              border: 2px solid #84aff4;
              line-height: 25px;
              background-color: #FFF;
              color: #84aff4;
            }
          }
        }
        .switcher {
          line-height: 27px;
          font-size: 16px;
          color: #a4a7ae;
          .default {
            color: #a4a7ae;
          }
          .active {
            color: #84aff4;
          }
          .button {
            padding: 4px;
            border: 2px solid #84aff4;
            color: #FFF;
            background-color: #84aff4;
            border-radius: 3px;
          }
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
    margin-top: 305px;
    background-color: #F9F9F9;
    & .scoreList {
      border-radius: 5px;
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
            padding-left: 10px;
            //color: #737373;
            color: #355ea9;
            background-color: #f5f8fe;
            text-align: left;
            div {
              display: inline-block;
              width: calc(100% - 50px);
              height: 30px;
              line-height: 30px;
              white-space: nowrap;
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
            height: 26px;
            vertical-align: middle;
            color: #abc8f7;
          }
        }
        .oddHide {
          position: relative;
          .inner {
            background-color: #fff;
          }
          .active {
            // border-left: 3px solid #f7c151;
            // box-shadow: 0 0 15px #f3d42e;
            // box-shadow: 0 0 5px #dcdfe6;
            // border-radius: 10px;
          }
          .header {
            font-size: 12px;
            color: #727477;
            div {
                min-width: 24.5%;
                overflow: hidden;
                display: inline-block;
                text-align: center;
            }
            .time {
                min-width: 35%;
                text-align: left;
                background: linear-gradient(130deg, #EFEFEF 83%, transparent 0);
                padding: 5px;
            }
            .live {
                min-width: 35%;
                text-align: center;
                padding: 3px;
                color: red;
                border: none;
            }
            .active {}
            .count {
                min-width: 12%;
                border: 1px solid #dcdfe6;
                border-radius: 5px;
                box-shadow: 0 0 5px #6b85b5;
                padding: 2px 0px;
                color: #355ea9;
            }
          }
          .mean {
            .team {
                .team-left {
                    width: 35%;
                    float: left;
                    text-align: center;
                    padding: 0px 5px;
                    font-size: 14px;
                    overflow: hidden;
                    color: #355ea9;
                    .team {
                      padding-top: 5px;
                      text-align: left;
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
                  color: #355ea9;
                  .score {
                    height: 33px;
                    line-height: 29px;
                  }
                }
                .team-right {
                width: 50%;
                float: right;
                .oddBox-area {
                  width: 24vw;
                  display: inline-block;
                  float: left;
                  & .oddBox {
                    position: relative;
                    text-align: center;
                    width: 100%;
                    height: inherit;
                    line-height: 25px;
                    background-color: #f5f8fe;
                    border: solid #dcdfe626 1px;
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
                        // &:active{
                        //   color: white;
                        //   background: #dc9d3d;
                        // }
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
                      color: #4b5567;
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
          .video {
            background-color: #fff;
            div {
              width: 20px;
              margin: 0px 0px 10px 10px;
              background-color: #e4e4e4;
              display: inline-block;

            }

            .active {
              background-color: #e6a23c;
            }
          }
          .activetest {
            // width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            border: 2px solid #f7c151;
            box-shadow: 0 0 15px #f3d42e;
            border-radius: 10px;
          }
        }
      }
    }
  }
}

</style>
