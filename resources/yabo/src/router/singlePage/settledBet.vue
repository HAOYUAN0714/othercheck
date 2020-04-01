<template>
  <div class="betPage">
    <div class="header">{{$t('TEXT_SETTLED_BET')}}</div>
    <div class="search">
      <span>{{$t('TEXT_START_DATE')}}</span>
      <date-picker :selectedDate="dateFrom" :dateArr="dateArr" @selectDate="$_selectStart" />
      <span style="margin-left:10px">{{$t('TEXT_END_DATE')}}</span>
      <date-picker :selectedDate="dateTo" :dateArr="dateArr" @selectDate="$_selectEnd" />
      <!--  -->
      <div @click="$_search" class="button">{{$t('TEXT_SEARCH')}}</div>
    </div>
    <div class="dateHint">{{$t('TEXT_MATCH_DATE')}}：{{dateFrom}} - {{dateTo}}(UTC-4)</div>
    <div class="dateOption">
      <div
        class="option"
        @click="$_selectTab(today)"
        :class="{active:selectedSet.dateFrom==today && selectedSet.dateTo ==today}"
      >{{$t('TEXT_TODAY')}}</div>
      <div
        class="option"
        @click="$_selectTab(yesterday)"
        :class="{active:selectedSet.dateFrom==yesterday  && selectedSet.dateTo ==yesterday}"
      >{{$t('TEXT_YESTERDAY')}}</div>
      <div
        class="option"
        @click="$_selectTab(thirtyDaysBefore)"
        :class="{active:!(selectedSet.dateFrom==today && selectedSet.dateTo ==today) && !(selectedSet.dateFrom==yesterday  && selectedSet.dateTo ==yesterday)}"
      >{{$t('TEXT_RECENT_30')}}</div>
    </div>
    <div class="betsTable">
      <div class="top">{{$t('TEXT_SETTLED_BET')}}</div>
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
        <loadingMask :trigger="loading" />
        <div class="wagerTable">
          <div class="wagerItem" v-for="(wager,index) in wagerList" :key="wager.WagerId">
            <div class="number">{{index+1}}</div>
            <div class="details">
              <div>{{$_parseDate(wager.WagerCreationDateTime).date}} {{$_parseDate(wager.WagerCreationDateTime).time}}</div>
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
                <!--注意-->
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
                <div
                  class="teams"
                  v-if="wagerItem.BetTypeId!=11"
                >{{wagerItem.HomeTeamName}} v {{wagerItem.AwayTeamName}}</div>
                <div class="teams" v-else>{{wagerItem.EventOutrightName}}</div>
                <div class="timeSet">
                  <span class="period">{{$t(periodId[wagerItem.PeriodId].textKey)}}</span>
                  <span class="live" v-if="wagerItem.Market==3">{{$t('TEXT_LIVE')}}</span>
                  <span>{{commonParseBetTypeName(wagerItem)}}</span>
                  <span
                    class="score"
                    v-if="wagerItem.WagerHomeTeamScore!=null && wagerItem.BetTypeId==1 && wagerItem.SportId==1"
                  >({{wagerItem.WagerHomeTeamScore}}:{{wagerItem.WagerAwayTeamScore}})</span>
                </div>
                <div
                  v-show="wagerItem.Market==1"
                  class="startDate"
                >{{$t('TEXT_MATCH_DATE')}} {{$_parseDate(wagerItem.EventDateTime).date}} {{$_parseDate(wagerItem.EventDateTime).time}}</div>

                <div class="bottom">
                  <span class="name">{{commonParseSpecifiers(wagerItem)}}</span>
                  <span
                    class="hdc"
                    v-if="wagerItem.BetTypeId==1 || wagerItem.BetTypeId ==2"
                  >{{$_parseHandicap(wagerItem)}}</span>
                  <span>@</span>
                  <span class="oddsValue">{{wagerItem.Odds}}</span>
                </div>
              </div>
            </div>
            <div class="money">{{commonToFixed(wager.InputtedStakeAmount)}}</div>
            <div
              class="winlose"
              :class="{win:wager.MemberWinLossAmount>0,lose:wager.MemberWinLossAmount<0}"
            >{{commonToFixed(wager.MemberWinLossAmount)}}</div>
            <div
              class="status"
              v-if="wager.BetConfirmationStatus==3"
            >{{$t(betStatusConfig[wager.BetConfirmationStatus].textKey)}}</div>
            <!--如果是被拒絕要優先顯示-->
            <div class="status win" v-else-if="wager.MemberWinLossAmount>0">{{$t('TEXT_WIN')}}</div>
            <div class="status draw" v-else-if="wager.MemberWinLossAmount==0">{{$t('TEXT_DRAW')}}</div>
            <div class="status lose" v-else-if="wager.MemberWinLossAmount<0">{{$t('TEXT_LOSE')}}</div>
          </div>
        </div>
        <div class="total">
          <span class="text">{{$t('TEXT_TOTAL_INPUTMONEY')}}</span>
          <span class="totalBet">{{commonToFixed(totalBet)}}</span>
          <span
            class="winLose"
            :class="{win:totalWinLose>0,lose:totalWinLose<0}"
          >{{commonToFixed(totalWinLose)}}</span>
          <span class="emptyBlock" />
        </div>
      </div>
      <div class="empty" v-else>{{$t('TEXT_CANT_FIND_HISTORY')}}</div>
    </div>
  </div>
