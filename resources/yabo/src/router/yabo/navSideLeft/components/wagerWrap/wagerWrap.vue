<template>
  <div class="wagerWrap">
    <div class="wagerItem" v-for="wagerSet in wagerArr" :key="wagerSet.WagerId">
      <div v-for="(wager,index) in wagerSet.WagerItemList" :key="`${wagerSet.WagerId}${index}`">
        <div class="infoTab" v-show="index==0">
          <div class="info">
            <span>i</span>
            <div class="infoDrop">
              <div class="header" v-if="wagerSet.ComboSelection==0">{{wager.CompetitionName}}</div>
              <div class="header" v-else>{{$t('TEXT_COMBO')}}</div>
              <div
                class="teams"
                v-if="wager.BetTypeId!=11 && wagerSet.ComboSelection==0"
              >{{wager.HomeTeamName}} v {{wager.AwayTeamName}}</div>

              <div class="teams" v-else>{{wager.EventOutrightName}}</div>
              <div
                class="teams"
                v-show="wager.Market==1 && wager.ComboSelection==0"
              >{{$_parseDate(wager.EventDateTime).date}} {{$_parseDate(wager.EventDateTime).time }}</div>
              <div
                class="betType"
                v-show="wagerSet.ComboSelection==0"
              >{{commonParseBetTypeName(wager)}}</div>
              <div class="wagerNumber">{{$t('TEXT_WAGER_ID')}}{{wagerSet.WagerId}}</div>
              <div
                class="wagerNumber"
              >{{$t('TEXT_BET_DATE')}}{{$_parseDate(wagerSet.WagerCreationDateTime).date}} {{$_parseDate(wagerSet.WagerCreationDateTime).time}}</div>
            </div>
          </div>
          <div class="infoSet">
            <span
              class="period"
              v-show="wagerSet.ComboSelection==0"
            >{{$t(`TEXT_PERIOD_${wager.PeriodId}`)}}&nbsp</span>
            <span
              class="isLive"
              v-if="wager.Market==3 && wagerSet.ComboSelection==0"
            >{{$t('TEXT_LIVE')}}&nbsp</span>
            <span v-if="wagerSet.ComboSelection==0">{{commonParseBetTypeName(wager)}}&nbsp</span>
            <span v-else>{{$t('TEXT_COMBO')}} - {{$t(`TEXT_COMBO_${wagerSet.ComboSelection}`)}}</span>
            <span
              class="isLive"
              v-if="wager.Market==3 && wagerSet.ComboSelection==0 && wager.WagerHomeTeamScore!=null && wager.BetTypeId==1 && wager.SportId==1"
            >({{wager.WagerHomeTeamScore}}:{{wager.WagerAwayTeamScore}})</span>
          </div>
        </div>
        <div class="wagerBody">
          <div v-if="wagerSet.ComboSelection!=0" class="textInfo">
            <span>{{$t(`TEXT_PERIOD_${wager.PeriodId}`)}}-</span>
            <span v-show="wager.Market==3">{{$t('TEXT_LIVE')}}&nbsp</span>
            <span>{{commonParseBetTypeName(wager)}}</span>

            <span
              v-if="wager.Market==3 && wager.WagerHomeTeamScore!=null && wager.SportId==1 && wager.BetTypeId==1"
            >({{wager.WagerHomeTeamScore}}:{{wager.WagerAwayTeamScore}})</span>
          </div>
          <div class="top">
            <span class="name" v-if="wager.BetTypeId!=11">{{commonParseSpecifiers(wager)}}</span>
            <span v-else>{{wager.OutrightTeamName}}</span>
            <span class="hdc">{{$_parseHandicap(wager)}}</span>
            <span>@</span>
            <span class="oddsValue">{{wager.Odds}}</span>
          </div>
          <div class="center" v-if="wager.BetTypeId!=11">
            <span class="home">{{wager.HomeTeamName}}</span>
            <span>v</span>
            <span class="away">{{wager.AwayTeamName}}</span>
          </div>
          <div class="center" v-else>{{wager.EventOutrightName}}</div>
          <div class="bottom" v-if="index==wagerSet.WagerItemList.length-1">
            <div class="left">
              <div class="top">{{$t('TEXT_BETTING_AMOUNT')}}</div>
              <div class="bottom">{{ commonToCurrency(commonToFixed(wagerSet.InputtedStakeAmount))}}</div>
            </div>
            <div class="right" v-if="!settled">
              <span class="top">{{$t('TEXT_WINNABLE_AMOUNT')}}</span>
              <span class="bottom">{{ commonToCurrency(commonToFixed(wagerSet.PotentialPayout))}}</span>
            </div>
            <div class="right" v-else>
              <span
                class="top"
                v-if="wagerSet.BetConfirmationStatus==3"
              >{{$t(betStatusConfig[wagerSet.BetConfirmationStatus].textKey)}}</span>
              <span class="top" v-else-if="wagerSet.MemberWinLossAmount<0">{{ $t('TEXT_LOSE')}}</span>
              <span class="top" v-else-if="wagerSet.MemberWinLossAmount==0">{{ $t('TEXT_DRAW')}}</span>
              <span class="top" v-else-if="wagerSet.MemberWinLossAmount>0">{{ $t('TEXT_WIN')}}</span>
              <span
                class="bottom"
                :class="{win:wagerSet.MemberWinLossAmount>0,lose:wagerSet.MemberWinLossAmount<0}"
              >{{ commonToCurrency(commonToFixed(wagerSet.MemberWinLossAmount)) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import config from "@/config/config";
import moment from "moment";
export default {
  props: {
    wagerArr: {
      type: Array,
      default: () => {}
    },
    settled: {
      type: Boolean,
      default: () => {}
    }
  },
  computed: {
    betStatusConfig() {
      return config.betStatusConfig;
    }
  },
  methods: {
    $_parseDate(date) {
      var d = new Date(date);
      var year = moment(d)
        .utcOffset(-4)
        .format("YYYY");
      var md = moment(d)
        .utcOffset(-4)
        .format("MM/DD");
      var hr = moment(d)
        .utcOffset(-4)
        .format("HH:mm");
      return { date: `${year}/${md}`, time: hr };
    },
    $_parseHandicap(oddsSet) {
      if (oddsSet.BetTypeId != 1 && oddsSet.BetTypeId != 2) return;
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
    }
  }
};
</script>

<style lang="scss" src="@/scss/router/yabo/navLeft/betRecords.scss"></style>