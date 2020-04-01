<template>
  <div class="dashboard-container">
    <div class="dashboard-choose">
      <div v-for="(item,index) in SportKind" :key="index" class="dashboard-choose-item">
        <div :class="{'active' : sportId == item.orderNumber }" @click="routerPush(item.orderNumber)">
          <div class="dashboard-choose-item-icon">
            <div
              class="icon"
              :class="[
                getIcon(index),
                {
                  active: +(sportId) === +(index)
                }
              ]"
            />
          </div>
          <div class="dashboard-choose-item-text">{{ item.sportName }}</div>
        </div>
      </div>
    </div>
    <div class="dashboard-scoreListArea">
      <div v-for="(menuSport,index) in sportOddData" :key="index" class="scoreList">
        <div class="item">
          <div class="logo" @click="back()">
            <font-awesome-icon class="icon" :icon="['fas','arrow-left']" />
          </div>
          <div class="title">
            <span>{{ SportKind[sportId] ? SportKind[sportId].sportName :'' }}</span>
            <!-- <span>{{ sportData[0].SportName }}</span> -->
            <span class="sub-text">{{ $t('S_MULTIPLES') }}</span>
          </div>
          <!-- <button :class="{buttonActive: showWay == 'bigsmall' }" @click="disply('bigsmall')" class="button-left">讓球＆大小</button>
          <button :class="{buttonActive: showWay == 'onetwo' }"  class="button-riht">1 X 2</button>-->
        </div>
        <div class="pass">
          <div class="pass-title">
            <span :class="{'active':chanal === 1}" @click="select(1)">{{ $t('S_COUPONS') }}</span>
            <span :class="{'active':chanal === 2}" @click="select(2)">{{ $t('S_MATCHES') }}</span>
            <span :class="{'active':chanal === 3}" @click="select(3)">{{ $t('S_OUTRIGHTS') }}</span>
          </div>
          <div v-show="chanal === 2" class="pass-title2">
            <div class="today" :class="{'active':ScoreChannel === ''}" @click="selectPeriod('')">{{ $t('S_ALL_MATCHES') }}</div>
            <div class="today" :class="{'active':ScoreChannel === 4}" @click="selectPeriod(4)">{{ $t('S_INLPAY_MATCHES') }}</div>
            <div class="today" :class="{'active':ScoreChannel === 2}" @click="selectPeriod(2)">{{ $t('S_TODAY_MATCH') }}</div>
            <div class="today" :class="{'active':ScoreChannel === 1}" @click="selectPeriod(1)">{{ $t('S_FUTURE') }}</div>
            <div v-for="(date,day) in sportData.dateList" :key="day" class="todayOther">
              <div :class="{'active': ScoreChannel === date }" @click="selectDate( 1, date )">
                {{ date }}
              </div>
            </div>
          </div>
        </div>
        <div v-show="chanal === 1" class="score">
          <div class="score-item" @click="passRouterPUsh(join.SportId, 'today')">
            <div class="score-title">{{ scoreList[0].title }}</div>
            <div class="score-count">{{ join.TodayCount }}</div>
          </div>
          <div class="score-item" @click="passRouterPUsh_Tomorrow(join.SportId, join.TomorrowDate)">
            <div class="score-title">{{ scoreList[1].title }}</div>
            <div class="score-count">{{ join.TomorrowCount }}</div>
          </div>
        </div>
        <div v-show="chanal === 3" class="score">
          <template>
            <div v-if="championData && championData.length > 0 ">
              <div v-for="(champion,championIndex) in championData" :key="championIndex" class="score-item" @click="championPUsh(champion.SportId, champion.CompetitionId, 'pass' )">
                <div class="score-title">{{ champion.CompetitionName }}</div>
                <div class="score-count">{{ champion.Count }}</div>
              </div>
            </div>
            <div v-else>
              <div class="score-item">
                <div class="score-title">{{ $t('S_SORRY') }}</div>
              </div>
            </div>
          </template>
        </div>
        <div v-show="chanal === 2" class="score">
          <div class="dashboard-scoreListArea">
            <div class="scoreList">
              <div class="text">
                <!-- 滾球 -->
                <div v-if="ScoreChannel === 4">
                  <!-- popular -->
                  <template>
                    <div v-if="sportliveData">
                      <div class="oddComponent">
                        <div class="text-title" @click="openPage = !openPage">
                          <span>
                            <font-awesome-icon class="icon" :icon="['fas','angle-down']" />
                          </span>
                          <span>{{ SportKind[sportId] ? SportKind[sportId].sportName :'' }}</span>
                        </div>
                        <div v-show="!openPage" class="oddHide">
                          <div v-for="(live,i) in liveData" :key="i">
                            <div class="score-item" @click="leagueRouterPush( live.data.CompetitionId, sportId, 3, '', 'isCombo' )">
                              <div class="score-title">{{ live.data.CompetitionName }}</div>
                              <div class="score-count">{{ live.count }}</div>
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
                  </template>
                </div>
                <!-- 早盤賽事收合 -->
                <div v-if="ScoreChannel === 2 || ScoreChannel === 1 || ScoreChannel === showDate || ScoreChannel === 3 || ScoreChannel === ''">
                  <!-- popular -->
                  <template>
                    <div v-if="sportData.popular && sportData.popular.length > 0 ">
                      <div v-for="(odds,i) in sportData.popular" :key="i" class="oddComponent">
                        <div class="text-title" @click="show(odds.ProgrammeId)">
                          <span>
                            <font-awesome-icon class="icon" :icon="['fas','angle-down']" />
                          </span>
                          <span>{{ odds.ProgrammeName }}</span>
                        </div>
                        <div v-show="!showcode[odds.ProgrammeId] === true" class="oddHide">
                          <div v-for="(teams,league) in odds.league" :key="'league-'+league">
                            <div class="score-item" @click="leagueRouterPush( teams.CompetitionId, teams.SportId, teams.Market, showDate, 'isCombo')">
                              <div class="score-title">{{ teams.CompetitionName }}</div>
                              <div class="score-count">{{ teams.Count }}</div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- otherList -->
                      <div v-for="(odds,b) in sportData.otherList" :key="'other-'+b" class="oddComponent">
                        <div class="text-title" @click="show(odds.ProgrammeId)">
                          <span>
                            <font-awesome-icon class="icon" :icon="['fas','angle-down']" />
                          </span>
                          <span>{{ odds.ProgrammeName }}</span>
                        </div>
                        <div v-show="showcode[odds.ProgrammeId] === true" class="oddHide">
                          <div v-for="(teams,league2) in odds.league" :key="'league2-'+league2">
                            <div class="score-item" @click="leagueRouterPush( teams.CompetitionId, teams.SportId, teams.Market, showDate, 'isCombo')">
                              <div class="score-title">{{ teams.CompetitionName }}</div>
                              <div class="score-count">{{ teams.Count }}</div>
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
                  </template>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div></template>

