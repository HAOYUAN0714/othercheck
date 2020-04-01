<template>
  <div class="mainTable">
    <morePlays
      :sportId="morePlayEvent.sportId"
      :oddsType="selectedOddsType"
      :event="morePlayEvent"
      @closeFunc="$_closeMorePlay"
      v-if="morePlayEvent.EventId"
    />
    <loadingMask />
    <div class="marqueeWrap" @click="$_routerLink('marquee')">
      <div class="bullhorn" />
      <marquee onmouseover="this.stop();" onmouseout="this.start();" class="marquee">{{marquee}}</marquee>
    </div>
    <div class="moreWrap">
      <div class="option" @click="$_routerLink('betHistory')">{{$t('TEXT_BET_HISTORY')}}</div>
      <div class="option" @click="$_routerLink('settledBet')">{{$t('TEXT_SETTLED_BET')}}</div>
      <div class="option" @click="$_routerLink('matchResult')">{{$t('TEXT_RESULT')}}</div>
      <div class="option" @click="$_routerLink('tutorials')">{{$t('TEXT_TUTORIAL')}}</div>
    </div>
    <div class="oddsTable" v-show="!selectLeague">
      <div class="header">
        <!-- <div class="allButton">{{$t("TEXT_ALL")}}</div>
        <i class="star el-icon-star-on" />-->

        <div class="rightWrap">
          <div
            class="oddsType"
            :title="isCombo==true?$t('TEXT_COMBO_ONLY_EUROPE'):''"
            ref="showOdds"
            @click="$_collapseOdds"
          >
            {{$t('TEXT_ODDS_TYPE')}}：
            {{$t(OddsTypeConfig[selectedOddsType].textKey)}}
            <div class="expandIcon" :class="{active:showOddsType}">
              <i v-show="!isCombo" class="el-icon-arrow-down" />
            </div>

            <div
              class="marketDrop"
              v-closable="{
        exclude: ['showOdds'],
        handler: '$_onCloseOdds'}"
              v-show="showOddsType"
            >
              <div
                class="marketItem"
                @click="$_selectOddsType(odds)"
                :class="{active:odds.id == selectedOddsType}"
                v-for="odds in oddsDropArr"
                :key="odds.id"
              >{{$t(odds.textKey)}}</div>
            </div>
          </div>
          <div
            class="selectLeague"
            v-show="selectedPlayListIds.length==0"
            @click="$_selectLeague(true)"
          >
            {{$t('TEXT_SELECT_LEAGUE')}}
            <span
              class="leagueCount"
              v-if="selectedBetType==11"
              v-show="championLeagueList.length>0"
            >{{championLeagueList.length}}</span>
            <span
              class="leagueCount"
              v-if="selectedBetType!=11"
              v-show="leagueList.length>0"
            >{{leagueList.length}}</span>
            <span
              class="leagueCount"
              v-if="selectedBetType==11?championLeagueList.length==0:leagueList==0"
            >{{$t('TEXT_ALL')}}</span>
          </div>
          <div
            class="show"
            v-show="selectedSport==1 && selectedBetType==1 && selectedPlayListIds.length==0"
            @click="show=!show"
          >
            <span v-show="show">{{$t('TEXT_SINGLE_LINE')}}</span>
            <span v-show="!show">{{$t('TEXT_STANDARD_LINE')}}</span>
          </div>
          <div class="orderBy" ref="showOrder" @click="showOrder=!showOrder">
            <i class="el-icon-time" v-if="orderBy==1" />
            <i class="el-icon-trophy" v-if="orderBy==2" />
            <i class="sortIcon el-icon-sort" />

            <div
              class="marketDrop"
              v-closable="{
        exclude: ['showOrder'],
        handler: '$_onCloseSort'}"
              v-show="showOrder"
            >
              <div
                class="marketItem"
                @click="$_selectOrder(market)"
                :class="{active:market.id == orderBy}"
                v-for="market in orderList"
                :key="market.id"
              >{{$t(market.textKey)}}</div>
            </div>
          </div>
          <div
            class="refresh"
            :title=" updateTimeCountDown>20?$t('TEXT_WAIT_UNTIL_20s'):''"
            :class="{blocked:updateTimeCountDown>20}"
            @click="$_timerUpdateFunc(false)"
          >
            <i class="el-icon-refresh" v-if="!isRefreshing" />
            <i class="el-icon-loading" v-if="isRefreshing" />
          </div>
          <update-timer
            ref="updateTimer"
            :updateTime="updateTime"
            @updateFunc="$_updateFunc(false)"
          ></update-timer>
          <!--給滾球用的，每五秒更新一次-->
          <update-timer
            ref="liveTimer"
            style="display:none"
            :updateTime="5"
            @updateFunc="$_updateLiveEvent"
          ></update-timer>
        </div>
      </div>
      <div class="breakfastWrap" v-if="market==1 || isCombo==true">
        <div
          class="breakfastDate"
          @click="$_selectDate(null)"
          :class="{active:selectedDate==null}"
        >{{$t('TEXT_ALL_DATES')}}</div>
        <div
          class="breakfastDate"
          v-show="isCombo"
          @click="$_selectDate(today)"
          :class="{active:selectedDate==`${today.yr}/${today.md}`}"
        >{{$t('TEXT_TODAY')}}</div>
        <div
          class="breakfastDate"
          :class="{active:selectedDate==`${date.yr}/${date.md}`}"
          v-for="date in dateWrap"
          @click="$_selectDate(date)"
          :key="date.md"
        >{{date.yr}}/{{date.md}}</div>
      </div>
      <!--只有選擇單一球種時-->
      <div class="oddsContainer" :class="{breakfast:isCombo || market==1 }">
        <component
          :show="show"
          :is="`${$_componentSport(selectedSport)}${betTypeId[selectedBetType]['typeName']}`"
          :key="`${selectedSport}${betTypeId[selectedBetType]['typeName']}`"
          :totalPage="totalPage"
          :sportId="selectedSport"
          :showLive="market==1?false:true"
          :list="selectedBetType==11?championData:selectedPlay"
          :orderBy="orderBy"
          :liveList="selectedLivePlay"
          :class="{breakfast:market==1 || isCombo ==true}"
          :betType="selectedBetType"
          :oddsType="selectedOddsType"
          @goPage="$_selectPage"
          @goTop="$_scrollTop"
          :currentPage="page"
          v-if="selectedPlayListIds.length==0"
        />
        <div
          v-if="showEmptySport"
          class="emptySport"
        >{{loading.length!=0?$t('TEXT_LOADING'):$t('TEXT_NO_MATCH_DATA')}}</div>
        <!--如果多個球種-->
        <compoennt
          v-if="selectedPlayListIds.length!=0"
          v-for="sport in selectedPlayListIds"
          :is="`${$_componentSport(sport)}Handicap`"
          :key="`${sport}-Live`"
          :totalPage="1"
          :showNonLive="false"
          :orderBy="orderBy"
          :sportId="sport"
          :currentPage="1"
          :betType="1"
          :liveList="selectedPlayList[sport]"
          :oddsType="selectedOddsType"
        />
      </div>
    </div>
    <selectLeague
      :selectedDate="selectedDate"
      :filteredLeagues="selectedBetType==11?championLeagueList:leagueList"
      @cancel="$_selectLeague(false)"
      @sendByLeagues="$_setLeagueIds"
      v-if="selectLeague"
    />
  </div>
