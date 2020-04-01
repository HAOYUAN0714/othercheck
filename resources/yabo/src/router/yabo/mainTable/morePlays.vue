<template>
  <div class="morePlays">
    <div class="closeBtn" @click="$_close">
      <i class="el-icon-close" />
    </div>
    <div class="table">
      <div class="header">
        <div class="sportName">{{$t(sportConfig[sportId]['textKey'])}}</div>
        <div class="right">
          <div
            class="oddsType"
            :title="isCombo==true?$t('TEXT_COMBO_ONLY_EUROPE'):''"
            @click="$_collapseOdds"
          >
            {{$t('TEXT_ODDS_TYPE')}}：
            {{$t(OddsTypeConfig[selectedOdds].textKey)}}
            <div class="expandIcon" :class="{active:showOddsType}">
              <i v-show="!isCombo" class="el-icon-arrow-down" />
            </div>
            <el-collapse-transition>
              <div class="marketDrop" v-show="showOddsType">
                <div
                  class="marketItem"
                  v-for="market in oddsDropArr"
                  @click="$_selectOddsType(market)"
                  :class="{active:market.id == selectedOdds}"
                  :key="market.id"
                >{{$t(market.textKey)}}</div>
              </div>
            </el-collapse-transition>
          </div>
          <div class="refresh" @click="$_updateFunc">
            <i class="el-icon-refresh" v-if="!isLoading" />
            <i class="el-icon-loading" v-if="isLoading" />
          </div>

          <update-timer
            ref="morePlayTimer"
            style="display:none"
            :updateTime="event.Market==3?5:30"
            @updateFunc="$_updateFunc"
          ></update-timer>
        </div>
      </div>
      <div class="body">
        <div class="banner">
          <div class="top">
            <div class="compName">{{event.Competition.CompetitionName}}</div>
            <div
              class="eventTime"
              v-if="!event.RBTime"
            >{{$_parseDate(event.EventDate).yr}}/{{$_parseDate(event.EventDate).md}} {{$_parseDate(event.EventDate).hm}}</div>
            <div v-else class="eventTime">
              <realTimeClock :event="event" :sportId="sportId" :time="event.RBTime" />
            </div>
          </div>
          <div class="bottom">
            <div class="home" :title="event.HomeTeam">
              {{event.HomeTeam}}
              <span v-if="event.GroundTypeId==0">{{$t('TEXT_NEUTRAL_S')}}</span>
            </div>
            <div
              class="redCard"
              v-show="event.HomeRedCard!=0 && event.HomeRedCard!=null"
            >{{event.HomeRedCard}}</div>
            <div class="score">
              <span v-show="event.Market==3">{{event.HomeScore?event.HomeScore:0}}</span>
              <span>-</span>
              <span v-show="event.Market==3">{{event.AwayScore?event.AwayScore:0}}</span>
            </div>
            <div
              class="redCard"
              v-show="event.AwayRedCard!=0 && event.AwayRedCard!=null"
            >{{event.AwayRedCard}}</div>
            <div class="away" :title="event.AwayTeam">{{event.AwayTeam}}</div>
          </div>
        </div>
        <div class="filterRow">
          <div
            class="item"
            @click="$_selectFilter(0)"
            :class="{active:filter==0}"
          >{{$t('TEXT_ALL_BET_TYPES')}}</div>
          <div
            class="item"
            @click="$_selectFilter(1)"
            :class="{active:filter==1}"
          >{{$t('TEXT_HANDICAP_AND_OU')}}</div>
          <div
            class="item"
            @click="$_selectFilter(2)"
            :class="{active:filter==2}"
          >{{$t('TEXT_CORRECT_SCORE')}}</div>
          <div
            class="item"
            @click="$_selectFilter(3)"
            :class="{active:filter==3}"
          >{{$t('TEXT_GOAL')}}</div>
          <div
            class="item"
            @click="$_selectFilter(4)"
            :class="{active:filter==4}"
          >{{$t('TEXT_HALF_COURT')}}</div>

          <div
            class="item"
            @click="$_selectFilter(6)"
            :class="{active:filter==6}"
          >{{$t('TEXT_SPECIAL_BET')}}</div>
        </div>
        <div class="tableBody">
          <div class="betBody" v-for="(period,index) in parseData" :key="index">
            <div
              class="betType"
              v-for="(betType,index) in period.betTypes"
              :key="index"
              v-show="betType.oddsList.length>0 && $_includeFilter(betType.info)"
            >
              <div
                class="header"
                @click="$_collapse(`${betType.info.BetTypeId}_${betType.info.PeriodId}`)"
              >
                <span class="periodKey">{{$t(periodId[period.periodId].textKey)}}</span>
                <span class="betTypeName">{{(commonParseBetTypeName(betType.info))}}</span>
                <div
                  class="expandIcon"
                  :class="{active:!collapsedList.includes(`${betType.info.BetTypeId}_${betType.info.PeriodId}`)}"
                >
                  <i class="el-icon-arrow-down" />
                </div>
              </div>
              <el-collapse-transition>
                <!--除了波膽以及特殊玩法-->
                <div
                  class="body"
                  v-show="!collapsedList.includes(`${betType.info.BetTypeId}_${betType.info.PeriodId}`)"
                  v-if="betType.info.BetTypeId!=6 && betType.info.BetTypeId!=35 && betType.info.BetTypeId!=62"
                >
                  <div
                    class="oddsSet"
                    v-for="(set,index) in betType.oddsList"
                    :key="`${betType.info.BetTypeName}${index}`"
                  >
                    <morePlayItem
                      :oddsSet="wager"
                      v-for="wager in set"
                      :key="wager.WagerSelectionId"
                    />
                  </div>
                </div>
                <!--波膽-->
                <bodanWrap
                  v-else-if="betType.info.BetTypeId==6"
                  v-show="!collapsedList.includes(`${betType.info.BetTypeId}_${betType.info.PeriodId}`)"
                  :betType="betType"
                />
                <multipleBodan
                  v-else-if="betType.info.BetTypeId==62"
                  v-show="!collapsedList.includes(`${betType.info.BetTypeId}_${betType.info.PeriodId}`)"
                  :betType="betType"
                />
                <threeWayWrap
                  v-else-if="betType.info.BetTypeId==35"
                  v-show="!collapsedList.includes(`${betType.info.BetTypeId}_${betType.info.PeriodId}`)"
                  :betType="betType"
                />
              </el-collapse-transition>
            </div>
          </div>
          <div v-show="isEmpty" class="empty">{{$t('TEXT_NO_DATA_UNDER_FILTER')}}</div>
        </div>
      </div>
    </div>
    <div class="bg" @click="$_close" />
  </div>
