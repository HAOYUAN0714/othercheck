<template>
  <div class="matchResult">
    <div class="header">{{$t('TEXT_RESULT')}}</div>
    <div class="searchWrap">
      <div class="dropList" @click="selectSport=!selectSport">
        {{$t(sportConfig[selectedSport].textKey)}}
        <div class="expandIcon" :class="{active:selectSport}">
          <i class="el-icon-arrow-down"></i>
        </div>
        <el-collapse-transition>
          <div class="dropWrap" v-show="selectSport">
            <div
              class="sportTab"
              v-for="sport in sportList"
              @click="$_selectSport(sport)"
              :key="sport"
            >{{$t(sportConfig[sport].textKey)}}</div>
          </div>
        </el-collapse-transition>
      </div>
      <div class="dropList">
        <div @click="selectLeague=!selectLeague">
          <span>{{$t('TEXT_EVENT_NAME')}}</span>
          <div class="expandIcon" :class="{active:selectLeague}">
            <i class="el-icon-arrow-down"></i>
          </div>
        </div>
        <el-collapse-transition>
          <div class="leagueDrop" v-show="selectLeague">
            <div class="header">
              <div style="display:flex" @click="$_selectAll">
                <div class="checkBox">
                  <i v-show="!selectAll" class="checkIcon el-icon-check" />
                </div>
                {{$t('TEXT_ALL')}}
              </div>
            </div>
            <div class="body">
              <div
                class="leagueItem"
                @click="$_addToSelected(league.CompetitionId)"
                v-for="league in competitionList"
                :key="league.CompetitionId"
              >
                <div class="checkBox">
                  <i
                    v-show="!selectedEvent.includes(league.CompetitionId)"
                    class="checkIcon el-icon-check"
                  />
                </div>
                <span class="name" :title="league.CompetitionName">{{league.CompetitionName}}</span>
              </div>
            </div>
            <div class="footer">
              <div class="button" @click="$_filter">{{$t('TEXT_SEND')}}</div>
            </div>
          </div>
        </el-collapse-transition>
      </div>
    </div>
    <div class="dateOption">
      <div
        class="option"
        @click="$_selectDate(today)"
        :class="{active:selectedDate==today && !champion}"
      >{{$t('TEXT_TODAY')}}</div>
      <div
        class="option"
        v-for="(date) in dateArr"
        :key="date"
        @click="$_selectDate(date)"
        :class="{active:selectedDate==date && !champion}"
      >
        <div>{{$t(`TEXT_DATE_${$_getD(date)}`)}}</div>
        <div>{{date}}</div>
      </div>
      <div
        class="option"
        @click="$_selectDate('champion')"
        :class="{active:champion}"
      >{{$t('TEXT_CHAMPION')}}</div>
    </div>
    <div class="content">
      <loadingMask />
      <div class="matchWrap" v-if="competitionList.length">
        <div
          class="matchTable"
          v-for="(competition,index) in layoutList"
          :key="competition.CompetitionId"
        >
          <div @click="$_addToCollapse(competition.CompetitionId)" class="top">
            <div
              :class="{active:collapsedEvent.includes(competition.CompetitionId)}"
              class="expandIcon"
            >
              <i class="el-icon-arrow-down"></i>
            </div>
            {{competition.CompetitionName}}
            <div class="right" v-show="!champion">
              <div class="time">{{$t('TEXT_HALF_TIME')}}</div>
              <div class="time">{{$t('TEXT_FULL_TIME')}}</div>
            </div>
            <div class="right champion" v-show="champion">
              <div class="time">{{$t('TEXT_WINNER')}}</div>
            </div>
          </div>
          <el-collapse-transition>
            <div class="body" v-show="!collapsedEvent.includes(competition.CompetitionId)">
              <div class="matchTab" v-for="event in competition.Events" :key="event.EventId">
                <!--定時賽-->
                <div class="normalMatch" v-if="!champion">
                  <div class="time">{{commonParseDate(event.EventDate).time}}</div>
                  <div class="match">{{event.HomeTeam}} vs {{event.AwayTeam}}</div>
                  <div class="TH">{{$_getPeriodScore(event.ResultList,2)}}</div>
                  <div class="FT">{{$_getPeriodScore(event.ResultList,1)}}</div>
                </div>
                <!--優勝冠軍-->
                <div class="winners" v-if="champion">
                  <div class="time">
                    <span>{{commonGetFullDate(event.EventDate)}}</span>
                  </div>
                  <div class="match">{{event.EventOutrightName}}</div>
                  <div class="result">{{(event.ResultList[0].OutrightWinnerTeamName)}}</div>
                </div>
              </div>
            </div>
          </el-collapse-transition>
        </div>
      </div>
      <div class="empty" v-else>{{$t('TEXT_NO_DATA_UNDER_SELECTED')}}</div>
    </div>
  </div>