</template>
<script>
import { mapActions } from "vuex";
import $ from "jquery";
import moment from "moment";
import loadingMask from "./components/loadingMask";
import datePicker from "@/components/common/DatePicker";
import config from "@/config/config";
export default {
  components: {
    datePicker,
    loadingMask
  },
  data() {
    return {
      datePicker: {},
      dateFrom: "2019/11/15",
      dateTo: "2019/11/20",
      selectedSet: { dateFrom: "", dateTo: "" },
      wagerList: [],
      loading: false
    };
  },
  computed: {
    dateArr() {
      const { today } = this;
      let dateFromAdd30 = moment(today, "YYYY/MM/DD")
        .subtract(30, "days")
        .format("YYYY/MM/DD");
      return [
        {
          from: dateFromAdd30,
          to: today
        }
      ];
    },
    periodId() {
      return config.periodId;
    },
    sportList() {
      return config.sportList;
    },
    today() {
      const today = new Date();
      return this.$_parseDate(today).date;
    },
    yesterday() {
      const today = new Date();
      const day1 = new Date(today);
      day1.setDate(day1.getDate() - 1);
      return this.$_parseDate(day1).date;
    },
    thirtyDaysBefore() {
      const today = new Date();
      const day1 = new Date(today);
      day1.setDate(day1.getDate() - 30);
      return this.$_parseDate(day1).date;
    },
    BetTypeConfig() {
      return config.BetTypeId;
    },
    OddsConfig() {
      return {
        1: {
          id: 1,
          textKey: "TEXT_MY_ODD"
        },
        2: {
          id: 2,
          textKey: "TEXT_HK_ODD"
        },
        3: {
          id: 3,
          textKey: "TEXT_EUROPE_ODD"
        },
        4: {
          id: 4,
          textKey: "TEXT_IN_ODD"
        }
      };
      return config.OddsTypeConfig;
    },
    betStatusConfig() {
      return config.betStatusConfig;
    },
    totalBet() {
      const { wagerList } = this;
      let num = 0;
      for (var i = 0; i < wagerList.length; i++) {
        num += wagerList[i].InputtedStakeAmount;
      }
      return num;
    },
    totalWinLose() {
      const { wagerList } = this;
      let num = 0;
      for (var i = 0; i < wagerList.length; i++) {
        num += wagerList[i].MemberWinLossAmount;
      }
      return num;
    }
  },
  methods: {
    ...mapActions(["apiGetSettled"]),
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
    $_selectStart(date) {
      this.dateFrom = date;
    },
    $_selectEnd(date) {
      this.dateTo = date;
    },
    $_selectTab(date) {
      this.dateFrom = date;
      if (date == this.yesterday) this.dateTo = this.yesterday;
      else this.dateTo = this.today;
      this.$_updateFunc();
    },
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
      return {
        date: `${year}/${md}`,
        time: hr
      };
    },
    $_search() {
      this.$_updateFunc();
    },
    $_scrollTop() {
      $(".wagerTable").animate({ scrollTop: 0 }, 400);
    },
    $_updateFunc() {
      this.loading = true;
      this.$_scrollTop();
      this.apiGetSettled({
        DateType: 2,
        StartDate: this.dateFrom,
        EndDate: this.dateTo
      })
        .then(res => {
          this.selectedSet.dateFrom = this.dateFrom;
          this.selectedSet.dateTo = this.dateTo;
          this.wagerList = res.data.WagerList ? res.data.WagerList : [];
          this.loading = false;
        })
        .catch(err => {
          this.loading = false;
        });
    }
  },
  mounted() {
    this.dateFrom = this.today;
    this.dateTo = this.today;
    this.selectedSet.dateFrom = this.dateFrom;
    this.selectedSet.dateTo = this.dateTo;
    this.$_updateFunc();
  }
};
</script>

<style lang="scss" src="@/scss/router/singlePage/betPage.scss"></style>