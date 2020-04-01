<template>
  <div>
    <div class="header clearfix">
      <div class="title">{{ $t('S_HOME_WIN') }}</div>
      <div class="title">{{ $t('S_DRAW') }}</div>
      <div class="title">{{ $t('S_AWAY_WIN') }}</div>
    </div>
    <div
      v-for="(playType, playKey) in oddsData"
      :key="playKey"
      class="clearfix"
    >
      <div
        v-for="(odds, oddsKey) in sortOddsData(playType.WagerSelections)"
        :key="`odds-table-${oddsKey}`"
        class="table clearfix"
      >
        <div class="team-name">{{ showTeamName(odds) }}</div>
        <div class="odds-wrap">
          <div class="odds-outer" @click="betFunction(playType, odds)">
            <odds
              :marketline-status-id="playType.MarketlineStatusId"
              :odds="odds"
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
    oddsData: {
      type: Array,
      default: () => []
    },
    teamName: {
      type: Array,
      default: () => []
    },
    betFunction: {
      type: Function,
      default: () => ({})
    }
  },
  computed: {
    ...mapGetters({
      curBetSlip: 'getBetSlip'
    })
  },
  methods: {
    showTeamName(val) {
      const firstChar = val.SelectionName.indexOf('(')
      const lastChar = val.SelectionName.indexOf(')')
      if (firstChar > -1) {
        const num = (val.Specifiers || '').split('=')[1]
        const [scoreX, scoreY] = num.split(':')

        return val.SelectionName.substring(firstChar + 1, lastChar)
          .replace('{x-y}', scoreX - scoreY)
          .replace('{y-x}', scoreY - scoreX)
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
    sortOddsData(odds) {
      return odds.map((item) => {
        if (item.SelectionId === 126) {
          return {
            ...item,
            pos: 1
          }
        }
        if (item.SelectionId === 127) {
          return {
            ...item,
            pos: 3
          }
        }
        return {
          ...item,
          pos: 2
        }
      }).sort((a, b) => a.pos - b.pos)
    }
  }
}
</script>

<style lang="scss" scoped>

.header {
  background: #E6E6E6;
  color: #848484;
  font-size: 15px;
  .title {
    float: left;
    width: 33.33%;
    height: 34px;
    line-height: 34px;
    text-align: center;
  }
}

.table {
  float: left;
  width: 33.33%;
  border-bottom: 1px solid #C9C6CD;
  border-right: 1px solid #C9C6CD;
  padding: 6px 5px;
  font-size: 12px;
  .team-name {
    float: left;
    width: 45%;
    padding-top: 12px;
    color: #003075;
  }
  .odds-wrap {
    float: left;
    width: 55%;
    padding-right: 5px;
    text-align: right;
  }
}
.odds {
  background-color: var(--content_odds_bg);
  display: inline-block;
  width: 55%;
  padding: 10px 0px 9px;
  border-radius: 3px;
  border: 1px solid #C9C6CD;
  font-weight: bold;
  text-align: center;
}
</style>
