<template>
  <div>
    <div
      v-for="(playType, playKey) in oddsData"
      :key="playKey"
      class="play-type-outer"
    >
      <div class="clearfix">
        <div class="title">{{ teamName[0] }}</div>
        <div
          v-for="(wager, wagerKey) in sortPlayType(playType.WagerSelections.filter(item => homePlayType.includes(item.SelectionId)))"
          :key="wagerKey"
          class="playType"
        >
          <div
            class="odds-wrap"
          >
            <span v-if="+(playType.MarketLineId) !== 2 && +(wager.Odds) > 0 && !playType.IsLocked" class="selection-name">{{ wager.SelectionNames }}</span>
            <span class="odds-outer">
              <odds
                :marketline="playType"
                :odds="wager"
                :teams="teamName"
              />
            </span>
          </div>
        </div>
      </div>
      <div class="clearfix">
        <div class="title">{{ $t('S_DRAW') }}</div>
        <div
          v-for="(wager, wagerKey) in sortPlayType(playType.WagerSelections.filter(item => drawPlayType.includes(item.SelectionId)))"
          :key="wagerKey"
          class="draw-playType"
        >
          <div
            class="odds-wrap"
          >
            <span v-if="+(playType.MarketLineId) !== 2 && +(wager.Odds) > 0 && !playType.IsLocked" class="selection-name">{{ wager.SelectionNames }}</span>
            <span class="odds-outer">
              <odds
                :marketline="playType"
                :odds="wager"
                :teams="teamName"
              />
            </span>
          </div>
        </div>
      </div>
      <div class="clearfix">
        <div class="title">{{ teamName[1] }}</div>
        <div
          v-for="(wager, wagerKey) in sortPlayType(playType.WagerSelections.filter(item => awayPlayType.includes(item.SelectionId)))"
          :key="wagerKey"
          class="playType"
        >
          <div
            class="odds-wrap"
          >
            <span v-if="+(playType.MarketLineId) !== 2 && +(wager.Odds) > 0 && !playType.IsLocked" class="selection-name">{{ wager.SelectionNames }}</span>
            <span class="odds-outer">
              <odds
                :marketline="playType"
                :odds="wager"
                :teams="teamName"
              />
            </span>
          </div>
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
    betFunction: {
      type: Function,
      default: () => ({})
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
    return {
      homePlayType: [227, 229, 231, 233, 235],
      awayPlayType: [228, 230, 232, 234, 236],
      drawPlayType: [237]
    }
  },
  computed: {
    ...mapGetters({})
  },
  methods: {
    ...mapActions({}),
    sortPlayType(val) {
      const temp = [...val]
      return temp.sort((a, b) => a.SelectionId - b.SelectionId)
    }
  }
}
</script>

<style lang="scss" scoped>
.play-type-outer {
  padding: 10px;
  font-size: 14px;
  .title {
    margin: 5px 10px;
    text-align: center;
  }
  .playType {
    background-color: #FCFCFC;
    float: left;
    width: 48%;
    margin-left: 2%;
    padding: 12px 0;
    .odds-wrap {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 10px;
      .selection-name {
        color: #A7A7A7;
      }
    }
  }
  .draw-playType {
    background-color: #FCFCFC;
    margin-left: 2%;
    padding: 12px 0;
    .odds-wrap {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 10px;
      .selection-name {
        color: #A7A7A7;
      }
    }
  }
}
</style>
