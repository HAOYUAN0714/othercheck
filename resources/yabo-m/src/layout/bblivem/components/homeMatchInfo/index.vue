<template>
  <div>
    <div v-if="sortedEvent.length > 0" class="dashboard-feature">
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
        <li>
          <div class="more-game" @click="toMoreMatch">
            <span>more更多賽事foryabolive</span>
            <i class="fa fa-chevron-right" aria-hidden="true" />
          </div>
        </li>
      </ul>
    </div>
    <!-- {{sortedEvent}} -->
    <div v-if="sortedEvent && sortedEvent.length > 0">
      <div class="dashboard-scoreListArea">
        <div class="scoreList">
          <div v-for="(games,item) in sortedEvent" :key="item" class="text">
            <!-- {{games.Market}} -->
            <div class="oddComponent">
              <div v-if="checkFirst(item)" class="text-title" @click="show(games.Competition.CompetitionId)">
                <div class="camp">
                  <!-- <img
                    :src="`/exsport/img/CompetitionImageFile/${games.Competition.CompetitionId}.png`"
                    class="league-img"
                    @error="setDefaultLogo"
                  > -->
                  <div class="league-name">{{ games.Competition.CompetitionName }}</div>
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
              <el-collapse-transition>
                <div v-show="!showcode[games.Competition.CompetitionId] === true && showWay === 1 " class="oddHide">
                  <div>
                    <div v-if="games.Market === 3" class="header">
                      <!-- <realTime :sportId="sportId" :time="games.RBTime" :timeStatus ="games.RBTimeStatus" /> -->
                    </div>
                    <div v-if="games.Market !== 3" class="header">
                      <div class="time">{{ formateTime(games.EventDate) }}</div>
                    </div>
                    <div class="mean">
                      <div class="team clearfix" @click="toMoreGamePage(games.EventId)">
                        <div
                          class="team-left"
                        >
                          <!-- <img
                            :src="`/exsport/img/TeamImageFile/${games.HomeTeamId}.png`"
                            class="team-img"
                            @error="setDefaultLogo"
                          > -->
                          <span class="team-name">{{ games.HomeTeam }}</span>
                        </div>
                        <div class="vs">{{ games.Market === 3 ? `${games.HomeScore} - ${games.AwayScore}` : 'VS' }}</div>
                        <div
                          class="team-right"
                        >
                          <span class="team-name">{{ games.AwayTeam }}</span>
                          <!-- <img
                            :src="`/exsport/img/TeamImageFile/${games.AwayTeamId}.png`"
                            class="team-img"
                            @error="setDefaultLogo"
                          > -->
                        </div>
                      </div>
                    </div>
                    <div v-if="games.Market!==3" class="status">
                      <div class="nonstart">未開始</div>
                    </div>
                    <div v-else class="status2">
                      <div class="start">
                        <realTime :sport-id="sportId" :time="games.RBTime" :time-status="games.RBTimeStatus" />
                      </div>

                    </div>
                  </div>
                  <div class="status">
                    <div class="statusRight">
                      <div
                        class="star"
                        :class="{
                          active: favoriteMatch.includes(games.EventId)
                        }"
                        @click="setFavoriteMatch(games.EventId)"
                      >
                        <i class="fa fa-star" />
                      </div>
                    </div>
                  </div>

                </div>
              </el-collapse-transition>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import moment from 'moment'
import { addFavoriteMatch, getAllEventCount, removeFavoriteMatch } from '@/api/m6'
import realTime from '@/layout/components/realTimeClock'
import defaultImg from '@/assets/m6app/ic_team_logo_default.png'

