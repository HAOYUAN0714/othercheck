<template>
  <div class="betInfoBlock">
    <div class="competitionName" v-if="betInfo.Competition">{{betInfo.Competition.CompetitionName}}</div>
    <div class="teamWrap" v-if="betInfo.BetTypeId!=11">{{betInfo.HomeTeam}} v {{betInfo.AwayTeam}}</div>
    <div class="date" v-if="!liveWager">{{commonGetFullDate(betInfo.EventDate)}}</div>
    <div class="date" v-else>
      {{$t('TEXT_LIVE')}}-
      <span>{{commonParseBetTypeName(betInfo)}}</span>
    </div>
  </div>
</template>
<script>
import { mapState, mapActions, mapMutations } from "vuex";
import moment from "moment";
export default {
  props: {
    betInfo: {
      type: Object,
      default: () => {}
    }
  },
  computed: {
    ...mapState({
      isCombo: state => state.status.isCombo
    }),
    liveWager() {
      const { isCombo, betInfo } = this;
      if (isCombo) return false;
      if (!isCombo && betInfo.Market == 3) return true;
      return false;
    }
  }
};
</script>
<style lang="scss" scoped>
.betInfoBlock {
  .competitionName {
    font-weight: 900;
  }
}
</style>
