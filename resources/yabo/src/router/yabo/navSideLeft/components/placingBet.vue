<template>
  <div class="placingBet">
    <div @click="$_return" class="returnTab">
      <i class="el-icon-back returnIcon" />
      <span>{{$t('TEXT_RETURN_TO_SPORT')}}</span>
    </div>
    <div class="betSlip" v-if="!betPlaced">
      <div class="tab">
        <span v-if="isCombo">{{$t('TEXT_PARLAY')}}</span>
        <span>{{$t('TEXT_BETTING_OFFER')}}</span>
      </div>
      <transition-group name="betSlip">
        <div class="matchInfoWrap" v-for="Wager in layoutWager" :key="Wager.WagerSelectionId">
          <div class="infoTab">
            <div class="info">
              <span>i</span>
              <betInfoBlock class="infoDrop" :betInfo="Wager" />
            </div>
            <div>
              <span
                v-if="Wager.PeriodId==1 || Wager.PeriodId==2 || Wager.PeriodId==3"
              >{{$t(periodConfig[Wager.PeriodId].textKey)}}-</span>
              <span v-if="Wager.Market==3">{{$t('TEXT_LIVE')}}</span>
              <span>{{commonParseBetTypeName(Wager)}}</span>
              <span
                v-if="Wager.Market==3 && Wager.HomeScore!=null && Wager.BetTypeId==1 && Wager.SportId==1"
              >({{Wager.HomeScore}}:{{Wager.AwayScore}})</span>
            </div>
            <div class="close" @click="$_removeWager(Wager)">
              <i class="el-icon-error" />
            </div>
          </div>
          <div class="infoTab">
            <!--主營-->
            <span
              class="hdc"
            >{{commonParseSpecifiers(Wager)}} {{$_parseHandicap(Wager)}}@{{Wager.Odds}}</span>
          </div>
          <div class="infoTab" v-if="Wager.BetTypeId!=11">{{Wager.HomeTeam}} v {{Wager.AwayTeam}}</div>
          <div class="infoTab" v-else>{{Wager.OutrightEventName}}</div>
        </div>
      </transition-group>
      <!--投注項目種類-->
      <betOptions
        v-for="(betOption,index) in comboBetSetting"
        :selected="selectedBetOption==index?true:false"
        :betOption="betOption"
        :index="index"
        ref="betOption"
        @selectBetOption="$_selectBetOption"
        :key="`betOption${index}`"
      />

      <div class="betInfo">
        <div class="infoTab">
          <span class="wagerAmount" v-show="isCombo">{{wagerAmount}}</span>
          <span>{{$t('TEXT_ALL_BETS')}}</span>
          <div class="right orange">{{ commonToCurrency(commonToFixed(totalBet))}} RMB</div>
        </div>
        <div class="infoTab">
          <span>{{$t('TEXT_WINNABLE_AMOUNT')}}</span>
          <div class="right blue">{{ commonToCurrency(commonToFixed(totalEstimate))}} RMB</div>
        </div>
      </div>
      <span v-if="!isCombo" class="automate">{{$t('TEXT_AUTO_RECEIVE_BETTER_ODDS')}}</span>
      <span v-if="isCombo" class="automate">{{$t('TEXT_AUTO_ANY_ODDS')}}</span>
      <div class="betButtons">
        <div
          v-show="checkIsCombo"
          @click="$_placeBet"
          :class="{loading:clicked}"
          class="button bet"
        >{{$t('TEXT_MAKE_BET')}}</div>
        <div @click="$_cancel" class="button cancel">{{$t('TEXT_CANCEL')}}</div>
      </div>
      <!-- <div class="updateOdds">
        <div class="infoTab">{{$t('TEXT_AUTO_RECEIVE_BETTER_ODDS')}}</div>
      </div>-->
    </div>
    <!--如下注成功-->
    <betPlaced
      @return="$_cancel"
      :successWagerList="successWagerList"
      :successBetOptions="successBetOptions"
      v-else
    />
  </div>
