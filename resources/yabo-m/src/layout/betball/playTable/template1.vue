<template>
  <div class="gameOdds" :style="{ height: (dropDownClose && `${playGroup.WagerSelections.length * 22 + 44}px`) || `0px` }">
    <div class="team">
      <div class="gmHome">
        <div class="colorBlue" />
        <span>{{ moreGameInfo.homeName }}</span>
      </div>
      <div class="gmAway">
        <div class="colorRed" />
        <span>{{ moreGameInfo.awayName }}</span>
      </div>
    </div>
    <div class="odds-wrap clearfix">
      <div
        v-for="(oddsInfo, index) in playGroup.WagerSelections"
        :key="`oddsInfo-${index}`"
        class="odds-info"
      >
        <div class="score">
          {{ oddsInfo.Handicap === null ? getSectionName(oddsInfo.SelectionName, oddsInfo.Specifiers) : oddsInfo.Handicap }}
        </div>
        <div class="odds">
          {{ oddsInfo.Odds }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import mixin from '../mixin/playTable'

export default {
  mixins: [mixin],
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
  overflow: hidden;
  height: 0px;
  transition: all .5s;

  .team{
    display: flex;

    .gmHome, .gmAway{
      width: 50%;
      padding:5px 10px;
      height: 44px;
      line-height: 44px;
      white-space: nowrap;
      overflow-x: scroll;

      .colorBlue{
        width: 5px;
        height: 15px;
        background: blue;
        margin-right:10px;
        float: left;
        margin-top: 14px;
      }

      .colorRed{
        width: 5px;
        height: 15px;
        background: red;
        margin-right:10px;
        float: left;
        margin-top: 14px;
      }

      span{
        font-size: 14px;
        color: #5d6c76;
        font-weight: 400;
      }
    }
  }
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