</template>
<script>
import $ from "jquery";
import { mapState, mapMutations, mapActions } from "vuex";
import realTimeClock from "@/components/common/realTimeClock";
import bodanWrap from "./morePlayComponents/bodanWrap";
import multipleBodan from "./morePlayComponents/multipleBodan";
import updateTimer from "@/components/common/updateTimer";
import threeWayWrap from "./morePlayComponents/threeWayWrap";
import morePlayItem from "./morePlayComponents/morePlayItem";
import moment from "moment";
import config from "@/config/config";
export default {
  props: {
    event: {
      type: Object,
      default: () => {}
    },
    sportId: {
      type: Number,
      default: () => 1
    },
    oddsType: {
      type: Number,
      default: () => 3
    }
  },
  components: {
    morePlayItem,
    multipleBodan,
    bodanWrap,
    updateTimer,
    realTimeClock,
    threeWayWrap
  },
  data() {
    return {
      sportConfig: config.sportList,
      //盤口清單
      OddsTypeConfig: config.OddsTypeConfig,
      oddsDropArr: config.oddsDropArr,
      showOddsType: false,
      selectedOddsType: 3,
      periodId: config.periodId,
      filter: 0,
      isLoading: false,
      collapsedList: [] //收合清單
    };
  },
  watch: {
    filter() {
      this.$_scrollTop();
    }
  },
  computed: {
    ...mapState({
      isCombo: state => state.status.isCombo,
      morePlayEvent: state => state.oddsTable.morePlayEvent
    }),
    isEmpty() {
      const { parseData, filter } = this;
      let filteredData = parseData;
      const set1 = [1, 2, 4, 31, 32, 35, 39, 83, 85, 86, 92, 93, 94];
      const set2 = [6, 62];
      //進球
      const set3 = [
        2,
        5,
        6,
        7,
        13,
        14,
        15,
        16,
        17,
        18,
        21,
        22,
        23,
        31,
        32,
        42,
        43,
        46,
        56,
        57,
        58,
        59,
        60,
        61,
        93,
        94
      ];
      //半場
      const set4 = [
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        14,
        15,
        18,
        19,
        20,
        22,
        23,
        24,
        25,
        26,
        27,
        31,
        32,
        33,
        34,
        35,
        39,
        56,
        57,
        58,
        93,
        94
      ];
      const set6 = [
        8,
        13,
        14,
        15,
        16,
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        25,
        26,
        27,
        28,
        29,
        30,
        31,
        32,
        33,
        34,
        35,
        39,
        40,
        41,
        44,
        45,
        46,
        58,
        59,
        63,
        64,
        65,
        78,
        79,
        80,
        82,
        85,
        86,
        89,
        90,
        82,
        93,
        94
      ];
      let arrOfBetTypes = Object.keys(parseData["1"].betTypes).concat(
        Object.keys(parseData["2"].betTypes).concat(
          Object.keys(parseData["3"].betTypes)
        )
      );
      let uniqueArray = arrOfBetTypes.filter(function(item, pos, self) {
        return self.indexOf(item) == pos;
      });
      uniqueArray = uniqueArray.map(function(x) {
        return parseInt(x, 10);
      });
      switch (filter) {
        case 0:
          if (uniqueArray.length == 0) return true;
          else return false;
        case 1: {
          return !this.$_ifTwoArrHasCommon(set1, uniqueArray);
        }
        case 2: {
          return !this.$_ifTwoArrHasCommon(set2, uniqueArray);
        }
        case 3: {
          return !this.$_ifTwoArrHasCommon(set3, uniqueArray);
        }
        case 4: {
          if (
            !this.$_isEmpty(parseData["2"].betTypes) ||
            !this.$_isEmpty(parseData["3"].betTypes)
          )
            return false;
          else return true;
        }
        case 6: {
          return !this.$_ifTwoArrHasCommon(set6, uniqueArray);
        }
        case 7: {
          return !this.$_ifTwoArrHasCommon(set7, uniqueArray);
        }
        default:
          return false;
      }
    },
    selectedOdds() {
      const { isCombo, selectedOddsType } = this;
      if (isCombo) return 3;
      else return selectedOddsType;
    },

    parseData() {
      var { event } = this;
      let oddsList = {
        1: {
          //全場
          betTypes: {},
          periodId: 1
        },
        2: {
          //上半場
          betTypes: {},
          periodId: 2
        },
        3: {
          //下半場
          betTypes: {},
          periodId: 3
        }
      };

      for (var i = 0; i < event.MarketLines.length; i++) {
        for (var p = 0; p < event.MarketLines[i].WagerSelections.length; p++) {
          let infoObj = {
            MarketlineId: event.MarketLines[i].MarketlineId,
            BetTypeId: event.MarketLines[i].BetTypeId,
            PeriodId: event.MarketLines[i].PeriodId,
            MarketlineStatusId: event.MarketLines[i].MarketlineStatusId,
            PeriodName: event.MarketLines[i].PeriodName,
            BetTypeName: event.MarketLines[i].BetTypeName,
            IsLocked: event.MarketLines[i].IsLocked,
            AwayScore: event.AwayScore,
            HomeScore: event.HomeScore,
            Competition: event.Competition,
            EventDate: event.EventDate,
            EventGroupId: event.EventGroupId,
            EventGroupTypeId: event.EventGroupTypeId,
            HomeTeam: event.HomeTeam,
            AwayTeam: event.AwayTeam,
            Market: event.Market,
            OpenParlay: event.OpenParlay,
            TotalMarketLineCount: event.TotalMarketLineCount,
            SportId: this.sportId,
            EventId: event.EventId
          };
          event.MarketLines[i].WagerSelections[p] = Object.assign(
            {},
            event.MarketLines[i].WagerSelections[p],
            infoObj
          );
        }
        //第一層是periodId
        oddsList[event.MarketLines[i].PeriodId]["betTypes"][
          event.MarketLines[i].BetTypeId
        ] = {
          ...oddsList[event.MarketLines[i].PeriodId]["betTypes"][
            event.MarketLines[i].BetTypeId
          ]
        };
        if (
          oddsList[event.MarketLines[i].PeriodId]["betTypes"][
            event.MarketLines[i].BetTypeId
          ]["oddsList"] == undefined
        ) {
          oddsList[event.MarketLines[i].PeriodId]["betTypes"][
            event.MarketLines[i].BetTypeId
          ]["oddsList"] = [];
          oddsList[event.MarketLines[i].PeriodId]["betTypes"][
            event.MarketLines[i].BetTypeId
          ]["collapse"] = false;
          oddsList[event.MarketLines[i].PeriodId]["betTypes"][
            event.MarketLines[i].BetTypeId
          ]["info"] = {
            BetTypeId: event.MarketLines[i].BetTypeId,
            BetTypeName: event.MarketLines[i].BetTypeName,
            PeriodId: event.MarketLines[i].PeriodId,
            Specifiers: event.MarketLines[i].WagerSelections[0].Specifiers //新增，為了給title用到specifiers
          };
          oddsList[event.MarketLines[i].PeriodId]["betTypes"][
            event.MarketLines[i].BetTypeId
          ]["oddsList"].push(event.MarketLines[i].WagerSelections);
        } else {
          oddsList[event.MarketLines[i].PeriodId]["betTypes"][
            event.MarketLines[i].BetTypeId
          ]["oddsList"].push(event.MarketLines[i].WagerSelections);
        }

        //第二層是BetTypeId
        //第三層就是賠率了
      }
      return oddsList;
    }
  },
  methods: {
    ...mapMutations(["$_setMorePlayEvent"]),
    ...mapActions(["apiGetSelectedEventInfo"]),
    $_collapse(id) {
      if (!this.collapsedList.includes(id)) this.collapsedList.push(id);
      else {
        this.collapsedList = this.collapsedList.filter(val => val !== id);
      }
    },

    $_scrollTop() {
      $(".tableBody").animate({ scrollTop: 0 }, 400);
    },
    $_ifTwoArrHasCommon(arr1, arr2) {
      return arr1.some(item => arr2.includes(item));
    },
    $_getClassType(betId) {
      switch (betId) {
        case 1:
          return "handicap";
          break;

        default:
          return "twoColumn";
          break;
      }
    },

    $_includeFilter(betInfo) {
      const { BetTypeId, PeriodId } = betInfo;

      const { filter } = this;
      const set1 = [1, 2, 4, 31, 32, 35, 39, 83, 85, 86, 92, 93, 94];
      const set2 = [6, 62];
      //進球
      const set3 = [
        2,
        5,
        6,
        7,
        13,
        14,
        15,
        16,
        17,
        18,
        21,
        22,
        23,
        31,
        32,
        42,
        43,
        46,
        56,
        57,
        58,
        59,
        60,
        61,
        62,
        93,
        94
      ];
      //半場
      const set4 = [
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        14,
        15,
        18,
        19,
        20,
        22,
        23,
        24,
        25,
        26,
        27,
        31,
        32,
        33,
        34,
        35,
        39,
        56,
        57,
        58,
        93,
        94
      ];
      // //特別關注
      const set6 = [
        8,
        13,
        14,
        15,
        16,
        17,
        18,
        19,
        20,
        21,
        22,
        23,
        24,
        25,
        26,
        27,
        28,
        29,
        30,
        31,
        32,
        33,
        34,
        35,
        39,
        40,
        41,
        44,
        45,
        46,
        58,
        59,
        63,
        64,
        65,
        78,
        79,
        80,
        82,
        85,
        86,
        89,
        90,
        82,
        93,
        94
      ];

      switch (filter) {
        case 0:
          return true;
        case 1: {
          if (set1.includes(BetTypeId)) return true;
          else return false;
        }
        case 2: {
          if (set2.includes(BetTypeId)) return true;
          else return false;
        }
        case 3: {
          if (set3.includes(BetTypeId)) return true;
          else return false;
        }
        case 4: {
          if (PeriodId != 1) return true;
          else return false;
        }

        case 6: {
          if (set6.includes(BetTypeId)) return true;
          else return false;
        }
        case 7: {
          if (set7.includes(BetTypeId)) return true;
          else return false;
        }
        default:
          return true;
      }
    },
    $_selectFilter(key) {
      this.filter = key;
    },
    $_isEmpty(obj) {
      for (var key in obj) {
        if (obj.hasOwnProperty(key)) return false;
      }
      return true;
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
        yr: year,
        md: md,
        hm: hr
      };
    },
    $_close() {
      this.$emit("closeFunc");
    },
    $_updateFunc() {
      this.isLoading = true;
      const { isCombo, sportId, selectedOdds, morePlayEvent } = this;
      return new Promise((resolve, reject) => {
        this.apiGetSelectedEventInfo({
          SportId: sportId,
          EventIds: [morePlayEvent.EventId],
          OddsType: selectedOdds,
          IsCombo: isCombo,
          IncludeGroupEvents: false
        })
          .then(res => {
            this.$_setMorePlayEvent({
              ...res.data.Sports[0].Events[0],
              sportId: obj.sportId
            });
            this.isLoading = false;
          })
          .catch(err => {
            this.isLoading = false;
          });
      });
    },
    $_selectOddsType(type) {
      this.selectedOddsType = type.id;
      this.$_updateFunc();
    },
    $_collapseOdds() {
      if (!this.isCombo) this.showOddsType = !this.showOddsType;
    }
  },
  created() {
    this.selectedOddsType = this.oddsType;
  }
};
</script>

<style lang="scss" src="@/scss/router/yabo/main/morePlays.scss"></style>