export default {
  components: {
    realTime
  },
  props: {
    sortedEvent: {
      type: Array,
      required: true
    },
    sportId: {
      type: [String, Number],
      default: () => ''
    }
  },
  data() {
    return {
      showcode: {},
      showWay: 1,
      collapseAllMatch: false,
      filterMatchStatus: +(this.$cookie.get('filterMatch'))
    }
  },
  computed: {
    ...mapGetters({
      favoriteMatch: 'getFavoriteMatch'
    })
  },
  methods: {
    ...mapActions({
      actionGetFavoriteMatch: 'm6app/actionGetFavoriteMatch',
      actionSetAllEventCount: 'm6app/actionSetAllEventCount'
    }),
    checkFirst(index) {
      if (index - 1 < 0) {
        return true
      }
      const current = this.sortedEvent[index].Competition.CompetitionId
      const before = this.sortedEvent[index - 1].Competition.CompetitionId
      return current !== before
    },
    show(id) {
      if (!this.showcode[id]) {
        this.showcode[id] = true
      } else {
        delete this.showcode[id]
      }
      this.$forceUpdate()
    },
    formateTime(val) {
      return moment(val).utcOffset(-4).format('MM/DD HH:mm')
    },
    toMoreMatch() {
      const routeName = this.$route.name
      console.log(routeName, 'routeName')
      switch (routeName) {
        case 'today':
          this.$router.push('/moreSportToday')
          break
        case 'breakfast':
          this.$router.push('/moreSportFuture')
          break
        case 'combo':
          this.$router.push('/moreSportCombo')
          break
        case 'faver':
          this.$router.push('/moreSportFaver')
          break
      }
    },
    toMoreGamePage(val) {
      var name = this.$route.name
      switch (name) {
        case 'today':
          name = 'moreSportToday'
          break
        case 'breakfast':
          name = 'moreSportFuture'
          break
        case 'combo':
          name = 'moreSportCombo'
          break
        case 'faver':
          name = 'moreSportFaver'
          break
      }
      this.$router.push(`/detail/${this.sportId}/${val}/${name}`)
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
    setFavoriteMatch(val) {
      if (this.favoriteMatch.includes(val)) {
        removeFavoriteMatch(this.sportId, val).then((res) => {
          const eventId = [...this.favoriteMatch]
          eventId.splice(eventId.indexOf(val), 1)
          this.actionGetFavoriteMatch(eventId)
        }).then((res) => {
          getAllEventCount().then((res) => {
            this.actionSetAllEventCount(res.EventCount)
          })
        })
        return
      }
      addFavoriteMatch(this.sportId, val).then((res) => {
        const eventId = [...this.favoriteMatch]
        eventId.push(val)
        this.actionGetFavoriteMatch(eventId)
      }).then(() => {
        getAllEventCount().then((res) => {
          this.actionSetAllEventCount(res.EventCount)
        })
      })
    },
    setDefaultLogo(e) {
      e.target.src = defaultImg
    }
  }
}
</script>

<style lang="scss" scoped>
div {
  // position: fixed;
  // width: 100vw;
  // top: 19vh;
.dashboard {
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
          vertical-align: top;
          &:last-child {
            display: block;
            float: right;
          }
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
      .more-game {
        padding-top: 3px;
      }
    }
  &-scoreListArea {
    background-color: #e8e8e8;
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
        & .title { // ------
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
        &-title {
          font-size: 14px;
          flex: 6;
          margin: 0;
          div {
            display: inline-block;
          }
          .camp {
            width: 75vw;
            padding: 2px 5px;
            background: linear-gradient(130deg, #c0c4cce0 86%, transparent 0);
            .league-img {
              width: 35px;
              height: 35px;
              background-size: contain !important;
            }
            .league-name {
              display: inline-block;
              width: 75%;
              white-space: nowrap;
              padding-top: 6px;
              vertical-align: top;
              overflow-x: scroll;
              overflow-y: hidden;
              &::-webkit-scrollbar {
              width: 5px;
              }
              &::-webkit-scrollbar:horizontal {
                height: 5px;
              }
              &::-webkit-scrollbar-track {
                background-color: transparentize(#c5c9d0, 0.7);
              }
              &::-webkit-scrollbar-thumb {
                border-radius: 5px;
                background: transparentize(#c5c9d0, 0.5);
                box-shadow: inset 0 0 6px #c5c9d0;
              }
            }
          }
          .arraw {
            width: 15vw;
            text-align: right;
            background: linear-gradient(-55deg, #e4e7ed 86%, transparent 0);
            height: 26px;
            padding-top: 7px;
            vertical-align: top;
            font-size: 20px;
            color: #A7A7A7;
          }
        }
        .oddHide {
          div {
            background-color: #fff;
            padding-bottom: 5px;
            position: relative;
            text-align: center;
            .header {
              padding: 5px;
              height: 30px;
              .time {
                text-align: center;
              }
            }
            .mean {
              .team {
                font-size: 14px;
                text-align: center;
                padding: 0 10px;
                color: #222;
                font-weight: bold;
                .team-left {
                  width: 39%;
                  float: left;
                  padding-top: 4px;
                  text-align: right;
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
                  .team-img,
                  .team-name {
                    display: inline-block;
                    vertical-align: middle;
                  }
                  .team-name {
                    white-space: nowrap;
                    overflow-x: scroll;
                    padding-right: 15px;
                  }
                  .team-img {
                    width: 45px;
                    height: 45px;
                  }
                }
                .vs {
                  float: left;
                  width: 22%;
                  font-size: 20px;
                  font-weight: bold;
                }
                .team-right {
                  width: 39%;
                  float: right;
                  padding-top: 4px;
                  text-align: left;
                  overflow-x: scroll;
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
                  .team-img,
                  .team-name {
                    display: inline-block;
                    vertical-align: middle;
                  }
                  .team-name {
                    white-space: nowrap;
                    overflow-x: scroll;
                    padding-left: 15px;
                  }
                  .team-img {
                    width: 45px;
                    height: 45px;
                  }
                }
              }
            }
            .clear {
                clear: both;
              }
            .status {
              width: 28vw;
              margin: 0 auto;
              background-color: #e4e7ed;
              padding-bottom: 0;
              border-radius: 20px;
              font-size: 14px;
              text-align: center;
              div {
                padding: 1px 5px;
                font-size: 14px;
                display: inline-block;
                background-color: #e4e7ed;
              }
              .nonstart {
                // background-color: #e4e7ed;
              }
              .start {
                background-color: #e6a23c;
                font-size: 12px
              }
              .statusLeft {
                color: #fff;
                min-width: 90px;
                background-color: #c5c9d0;
                padding: 5px 10px;
                border-radius: 20px 0px 0px 20px;
                background: linear-gradient(120deg, #c0c4cce0 70%, transparent 0);
              }
              .statusRight {
                color: #000;
                padding: 5px;
                background-color: #e4e7ed;
              }
            }
            .status2 {
              width: 28vw;
              margin: 0 auto;
              background-color: #e6a23c;
              padding-bottom: 0;
              border-radius: 20px;
              font-size: 14px;
              text-align: center;
              .start {
                background-color: #e6a23c;
                font-size: 12px;
                border-radius: 20px;
                padding: 2px;
              }
            }
            .star {
              position: absolute;
              bottom: 4px;
              right: 10px;
              color: #DDD;
              &.active {
                color: #E7C679;
              }
            }
          }
        }
      }
    }
  }
}
}

</style>
