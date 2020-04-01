<template>
  <div class="body bodan">
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
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,17)" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,12)" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,13)" />

      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,22)" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,18)" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,14)" />

      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,23)" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,24)" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,19)" />

      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,27)" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,30)" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,15)" />

      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,28)" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,36)" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,20)" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,29)" />
      <morePlayItem :oddsSet="{Odds:'ZERO'}" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,25)" />

      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,32)" />
      <morePlayItem :oddsSet="{Odds:'ZERO'}" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,16)" />

      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,33)" />
      <morePlayItem :oddsSet="{Odds:'ZERO'}" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,21)" />

      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,34)" />
      <morePlayItem :oddsSet="{Odds:'ZERO'}" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,26)" />

      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,35)" />
      <morePlayItem :oddsSet="{Odds:'ZERO'}" />
      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,31)" />

      <morePlayItem :oddsSet="$_getOddsBySelectionId(set,439)" />
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