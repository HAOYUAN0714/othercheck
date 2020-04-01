<template>
  <div class="dashboard-container">
    <loading v-show="loadingStatus && !initTimer" />
    <!--有開賽各球種-->
    <div class="dashboard-choose">
      <div v-for="(item,index) in curGameCount.sportMapping" :key="index" class="dashboard-choose-item">
        <div @click="routerPush(item.orderNumber)">
          <div class="dashboard-choose-item-icon">
            <div class="icon" :class="[getIcon(index),]" />
            <!-- <font-awesome-icon :icon="['fas',getIcon(index)]" /> -->
          </div>
          <div class="dashboard-choose-item-text">{{ item.sportName }}</div>
        </div>
      </div>
    </div>
    <div class="dashboard-scoreListArea">
      <!--滾球區塊-->
      <div class="scoreList">
        <div v-for="(sportItem,index) in sportDataList" :key="`runBall-${index}`">
          <div v-if="index === 0" class="item">
            <p class="title">{{ $t('S_RUN_BALL') }}</p>
            <p class="count">{{ curGameCount.live && curGameCount.live.all }}</p>
          </div>
          <div v-if="getRunBallCount(sportItem) > 0" class="scoreList-inner">
            <div class="text">
              <h3 class="text-title">{{ sportItem.SportName }}</h3>
              <div
                v-for="(score,index2) in sportItem.Events"
                v-show="+(score.Market) === 3"
                :key="index2"
                class="text-teamArea"
                @click="moregame(score.EventId, sportItem.SportId)"
              >
                <!-- {{ score.EventId }} -->
                <span class="score">
                  <p>{{ score.HomeScore }}</p>
                  <p>{{ score.AwayScore }}</p>
                </span>
                <span class="team">
                  <p>{{ score.HomeTeam }}
                    <span v-show="score.GroundTypeId == 0">({{ $t('S_NEUTRAL_GROUND') }})</span>
                    <span v-show="score.HomeRedCard != 0 && score.HomeRedCard != null" class="spaceLeft redCard">{{ score.HomeRedCard }}</span>
                  </p>
                  <p>{{ score.AwayTeam }}
                    <span v-show="score.AwayRedCard != 0 && score.AwayRedCard != null" class="spaceLeft redCard">{{ score.AwayRedCard }}</span>
                  </p>
                </span>
                <div class="time">
                  <p>
                    <real-time :time="score.RBTime" :sport-id="sportItem.SportId" :time-status="score.RBTimeStatus" />
                  </p>
                  <!-- <p>
                    <span class="detail">
                      {{ formatMatchSet(score.RBTime) }}
                    </span>
                  </p> -->
                  <div class="iconBox">
                    <i class="el-icon-arrow-right" />
                  </div>
                </div>
                <div class="text-borderBottom" />
              </div>
            </div>
            <div
              class="all-play-btn"
              @click="toRunBallPage(sportItem.SportId)"
            >
              <div class="title">{{ `${$t('S_ALL')} ${sportItem.SportName} ${$t('S_RUN_BALL')}` }}</div>
              <div class="count">
                {{ curGameCount.live && curGameCount.live[sportItem.SportId] }}
                <i class="el-icon-arrow-right" />
              </div>
            </div>
          </div>
        </div>
        <div
          v-if="curGameCount.live && curGameCount.live.all > 0"
          class="all-play-btn"
          @click="toRunBallPage(Object.keys(curGameCount.live)[0])"
        >
          <div class="title">{{ $t('S_ALL') }} {{ $t('S_RUN_BALL') }}</div>
          <div class="count">
            {{ curGameCount.live && curGameCount.live.all }}
            <i class="el-icon-arrow-right" />
          </div>
        </div>
      </div>
      <!--體育區塊-->
      <div v-for="(sportItem,index) in sportDataList" :key="`today-${index}`" class="scoreList">
        <div class="scoreList-inner">
          <div v-if="index === 0" class="item">
            <p class="title">{{ $t('S_SPORTS') }}</p>
            <!-- <p class="count">{{ sportItem.count }}</p> -->
          </div>
          <div class="text">
            <h3 class="text-title">{{ sportItem.SportName }}</h3>
            <div
              v-for="(score,index2) in sportItem.Events"
              v-show="+(score.Market) === 1 || +(score.Market) === 2"
              :key="index2"
              class="text-teamArea"
              @click="moregame(score.EventId, sportItem.SportId)"
            >
              <span v-if="score.HomeScore && score.AwayScore" class="score">
                <p>{{ score.HomeScore }}</p>
                <p>{{ score.AwayScore }}</p>
              </span>
              <span
                class="team"
                :class="{
                  'no-score': !score.HomeScore && !score.AwayScore
                }"
              >
                <p>{{ score.HomeTeam }}</p>
                <p>{{ score.AwayTeam }}</p>
              </span>
              <span class="time">
                <p class="detail">
                  {{ formatDate(score.EventDate) }}
                  <br>
                  {{ formatTime(score.EventDate) }}
                </p>
                <span class="iconBox">
                  <i class="el-icon-arrow-right" />
                </span>
              </span>
              <div class="text-borderBottom" />
            </div>
            <div
              v-for="(period, periodKey) in (curGameCount['today'] && curGameCount['today'][sportItem.SportId])"
              :key="`period-${periodKey}`"
            >
              <div
                v-if="periodKey === 'all'"
                class="all-play-btn"
                @click="toSportPage(sportItem.SportId)"
              >
                <div class="title">{{ +(sportItem.SportId) === 1 ? $t('S_TODAY_MATCH') : $t('S_ALL_TODAY_MATCH') }}</div>
                <div class="count">
                  {{ period }}
                  <i class="el-icon-arrow-right" />
                </div>
              </div>
              <div v-if="periodKey === 'rank'">
                <div
                  v-for="(rank, rankKey) in period"
                  :key="rankKey"
                  class="all-play-btn"
                  @click="toLeaguePage(sportItem.SportId, rank.CompetitionId )"
                >
                  <div class="title">{{ rank.CompetitionName }}</div>
                  <div class="count">
                    {{ rank.Count }}
                    <i class="el-icon-arrow-right" />
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
import { getHot } from '@/utils/getBallData'
import moment from 'moment'
import loading from '@/layout/components/loading'
import icon from '@/utils/sportIconList'
import realTime from '@/layout/components/realTimeClock'