</template>
<script>
import { mapState, mapActions, mapMutations } from "vuex";
import moment from "moment";
import $ from "jquery";
import loadingMask from "@/components/common/loadingMask";
import bullhorn from "vue-material-design-icons/BullhornOutline";
import SoccerHandicap from "./components/soccer/handicap";
import SoccerSolowin from "./components/soccer/solowin";
import SoccerOddEven from "./components/soccer/oddEven";
import SoccerTotalScores from "./components/soccer/totalScores";
import SoccerBodan from "./components/soccer/bodan";
import SoccerChampion from "./components/others/champion";
import SoccerHalfAll from "./components/soccer/halfAll";
//

//
import normalChampion from "./components/others/champion";
import normalHandicap from "./components/others/handicap";
//
import morePlays from "./morePlays";
import updateTimer from "@/components/common/updateTimer";
import selectLeague from "./selectLeague";
import config from "../../../config/config";
export default {
  components: {
    bullhorn,
    loadingMask,
    SoccerBodan,
    SoccerHandicap,
    SoccerSolowin,
    SoccerOddEven,
    SoccerTotalScores,
    SoccerChampion,
    SoccerHalfAll,
    normalChampion,
    normalHandicap,
    selectLeague,
    updateTimer,
    morePlays
  },
  data() {
    return {
      isMounted: false,
      langList: config.LangList,
      //盤口清單
      OddsTypeConfig: config.OddsTypeConfig,
      oddsDropArr: config.oddsDropArr,
      //排序清單
      orderList: config.orderList,
      //球種清單mapping
      sportList: config.sportList,
      //玩法ip
      betTypeId: config.BetTypeId,

      //現在頁碼
      page: 1,
      //總共頁數
      totalPage: 1,
      //排序依照
      orderBy: 2,
      //盤口下拉key
      showOddsType: false,
      //排序下拉key
      showOrder: false,
      //選擇日期(僅供早餐)
      selectedDate: null,
      //oddslist版本
      delta: 0,
      //過關模式

      //選擇聯盟trigger
      selectLeague: false,
      //以聯盟篩選的id
      leagueList: [],
      //冠軍已聯盟篩選
      championLeagueList: [],
      //更新時間
      updateTime: 30,
      //更多玩法的event
      // morePlayEvent: {},
      //正在定時更新/手動更新的trigger
      isRefreshing: false,
      //冠軍
      championData: {},
      //單線盤口true，標準顯示false
      show: true
    };
  },
  watch: {
    market() {
      this.page = 1;
      this.leagueList = [];
      this.championLeagueList = [];
      this.selectLeague = false;
      this.$_clearMorePlayEvent();
      if (this.isCombo == false) {
        //避免一直重新call
        this.$_updateFunc();
      }
      // this.morePlayEvent = {};
    },
    selectedOddsType() {
      this.$_updateFunc();
    },
    selectedBetType(newType, oldType) {
      if (newType == 11 || oldType == 11) {
        this.selectLeague = false;
      }
    },
    selectedPlayListIds() {
      if (this.selectedPlayListIds.length != 0) {
        this.$_changeMarket(2); //切換今日所有日期
        this.$_setIsCombo(false);
        this.selectLeague = false;
      } else {
        //修正：結束選取滾球賽是時，讓market切成
      }
    },
    isCombo() {
      this.selectedDate = null; //預設變成全部日期
      if (this.isCombo == true) {
        //如果切換到過關模式
        this.$_changeMarket(1); //切成早餐所有日期
        //清空冠軍，因為連串不該有冠軍
        this.championData = { Events: [] };
        this.$_setOddsType(3); //串關只能有歐洲盤
      }
      this.page = 1;
      this.leagueList = [];
      this.championLeagueList = [];
      this.selectLeague = false;
      this.$_clearMorePlayEvent();
      this.$_updateFunc();
      // this.morePlayEvent = {};
    },
    orderBy() {
      this.page = 1;
      this.$_updateFunc();
    },
    selectedSport() {
      if (!this.isCombo) this.$_clear();
      this.$_getEventInfo(this.selectedSport);
      this.$_getLiveEventInfo(this.selectedSport);
    }
  },
  computed: {
    ...mapState({
      announcements: state => state.common.announcements,
      morePlayEvent: state => state.oddsTable.morePlayEvent,
      loading: state => state.common.loading,
      market: state => state.status.Market,
      isCombo: state => state.status.isCombo,
      selectedSport: state => state.status.selectedSport,
      selectedBetType: state => state.oddsTable.selectedBetType,
      selectedPlay: state => state.status.selectedPlay,
      selectedPlayListIds: state => state.status.selectedPlayListIds,
      selectedLivePlay: state => state.status.selectedLivePlay,
      selectedPlayList: state => state.status.selectedPlayList,
      selectedOddsType: state => state.status.selectedOddsType
    }),
    marquee() {
      let { announcements } = this;
      let str = "";
      announcements = announcements.sort((a, b) =>
        moment(b.PostingDate).diff(moment(a.PostingDate))
      );
      announcements.forEach(ann => {
        if (ann.AnnouncementDetail[0].LanguageCode == "CHS")
          str += ann.AnnouncementDetail[0].Content;
        if (ann.AnnouncementDetail[1].LanguageCode == "CHS")
          str += ann.AnnouncementDetail[1].Content;
      });
      return str;
    },

    updateTimeCountDown() {
      if (!this.isMounted) return;
      const _updateTimer = this.$refs.updateTimer;
      if (_updateTimer) {
        return _updateTimer.update;
      }
    },
    showEmptySport() {
      const {
        selectedLivePlay,
        selectedPlay,
        selectedBetType,
        selectedPlayListIds,
        market,
        championData
      } = this;
      if (selectedPlayListIds.length != 0) {
        //只要正在觀看滾球賽事清單，就不要顯示
        return false;
      }
      if (selectedBetType == 11 && championData.Events.length == 0) {
        return true;
      }
      if (
        selectedLivePlay.Events.length == 0 &&
        selectedPlay.Events.length == 0 &&
        selectedBetType != 11 &&
        market == 2
      ) {
        return true;
      }
      if (selectedPlay.Events.length == 0 && market == 1) {
        return true;
      }
      //如果滾球以及非滾球都空的才會顯示
      return false;
    },
    updateTimeCount() {
      const _updateTimer = this.$refs.updateTimer;
      if (_updateTimer) return _updateTimer.update;
    },
    today() {
      const today = new Date();
      return this.$_parseDate(today);
    },
    dateWrap() {
      const today = new Date();
      const day1 = new Date(today);
      const day2 = new Date(today);
      const day3 = new Date(today);
      const day4 = new Date(today);
      const day5 = new Date(today);
      const day6 = new Date(today);
      const day7 = new Date(today);
      day1.setDate(day1.getDate() + 1);
      day2.setDate(day2.getDate() + 2);
      day3.setDate(day3.getDate() + 3);
      day4.setDate(day4.getDate() + 4);
      day5.setDate(day5.getDate() + 5);
      day6.setDate(day6.getDate() + 6);
      day7.setDate(day7.getDate() + 7);
      let dateArr = [];
      dateArr.push(this.$_parseDate(day1));
      dateArr.push(this.$_parseDate(day2));
      dateArr.push(this.$_parseDate(day3));
      dateArr.push(this.$_parseDate(day4));
      dateArr.push(this.$_parseDate(day5));
      dateArr.push(this.$_parseDate(day6));
      dateArr.push(this.$_parseDate(day7));

      return dateArr;
    }
  },
  methods: {
    ...mapMutations([
      "$_clearMorePlayEvent",
      "$_changeMarket",
      "$_setIsCombo",
      "$_clearWager",
      "$_clearWagerList",
      "$_setPlayListIds",
      "$_setOddsType",
      "$_setLivePlay",
      "$_setSelectedPlay",
      "$_setPlayList",
      "$_setLiveSports"
    ]),
    ...mapActions([
      "apiGetFilteredChampions",
      "apiGetLiveEventInfo",
      "apiGetEventInfoByPage",
      "apiGetEventInfoAllMarket"
    ]),
    $_collapseOdds() {
      const { isCombo } = this;
      if (!isCombo) this.showOddsType = !this.showOddsType;
    },
    $_updateLiveEvent() {
      const {
        market,
        selectedPlayListIds,
        selectedOddsType,
        isCombo,
        orderBy
      } = this;
      if (selectedPlayListIds.length == 0) {
        if (market != 1) {
          this.$_getLiveEventInfo(this.selectedSport);
        }
      } else {
        this.apiGetLiveEventInfo({
          SportIds: selectedPlayListIds,
          //CompetitionIds: this.leagueList 滾球清單的聯盟篩選先不適用
          OddsType: selectedOddsType,
          BetTypeIds: [1, 2, 3, 4, 5, 6, 7, 9],
          IsCombo: isCombo,
          OrderBy: orderBy
        }).then(res => {
          if (res.data.Sports.length) {
            res.data.Sports.forEach(sport => {
              this.$_setPlayList({
                sportId: sport.SportId,
                data: sport
              });
            });
          }
        });
      }
    },
    $_onCloseOdds() {
      this.showOddsType = false;
    },
    $_onCloseSort() {
      this.showOrder = false;
    },
    $_componentSport(sport) {
      switch (sport) {
        case 1:
          return this.sportList[sport]["iconKey"];

        default:
          return "normal";
      }
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
    //選擇日期，僅供早餐盤
    $_selectDate(date) {
      if (date == null) {
        this.selectedDate = null;
        if (this.isCombo == true) {
          //同時還是過關
          this.$_changeMarket(1);
        }
        this.page = 1;
        this.$_updateFunc();
        this.$_scrollTop();
        //全部時間
      } else {
        this.selectedDate = `${date.yr}/${date.md}`; //選擇日期
        if (this.isCombo == true) {
          //如果是今日
          if (this.selectedDate == `${this.today.yr}/${this.today.md}`) {
            //同時還是過關
            this.$_changeMarket(2);
          } else {
            this.$_changeMarket(1);
          }
        }
        this.page = 1;
        this.leagueList = [];
        this.$_updateFunc();
        this.$_scrollTop();
      }
    },
    $_routerLink(routerName) {
      this.$_openNewTab({
        name: routerName
      });
    },
    $_openNewTab(to) {
      const routeData = this.$router.resolve(to);
      window.open(
        routeData.href,
        "_blank",
        "toolbar=yes, width=1000, height=800"
      );
    },

    $_scrollTop() {
      $(".oddsContainer").animate({ scrollTop: 0 }, 400);
    },
    $_selectOddsType(type) {
      this.$_setOddsType(type.id);
    },
    $_selectOrder(order) {
      this.orderBy = order.id;
    },
    //取得event info by page
    $_getEventInfo(sportId = 1, betType = 1, loadingMask = true) {
      const {
        isCombo,
        market,
        leagueList,
        selectedDate,
        selectedOddsType,
        page,
        orderBy,
        championLeagueList
      } = this;
      return new Promise((resolve, reject) => {
        if (sportId != this.selectedSport || betType != this.selectedBetType)
          this.page = 1;

        // if (this.market != 1)
        //非才能call
        if (!isCombo)
          this.apiGetFilteredChampions({
            SportId: sportId,
            Market: market,
            CompetitionIds: championLeagueList,
            EventDate: selectedDate,
            OddsType: selectedOddsType,
            IsCombo: isCombo,
            BetTypeIds: [11],
            OrderBy: orderBy,
            Page: page,
            loadingMask: loadingMask
          }).then(res => {
            this.championData = {};
            this.championData = { Events: res.data.Events };
          });
        if (isCombo && selectedDate == null) {
          this.apiGetEventInfoAllMarket({
            SportId: sportId,
            Market: market,
            CompetitionIds: leagueList,
            EventDate: selectedDate,
            OddsType: selectedOddsType,
            IsCombo: isCombo,
            BetTypeIds: [1, 2, 3, 4, 5, 6, 7, 9], //因為讓球要有三種 讓球 獨贏 跟大小
            OrderBy: orderBy,
            Page: page,
            loadingMask: loadingMask
          })
            .then(res => {
              if (res.data.Sports[0]) {
                this.$_setSelectedPlay(res.data.Sports[0]);
              } else {
                this.$_setSelectedPlay({ Events: [] });
              }
              this.totalPage = res.data.PageSize || 1;
              return resolve(res);
            })
            .catch(err => {
              return reject(err);
            });
        } else {
          this.apiGetEventInfoByPage({
            SportId: sportId,
            Market: market,
            CompetitionIds: leagueList,
            EventDate: selectedDate,
            OddsType: selectedOddsType,
            IsCombo: isCombo,
            BetTypeIds: [1, 2, 3, 4, 5, 6, 7, 9], //因為讓球要有三種 讓球 獨贏 跟大小
            OrderBy: orderBy,
            Page: page,
            loadingMask: loadingMask
          })
            .then(res => {
              if (res.data.Sports[0]) {
                this.$_setSelectedPlay(res.data.Sports[0]);
              } else {
                this.$_setSelectedPlay({ Events: [] });
              }
              this.totalPage = res.data.PageSize || 1;
              return resolve(res);
            })
            .catch(err => {
              return reject(err);
            });
        }
      });
    },
    //取得滾球賽事
    $_getLiveEventInfo(
      sportId = this.selectedSport,
      betType = this.selectedBetType
    ) {
      const { selectedSport, selectedOddsType, selectedBetType } = this;
      if (betType != 11)
        this.apiGetLiveEventInfo({
          SportIds: [sportId],
          CompetitionIds: this.leagueList,
          OddsType: selectedOddsType,
          BetTypeIds: [1, 2, 3, 4, 5, 6, 7, 9], //因為讓球要有三種 讓球 獨贏 跟大小
          IsCombo: this.isCombo,
          OrderBy: this.orderBy
        }).then(res => {
          if (res.data.Sports.length) {
            this.$_setLivePlay(res.data.Sports[0]);
          } else {
            this.$_setLivePlay({ Events: [] });
          }
        });
    },
    $_timerUpdateFunc(loadingMask = true) {
      const { updateTimeCount } = this;
      if (this.updateTimeCount > 20) return;
      else this.$_updateFunc(loadingMask);
    },

    $_updateFunc(loadingMask = true) {
      const _updateTimer = this.$refs.updateTimer;
      const { selectedPlayListIds } = this;
      if (_updateTimer) _updateTimer.reCounter();
      //loading mask有時候更新的時候我不想要讓他一直出現
      //要發出更新通知，讓組建內知道要更新了
      this.isRefreshing = true;
      //取得賽事資料
      if (selectedPlayListIds.length == 0) {
        //如果現在不是在多球種的狀態
        this.$_getEventInfo(
          this.selectedSport,
          this.selectedBetType,
          loadingMask
        )
          .then(res => {
            this.isRefreshing = false;
          })
          .catch(err => {
            this.isRefreshing = false;
          });
        //取得滾球賽事資料
        if (this.market == 2)
          //如果是今日才要拿滾球資料
          this.$_getLiveEventInfo();
      } else {
        this.apiGetLiveEventInfo({
          SportIds: selectedPlayListIds,
          OddsType: this.selectedOddsType,
          BetTypeIds: [1, 2, 3, 4, 5, 6, 7, 9],
          IsCombo: this.isCombo,
          OrderBy: this.orderBy
        })
          .then(res => {
            this.isRefreshing = false;
            res.data.Sports.forEach(sport => {
              this.$_setPlayList({
                sportId: sport.SportId,
                data: sport
              });
            });
          })
          .catch(err => {
            this.isRefreshing = false;
          });
      }
    },
    $_selectPage(page) {
      this.page = page;
      this.$_updateFunc();
      this.$_scrollTop();
    },
    $_selectLeague(key) {
      this.selectLeague = key;
    },
    $_setLeagueIds(list) {
      const { selectedBetType, selectedSport } = this;
      this.selectLeague = false;
      if (selectedBetType != 11) this.leagueList = list;
      else {
        this.championLeagueList = list;
      }
      this.$_updateFunc();
    },

    $_closeMorePlay() {
      //this.morePlayEvent = {};
      this.$_clearMorePlayEvent();
      const _updateTimer = this.$refs.updateTimer;
      if (_updateTimer) _updateTimer.start();
    },

    //換玩法，球種時，要把所有資料清空
    $_clear() {
      this.leagueList = [];
      this.championLeagueList = [];
      this.selectLeague = false;
      this.selectedDate = null;
      this.showOddsType = false;
      this.page = 1;
      this.$_clearMorePlayEvent();
      this.$_setIsCombo(false);
      this.$_setPlayListIds([]);
      this.$_clearWager();
      this.$_clearWagerList();
      const _updateTimer = this.$refs.updateTimer;
      if (_updateTimer) _updateTimer.reCounter();
    }
  },

  mounted() {
    this.isMounted = true;
    this.$_updateFunc();
  }
};
</script>
<style lang="scss" src="@/scss/router/yabo/main/mainTable.scss"></style>