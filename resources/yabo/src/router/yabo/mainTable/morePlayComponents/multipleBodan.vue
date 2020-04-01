<template>
  <div class="body bodan">
    <div
      class="oddsSet"
      v-for="(set,index) in betType.oddsList"
      :key="`${betType.info.typeName}${index}`"
    >
      <div class="team">{{$t('TEXT_HOME_S_TEAM')}}</div>

      <morePlayItem style="flex:calc(100% / 3)" :oddsSet="$_getOddsBySelectionId(set,227)" />
      <morePlayItem style="flex:calc(100% / 3)" :oddsSet="$_getOddsBySelectionId(set,231)" />
      <morePlayItem style="flex:calc(100% / 3)" :oddsSet="$_getOddsBySelectionId(set,233)" />
      <morePlayItem style="flex:calc(100% / 3)" :oddsSet="$_getOddsBySelectionId(set,229)" />

      <morePlayItem style="flex:calc(100% / 1)" :oddsSet="$_getOddsBySelectionId(set,235)" />

      <div class="team">{{$t('TEXT_DRAW')}}</div>
      <morePlayItem style="flex:calc(100% / 1)" :oddsSet="$_getOddsBySelectionId(set,237)" />

      <div class="team">{{$t('TEXT_AWAY_S_TEAM')}}</div>
      <morePlayItem style="flex:calc(100% / 3)" :oddsSet="$_getOddsBySelectionId(set,228)" />
      <morePlayItem style="flex:calc(100% / 3)" :oddsSet="$_getOddsBySelectionId(set,230)" />
      <morePlayItem style="flex:calc(100% / 3)" :oddsSet="$_getOddsBySelectionId(set,232)" />
      <morePlayItem style="flex:calc(100% / 3)" :oddsSet="$_getOddsBySelectionId(set,234)" />
      <morePlayItem style="flex:calc(100% / 1)" :oddsSet="$_getOddsBySelectionId(set,236)" />
    </div>
  </div>
</template>
<script>
import morePlayItem from "./morePlayItem";
export default {
  components: {
    morePlayItem
  },
  props: {
    betType: {
      type: Object,
      default: () => {}
    }
  },
  methods: {
    $_getOddsBySelectionId(betList, selectionId) {
      for (var i = 0; i < betList.length; i++) {
        if (betList[i].SelectionId == selectionId) return betList[i];
      }
      return {
        Odds: ""
      };
    }
  }
};
</script>
<style lang="scss" src="@/scss/router/yabo/main/morePlays.scss"></style>