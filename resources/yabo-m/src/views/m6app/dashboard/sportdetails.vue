<template>
  <div class="sport">
    <div class="sportHeader">
      <div class="header clearfix">
        <div class="backBtn" @click="goBack()" />
        <div class="alliance">{{ moreGameInfo.leagueName }}</div>
        <div class="myBet" />
      </div>
      <div v-if="hasVideo">
        <div class="sportTeam clearfix">
          <div class="team-wrap">
            <img src="@/icons/png/taipei.png" class="teamImg">
            <div class="text">
              {{ moreGameInfo.homeName }}
            </div>
          </div>
          <div class="score">
            <div class="battle">
              <div v-if="navHeader === 'live'" class="scores">
                {{ moreGameInfo.homeScore }}&nbsp;-&nbsp;{{ moreGameInfo.awayScore }}
              </div>
              <div v-else class="vs">
                VS
              </div>
            </div>
            <div class="sessions">
              <!-- <div v-if="navHeader === 'live'" class="game-score-info">
                半&nbsp;{{ moreGameInfo.homeScore }}&nbsp;:&nbsp;{{ moreGameInfo.awayScore }}
              </div> -->
              <div class="game-start-time">
                {{ moment(moreGameInfo.EventDate).format('MM-DD HH:mm') }}
              </div>
            </div>
          </div>
          <div class="team-wrap">
            <img src="http://teamicon.jswswl.com/api/team/icon/1?kw=中国" class="teamImg">
            <div class="text">
              {{ moreGameInfo.awayName }}
            </div>
          </div>
        </div>
        <div class="buttom">
          <div class="sub-line" />
          <div class="status">
            <div class="text">
              {{ navHeader === 'live' ? formatMatchSet(moreGameInfo.RBTime) : $t('S_NOT_START') }}
            </div>
          </div>
        </div>
        <div class="video-wrap clearfix">
          <div
            v-if="moreGameInfo.BREventId"
            class="video"
            @click="showVideo(moreGameInfo.BREventId)"
          >
            {{ $t('S_ANIMATION_LIVE') }}
          </div>
          <!-- <div class="videoBtn">視訊直撥</div> -->
        </div>
      </div>
      <widget v-else :match-id="matchId" />
    </div>
    <div class="sportContainer">
      <div class="sportContent">
        <div class="bet-controller" @click="switchAll">+ {{ $t('S_CLICK') }}&nbsp;{{ showtext }}&nbsp;{{ $t('S_ALL_BET_SLIP') }}</div>
        <!-- <div class="gameCorrectScore">
          <div class="gameTitle" @click="showCorrectScore()">
            <span>波膽</span>
            <img src="../../../icons/png/up.png" class="arrow">
          </div>
          <div v-show="isShowCorrectOdds" class="gameOdds">
            <div class="odds">
              <div class="homeOdds">
                <div class="homeTeam">1</div>
                <div class="h_block">
                  <div class="h_score">1-0</div>
                  <div class="h_odds">9.4</div>
                </div>
                <div class="h_block">
                  <div class="h_score">1-0</div>
                  <div class="h_odds">9.4</div>
                </div>
              </div>
              <div class="Tie">
                <div class="tieX">x</div>
                <div class="t_block">
                  <div class="t_score">0-0</div>
                  <div class="t_odds">20</div>
                </div>
                <div class="t_block">
                  <div class="t_score">0-0</div>
                  <div class="t_odds">20</div>
                </div>
              </div>
              <div class="awayOdds">
                <div class="awayTeam">2</div>
                <div class="a_block">
                  <div class="a_score">0-1</div>
                  <div class="a_odds">31</div>
                </div>
                <div class="a_block">
                  <div class="a_score">0-1</div>
                  <div class="a_odds">31</div>
                </div>
              </div>
            </div>
          </div>
        </div> -->
        <div
          v-for="(playGroup, playGroupId) in moreGameInfo.playGroupList"
          :key="`moreGameInfo-${playGroupId}`"
          class="gameDetails"
        >
          <div :class="['gameTitle clearfix', `table${playGroup.BetTypeId}`]" @click="showDetailOdds(playGroup.MarketlineId)">
            <div class="text">{{ playGroup.PeriodName }} {{ getSectionName(playGroup.BetTypeName, playGroup.WagerSelections[0].Specifiers) }}</div>
            <img src="../../../icons/png/up.png" :class="['arrow', { down: dropDownCloseList[playGroup.MarketlineId] }]">
          </div>
          <component
            :is="dynamicTemplate(playGroup.BetTypeId)"
            :play-group="playGroup"
            :more-game-info="moreGameInfo"
            :drop-down-close="!!dropDownCloseList[playGroup.MarketlineId]"
          />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
