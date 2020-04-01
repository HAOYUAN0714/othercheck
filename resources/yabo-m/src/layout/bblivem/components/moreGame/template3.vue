<template>
  <div>
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
        <span v-if="+(playType.MarketLineId) !== 2 && +(wager.Odds) > 0 && !playType.IsLocked" class="selection-name">{{ wager.SelectionNames }}</span>
        <span
          class="odds-outer"
        >
          <odds
            :marketline="playType"
            :odds="wager"
            :teams="teamName"
          />
        </span>
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
    ...mapGetters({
      curBetSlip: 'getBetSlip'
    })
  },
  methods: {
    ...mapActions({}),
    sortPlayType(val) {
      const temp = [...val]
      return temp.map((item) => {
        if (item.SelectionId === 5 || item.SelectionId === 43 || item.SelectionId === 126 || item.SelectionId === 372) {
          return {
            ...item,
            pos: 1
          }
        }
        if (item.SelectionId === 6 || item.SelectionId === 44 || item.SelectionId === 127 || item.SelectionId === 373) {
          return {
            ...item,
            pos: 3
          }
        }
        if (item.SelectionId === 7 || item.SelectionId === 45 || item.SelectionId === 128 || item.SelectionId === 374) {
          return {
            ...item,
            pos: 2
          }
        }
      }).sort((a, b) => a.pos - b.pos)
    }
  }
}
</script>

<style lang="scss" scoped>
.playType {
  display: flex;
  padding: 10px 0;
  .odds-wrap {
    background-color: #FCFCFC;
    // float: left;
    display: flex;
    width: 30%;
    margin-left: 3%;
    padding: 5px 0;
    flex: 1;
    justify-content: space-around;
    align-items: center;
    font-size: 14px;
    .selection-name {
      color: #A7A7A7;
    }
  }
}

</style>
