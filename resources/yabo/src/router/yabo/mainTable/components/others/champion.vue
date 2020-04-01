<template>
  <div class="champion">
    <div class="betBody">
      <div class="banner" @click="$_collapseTable(marketIndex)">
        <div class="name">
          <span>{{$t(sportList[sportId].textKey)}}</span>
          <span style="padding-left:10px">{{$t('TEXT_CHAMPION')}}</span>
        </div>
        <div class="starColumn"></div>
      </div>
      <div class="championOdds" v-for="event in shownData" :key="event.EventId">
        <div class="league" @click="$_collapseEvent(event.Competition.CompetitionId)">
          {{event.Competition.CompetitionName}}
          <div class="right">
            <div class="monthDate">{{commonGetFullDate(event.EventDate)}}</div>
          </div>
        </div>
        <el-collapse-transition>
          <div
            v-show="!collapsedCompetitionList.includes(event.Competition.CompetitionId)"
            style="width:100%"
          >
            <div class="tabRow">{{event.OutrightEventName}}</div>
            <div class="table eventItem champion">
              <div
                class="wrap"
                v-show="oddsItem.Odds!=0"
                v-for="(oddsItem,index) in  $_sortArray(event.MarketLines[0].WagerSelections)"
                :key="oddsItem.WagerSelectionId"
              >
                <div class="type">{{oddsItem.SelectionName}}</div>

                <div
                  @click="$_placeBets(oddsItem)"
                  class="odds"
                  :class="{ 'active':$_selectedWager(oddsItem) }"
                  v-if="oddsItem.Odds!=0 && event.MarketLines[0].MarketlineStatusId==1"
                >{{oddsItem.Odds}}</div>
                <div v-else class="locked">
                  <i class="el-icon-lock" />
                </div>
              </div>
            </div>
          </div>
        </el-collapse-transition>
      </div>
    </div>
  </div>
</template>
<script>
import { mapState, mapActions, mapMutations } from "vuex";
import moment from "moment";
import pageComponent from "@/components/common/pageComponent";
import oddsWrapMixin from "../oddsWrapMixin";
export default {
  mixins: [oddsWrapMixin],
  components: {
    pageComponent
  },
  data() {
    return {};
  },
  methods: {
    ...mapActions(["apiGetBetInfo"]),
    $_sortArray(array) {
      return array.sort((a, b) => {
        if (a.Odds < b.Odds) return -1;
        if (a.Odds == b.Odds) return 0;
        if (a.Odds > b.Odds) return 1;
      });
    },
    $_selectedWager(OddsSet) {
      if (!OddsSet) return false;
      const { isCombo, wager, wagerList } = this;
      if (!isCombo) {
        //單著模式
        if (wager.WagerSelectionId == OddsSet.WagerSelectionId) return true;
        else return false;
      } else {
        //串觀摩是
        if (wagerList[OddsSet.EventGroupId]) {
          //如果這個event的注單存在
          if (
            wagerList[OddsSet.EventGroupId].WagerSelectionId ==
            OddsSet.WagerSelectionId
          ) {
            return true;
          } else return false;
        }
        return false;
      }
    },
    $_placeBets(oddsSet) {
      const { betPlaced } = this;
      if (betPlaced == true) {
        this.$store.commit("$_setBetPlaced", false); //更新為還沒下注
        // this.$store.commit("$_clearWager"); //清空bet
        // this.$store.commit("$_clearWagerList"); //清空bet
      }
      if (this.wager.WagerSelectionId == oddsSet.WagerSelectionId) {
        return this.$store.commit("$_clearWager");
      }
      let wagerSet = {
        SportId: this.sportId,
        MarketlineId: oddsSet.MarketlineId,
        EventId: oddsSet.EventId,
        BetTypeId: 11,
        PeriodId: 1,
        BetTypeSelectionId: 0,
        WagerSelectionId: oddsSet.WagerSelectionId,
        AwayScore: 0,
        HomeScore: 0,
        OutrightTeamId: oddsSet.SelectionId,
        Handicap: 0,
        OddsType: oddsSet.OddsType,
        Odds: oddsSet.Odds,
        OutrightEventName: oddsSet.OutrightEventName,
        Specifiers: oddsSet.Specifiers
      };
      this.$_getBetInfo([wagerSet], oddsSet);
    },
    $_getBetInfo(wager, oddsInfo) {
      this.apiGetBetInfo({
        WagerType: 1, //單注,2是連串
        WagerSelectionInfos: wager
      }).then(res => {
        if (res.data.StatusCode == 100) {
          this.$store.commit("$_setPlacingBet", true);
          //如果抓取成功
          let betSetting = res.data.BetSetting;
          this.$store.commit("$_setWager", {
            ...res.data.WagerSelectionInfos[0],
            ...res.data.BetSetting[0],
            ...oddsInfo,
            OutrightEventName: wager[0].OutrightEventName,
            SportId: this.sportId
          });
          this.$store.commit("$_setComboBetSetting", betSetting);
        } else {
          this.$store.commit("$_commonHintMsg", {
            type: "error",
            message: `TEXT_ERROR_STATUS_${res.data.StatusCode}`
          });
        }
      });
    }
  },
  computed: {
    ...mapState({
      wager: state => state.wager.wager,
      wagerList: state => state.wager.wagerList,
      betPlaced: state => state.wager.betPlaced,
      isCombo: state => state.status.isCombo
    }),
    shownData() {
      let arr = JSON.parse(JSON.stringify(this.list.Events));
      if (arr.length > 0) {
        for (let p = 0; p < arr.length; p++) {
          for (
            var i = 0;
            i < arr[p].MarketLines[0].WagerSelections.length;
            i++
          ) {
            //arr[p].MarketLines[0].WagerSelections[i]
            arr[p].MarketLines[0].WagerSelections[i] = {
              ...arr[p].MarketLines[0].WagerSelections[i],
              MarketlineId: arr[p].MarketLines[0].MarketlineId,
              BetTypeId: arr[p].MarketLines[0].BetTypeId,
              BetTypeName: arr[p].MarketLines[0].BetTypeName,
              PeriodId: arr[p].MarketLines[0].PeriodId,
              PeriodName: arr[p].MarketLines[0].PeriodName,
              MarketlineStatusId: arr[p].MarketLines[0].MarketlineStatusId,
              EventId: arr[p].EventId,
              EventStatusId: arr[p].EventStatusId,
              OutrightEventName: arr[p].OutrightEventName,
              EventDate: arr[p].EventDate,
              Competition: arr[p].Competition
            };
          }
        }
        if (this.orderBy == 2) return arr;
        else {
          const sortedArray = arr.sort((a, b) =>
            moment(a.EventDate).diff(moment(b.EventDate))
          );
          return sortedArray;
        }
      } else {
        return [];
      }
    }
  }
};
</script>
<style lang="scss" src="@/scss/router/yabo/main/oddsTable.scss"></style>