export default {
  name: 'Dashboard',
  components: {
    loading,
    realTime
  },
  data() {
    return {
      sportDataList: '',
      icon,
      loadingStatus: false,
      initTimer: false,
      timeout: ''
    }
  },
  computed: {
    ...mapGetters(['curGameCount'])
  },
  created() {
    this.getHotGameData()
    // this.getGameCount()
  },
  beforeDestroy() {
    clearInterval(this.timeout)
  },
  methods: {
    ...mapActions({
      actionGetGameCount: 'ball/actionGetGameCount'
    }),
    // getGameCount() {
    //   getHomeInfo().then((res) => {
    //     this.actionGetGameCount(res)
    //   })
    // },
    routerPush(navItem) {
      this.$router.push({ path: '/sport/' + navItem })
    },
    moregame(gameId, sportId) {
      this.$router.push({ path: '/detail/' + sportId + '/' + gameId })
    },
    getIcon(sportId) {
      if (this.icon.includes(sportId)) {
        return `icon-${sportId}`
      } else {
        return 'icon-1'
      }
    },
    getHotGameData() {
      this.loadingStatus = true
      getHot().then((res) => {
        this.loadingStatus = false
        this.sportDataList = res
        if (!this.initTimer) {
          this.timeout = setInterval(() => {
            this.getHotGameData()
            this.initTimer = true
          }, 30000)
        }
      }).catch((res) => {
        this.loadingStatus = false
      })
    },
    getRunBallCount(sportData) {
      let count = 0
      Object.keys(sportData).forEach((item) => {
        Object.keys(sportData[item]).forEach((innerItem) => {
          if (+(sportData[item][innerItem].Market) === 3) {
            count += 1
          }
        })
      })
      return count
    },
    formatDate(val) {
      return moment(val).utcOffset(-4).format('MM/DD')
    },
    formatTime(val) {
      return moment(val).utcOffset(-4).format('HH:mm')
    },
    formatMatchSet(val) {
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
    toSportPage(sportId) {
      this.$router.push(`/today/${sportId}`)
    },
    toLeaguePage(sportId, leagueId) {
      this.$router.push(`/league/${leagueId}/${sportId}/2`)
    },
    toRunBallPage(sportId) {
      this.$router.push(`/live/${sportId}`)
    }
  }
}
</script>

<style lang='scss' scoped  src="@/styles/dashboard-john.scss"></style>
