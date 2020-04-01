<template>
  <div class="wager" :class="{empty:oddsSet.Odds=='ZERO'}">
    <div class="name" :title="parseSpecifiers">{{parseSpecifiers}}</div>

    <div class="hdc">
      <span>{{$_parseHandicap(oddsSet)}}</span>
    </div>

    <!--如果關盤，或是賠率為0則顯示鎖頭-->
    <div
      class="odds"
      v-if="(oddsSet.MarketlineStatusId==1 && oddsSet.IsLocked==false && oddsSet.Odds!=0)"
      :class="[oddsSet.status,{ 'active':$_selectedWager(oddsSet) }]"
      @click="$_placeBets(oddsSet)"
    >{{$_parseOdds(oddsSet)}}</div>
    <div v-else class="locked">
      <i class="el-icon-lock" />
    </div>
  </div>
</template>
<script>
import { mapState } from "vuex";
import oddsItemMixin from "../components/oddsItemMixin";
export default {
  mixins: [oddsItemMixin],
  props: {
    oddsSet: {
      type: Object,
      default: () => {}
    }
  },
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
      if (Handicap == 0) {
        return 0;
      }
      if (selectionId == 3 || selectionId == 4) {
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
            return "-" + hdc;
          }
          return "-" + returnVal;
        }
        if (selectionId == 1) {
          if (mod == 0) {
            //如果是5的整數
            return hdc;
          }
          return returnVal;
        }
      }
      if (Handicap > 0) {
        if (selectionId == 1) {
          if (mod == 0) {
            //如果是5的整數
            return "-" + hdc;
          }
          return "-" + returnVal;
        }
        if (selectionId == 2) {
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
      // clearTimeout(this.clearDiffClock);
      // var self = this;
      // this.clearDiffClock = setTimeout(() => {
      //   self.oddsSet["status"] = "";
      // }, 5000);
    }
  },
  computed: {
    ...mapState({
      wager: state => state.wager.wager
    }),
    parseSpecifiers() {
      const { oddsSet } = this;
      if (
        oddsSet.BetTypeId === 1 ||
        oddsSet.BetTypeId === 3 ||
        oddsSet.BetTypeId === 4
      ) {
        if (
          oddsSet.SelectionId === 1 ||
          oddsSet.SelectionId === 5 ||
          oddsSet.SelectionId === 8
        ) {
          return oddsSet.HomeTeam;
        }
        if (
          oddsSet.SelectionId === 2 ||
          oddsSet.SelectionId === 6 ||
          oddsSet.SelectionId === 9
        ) {
          return oddsSet.AwayTeam;
        }
        return this.$t("TEXT_DRAW");
      }
      if (oddsSet.BetTypeId == 81) {
        const score = oddsSet.Specifiers.split("=")[1];
        return `${oddsSet.SelectionName}先得${score}分`;
      }
      if (oddsSet.BetTypeId === 35) {
        const num = (oddsSet.Specifiers || "").split("=")[1];
        const [scoreX, scoreY] = num.split(":");
        return oddsSet.SelectionName.replace("{x-y}", scoreX - scoreY).replace(
          "{y-x}",
          `${scoreY - scoreX > 0 ? "+" : ""}${scoreY - scoreX}`
        );
      }
      if (oddsSet.BetTypeId === 11) {
        return oddsSet.OutrightTeamName;
      }
      const specifiers = oddsSet.Specifiers;
      if (!specifiers || specifiers === "") {
        return oddsSet.SelectionName;
      }
      let selectionName = oddsSet.SelectionName;
      const regex = /[^&=?]+=[^&#]*/g;
      const target = specifiers.match(regex);
      if (target) {
        target.forEach((item, index) => {
          const [key, value] = item.split("=");
          selectionName = selectionName.replace(`{${key}}`, value);
        });
      }
      return selectionName;
    }
  }
};
</script>
<style lang="scss">
@import "@/scss/common/mixin";
.wager {
  &.empty {
    visibility: hidden !important;
  }
  .name {
    @include overFlowDot();
  }
}
.locked {
  margin-left: auto;
  text-align: center;
  min-width: 30px;
  max-width: 60px;
}
.odds {
  &.active {
    background: var(--secondary);
    color: var(--textColor);
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