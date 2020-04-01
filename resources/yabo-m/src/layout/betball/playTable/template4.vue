<template>
  <div class="gameTeam">
    <!-- 每一個 odds-info 高 46px -->
    <div class="gameOdds" :style="{ height: (dropDownClose && `${playGroup.WagerSelections.length * 46}px`) || `0px` }">
      <div class="odds-wrap">
        <div
          v-for="(oddsInfo, index) in playGroup.WagerSelections"
          :key="`oddsInfo-${index}`"
          class="odds-info clearfix"
        >
          <div class="score">
            {{ oddsInfo.SelectionName }}
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
  height: 0;
  overflow: hidden;
  transition: all .5s;

  .odds-wrap {
    background: #F5F9FB;

    .odds-info {
      background-color: #FFF;
      padding: 0 10px;
      height: 44px;
      line-height: 44px;
      margin-bottom: 2px;
      padding: 0 20px;

      .score{
        color: #5d6c76;
        float: left;
      }
      .odds{
        float: right;
      }
    }
  }
}
</style>