import moment from 'moment'
import template1 from '@/layout/betball/playTable/template1'
import template2 from '@/layout/betball/playTable/template2'
import template3 from '@/layout/betball/playTable/template3'
import template4 from '@/layout/betball/playTable/template4'
import widget from '@/components/common/widget/'
import mixin from '@/layout/betball/mixin/playTable'

export default {
  components: {
    template1,
    template2,
    template3,
    template4,
    widget
  },
  mixins: [mixin],
  data() {
    return {
      moment,
      dropDown: false,
      dropDownCloseList: {},
      hasVideo: true
    }
  },
  computed: {
    ...mapGetters({
      navHeader: 'betball/getNavHeader',
      moreGameInfo: 'betball/getMoreGameInfo'
    }),
    showtext() {
      return Object.values(this.dropDownCloseList).some(item => item) ? this.$t('S_CLOSE') : this.$t('S_OPEN')
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
    },
    showDetailOdds(val) {
      this.dropDownCloseList = {
        ...this.dropDownCloseList,
        [val]: !this.dropDownCloseList[val]
      }
    },
    dynamicTemplate(val) {
      switch (val) {
        case 1:
        case 4:
          return `template1`
        case 2:
        case 5:
        case 82:
        case 84:
        case 88:
          return `template2`
        case 3:
          return `template3`
        case 7:
        case 8:
        case 9:
        case 10:
        case 13:
        case 14:
        case 15:
        case 16:
        case 17:
        case 18:
        case 19:
        case 20:
        case 21:
        case 22:
        case 23:
        case 24:
        case 25:
        case 26:
        case 27:
        case 28:
        case 29:
        case 30:
        case 31:
        case 32:
        case 33:
        case 34:
        case 35:
        case 36:
        case 37:
        case 38:
        case 39:
        case 40:
        case 41:
        case 42:
        case 43:
        case 44:
        case 45:
        case 46:
        case 47:
        case 48:
        case 49:
        case 50:
        case 51:
        case 52:
        case 53:
        case 54:
        case 55:
        case 56:
        case 57:
        case 58:
        case 59:
        case 60:
        case 61:
        case 62:
        case 63:
        case 64:
        case 65:
        case 66:
        case 67:
        case 68:
        case 69:
        case 70:
        case 71:
        case 72:
        case 73:
        case 74:
        case 75:
        case 76:
        case 77:
        case 78:
        case 79:
        case 80:
        case 83:
        case 85:
        case 86:
        case 87:
        case 89:
        case 90:
        case 91:
        case 92:
        case 93:
        case 94:
          return `template4`

        default:
          return `template1`
      }
    },
    switchAll() {
      const switchValue = Object.values(this.dropDownCloseList).some(item => item)

      if (Object.keys(this.moreGameInfo.playGroupList).length !== Object.values(this.dropDownCloseList).length) {
        Object.values(this.moreGameInfo.playGroupList).forEach(item => {
          this.dropDownCloseList[item.MarketlineId] = !switchValue
        })

        this.dropDownCloseList = { ...this.dropDownCloseList }
        return
      }

      this.dropDownCloseList = Object.keys(this.dropDownCloseList).reduce((prev, curr) => {
        return {
          ...prev,
          [curr]: !switchValue
        }
      }, {})
    },
    showVideo(val) {
      this.matchId = val
      this.hasVideo = false
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
    }
  }
}
</script>

