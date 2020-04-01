<template>
  <div class="body">
    <div class="title">
      <div class="item">{{$t('TEXT_HOME_S_TEAM')}}</div>
      <div class="item">{{$t('TEXT_DRAW')}}</div>
      <div class="item">{{$t('TEXT_AWAY_S_TEAM')}}</div>
    </div>
    <div
      class="oddsSet"
      v-for="(set,index) in betType.oddsList"
      :key="`${betType.info.typeName}${index}`"
    >
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,126)" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,128)" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,127)" />
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