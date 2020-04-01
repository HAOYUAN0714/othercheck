<template>
  <div class="gameWinlose">
    <div class="gameOdds" :style="{ height: (dropDownClose && `${playGroup.WagerSelections.length * 44 / 3}px`) || `0px` }">
      <div class="odds-wrap">
        <div class="odds-wrap clearfix">
          <div
            v-for="(oddsInfo, index) in playGroup.WagerSelections"
            :key="`oddsInfo-${index}`"
            class="odds-info"
          >
            <div class="score">
              {{ scoreTable[index] }}
            </div>
            <div class="odds">
              {{ oddsInfo.Odds }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    moreGameInfo: {
      type: Object,
      required: true
    },
    playGroup: {
      type: Object,
      required: true
    },
    dropDownClose: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      scoreTable: {
        0: '1',
        1: 'x',
        2: '2'
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.gameOdds{
  font-size: 14px;
  border-top: 1px solid #EEE;
  overflow: hidden;
  height: 0px;
  transition: all .5s;

  .odds-wrap {
    .odds-info {
      width: 33.333%;
      float: left;
      padding: 0 22px;
      border-left: 1px solid #EEE;
      line-height: 44px;
      height: 44px;

      .score{
        color: #5d6c76;
        float: left;
      }
      .odds{
        color: #121317;
        font-weight: 700;
        float: right;
      }

      &:first-child {
        border: none;
      }
    }
  }
}
</style>