<style lang="scss" scoped>
.sport{
  .sportHeader{
    height: 35%;
    background: url('~@/icons/png/detailBg.png') no-repeat;
    background-size: 100% 100%;
    .header{
      padding: 5px 0;
    }

    .backBtn{
      float: left;
      width: 35px;
      height: 35px;
      background: url('~@/icons/png/backWhite.png') no-repeat 8px center;
      background-size:cover;
    }

    .alliance{
      float: left;
      padding-left: 5px;
      font-size: 16px;
      line-height: 35px;
      font-weight: 600;
      color: #fff;
      text-align: center;
      white-space: nowrap;
      overflow-x: scroll;
      width: calc(100% - 85px);
    }

    .myBet{
      width: 30px;
      height: 30px;
      margin: 3px 10px;
      float:right;
      background: url('~@/icons/png/leftBar/i-oddsBet.png') no-repeat center;
      background-size: cover;
    }
    .sportTeam{
      background: #fff;
      border-radius: 8px;
      width: 90%;
      height: 45%;
      margin: 0 auto;

      .teamImg{
        height: 55px;
        margin: 0  auto;
        display: block;
      }

      .team-wrap{
        float: left;
        width: 30%;
        padding: 10px 5px 5px;
        text-align: center;
        font-size: 12px;

        .text {
          white-space: nowrap;
          overflow: scroll;
          line-height: 25px;
        }
      }

      .score{
        float: left;
        height: 100px;
        width: 40%;
        padding-top: 10px;
        text-align: center;
      }
      .battle{
        font-size: 32px;
        color: #dc1111;
        font-weight: 500;
        padding-top: 10px;
      }
      .sessions{
        margin-top: 3%;
        font-size: 12px;
        color: #5d6c76;
      }

    }
    .buttom{
      margin: 0 auto;
      position: relative;
      top: -3px;
      height: 30px;

      .sub-line{
        width: 88%;
        margin: 0 auto;
        height: 4px;
        border-radius: 5px;
        background:#e5e5e5;
      }
      .status {
        text-align: center;
        font-size: 14px;
        height: 20px;

        .text {
          border-radius: 10px;
          color: #FFF;
          background: #DC1111;
          white-space: nowrap;
          overflow-x: scroll;
          display: inline-block;
          padding: 3px 7px;
          margin-top: -8px;
        }
      }
    }
    .video-wrap {
      display: flex;
      justify-content: center;

      .video {
        background: rgba(255,255,255,.35);
        padding: 5px 10px;
        border-radius: 15px;
        // width: 20%;
        font-size: 12px;
        text-align: center;
        color: #FFF;
        margin: 5px 5px 10px;
      }
    }
  }
  .sportContainer{
    background: #f5f9fb;
    padding: 10px;
    .bet-controller{
      background: #fff;
      padding: 15px;
      border-radius: 8px;
      font-size: 13px;
    }
    .gameDetails{
      background: #fff;
      border-radius: 8px;
      font-size: 13px;
      margin-top: 8px;
      .gameTitle{
        padding: 12px 30px;

        .text {
          color: #5d6c76;
          font-size: 16px;
          white-space: nowrap;
          overflow-x: scroll;
          width: calc(100% - 30px);
          float: left;
        }
        .arrow{
          height: 20px;
          padding: 3px 0;
          width: auto;
          float:right;
          transition: all .4s;

          &.down {
            transform: rotate(180deg);
          }
        }
      }
    }
    .gameBigsmall{
      background: #FFF;
      border-radius: 8px;
      font-size: 13px;
    }

    .gameWinlose{
      background: #FFF;
      border-radius: 8px;
      font-size: 13px;
    }

    .gameCorrectScore{
      background: #FFF;
      border-radius: 8px;
      font-size: 13px;
    }

    .gameTeam{
      background: #FFF;
      border-radius: 8px;
      font-size: 13px;
    }
  }
}

</style>
