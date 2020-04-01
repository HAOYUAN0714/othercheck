<template>
  <div class="betSlip">
    <!--成功投注-->
    <div class="matchInfoWrap" v-for="(wagerItem,eventId,index) in successWagerList" :key="eventId">
      <div class="infoTab">
        <div class="info">
          <span>i</span>
          <betInfoBlock class="infoDrop" :betInfo="wagerItem" />
        </div>
        <div>
          <span
            v-if="wagerItem.PeriodId==1 || wagerItem.PeriodId==2 || wagerItem.PeriodId==3"
          >{{$t(periodConfig[wagerItem.PeriodId].textKey)}}-</span>
          <span v-if="liveWager(wagerItem)">{{$t('TEXT_LIVE')}}-</span>
          <span>{{commonParseBetTypeName(wagerItem)}}</span>
          <span
            v-if="wagerItem.Market==3 && wagerItem.HomeScore!=null && wagerItem.SportId==1 && wagerItem.BetTypeId==1"
          >({{wagerItem.HomeScore}}:{{wagerItem.AwayScore}})</span>
        </div>
      </div>
      <div class="infoTab">
        <span class="hdc">{{commonParseSpecifiers(wagerItem)}} {{$_parseHandicap(wagerItem)}}</span>
        @
        <span class="odds">{{wagerItem.Odds}}</span>
      </div>
      <div
        class="infoTab"
        v-if="wagerItem.BetTypeId!=11"
      >{{wagerItem.HomeTeam}} v {{wagerItem.AwayTeam}}</div>
      <div class="infoTab" v-else>{{wagerItem.OutrightEventName}}</div>
      <div class="betInfo" v-if="index=== Object.keys(successWagerList).length - 1">
        <div class="text">
          <div class="left">{{$t('TEXT_BETTING_AMOUNT')}}</div>
          <div class="right">{{$t('TEXT_WINNABLE_AMOUNT')}}</div>
        </div>
        <div class="value">
          <div class="left">{{successBetOptions.stakeAmount}}</div>
          <div class="right">{{successBetOptions.totalWinnableAmount}}</div>
        </div>
      </div>
      <div class="bottom" v-show="!isCombo">
        <div class="wagerId">
          <span>{{$t('TEXT_WAGER_NUMBER')}}:</span>
          <span>{{successBetOptions.WagerSelectionInfos[0].WagerId}}</span>
        </div>
      </div>
    </div>
    <!--串關模式下，要顯示所有串關單據-->
    <div class="moreComboSelection" v-if="isCombo">
      <div class="tab">{{$t('TEXT_MORE_COMBO_SELECTIONS')}}</div>
      <div
        class="moreComboWrap"
        v-for="combo in successBetOptions.WagerSelectionInfos"
        :key="combo.WagerId"
      >
        <div class="top">
          <span>{{$t(`TEXT_COMBO_${combo.ComboSelectionId}`)}}</span>
        </div>
        <div class="center">
          <div class="left">
            <div class="top">{{$t('TEXT_BETTING_AMOUNT')}}</div>
            <div class="bottom">
              <span>{{commonToFixed(combo.betAmount)}}</span>
              <span class="amount">X {{combo.wagerAmount}}</span>
            </div>
          </div>
          <div class="right">
            <div class="top">{{$t('TEXT_WINNABLE_AMOUNT')}}</div>
            <div class="bottom">{{combo.estimate}}</div>
          </div>
        </div>
        <div class="bottom">
          <div class="wagerId">
            <div>{{$t('TEXT_WAGER_NUMBER')}}:</div>
            <div>{{combo.WagerId}}</div>
          </div>
        </div>
      </div>
    </div>
    <!--所有投注金額以即可贏金額-->
    <div class="betInfo">
      <div class="infoTab">
        <span>{{$t('TEXT_ALL_BETS')}}</span>
        <div
          class="right orange"
        >{{ commonToCurrency(commonToFixed(successBetOptions.totalBet))}} RMB</div>
      </div>
      <div class="infoTab">
        <span>{{$t('TEXT_WINNABLE_AMOUNT')}}</span>
        <div
          class="right blue"
        >{{ commonToCurrency(commonToFixed(successBetOptions.totalWinnableAmount))}} RMB</div>
      </div>
    </div>

    <!--confirm tab-->
    <div class="confirmTab">
      <i class="el-icon-circle-check" />
      {{$t('TEXT_WAGER_CONFIRMED')}}
    </div>
    <div class="betButtons">
      <div @click="$_return" class="button bet">{{$t('TEXT_CONFIRM')}}</div>
      <!-- <div @click="$_return" class="button cancel">{{$t('TEXT_CANCEL')}}</div> -->
    </div>
  </div>
</template>
<script>
import { mapState } from "vuex";
import config from "@/config/config";
import betInfoBlock from "@/components/common/betInfoBlock";
export default {
  components: {
    betInfoBlock
  },
  props: {
    successWagerList: {
      type: Array,
      default: () => []
    },
    successBetOptions: {
      type: Object,
      default: () => {}
    }
  },
  computed: {
    ...mapState({
      isCombo: state => state.status.isCombo
    }),

    periodConfig() {
      return config.periodId;
    }
  },
  methods: {
    liveWager(wager) {
      const { isCombo } = this;
      if (isCombo) return false;
      if (!isCombo && wager.Market == 3) return true;
      return false;
    },
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
    },

    $_return() {
      this.$emit("return");
    }
  }
};
</script>

<style lang="scss" src="@/scss/router/yabo/navLeft/placingBet.scss"></style>