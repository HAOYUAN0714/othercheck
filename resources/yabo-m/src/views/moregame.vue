<template>
  <div class="more-game">
    <div class="title">
      <div class="inner left" @click="back()"> ←</div>
      <div class="inner right">
        <span class="orange">{{ tran[detail.period] }}</span>
        {{ tran[detail.sport] }}
        <br>
        {{ tranLeague[detail.league] }}
      </div>
    </div>
    <div class="clear" />
    <div v-if="detail.period === 'live'" class="live-score">
      <div class="time">
        <span class="timer">
          {{ dataGame[gameId].time }}
        </span>
        <span class="text">
          <!-- {{ tran[detail.period] }} -->
          {{ dataGame[gameId].status }}
        </span>
      </div>
      <div class="score">
        <div class="content">
          <span class="team-name">{{ dataGame[gameId].team1 }}</span>
          <span class="team-score-home">{{ dataGame[gameId].team1score }}</span>
          <span class="team-score-away">{{ dataGame[gameId].team2score }}</span>
          <span class="team-name">{{ dataGame[gameId].team2 }}</span>
        </div>
      </div>
    </div>
    <div v-if="detail.period !== 'live'" class="normal">
      <div class="content">
        <span class="team-name">{{ dataGame[gameId].team1 }}</span>
        <span class="orange">v</span>
        <span class="team-name">{{ dataGame[gameId].team2 }}</span>
        <br>
        <span class="time">30/11 20:30</span>
      </div>
    </div>
    <div class="clear" />

    <div class="play-display">
      <div class="play-title">
        讓球
      </div>
      <div v-if="oddsList[1]">
        <div v-if="oddsList[1].h || oddsList[1].c">
          <div class="inner">
            <div class="option">{{ dataGame[gameId].team1 }}</div>
            <div class="odds" @click="actionBet(oddsList[1].h)">{{ dataOdds[oddsList[1].h].odd }}</div>
            <div class="cap">{{ dataOdds[oddsList[1].h].cap }}</div>
          </div>
          <div class="inner">
            <div class="option">{{ dataGame[gameId].team2 }}</div>
            <div class="odds" @click="actionBet(oddsList[1].c)">{{ dataOdds[oddsList[1].c].odd }}</div>
            <div class="cap">{{ dataOdds[oddsList[1].c].cap }}</div>
          </div>
        </div>
        <div v-if="oddsList[1].h2 || oddsList[1].c2">
          <div class="inner">
            <div class="option">{{ dataGame[gameId].team1 }}</div>
            <div class="odds" @click="actionBet(oddsList[1].h2)">{{ dataOdds[oddsList[1].h2].odd }}</div>
            <div class="cap">{{ dataOdds[oddsList[1].h2].cap }}</div>
          </div>
          <div class="inner">
            <div class="option">{{ dataGame[gameId].team2 }}</div>
            <div class="odds" @click="actionBet(oddsList[1].c2)">{{ dataOdds[oddsList[1].c2].odd }}</div>
            <div class="cap">{{ dataOdds[oddsList[1].c2].cap }}</div>
          </div>
        </div>
        <div v-if="oddsList[1].h3 || oddsList[1].c3">
          <div class="inner">
            <div class="option">{{ dataGame[gameId].team1 }}</div>
            <div class="odds" @click="actionBet(oddsList[1].h3)">{{ dataOdds[oddsList[1].h3].odd }}</div>
            <div class="cap">{{ dataOdds[oddsList[1].h3].cap }}</div>
          </div>
          <div class="inner">
            <div class="option">{{ dataGame[gameId].team2 }}</div>
            <div class="odds" @click="actionBet(oddsList[1].c3)">{{ dataOdds[oddsList[1].c3].odd }}</div>
            <div class="cap">{{ dataOdds[oddsList[1].c3].cap }}</div>
          </div>
        </div>
        <div v-if="oddsList[1].h4 || oddsList[1].c4">
          <div class="inner">
            <div class="option">{{ dataGame[gameId].team1 }}</div>
            <div class="odds" @click="actionBet(oddsList[1].h4)">{{ dataOdds[oddsList[1].h4].odd }}</div>
            <div class="cap">{{ dataOdds[oddsList[1].h4].cap }}</div>
          </div>
          <div class="inner">
            <div class="option">{{ dataGame[gameId].team2 }}</div>
            <div class="odds" @click="actionBet(oddsList[1].c4)">{{ dataOdds[oddsList[1].c4].odd }}</div>
            <div class="cap">{{ dataOdds[oddsList[1].c4].cap }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="play-display">
      <div class="play-title">
        大小
      </div>
      <div v-if="oddsList[2]">
        <div v-if="oddsList[2].h || oddsList[2].c">
          <div class="inner">
            <div class="option">{{ dataGame[gameId].team1 }}</div>
            <div class="odds" @click="actionBet(oddsList[2].h)">{{ dataOdds[oddsList[2].h].odd }}</div>
            <div class="cap">{{ dataOdds[oddsList[2].h].cap }}</div>
          </div>
          <div class="inner">
            <div class="option">{{ dataGame[gameId].team2 }}</div>
            <div class="odds" @click="actionBet(oddsList[2].c)">{{ dataOdds[oddsList[2].c].odd }}</div>
            <div class="cap">{{ dataOdds[oddsList[2].c].cap }}</div>
          </div>
        </div>
        <div v-if="oddsList[2].h2 || oddsList[2].c2">
          <div class="inner">
            <div class="option">{{ dataGame[gameId].team1 }}</div>
            <div class="odds" @click="actionBet(oddsList[2].h2)">{{ dataOdds[oddsList[2].h2].odd }}</div>
            <div class="cap">{{ dataOdds[oddsList[2].h2].cap }}</div>
          </div>
          <div class="inner">
            <div class="option">{{ dataGame[gameId].team2 }}</div>
            <div class="odds" @click="actionBet(oddsList[2].c2)">{{ dataOdds[oddsList[2].c2].odd }}</div>
            <div class="cap">{{ dataOdds[oddsList[2].c2].cap }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="play-display">
      <div class="play-title">
        獨贏
      </div>
      <div v-if="oddsList[3]">
        <div>
          <div class="inner">
            <div class="option">{{ dataGame[gameId].team1 }}</div>
            <div class="odds" @click="actionBet(oddsList[3].h)">{{ dataOdds[oddsList[3].h].odd }}</div>
          </div>
          <div class="inner">
            <div class="option">{{ dataGame[gameId].team2 }}</div>
            <div class="odds" @click="actionBet(oddsList[3].c)">{{ dataOdds[oddsList[3].c].odd }}</div>
          </div>
          <div class="inner">
            <div class="option">和局</div>
            <div class="odds" @click="actionBet(oddsList[3].d)">{{ dataOdds[oddsList[3].d].odd }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- game: {{ dataGame[gameId] }}<hr>
    oddsList:{{ oddsList }}<hr> -->

  </div></template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
  components: {
  },
  data() {
    return {
      gameId: 'G1'
    }
  },
  computed: {
    ...mapGetters([
      'name',
      'mainStruct',
      'tranLeague',
      'dataGame',
      'dataOdds',
      'betList',
      'betSlip',
      'tran'
    ]),
    detail() {
      return this.dataGame[this.gameId].parent
    },
    oddsList() {
      const { period, sport, league } = this.dataGame[this.gameId].parent
      return this.mainStruct[period][sport][league][this.gameId]
    }

  },
  created() {
  },
  mounted() {
    this.gameId = this.$route.params.gameid
  },
  methods: {
    ...mapActions({
      goBet: 'ball/appendBet',
      switchDisplay: 'ball/toggleBetSlip'
    }),
    actionBet(id) {
      this.goBet(id)
      this.switchDisplay(true)
    },
    test() {
      console.log('123')
    },
    back() {
      this.$router.go(-1) // 返回上一层
    }
  }
}
</script>
<style lang="scss" scoped>
.more-game {
  -webkit-font-smoothing:auto;
  .clear {
    clear: both
  }
  .title {
    padding-left: 10px;
    height: 70px;
    background: #32a6de;
    color: white;
    .inner {
      float: left,
    }
    .left {
      line-height: 70px;
      font-size: 30px;
      margin-right: -13px;
    }
    .right {
      padding-left: 15px;
      background: #32a6de;
      font-size: 15px;
      font-weight: 350;
      line-height: 20px;
      padding-top: 14px;
      .orange {
        color: #eca73d;
      }
    }
  }
  .normal {
    padding: 4px;
    font-size: 13px;
    .orange {
      color: #eca73d;
    }

    .content {
      padding-left: 9px;
      padding-top: 11px;
      height: 60px;
      line-height: 20px;
      background: black;
      color: white;
    }
  }
  .live-score {
    // height: 117px;
    padding: 4px;
    .time {
      height: 25px;
      color: #32a6de;
      line-height: 25px;
      font-size: 13px;
      .text {
        float: right;
      }
      .timer {
        color: black;
        margin-left: 8px;
        float: right;
      }
    }
    .score {
      height: 74px;
      line-height: 74px;
      font-size: 15px;
      background: black;
      .content {
        text-align: center;
        .team-name {
          color: white
        }
        .team-score-home {
          margin-left: 5px;
          color: #32a6de
        }
        .team-score-away {
          margin-left: 5px;
          margin-right: 5px;
          color: #eca73d
        }
      }

    }
  }
  .play-display {
    .play-title {
      padding-left: 20px;
      line-height: 55px;
      height: 55px;
      color: #b4b4b4;
      background: #003a54;
    }
    .inner {
      height: 55px;
      font-size: 15px;
      line-height: 41px;
      padding: 6px 10px 6px 8px;
      border-bottom: #c9c6cd 1px solid;
      .cap {
        float: right;
        color: #32a6de;
        margin-right: 7px;
      }
      .odds {
        height: 100%;
        float: right;
        color: #0a2900;
        border: 1px solid #c9c6cd;
        border-radius: 3px;
        text-align: center;
        width: 46px;
      }
      .option {
        float: left;
        color: #003075;
      }
    }

  }
}
</style>
