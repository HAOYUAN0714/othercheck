<template>
  <div class="oddsWrap" v-if="oddsSet">
    <!--開盤 還有不為０-->
    <div class="hdc">
      <span class="hidden">{{oddsSet.Handicap}}</span>
      <span>{{$_parseHandicap(oddsSet)}}</span>
    </div>
    <div
      @click="$_placeBets(oddsSet)"
      :class="[oddsSet.status,{ 'active':$_selectedWager(oddsSet) }]"
      class="odds"
      v-if="oddsSet.MarketlineStatusId==1 && oddsSet.IsLocked== false && oddsSet.Odds!=0 "
    >{{$_parseOdds(oddsSet)}}</div>
    <div v-else class="locked">
      <i class="el-icon-lock" />
    </div>
  </div>
</template>
<script>
import oddsItemMixin from "./oddsItemMixin";
export default {
  mixins: [oddsItemMixin],
  data() {
    return {
      clearDiffClock: 0
    };
  },
  methods: {
    $_parseHandicap(oddsSet) {
      if (oddsSet.BetTypeId != 1 && oddsSet.BetTypeId != 2) return;
      let Handicap = oddsSet.Handicap;
      if (Handicap == null) return;
      const selectionId = oddsSet.SelectionId;
      const sportId = oddsSet.SportId;
      let hdc = Handicap < 0 ? Handicap * -1 : Handicap;
      let mod = hdc % 0.5;
      let returnVal = `${hdc - mod}/${hdc + mod}`;
      if (sportId == 2 && (selectionId == 3 || selectionId == 4)) {
        //如果是籃球的大小
        if (mod == 0) {
          //如果是5的整數
          return hdc;
        }
        return returnVal;
      }
      if (Handicap < 0) {
        if (selectionId == 2) {
          if (mod == 0) {
            //如果是5的整數
            return hdc;
          }
          return returnVal;
        }
      }
      if (Handicap > 0) {
        if (selectionId == 1 || selectionId == 3) {
          if (mod == 0) {
            //如果是5的整數
            return hdc;
          }
          return returnVal;
        }
      }
      if (Handicap == 0) {
        if (selectionId == 1 || selectionId == 3) {
          if (mod == 0) {
            //如果是5的整數
            return hdc;
          }
          return returnVal;
        }
      }
    }
  },
  watch: {
    oddsSet(newSet, oldSet) {
      if (newSet.Odds > oldSet.Odds) {
        this.oddsSet["status"] = "up";
      }
      if (newSet.Odds < oldSet.Odds) {
        this.oddsSet["status"] = "down";
      }
      //*晚點再看
      clearTimeout(this.clearDiffClock);
      var self = this;
      // this.clearDiffClock = setTimeout(() => {
      //   delete self.oddsSet["status"];
      // }, 5000);
    }
  }
};
</script>

<style lang="scss" src="@/scss/router/yabo/main/oddsTable.scss"></style>
<style lang="scss">
.locked {
  text-align: center;
}
.hidden {
  display: none;
  color: green;
}
.odds {
  &:hover {
    background: var(--secondary) !important;
  }
  &.up {
    //@at-rootbackground: green;
    animation: oddUp 5s;
  }
  &.down {
    //background: red;
    animation: oddDown 5s;
  }
}
</style>