<template>
  <span
    :class="{
      active: curBetSlip.some(item => item.odds_id === odds.WagerSelectionId),
      disable: +(marketline.MarketLineId) === 2 || +(odds.Odds) === 0 || marketline.IsLocked,
      up: oddsStatus === 'up',
      down: oddsStatus === 'down'
    }"
    class="odds"
  >
    <i v-if="+(marketline.MarketLineId) === 2 || +(odds.Odds) === 0 || marketline.IsLocked" class="fas fa-lock" />
    <span v-else>{{ parseOdds(odds) }}</span>
  </span>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  props: {
    marketline: {
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
  }
}
</script>

<style lang="scss" scoped>

.odds-wrap {
  .active {
    background-color: #ffcb46;
    color: #222;
    border-color: #502a08;
  }
  .disable {
    background-color: var(--content_odds_bg);
    // height: 38px;
    color: #BBBABA;
  }
  .up {
    background-color: #44C38A;
    color: #FFF;
    border-color: #44C38A;
    transition: background-color .3s linear;
  }
  .down {
    background-color: #E54242;
    color: #FFF;
    border-color: #E54242;
    transition: background-color .3s linear;
  }
}
</style>
