<template>
  <div class="betPage singlePage">
    <div class="header">{{$t('TEXT_UNSETTLED_BET')}}</div>
    <div class="hint">{{$t('TEXT_THIS_PAGE_WILL_SHOW_UNSETTLED_BETS')}}</div>

    <div class="betsTable">
      <div class="header">
        <div class="number">{{$t('TEXT_NUMBER')}}</div>
        <div class="details">{{$t('TEXT_BET_DETAILS')}}</div>
        <div class="type">{{$t('TEXT_BET_PLAY_TYPE')}}</div>
        <div class="option">{{$t('TEXT_OPTION')}}</div>
        <div class="money">{{$t('TEXT_BETTING_AMOUNT')}}</div>
        <div class="winlose">{{$t('TEXT_WIN_LOSE')}}</div>
        <div class="status">{{$t('TEXT_STATUS')}}</div>
      </div>
      <div class="body" v-if="wagerList.length!=0">
        <div class="wagerTable">
          <div class="wagerItem" v-for="(wager,index) in wagerList" :key="wager.WagerId">
            <div class="number">{{index+1}}</div>
            <div class="details">
              <div>{{commonGetFullDate(wager.WagerCreationDateTime)}}</div>
              <div>{{wager.WagerId}}</div>
            </div>
            <div class="type">
              <div
                class="inside"
                v-show="index==0"
                v-for="(wagerItem,index) in wager.WagerItemList"
                :key="index"
              >
                <div v-if="wager.WagerItemList.length==1">
                  <span v-if="wagerItem.Market==3">{{$t('TEXT_LIVE')}}-</span>
                  <span>{{commonParseBetTypeName(wagerItem)}}</span>
                </div>
                <!--如果是單注，只會顯示一個玩法-->
                <div v-else>{{$t(`TEXT_COMBO_${wager.ComboSelection}`)}}x{{wager.NoOfCombination}}</div>
                <div>{{$t(OddsConfig[wager.OddsType].textKey)}}</div>
              </div>
            </div>
            <div class="option">
              <div
                class="inside"
                :title="$t(sportList[wagerItem.SportId].textKey)"
                v-for="(wagerItem,index) in wager.WagerItemList"
                :key="index"
              >
                <div class="leagueName">{{wagerItem.CompetitionName}}</div>
                <div class="teams">
                  <span
                    v-if="wagerItem.BetTypeId!=11"
                  >{{wagerItem.HomeTeamName}} v {{wagerItem.AwayTeamName}}</span>
                  <span v-else>{{wagerItem.EventOutrightName}}</span>
                </div>
                <div class="timeSet">
                  <span class="period">{{$t(periodId[wagerItem.PeriodId].textKey)}}&nbsp</span>
                  <span class="live" v-if="wagerItem.Market==3">{{$t('TEXT_LIVE')}}&nbsp</span>
                  <span>{{commonParseBetTypeName(wagerItem)}}&nbsp</span>
                  <span
                    class="score"
                    v-if="wagerItem.WagerHomeTeamScore!=null && wagerItem.BetTypeId==1 && wagerItem.SportId==1"
                  >({{wagerItem.WagerHomeTeamScore}}:{{wagerItem.WagerAwayTeamScore}})</span>
                </div>
                <div v-show="wagerItem.Market==1" class="startDate">
                  {{$t('TEXT_MATCH_DATE')}}
                  {{commonGetFullDate(wager.WagerCreationDateTime)}}
                </div>
                <div class="bottom">
                  <span class="name">{{commonParseSpecifiers(wagerItem)}}</span>
                  <span
                    class="hdc"
                    v-if="wagerItem.BetTypeId==1 || wagerItem.BetTypeId==2"
                  >{{$_parseHandicap(wagerItem)}}</span>

                  <span>@</span>
                  <span class="oddsValue">{{wagerItem.Odds}}</span>
                </div>
              </div>
            </div>
            <div class="money">{{commonToFixed(wager.InputtedStakeAmount)}}</div>
            <div class="winlose">--</div>
            <div class="status">{{$t(betStatusConfig[wager.BetConfirmationStatus].textKey)}}</div>
          </div>
        </div>
        <div class="total">
          <span class="text">{{$t('TEXT_TOTAL_INPUTMONEY')}}</span>
          <span class="totalBet">{{commonToFixed(totalBet)}}</span>
          <span class="winLose"></span>
          <span class="emptyBlock" />
        </div>
      </div>
      <div class="empty" v-else>{{$t('TEXT_CANT_FIND_HISTORY')}}</div>
    </div>
  </div>
</template>
<script>
import { mapActions } from "vuex";
import config from "@/config/config";
export default {
  data() {
    return {
      wagerList: []
    };
  },
  computed: {
    periodId() {
      return config.periodId;
    },
    BetTypeConfig() {
      return config.BetTypeId;
    },
    OddsConfig() {
      return config.OddsTypeConfig;
    },
    betStatusConfig() {
      return config.betStatusConfig;
    },
    sportList() {
      return config.sportList;
    },
    totalBet() {
      const { wagerList } = this;
      let num = 0;
      for (var i = 0; i < wagerList.length; i++) {
        num += wagerList[i].InputtedStakeAmount;
      }
      return num;
    }
  },
  methods: {
    ...mapActions(["apiGetBetList"]),
    $_parseHandicap(oddsSet) {
      let Handicap = oddsSet.Handicap;
      if (Handicap == null) return;
      const selectionId = oddsSet.BetTypeSelectionId;
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
            return "-" + hdc;
          }
          return "-" + returnVal;
        }
      }
      if (Handicap > 0) {
        if (selectionId == 1) {
          if (mod == 0) {
            //如果是5的整數
            return hdc;
          }
          return returnVal;
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

    $_updateFunc() {
      this.apiGetBetList({
        BetConfirmationStatus: [1, 2]
      }).then(res => {
        this.wagerList = res.data.WagerList;
      });
    }
  },
  mounted() {
    this.$_updateFunc();
  }
};
</script>

<style lang="scss" src="@/scss/router/singlePage/betPage.scss"></style>