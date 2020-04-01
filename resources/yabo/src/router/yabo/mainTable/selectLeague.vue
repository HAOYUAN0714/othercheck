<template>
  <div class="selectLeagueTable">
    <div class="header">
      {{$t(sportList[this.selectedSport]["textKey"])}} {{$t('TEXT_EVENT_NAME')}} {{$t('TEXT_MENU')}}
      <div class="right" @click="$_checkAll">
        <span>{{$t('TEXT_SELECT_ALL')}}</span>
        <div class="checkBox">
          <i v-show="checkAll" class="checkIcon el-icon-check" />
        </div>
      </div>
    </div>
    <div class="body" v-if="betType!=11">
      <div class="countrySet" v-for="(country,countryId) in leagueList" :key="countryId">
        <div class="countryTab" @click="$_collapseCountry(countryId)">
          <div class="expandIcon" :class="{active:!collapsedCountry.includes(countryId)}">
            <i class="el-icon-arrow-down" />
          </div>
          <span>{{country.ProgrammeName}}</span>
        </div>
        <el-collapse-transition>
          <div class="countryCompetitions" v-show="!collapsedCountry.includes(countryId)">
            <div
              class="leagueTab"
              :class="{active:selectedLeagueList.includes(league.CompetitionId)}"
              @click="$_checkLeague(league.CompetitionId)"
              v-for="(league,index) in country.Competitions"
              :key="`${league.CompetitionName}${index}`"
            >
              <div class="checkBox">
                <i
                  v-show="selectedLeagueList.includes(league.CompetitionId)"
                  class="checkIcon el-icon-check"
                />
              </div>
              <div class="name">{{league.CompetitionName}}</div>
              <div class="count">{{league.Count}}</div>
            </div>
          </div>
        </el-collapse-transition>
      </div>
    </div>
    <div class="body" v-else>
      <div
        class="leagueTab"
        :class="{active:selectedLeagueList.includes(leagueId)}"
        @click="$_checkLeague(leagueId)"
        v-for="(league,leagueId) in leagueList"
        :key="leagueId"
      >
        <div class="checkBox">
          <i v-show="selectedLeagueList.includes(leagueId)" class="checkIcon el-icon-check" />
        </div>
        <div class="name">{{league.CompetitionName}}</div>
        <div class="count">{{league.Count}}</div>
      </div>
    </div>

    <div class="bottom">
      <div class="button" @click="$_cancel">{{$t('TEXT_CANCEL')}}</div>
      <div class="button" @click="$_send">{{$t('TEXT_SEND')}}</div>
    </div>
  </div>