<script>
import { mapGetters } from 'vuex'
import { getLeagueCountNew, getLeagueBasicData, getOutRightLeagueCount, getEventCount, getSportKind, getLiveBasicData } from '@/utils/getBallData'
import icon from '@/utils/sportIconList'

export default {
  name: 'Dashboard',
  components: {},
  data() {
    return {
      openPage: false,
      icon,
      SportKind: {},
      join: {},
      championData: [],
      sportId: 1,
      sportData: [],
      sportliveData: [],
      showDate: '',
      chanal: 2,
      ScoreChannel: '',
      showcode: {},
      showWay: 'bigsmall',
      sportOddData: [
        {
          title: '菜單',
          secondTitle: '復式過關'
        }
      ],
      scoreList: [
        { title: this.$t('S_TODAY_MATCH'), count: '36' },
        { title: this.$t('S_TOMORROW_MATCH'), count: '57' }
      ]
    }
  },
  computed: {
    ...mapGetters(['name']),
    liveData() {
      const final = {}
      if (!this.sportliveData.Events) {
        return {}
      }
      this.sportliveData.Events.forEach((data) => {
        if (!final[data.Competition.CompetitionId]) {
          final[data.Competition.CompetitionId] = {
            count: 1,
            data: data.Competition
          }
        } else {
          final[data.Competition.CompetitionId].count += 1
        }
      })
      return final
    }
  },
  mounted() {
    // this.sportId = this.$route.params.sportid
    this.getSportData(1, '')

    // this.timeout = setInterval(() => {
    //   this.getSportData(1, '')
    // }, 60000)
  },
  // beforeDestroy() {
  //   clearInterval(this.timeout)
  // },
  methods: {
    getIcon(sportId) {
      if (this.icon.includes(sportId)) {
        return `icon-${sportId}`
      } else {
        return 'icon-1'
      }
    },
    championPUsh(sportId, championid, pass) {
      this.$router.push({ path: '/champion/' + sportId + '/' + championid + '/' + pass })
    },
    leagueRouterPush(leagueid, sportid, market, date = '', isCombo) {
      if (date === '') {
        this.$router.push({ path: '/league/' + leagueid + '/' + sportid + '/' + market + '/' + isCombo })
        return
      }
      this.$router.push({ path: '/league/' + leagueid + '/' + sportid + '/' + market + '/' + isCombo + '/' + date })
    },
    selectPeriod(period) {
      if (period === this.ScoreChannel) {
        return
      }
      this.getSportData(this.sportId, period)
      this.ScoreChannel = period
    },
    selectDate(period, date) {
      this.getSportData(this.sportId, period, date)
      // console.log('date,', date)
      this.showDate = date
      this.ScoreChannel = date
    },
    // market: 1=早盤 ２=今日
    getSportData(id, market, EventDate) {
      // console.log('id:', id, 'market:', market, 'EventDate:', EventDate)
      switch (market) {
        case 4:
          // live
          getLiveBasicData(id, 1, true).then((res) => {
            this.sportliveData = res.Sports[0]
          })
          break
        default:
          getLeagueCountNew(id, market, EventDate, true).then((res) => {
            this.sportData = res
            this.sportId = id
            // console.log('this.sportData,', this.sportData)
            // console.log('id,', id)
            // console.log('market,', market)
            // console.log('3/all', market)
          })
          break
      }

      getOutRightLeagueCount(id, true).then((res) => {
        this.championData = res.league
        // console.log(' this.championData:', this.championData)
      })
      getEventCount(id, true).then((res) => {
        this.join = res
        // console.log(' this.join:', this.join)
      })
      getSportKind(true).then((res) => {
        this.SportKind = res
        // console.log(' this.SportKind:', this.SportKind)
      })
    },
    test2() {
      getLeagueBasicData(13556, 1, 2)
    },
    select(number) {
      this.chanal = number
    },
    selectScore(nu) {
      this.ScoreChannel = nu
    },
    show(id) {
      if (!this.showcode[id]) {
        this.showcode[id] = true
      } else {
        delete this.showcode[id]
      }
      this.$forceUpdate()
    },
    change(path) {
      // console.log('path', path)
      this.$router.push(path)
    },
    disply(way) {
      this.showWay = way
      // console.log(' this.showWay', this.showWay)
    },
    routerPush(navItem) {
      this.sportId = navItem
      this.getSportData(navItem)
      this.ScoreChannel = ''
    },
    passRouterPUsh(sportid, date) {
      if (this.join.TodayCount === 0) {
        return
      }
      this.$router.push({ path: '/join/combo/' + sportid + '/' + date })
    },
    passRouterPUsh_Tomorrow(sportid, date) {
      if (this.join.TomorrowCount === 0) {
        return
      }
      this.$router.push({ path: '/join/combo/' + sportid + '/' + date })
    },
    back() {
      this.$router.go(-1) // 返回上一层
    }
  }
}
</script>

<style lang='scss' scoped  src="@/styles/dashboard.scss"></style>
<style lang='scss' scoped  src="@/styles/score.scss"></style>

