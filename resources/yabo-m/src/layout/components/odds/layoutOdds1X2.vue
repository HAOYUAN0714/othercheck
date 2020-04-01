<template>
  <div
    :class="{
      active: curBetSlip.some(item => item.odds_id === odds.WagerSelectionId),
      disable: +(marketlineStatus) === 2 || +(odds.Odds) === 0,
      up: oddsStatus === 'up',
      down: oddsStatus === 'down'
    }"
    class="oddBox"
  >
    <div v-if="+(marketlineStatus.MarketlineStatusId) === 2 || +(odds.Odds) === 0 || !marketlineStatus || marketlineStatus.IsLocked" class="lock">
      <i class="fas fa-lock" />
    </div>
    <div v-else>
      <div v-if="+(odds.SelectionId) === 5">1</div>
      <div v-if="+(odds.SelectionId) === 6">2</div>
      <div v-if="+(odds.SelectionId) === 7">X</div>
      <div
        :class="{
          active: curBetSlip.some(item => item.odds_id === odds.WagerSelectionId)
        }"
        class="black"
      >
        {{ parseOdds(odds) }}
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
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
  }
}
</script>

<style lang='scss' scoped  src="@/styles/score.scss"></style>