</template>
<script>
import { mapState, mapActions, mapMutations } from "vuex";
import config from "@/config/config";
export default {
  data() {
    return {
      //球種清單mapping
      sportList: config.sportList,
      //league list
      leagueList: {},
      //選擇全部
      checkAll: true,
      listLength: 0,
      collapsedCountry: [],
      selectedLeagueList: []
    };
  },
  watch: {
    "$store.state.common.lang"() {
      this.$_updateFunc();
    }
  },
  computed: {
    ...mapState({
      market: state => state.status.Market,
      isCombo: state => state.status.isCombo,

      selectedSport: state => state.status.selectedSport,
      betType: state => state.oddsTable.selectedBetType
    })
  },
  props: {
    selectedDate: {
      type: String,
      default: () => null
    },
    //已經選取出來的array
    filteredLeagues: {
      type: Array,
      default: () => []
    }
  },

  methods: {
    $_updateFunc() {
      this.$_getAllCompetitionCounts();
    },
    $_collapseCountry(id) {
      if (this.collapsedCountry.includes(id)) {
        const index = this.collapsedCountry.indexOf(id);
        this.collapsedCountry.splice(index, 1);
      } else {
        this.collapsedCountry.push(id);
      }
    },
    $_getAllCompetitionCounts() {
      const { selectedSport, selectedDate, market, isCombo, betType } = this;
      // const target= betType==11?"":"$_getAllCompetitionCount";
      const target =
        betType == 11
          ? "$_getChampionCompetitionList"
          : "$_getAllCompetitionCount";
      this.$store
        .dispatch(target, {
          SportId: selectedSport,
          IsCombo: isCombo,
          Market: market,
          EventDate: selectedDate,
          IncludeCloseEvent: false
        })
        .then(res => {
          let leagueArr = this.leagueList;
          let length = this.listLength;
          if (betType != 11) {
            for (var i = 0; i < res.data.CompetitionCount.length; i++) {
              if (!leagueArr[res.data.CompetitionCount[i].ProgrammeId])
                leagueArr[res.data.CompetitionCount[i].ProgrammeId] = {
                  ProgrammeName: res.data.CompetitionCount[i].ProgrammeName,
                  Competitions: []
                };
              let included = false;
              for (
                var k = 0;
                k <
                leagueArr[res.data.CompetitionCount[i].ProgrammeId].Competitions
                  .length;
                k++
              ) {
                if (
                  leagueArr[res.data.CompetitionCount[i].ProgrammeId]
                    .Competitions[k].CompetitionId ==
                  res.data.CompetitionCount[i].CompetitionId
                ) {
                  leagueArr[
                    res.data.CompetitionCount[i].ProgrammeId
                  ].Competitions[k].Count += res.data.CompetitionCount[i].Count;
                  included = true;
                  break;
                }
              }
              if (!included) {
                leagueArr[
                  res.data.CompetitionCount[i].ProgrammeId
                ].Competitions.push(res.data.CompetitionCount[i]);
                //注意，這裡之後要加上同聯盟id要合併
                length += 1;
              }
            }
          } else {
            for (let id in res.data.league) {
              if (res.data.league.hasOwnProperty(id)) {
                leagueArr[id] = {
                  ...res.data.league[id],
                  check: true
                };
              }
              length += 1;
            }
          }
          this.listLength = length;
          this.leagueList = leagueArr;
          this.$_filterLeague();
        });
      if (market == 2) {
        //如果是今日
        this.$store
          .dispatch(target, {
            SportId: selectedSport,
            IsCombo: isCombo,
            Market: 3, //要包含滾球
            EventDate: selectedDate,
            IncludeCloseEvent: false
          })
          .then(res => {
            let leagueArr = JSON.parse(JSON.stringify(this.leagueList));
            let length = JSON.parse(JSON.stringify(this.listLength));
            if (betType != 11) {
              for (var i = 0; i < res.data.CompetitionCount.length; i++) {
                if (!leagueArr[res.data.CompetitionCount[i].ProgrammeId])
                  leagueArr[res.data.CompetitionCount[i].ProgrammeId] = {
                    ProgrammeName: res.data.CompetitionCount[i].ProgrammeName,
                    Competitions: []
                  };
                let included = false;
                for (
                  var k = 0;
                  k <
                  leagueArr[res.data.CompetitionCount[i].ProgrammeId]
                    .Competitions.length;
                  k++
                ) {
                  if (
                    leagueArr[res.data.CompetitionCount[i].ProgrammeId]
                      .Competitions[k].EventId ==
                    res.data.CompetitionCount[i].CompetitionId
                  ) {
                    leagueArr[
                      res.data.CompetitionCount[i].ProgrammeId
                    ].Competitions[k].Count +=
                      res.data.CompetitionCount[i].Count;
                    included = true;
                    break;
                  }
                }
                if (!included) {
                  leagueArr[
                    res.data.CompetitionCount[i].ProgrammeId
                  ].Competitions.push(res.data.CompetitionCount[i]);

                  length += 1;
                }
              }
            } else {
              for (let id in res.data.league) {
                if (res.data.league.hasOwnProperty(id)) {
                  leagueArr[id] = {
                    ...res.data.league[id],
                    check: true
                  };
                }
                length += 1;
              }
            }
            this.listLength = length;
            this.leagueList = leagueArr;
            this.$_filterLeague();
          });
      }
    },
    $_filterLeague() {
      const { filteredLeagues } = this;
      if (filteredLeagues.length != 0) {
        this.selectedLeagueList = JSON.parse(JSON.stringify(filteredLeagues));
      }
      this.$forceUpdate();
    },
    $_checkLeague(id) {
      if (this.selectedLeagueList.includes(id)) {
        const index = this.selectedLeagueList.indexOf(id);
        this.selectedLeagueList.splice(index, 1);
      } else {
        this.selectedLeagueList.push(id);
      }
      //this.$forceUpdate();
    },
    $_checkAll() {
      var { leagueList, betType } = this;
      if (!this.checkAll) {
        this.selectedLeagueList = [];
        for (var countryId in leagueList) {
          if (leagueList.hasOwnProperty(countryId)) {
            if (betType != 11)
              for (
                var i = 0;
                i < leagueList[countryId].Competitions.length;
                i++
              ) {
                this.selectedLeagueList.push(
                  leagueList[countryId].Competitions[i].CompetitionId
                );
              }
            else {
              this.selectedLeagueList.push(
                String(leagueList[countryId].CompetitionId)
              );
            }
          }
        }
      } else {
        this.selectedLeagueList = [];
      }
      this.checkAll = !this.checkAll;
    },
    $_cancel() {
      this.$emit("cancel");
    },
    $_send() {
      var { selectedLeagueList } = this;

      this.$emit("sendByLeagues", selectedLeagueList);
    }
  },
  created() {
    this.$_getAllCompetitionCounts();
    this.$_checkAll();
  }
};
</script>
<style lang="scss" src="@/scss/router/yabo/main/selectLeague.scss"></style>