</template>
<script>
import { mapActions } from "vuex";
import $ from "jquery";
import config from "@/config/config";
import loadingMask from "@/components/common/loadingMask";
import moment from "moment";
export default {
  components: {
    loadingMask
  },
  data() {
    return {
      selectedDate: 0,
      sportConfig: config.sportList,
      collapsedEvent: [],
      selectedEvent: [],
      sportList: [
        1,
        2,
        3,
        6,
        7,
        8,
        11,
        13,
        15,
        18,
        19,
        21,
        23,
        25,
        29,
        31,
        32,
        34,
        40
      ], //預設就只有這幾種球種
      selectedSport: 1,
      selectSport: false,
      selectLeague: false,
      competitionList: [],
      allLeagueId: [],
      filterIdArr: [], //有在裡面的不顯示
      filter: false,
      selectAll: true,
      champion: false
    };
  },
  watch: {
    allLeagueId() {
      if (this.allLeagueId.length == this.competitionList.length)
        this.selectAll = true;
      else this.selectAll = false;
    },
    competitionList() {
      let arr = [];
      for (var i = 0; i < this.competitionList.length; i++) {
        arr.push(this.competitionList[i].CompetitionId);
      }
      this.allLeagueId = arr;
    }
  },
  computed: {
    today() {
      const today = new Date();
      return this.$_parseDate(today);
    },
    layoutList() {
      if (this.filter == true) {
        let arr = [];
        for (var i = 0; i < this.competitionList.length; i++) {
          if (
            !this.filterIdArr.includes(this.competitionList[i].CompetitionId)
          ) {
            arr.push(this.competitionList[i]);
          }
        }
        return arr;
      }
      return this.competitionList;
    },
    dateArr() {
      const today = new Date();

      const day1 = new Date(today);
      const day2 = new Date(today);
      const day3 = new Date(today);
      const day4 = new Date(today);
      const day5 = new Date(today);
      const day6 = new Date(today);
      const day7 = new Date(today);
      day1.setDate(day1.getDate() - 1);
      day2.setDate(day2.getDate() - 2);
      day3.setDate(day3.getDate() - 3);
      day4.setDate(day4.getDate() - 4);
      day5.setDate(day5.getDate() - 5);
      day6.setDate(day6.getDate() - 6);
      day7.setDate(day7.getDate() - 7);
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
    ...mapActions(["apiGetCompletedResults"]),
    $_addToCollapse(id) {
      if (this.collapsedEvent.includes(id)) {
        const index = this.collapsedEvent.indexOf(id);
        this.collapsedEvent.splice(index, 1);
      } else {
        this.collapsedEvent.push(id);
      }
    },
    $_addToSelected(id) {
      if (this.selectedEvent.includes(id)) {
        const index = this.selectedEvent.indexOf(id);
        this.selectedEvent.splice(index, 1);
      } else {
        this.selectedEvent.push(id);
      }
    },
    $_includes(id) {
      if (this.allLeagueId.includes(id)) return true;
      else return false;
    },
    $_scrollTop() {
      $(".matchWrap").animate({ scrollTop: 0 }, 400);
    },
    //取得星期
    $_getD(date) {
      return moment(date).format("d");
    },
    $_selectAll() {
      if (this.selectAll == false) {
        this.selectAll = true;
        let arr = [];
        for (var i = 0; i < this.competitionList.length; i++) {
          arr.push(this.competitionList[i].CompetitionId);
        }
        this.selectedEvent = arr;
      } else {
        this.selectAll = false;
        this.selectedEvent = [];
      }
    },
    $_filter() {
      this.filterIdArr = [];
      // for (var i = 0; i < this.competitionList.length; i++) {
      //   if (
      //     !this.selectedEvent.includes(this.competitionList[i].CompetitionId)
      //   ) {
      //     this.filterIdArr.push(this.competitionList[i].CompetitionId);
      //   }
      // }
      for (var i = 0; i < this.selectedEvent.length; i++) {
        this.filterIdArr.push(this.selectedEvent[i]);
      }
      this.selectLeague = false;
      this.filter = true;
    },

    $_parseDate(date) {
      const dt = this.commonParseDate(date);
      return `${dt.year}/${dt.date}`;
    },

    $_collapse(index) {
      this.competitionList[index].collapse = !this.competitionList[index]
        .collapse;
      this.$forceUpdate();
    },
    $_getPeriodScore(list, periodId) {
      for (var i = 0; i < list.length; i++) {
        if (list[i].PeriodId == periodId) {
          if (list[i].HomeScore != null)
            return list[i].HomeScore + "-" + list[i].AwayScore;
        }
      }
      return "-";
    },
    $_selectSport(id) {
      if (this.selectedSport == id) return;
      this.selectedSport = id;
      this.filter = false;
      this.selectedDate = this.today;
      this.$_updateFunc();
    },
    $_selectDate(date) {
      this.filter = false;
      this.champion = false;
      this.selectedDate = date;
      this.collapsedEvent = [];
      if (date == "champion") {
        this.champion = true;
        this.selectedDate = this.today;
      }
      this.$_updateFunc();
    },
    $_updateFunc() {
      this.$_scrollTop();
      const { selectedSport, today, champion, selectedDate } = this;

      var dayAfter = moment(selectedDate)
        .add(1, "day")
        .format("YYYY/MM/DD");
      var sevenDaysBefore = moment(today)
        .subtract(7, "day")
        .format("YYYY/MM/DD");
      this.apiGetCompletedResults({
        SportId: selectedSport,
        EventTypeId: champion == true ? 2 : 1,
        StartDate: champion == true ? sevenDaysBefore : selectedDate,
        EndDate: champion == true ? today : dayAfter
      }).then(res => {
        this.competitionList = res.data.Competitions;
      });
    }
  },
  mounted() {
    this.selectedDate = this.today;
    this.$_updateFunc();
  }
};
</script>

<style lang="scss" src="@/scss/router/singlePage/matchResult.scss"></style>