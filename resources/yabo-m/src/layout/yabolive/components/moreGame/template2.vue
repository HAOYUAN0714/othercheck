<template>
  <div>
    <div v-if="filterPlayType" class="team-name clearfix">
      <div class="team">{{ teamName[0] }}</div>
      <div class="team">{{ teamName[1] }}</div>
    </div>
    <div
      v-for="(playType, playKey) in oddsData"
      :key="playKey"
      class="playType clearfix"
    >
      <div
        v-for="(wager, wagerKey) in sortPlayType(playType.WagerSelections)"
        :key="wagerKey"
        class="odds-wrap"
      >
        <template v-if="+(playType.MarketLineId) !== 2 && +(wager.Odds) > 0 && !playType.IsLocked">
          <div v-if="wager.Handicap || wager.Handicap === 0" class="cap">{{ formatCap(wager) }}</div>
          <div v-if="!filterPlayType" class="selection-name">{{ wager.SelectionNames }}</div>
        </template>
        <div
          class="odds-outer"
          :class="{
            'no-cap': !wager.Handicap && wager.Handicap !== 0 && filterPlayType
          }"
        >
          <odds
            :marketline="playType"
            :odds="wager"
            :teams="teamName"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
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
    },
    betFunc: {
      type: Function,
      default: () => {}
    },
    sportId: {
      type: String,
      default: () => ''
    },
    iscombo: {
      type: Boolean,
      default: () => false
    }
  },
  data() {
    return {}
  },
  computed: {
    ...mapGetters({}),
    filterPlayType() {
      if (this.betTypeId === 7 || this.betTypeId === 14 || this.betTypeId === 24 || this.betTypeId === 25 || this.betTypeId === 31 || this.betTypeId === 32) {
        return false
      }
      return true
    }
  },
  methods: {
    ...mapActions({}),
    sortPlayType(val) {
      const temp = [...val]
      return temp.sort((a, b) => a.SelectionId - b.SelectionId)
    },
    formatCap(val) {
      if (val.Handicap === 0) {
        return 0
      }
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
.team-name {
  color: #b1b1b1;
  padding: 10px 0;
  font-size: 14px;
  .team {
    float: left;
    width: 50%;
    text-align: center;
  }
}
.playType {
  margin-bottom: 5px;
  .odds-wrap {
    background-color: #FCFCFC;
    float: left;
    width: 47%;
    padding: 10px 0;
    margin-left: 2%;
    text-align: center;
    font-size: 13px;
    .cap {
      float: left;
      width: 50%;
      color: #A7A7A7;
      text-align: right;
    }
    .odds-outer {
      float: left;
      width: 45%;
      margin-left: 5%;
      text-align: left;
      &.no-cap {
        width: 100%;
        text-align: center;
        margin-left: 0;
      }
    }
    .selection-name {
      float: left;
      width: 50%;
      padding-left: 10px;
      color: #A7A7A7;
      text-align: left;
    }
    .cap,
    .odds-outer,
    .selection-name {
      height: 20px;
      line-height: 20px;
    }
    .cap{
      float: left;
    }
    .odds-outer{
      float: right;
    }
  }
}
</style>
