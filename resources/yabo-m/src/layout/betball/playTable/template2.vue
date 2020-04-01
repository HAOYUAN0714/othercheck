<template>
  <div class="gameBigsmall">
    <div class="gameOdds" :style="{ height: (dropDownClose && `${playGroup.WagerSelections.length * 22}px`) || `0px` }">
      <div class="odds-wrap clearfix">
        <div
          v-for="(oddsInfo, index) in playGroup.WagerSelections"
          :key="`oddsInfo-${index}`"
          class="odds-info"
        >
          <div class="score">
            {{ oddsInfo.SelectionName }} {{ oddsInfo.Handicap }}
          </div>
          <div class="odds">
            {{ oddsInfo.Odds }}
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
  }
}
</script>

<style lang="scss" scoped>
.gameOdds{
  font-size: 14px;
  border-top: 1px solid #EEE;
  padding: 0 15px;
  overflow: hidden;
  height: 0px;
  transition: all .5s;

  .odds-wrap {
    .odds-info {
      width: 50%;
      float: left;
      padding: 0 10px;
      height: 44px;
      line-height: 44px;
      border-left: 1px solid #EEE;

      .score{
        color: #5d6c76;
        float: left;
      }
      .odds{
        float: right;
      }

      &:nth-child(2n-1) {
        border: none;
      }
    }
  }
}
</style>