</template>
<script>
import { mapState, mapActions, mapMutations } from "vuex";
import config from "@/config/config";
import betInfoBlock from "@/components/common/betInfoBlock";
import betPlaced from "./betPlaced";
import betOptions from "./betOptions/betOptions";

export default {
  components: {
    betPlaced,
    betOptions,
    betInfoBlock
  },
  data() {
    return {
      stakeAmount: "",
      //如已點擊下注按鈕，鎖住避免重複發
      clicked: false,
      successWagerList: [],
      successBetOptions: {
        totalBet: 0,
        totalWinnableAmount: 0,
        WagerSelectionInfos: []
      },
      //現在選擇的串關模式，預設為第0項
      selectedBetOption: 0,
      isMounted: false
    };
  },
  computed: {
    ...mapState({
      wager: state => state.wager.wager,
      wagerList: state => state.wager.wagerList,
      betPlaced: state => state.wager.betPlaced,
      isCombo: state => state.status.isCombo,
      betType: state => state.oddsTable.selectedBetType,
      oddsType: state => state.status.selectedOddsType,
      comboBetSetting: state => state.wager.comboBetSetting
    }),

    liveWager() {
      const { isCombo, wager } = this;
      if (isCombo) return false;
      if (!isCombo && wager.Market == 3) return true;
      return false;
    },

    //總注單量
    wagerAmount() {
      const { isCombo, isMounted, comboBetSetting } = this;
      if (!this.isMounted) return;
      let num = 0;
      if (!isCombo) {
        return 1; //單注當然一張
      }
      const refOption = this.$refs.betOption;
      if (refOption) {
        for (var i = 0; i < refOption.length; i++) {
          if (refOption[i].stakeAmount != 0 && refOption[i].stakeAmount != "") {
            num += Number(refOption[i].betOption.NoOfCombination);
          }
        }
      }
      return num;
    },
    //總可盈額
    totalEstimate() {
      const { isCombo, isMounted, comboBetSetting } = this;
      if (!this.isMounted) return;
      let num = 0;

      const refOption = this.$refs.betOption;
      if (refOption) {
        for (var i = 0; i < refOption.length; i++) {
          num += Number(refOption[i].winnableAmount);
        }
      }
      return num;
    },
    comboSelections() {
      const { isCombo, isMounted, comboBetSetting } = this;
      if (!this.isMounted) return;
      let arr = [];
      const refOption = this.$refs.betOption;
      if (refOption) {
        for (var i = 0; i < refOption.length; i++) {
          if (Number(refOption[i].stakeAmount) > 0) {
            let obj = {
              ComboSelection: refOption[i].betOption.ComboSelection,
              StakeAmount: Number(refOption[i].stakeAmount)
            };
            arr.push(obj);
          }
        }
      }

      return arr;
    },
    //總下注額
    totalBet() {
      const { isCombo, isMounted, comboBetSetting } = this;
      if (!this.isMounted) return;
      let num = 0;

      const refOption = this.$refs.betOption;
      if (refOption) {
        for (var i = 0; i < refOption.length; i++) {
          num += Number(refOption[i].actualStakeAmount);
        }
      }
      return num;
    },
    periodConfig() {
      return config.periodId;
    },

    checkIsCombo() {
      const { isCombo, wagerList } = this;
      if (!isCombo) return true;
      if (isCombo) {
        if (wagerList.length < 2) return false;
        else {
          return true;
        }
      }
    },

    layoutWager() {
      if (this.isCombo) {
        return this.wagerList;
      } else {
        return [this.wager];
      }
    }
  },
  watch: {
    oddsType(odds) {
      const { wager, wagerList, oddsType, isCombo } = this;
      if (isCombo) return; //過關單不支援轉換盤口
      if (wager.WagerSelectionId) {
        wager.OddsType = oddsType;
        this.updateBetInfo([wager], wager);
      }
      //要重新發一次getBetInfo
    },
    wagerList(list) {
      if (list.length == 0) {
        this.$_setPlacingBet(false);
      }
    }
  },
  methods: {
    ...mapActions([
      "apiGetBetList",
      "apiGetBetInfo",
      "apiGetBalance",
      "apiPlaceBet"
    ]),
    ...mapMutations([
      "$_setWager",
      "$_clearWager",
      "$_clearWagerList",
      "$_clearBetSetting",
      "$_setComboBetSetting",
      "$_setPlacingBet",
      "$_commonHintMsg",
      "$_removeWagerFromList",
      "$_setBetPlaced"
    ]),
    $_isMainPlay(betTypeId) {
      if (betTypeId == 1 || betTypeId == 3 || betTypeId == 4) {
        return true;
      }
      return false;
    },
    $_selectBetOption(index) {
      this.selectedBetOption = index;
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
    updateBetInfo(wager, oddsInfo) {
      const { isCombo, betType } = this;
      this.apiGetBetInfo({
        WagerType: isCombo ? 2 : 1,
        WagerSelectionInfos: wager
      }).then(res => {
        if (res.data.StatusCode == 100) {
          this.$_setPlacingBet(true);
          //如果抓取成功
          if (!isCombo) {
            this.$_setWager({
              ...wager[0],
              ...res.data.WagerSelectionInfos[0],
              ...res.data.BetSetting[0],
              HomeTeam: oddsInfo.HomeTeam,
              EventDate: oddsInfo.EventDate,
              WagerSelectionId: oddsInfo.WagerSelectionId,
              AwayTeam: oddsInfo.AwayTeam,
              BetTypeId: oddsInfo.BetTypeId,
              BetTypeName: oddsInfo.BetTypeName,
              OutrightTeamId: betType == 11 ? oddsInfo.SelectionId : 0,
              SportId: oddsInfo.SportId,
              SelectionName: oddsInfo.SelectionName,
              Competition: oddsInfo.Competition
            });
          }
          this.$_setComboBetSetting(res.data.BetSetting);
        } else {
          this.$_commonHintMsg({
            type: "error",
            message: res.data.sys_result
          });
        }
      });
    },

    $_removeWager(wager) {
      const { isCombo, wagerList } = this;
      if (isCombo) {
        this.$_removeWagerFromList(wager);
        if (wagerList.length != 0) this.updateBetInfo(wagerList);
      } else {
        this.$_clearWager();
      }
    },
    $_return() {
      this.$_clearWager();
      this.$_setPlacingBet(false);
      this.$_setBetPlaced(false);
      this.successWagerList = [];
      this.$emit("closeFunc");
    },
    $_cancel() {
      this.$_clearWager();
      this.$_clearWagerList();
      this.$_clearBetSetting();
      this.$_setPlacingBet(false);
      this.$_setBetPlaced(false);
      this.successWagerList = [];
      this.$emit("closeFunc");
    },
    $_placeBet() {
      const {
        isCombo,
        wager,
        stakeAmount,
        wagerList,
        totalBet,
        clicked
      } = this;
      if (clicked) return;
      //如果沒下注

      var self = this;

      let wagerSelectionArr = [];
      if (isCombo == false) {
        //如果是單注
        wagerSelectionArr = [
          {
            WagerSelectionId: wager.WagerSelectionId,
            MarketlineId: wager.MarketlineId,
            BetTypeId: wager.BetTypeId,
            BetTypeSelectionId: wager.BetTypeSelectionId,
            OutrightTeamId: wager.OutrightTeamId,
            OddsType: wager.OddsType,
            Handicap: wager.Handicap ? wager.Handicap : 0,
            Odds: wager.Odds,
            EventId: wager.EventId,
            SportId: wager.SportId,
            Market: wager.Market,
            HomeScore: wager.HomeScore ? wager.HomeScore : 0,
            AwayScore: wager.AwayScore ? wager.AwayScore : 0,
            Specifiers: wager.Specifiers
          }
        ];
      } else {
        //串關模式
        Object.keys(wagerList).forEach(eventId => {
          let wagerItem = {
            WagerSelectionId: wagerList[eventId].WagerSelectionId,
            MarketlineId: wagerList[eventId].MarketlineId,
            BetTypeId: wagerList[eventId].BetTypeId,
            BetTypeSelectionId: wagerList[eventId].SelectionId,
            OutrightTeamId: wager.OutrightTeamId,
            OddsType: wagerList[eventId].OddsType,
            Handicap: wagerList[eventId].Handicap
              ? wagerList[eventId].Handicap
              : 0,
            Odds: wagerList[eventId].Odds,
            EventId: wagerList[eventId].EventId,
            SportId: wagerList[eventId].SportId,
            Market: wagerList[eventId].Market,
            HomeScore: wagerList[eventId].HomeScore
              ? wagerList[eventId].HomeScore
              : 0,
            AwayScore: wagerList[eventId].AwayScore
              ? wagerList[eventId].AwayScore
              : 0,
            Specifiers: wagerList[eventId].Specifiers
          };
          wagerSelectionArr.push(wagerItem);
        });
      }
      this.$store.state.wager.gettinBetInfo = true;
      this.clicked = true;
      this.apiPlaceBet({
        WagerType: isCombo == true ? 2 : 1,
        WagerSelectionInfos: wagerSelectionArr,
        IsComboAcceptAnyOdds: true,
        ComboSelections: this.comboSelections
      }).then(res => {
        this.$store.state.wager.gettinBetInfo = false;
        this.clicked = false;
        if (res.data.StatusCode == 100) {
          this.apiGetBetList({
            BetConfirmationStatus: [1, 2, 3, 4]
          });
          this.$_setBetPlaced(true);
          if (!isCombo) {
            //單注
            let wager = this.wager;
            wager.Odds = res.data.AcceptedWagerSelectionList[0].StakeOdds; //賠率改成新的
            this.successWagerList = [wager];
          } else {
            let obj = this.wagerList;
            for (var eventGroupId in obj) {
              if (obj.hasOwnProperty(eventGroupId)) {
                for (
                  var i = 0;
                  i < res.data.AcceptedWagerSelectionList.length;
                  i++
                ) {
                  if (
                    obj[eventGroupId].EventId ==
                    res.data.AcceptedWagerSelectionList[i].EventId
                  ) {
                    obj[eventGroupId].Odds =
                      res.data.AcceptedWagerSelectionList[i].StakeOdds;
                    break;
                  }
                }
              }
            }
            this.successWagerList = obj;
          }
          this.successBetOptions.totalBet = this.totalBet;
          this.successBetOptions.WagerSelectionInfos =
            res.data.WagerSelectionInfos;
          for (
            var j = 0;
            j < this.successBetOptions.WagerSelectionInfos.length;
            j++
          ) {
            const refOption = this.$refs.betOption;
            if (refOption) {
              for (var c = 0; c < refOption.length; c++) {
                if (
                  refOption[c].betOption.ComboSelection ==
                  this.successBetOptions.WagerSelectionInfos[j].ComboSelectionId
                ) {
                  this.successBetOptions.WagerSelectionInfos[j] = {
                    ...this.successBetOptions.WagerSelectionInfos[j],
                    wagerAmount: Number(refOption[c].betOption.NoOfCombination),
                    estimate: Number(refOption[c].winnableAmount),
                    betAmount: Number(refOption[c].stakeAmount)
                  };
                  continue;
                }
              }
            }
          }
          this.successBetOptions.totalWinnableAmount = this.totalEstimate;
          this.$_clearWager(); //清空bet
          this.$_clearWagerList(); //清空bet
          this.$forceUpdate();
          this.apiGetBalance();
        } else {
          this.$store.state.wager.gettinBetInfo = false;
          this.clicked = false;
          this.$_commonHintMsg({ type: "error", message: res.data.sys_result });
        }
      });
    }
  },
  mounted() {
    this.isMounted = true;
  }
};
</script>
<style lang="scss" src="@/scss/router/yabo/navLeft/placingBet.scss"></style>