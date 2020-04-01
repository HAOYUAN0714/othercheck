<template>
  <div class="dashboard-container">
    <div class="dashboard-scoreListArea">
      <div v-for="(menuSport,index) in sportOddData" :key="index" class="scoreList">
        <div class="item">
          <div class="logo" @click="back()">
            <font-awesome-icon class="icon" :icon="['fas','arrow-left']" />
          </div>
          <div class="title">
            <span>{{ SportKind[sportId] ? SportKind[sportId].sportName :'' }}</span>
            <span style="color:#fff;">{{ $t('S_MENU') }}</span>
          </div>
        </div>
        <div class="pass">
          <div class="pass-title">
            <span :class="{'active':chanal === 1}" @click="select(1)">{{ $t('S_COUPONS') }}</span>
            <span :class="{'active':chanal === 2}" @click="select(2)">{{ $t('S_MATCHES') }}</span>
            <span :class="{'active':chanal === 3}" @click="select(3)">{{ $t('S_OUTRIGHTS') }}</span>
          </div>
          <div v-show="chanal === 2" class="pass-title2">
            <div class="today" :class="{'active':ScoreChannel === 3}" @click="selectPeriod(3)">{{ $t('S_ALL_MATCHES') }}</div>
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
          <!-- championData: {{ championData }} -->
          <div v-if="championData && Object.keys(championData).length > 0 ">
            <div v-for="(champion,championIndex) in championData" :key="championIndex" class="score-item" @click="championPUsh(champion.SportId, champion.CompetitionId, 'unpass' )">
              <div class="score-title">{{ champion.CompetitionName }}</div>
            </div>
          </div>
          <div v-else>
            <div class="score-item">
              <div class="score-title">{{ $t('S_SORRY') }}</div>
            </div>
          </div>
        </div>
        <div v-if="chanal === 2" class="score">
          <div class="dashboard-scoreListArea">
            <div class="scoreList">
              <div class="text">
                <!-- 早盤賽事收合 -->
                <div v-if="ScoreChannel === 2 || ScoreChannel === 1 || ScoreChannel === showDate || ScoreChannel === 3">
                  <template>
                    <div v-if="sportData.popular && sportData.popular.length >0">
                      <!-- popular -->
                      <div v-for="(odds,i) in sportData.popular" :key="i" class="oddComponent">
                        <div class="text-title" @click="show(odds.ProgrammeId)">
                          <span>
                            <font-awesome-icon class="icon" :icon="['fas','angle-down']" />
                          </span>
                          <span>{{ odds.ProgrammeName }}</span>
                        </div>
                        <div v-show="!showcode[odds.ProgrammeId] === true" class="oddHide">
                          <div v-for="(teams,league) in odds.league" :key="'league-'+league">
                            <div class="score-item" @click="leagueRouterPush( teams.CompetitionId, teams.SportId, teams.Market, showDate )">
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
                            <div class="score-item" @click="leagueRouterPush( teams.CompetitionId, teams.SportId, teams.Market, showDate )">
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
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { getEventCount, getLeagueCountNew, getLeagueBasicData, getOutRightLeagueCount, getSportKind } from '@/utils/getBallData'

export default {
  name: 'Dashboard',
  components: {},
  data() {
    return {
      sportId: '',
      SportKind: {},
      join: {},
      championData: {},
      sportData: [],
      showDate: '',
      chanal: 1,
      ScoreChannel: 3,
      showcode: {},
      showWay: 'bigsmall',
      currentDate: '',
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
    ...mapGetters(['name'])
  },
  mounted() {
    this.sportId = this.$route.params.sportid
    this.getSportData(this.sportId)
  },
  methods: {
    championPUsh(sportId, championid, pass) {
      this.$router.push({ path: '/champion/' + sportId + '/' + championid + '/' + pass })
    },
    leagueRouterPush(id, sportId, market, showDate) {
      const isCombo = 'noCombo'
      this.$router.push({ path: '/league/' + id + '/' + sportId + '/' + market + '/' + isCombo + '/' + showDate })
    },
    updateJoinCount() {
      getEventCount().then((res) => {

      })
    },
    selectPeriod(period, date) {
      this.currentDate = ''
      const inputPeriod = period === 3 ? '' : period
      // console.log('TESTperiod,', period)
      this.getSportData(this.sportId, inputPeriod, date)
      this.ScoreChannel = period
    },
    selectDate(period, date) {
      this.currentDate = date
      this.getSportData(this.sportId, period, date)
      // console.log('date,', date)
      this.showDate = date
      this.ScoreChannel = date
    },
    // market: 1=早盤 ２=今日
    getSportData(id, market, EventDate) {
      getLeagueCountNew(id, market, EventDate).then((res) => {
        this.sportData = res
        // console.log('this.sportData,', this.sportData)
        // console.log('id,', id)
        // console.log('market,', market)
      })
      getOutRightLeagueCount(id).then((res) => {
        this.championData = res.league
        // console.log(' this.championData:', this.championData)
      })
      getEventCount(id).then((res) => {
        this.join = res
        // console.log(' this.join:', this.join)
      })
      getSportKind().then((res) => {
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
      this.$router.push(path)
    },
    disply(way) {
      this.showWay = way
    },
    routerPush(navItem) {
      this.$router.push({ path: navItem })
    },
    // joinTodayPush(sportid) {
    //   this.$router.push({ path: '/join/' + sportid + '/' + 'today' })
    // },
    // joinTomorrowPush(sportid, date) {
    //   if (!date) {
    //     date = null
    //   }
    //   this.$router.push({ path: '/join/' + sportid + '/' + date })
    // },
    passRouterPUsh(sportid, date) {
      if (this.join.TodayCount === 0) {
        return
      }
      this.$router.push({ path: '/join/all/' + sportid + '/' + date })
    },
    passRouterPUsh_Tomorrow(sportid, date) {
      if (this.join.TomorrowCount === 0) {
        return
      }
      this.$router.push({ path: '/join/all/' + sportid + '/' + date })
    },
    back() {
      this.$router.go(-1) // 返回上一层
    }
  }
}
</script>

<style lang='scss' scoped  src="@/styles/dashboard.scss"></style>
<style lang='scss' scoped  src="@/styles/score.scss"></style>

