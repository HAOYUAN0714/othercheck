<template>
  <div
    :class="{
      active: curBetSlip.some(item => item.odds_id === odds.WagerSelectionId),
      disable: !odds || +(marketlineStatus.MarketlineStatusId) === 2 || +(odds.Odds) === 0
    }"
    class="oddBox"
  >
    <div v-if="!odds || +(marketlineStatus.MarketlineStatusId) === 2 || +(odds.Odds) === 0 || !marketlineStatus || marketlineStatus.IsLocked" class="lock">
      <i class="fas fa-lock" />
    </div>
    <div v-else>
      <div
        :class="{
          change: oddsStatus === 'up' || oddsStatus === 'down',
          'no-cap': !odds.Handicap && odds.Handicap!== 0
        }"
        class="black"
        @click="clickOdds"
      >
        {{ parseOdds(odds) }}
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import yaboliveMixin from '@/utils/yaboliveMixin'
export default {
  mixins: [yaboliveMixin],
  props: {
    marketlineStatus: {
      type: Object,
      default: () => ({})
    },
    odds: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      oddsStatus: '',
      timeout: ''
    }
  },
  computed: {
    ...mapGetters({
      curBetSlip: 'getBetSlip'
    })
  },
  watch: {
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
  beforeDestroy() {
    clearTimeout(this.timeout)
  },
  methods: {
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
    }
  }
}
</script>

<style lang='scss' scoped>
.up {
  color: #44C38A;
  font-weight: bold;
}
.down {
  color: #E54242;
  font-weight: bold;
}
.black {
  font-size: 14px;
  width: 40%;
  display: inline-block;
  margin: 2px 0px;
  &:active{
    color: white;
    background: #dc9d3d;
  }
}
</style>
