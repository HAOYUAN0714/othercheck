<template>
  <div>
    <div
      v-for="(playType, playKey) in oddsData"
      :key="playKey"
    >
      <div
        v-for="(oddsInner, oddsKey) in sortPlayType(playType.WagerSelections)"
        :key="`odds-table-${oddsKey}`"
        class="table clearfix"
      >
        <!-- templete1: playType :{{ playType }} -->
        <!-- templaet1: competition: {{ competition }} -->
        <div class="team-name">{{ showTeamName(oddsInner) }}</div>
        <div class="odds-wrap">
          <span class="cap">{{ formatCap(oddsInner, playType) }}</span>
          <div class="odds-outer" @click="betFunction(playType, oddsInner)">
            <odds
              :marketline="playType"
              :odds="oddsInner"
              :teams="teamName"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
import odds from '../odds/moreGameOdds'
export default {
  components: {
    odds
  },
  props: {
    games: {
      type: Object,
      default: () => ({})
    },
    betFunction: {
      type: Function,
      default: () => ({})
    },
    oddsData: {
      type: Array,
      default: () => []
    },
    teamName: {
      type: Array,
      default: () => []
    },
    betTypeId: {
      type: Number,
      default: 0
    },
    competition: {
      type: Array,
      default: () => []
    }
  },
  computed: {
    ...mapGetters({
      curBetSlip: 'getBetSlip'
    })
  },
  methods: {
    // ...mapActions({
    //   setBet: 'ball/actionBet'
    // }),
    // clickOdds(playId, betId, games, sportId) {
    //   // console.log(playId, betId, games, sportId)
    //   this.setBet({
    //     bet: this.getBetTypeData(games.MarketLines, playId, betId),
    //     playType: this.getMarketLineId(games.MarketLines, playId),
    //     games,
    //     sportId: +(sportId)
    //   })
    // },
    showTeamName(val) {
      if (this.betTypeId === 1 || this.betTypeId === 3 || this.betTypeId === 4) {
        if (val.SelectionId === 1 || val.SelectionId === 5 || val.SelectionId === 8) {
          return this.teamName[0]
        }
        if (val.SelectionId === 2 || val.SelectionId === 6 || val.SelectionId === 9) {
          return this.teamName[1]
        }
        return this.$t('S_DRAW')
      }
      if (val.BetTypeId === 81) {
        const score = val.Specifiers.split('=')[1]
        return `${val.SelectionName}先得${score}分`
      }
      const specifiers = val.Specifiers
      if (!specifiers || specifiers === '') {
        return val.SelectionName
      }
      let selectionName = val.SelectionName
      const regex = /[^&=?]+=[^&#]*/g
      const target = specifiers.match(regex)
      if (target) {
        target.forEach((item, index) => {
          const [key, value] = item.split('=')
          selectionName = selectionName.replace(`{${key}}`, value)
        })
      }
      return selectionName
    },
    sortPlayType(val) {
      const temp = [...val]
      return temp.sort((a, b) => {
        a.SelectionId - b.SelectionId
      })
    },
    formatCap(val) {
      if (!val.Handicap && val.Handicap !== 0) {
        return
      }
      const mod = val.Handicap % 0.5
      const selectionId = +(val.SelectionId)
      if (mod === 0) {
        if (this.betTypeId === 1 && selectionId === 1) {
          if (val.Handicap > 0) {
            return `-${val.Handicap}`
          }
          return `${val.Handicap === 0 ? 0 : +Math.abs(val.Handicap)}`
        }
        if (this.betTypeId === 1 && selectionId === 2) {
          if (val.Handicap > 0) {
            return `+${val.Handicap}`
          }
          return `${val.Handicap === 0 ? 0 : -Math.abs(val.Handicap)}`
        }
        return val.Handicap
      }
      if (this.betTypeId === 1 && selectionId === 1) {
        if (val.Handicap > 0) {
          return `-${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
        }
        return `+${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
      }
      if (this.betTypeId === 1 && selectionId === 2) {
        if (val.Handicap > 0) {
          return `+${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
        }
        return `-${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
      }
      if (this.betTypeId === 2) {
        return `${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
      }
      return val.Handicap
    }
  }
}
</script>
<style lang="scss" scoped>
.table {
  border-bottom: 1px solid #C9C6CD;
  padding: 6px 10px;
  font-size: 15px;
  .team-name {
    float: left;
    width: 50%;
    padding-top: 12px;
    color: #003075;
  }
  .odds-wrap {
    float: left;
    width: 50%;
    text-align: right;
    font-size: 15px;
    .cap {
      margin-right: 6px;
      color: #8c5320;
    }
    .odds-outer {
      display: inline-block;
    }
  }
}
.odds {
  background-color: var(--content_odds_bg);
  display: inline-block;
  width: 55px;
  //width: 32%;
  padding: 10px 6px 9px;
  border-radius: 3px;
  border: 1px solid #C9C6CD;
  font-weight: bold;
  text-align: center;
}
</style>
