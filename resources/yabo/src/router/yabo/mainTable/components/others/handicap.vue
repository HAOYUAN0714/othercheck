<template>
  <div class="handicap">
    <div
      class="betBody"
      v-if="!$_isEmpty(layoutData[marketIndex])"
      v-show="(marketIndex==0 && showLive==true) || (marketIndex==1 && showNonLive==true)"
      v-for="(market,marketIndex) in 2"
      :key="market"
    >
      <div class="banner" @click="$_collapseTable(marketIndex)">
        <div class="name">
          <span>{{$t(sportList[sportId].textKey)}}</span>
          <span
            style="padding-left:10px"
          >{{marketIndex==0?$t('TEXT_LIVE'):isCombo==true?$t('TEXT_PARLAY'):isCombo==true?$t('TEXT_PARLAY'):currentMarket==1?$t('TEXT_EARLY'):$t('TEXT_TODAY')}}</span>
        </div>
        <div class="multipleTitle">
          <div class="types">
            <div class="typeName">{{$t('TEXT_SOLO_S')}}</div>
            <div class="typeName">{{$t('TEXT_HANDICAP')}}</div>
            <div class="typeName">{{$t('TEXT_OVERUNDER')}}</div>
            <div class="typeName">{{$t('TEXT_ODDEVEN_S')}}</div>
          </div>
        </div>
        <div class="starColumn"></div>
      </div>
      <el-collapse-transition>
        <div v-show="!collapseTable[marketIndex]">
          <transition-group name="list">
            <div
              class="odds"
              v-if="layoutData[marketIndex]"
              v-for="(event,index) in layoutData[marketIndex]"
              :key="`${event.eventInfo.EventId}_${marketIndex}`"
            >
              <!--
              v-show="index==0 || (event.Competition.CompetitionId!= list.Events[index-1].Competition.CompetitionId)"-->
              <div
                class="league"
                v-if="$_sameLeague(event,layoutData[marketIndex][index-1],index)"
                @click="$_collapseEvent(event.eventInfo.Competition.CompetitionId)"
              >
                {{event.eventInfo.Competition.CompetitionName}}
                <div class="star">
                  <!--<i class="el-icon-star-off" />-->
                </div>
              </div>
              <el-collapse-transition>
                <div
                  style="width:100%"
                  v-show="!collapsedCompetitionList.includes(event.eventInfo.Competition.CompetitionId)"
                >
                  <div
                    class="table eventItem"
                    v-for="i in event.maxMarketLevel"
                    :key="`${i}-${event.eventInfo.EventId}`"
                  >
                    <div class="timeMatchWrap">
                      <div class="timeInfo" v-if="event.eventInfo.Market==3">
                        <div
                          class="score"
                          v-show="i==1 && event.eventInfo.HomeScore!=null"
                        >{{event.eventInfo.HomeScore}}:{{event.eventInfo.AwayScore}}</div>
                        <div class="score" v-show="i==1">
                          <realTimeClock
                            :event="event.eventInfo"
                            :sportId="sportId"
                            :time="event.eventInfo.RBTime"
                          />
                        </div>
                      </div>
                      <div class="timeInfo" v-else>
                        <div
                          class="monthDate"
                          v-show="i==1"
                        >{{$_parseDate(event.eventInfo.EventDate).md}}</div>
                        <div
                          class="hourMinute"
                          v-show="i==1"
                        >{{$_parseDate(event.eventInfo.EventDate).hm}}</div>
                      </div>
                      <div class="matchInfo">
                        <div class="team" :title="event.eventInfo.HomeTeam">
                          <span
                            :title="event.eventInfo.HomeTeam"
                            class="teamName"
                            :class="{strongTeam:event.eventInfo.StrongTeam=='HomeTeam'}"
                          >
                            {{event.eventInfo.HomeTeam}}
                            <template
                              v-if="event.eventInfo.GroundTypeId==0"
                            >{{$t('TEXT_NEUTRAL_S')}}</template>
                          </span>
                          <span
                            v-show="event.eventInfo.HomeRedCard!=0 &&  event.eventInfo.HomeRedCard!=null"
                            class="redCard"
                          >{{event.eventInfo.HomeRedCard}}</span>
                        </div>
                        <div class="team" :title="event.eventInfo.AwayTeam">
                          <span
                            :title="event.eventInfo.AwayTeam"
                            class="teamName"
                            :class="{strongTeam:event.eventInfo.StrongTeam=='AwayTeam'}"
                          >{{event.eventInfo.AwayTeam}}</span>
                          <span
                            v-show="event.eventInfo.AwayRedCard!=0 &&  event.eventInfo.AwayRedCard!=null"
                            class="redCard"
                          >{{event.eventInfo.AwayRedCard}}</span>
                        </div>
                      </div>
                    </div>
                    <div class="oddsTable" v-if="event['oddsList'][1]">
                      <!--全場獨贏-->
                      <div class="oddsColumn noneCentering" v-if="event['oddsList'][1][4][i]">
                        <div
                          class="oddsWrap"
                          v-for="(odds,oIndex) in event['oddsList'][1][4][i]"
                          :key="odds.WagerSelectionId"
                        >
                          <!-- <div class="hdc">{{oIndex==0?odds.Handicap*-1:odds.Handicap}}</div> -->
                          <!-- <div @click="$_placeBets" class="odds">{{odds.Odds}}</div> -->
                          <oddsItem :oddsSet="odds" />
                        </div>
                      </div>
                      <div class="oddsColumn noneCentering" v-else />
                      <!--全場讓球，有時候會沒有該賠率-->
                      <div class="oddsColumn noneCentering" v-if="event['oddsList'][1][1][i]">
                        <div
                          class="oddsWrap"
                          v-for="(odds,oIndex) in event['oddsList'][1][1][i]"
                          :key="odds.WagerSelectionId"
                        >
                          <!-- <div class="hdc">{{oIndex==0?odds.Handicap*-1:odds.Handicap}}</div>
                          <div @click="$_placeBets" class="odds">{{odds.Odds}}</div>-->
                          <oddsItem :oddsSet="odds" />
                        </div>
                      </div>
                      <div class="oddsColumn noneCentering" v-else />
                      <!--全場大小-->
                      <div class="oddsColumn noneCentering" v-if="event['oddsList'][1][2][i]">
                        <div
                          class="oddsWrap"
                          v-for="(odds,oIndex) in event['oddsList'][1][2][i]"
                          :key="odds.WagerSelectionId"
                        >
                          <!-- <div class="hdc">{{oIndex==0?odds.Handicap*-1:odds.Handicap}}</div>
                          <div @click="$_placeBets" class="odds">{{odds.Odds}}</div>-->
                          <oddsItem :oddsSet="odds" />
                        </div>
                      </div>
                      <div class="oddsColumn noneCentering" v-else />
                      <!--全場單雙-->
                      <div class="oddsColumn noneCentering" v-if="event['oddsList'][1][5][i]">
                        <div
                          class="oddsWrap"
                          v-for="(odds,oIndex) in event['oddsList'][1][5][i]"
                          :key="odds.WagerSelectionId"
                        >
                          <!-- <div class="hdc">{{oIndex==0?odds.Handicap*-1:odds.Handicap}}</div>
                          <div @click="$_placeBets" class="odds">{{odds.Odds}}</div>-->
                          <oddsItem :oddsSet="odds" />
                        </div>
                      </div>
                      <div class="oddsColumn noneCentering" v-else />
                      <div class="starColumn">
                        <div
                          v-show="i==1 && event.eventInfo.TotalMarketLineCount>0"
                          class="morePlaysBtn"
                          @click="$_showMorePlays(event.eventInfo)"
                        >{{event.eventInfo.TotalMarketLineCount}} ></div>
                      </div>
                    </div>
                  </div>
                </div>
              </el-collapse-transition>
            </div>
          </transition-group>
        </div>
      </el-collapse-transition>
    </div>
    <!-- <div
      v-else
      class="empty"
      v-show="((marketIndex==0 && showLive==true) || (marketIndex==1 && showNonLive==true) )&& marketIndex!=0"
    >{{$t('TEXT_NO_MATCH_DATA')}}</div>-->
    <div class="pagerWrap" v-show="totalPage>1">
      <pageComponent
        @goPage="$_selectPage"
        @goTop="$_scrollTop"
        :currentPage="currentPage"
        :totalPage="totalPage"
      />
    </div>
  </div>
</template>
<script>
import oddsItem from "../oddsItem";
import realTimeClock from "@/components/common/realTimeClock";
import pageComponent from "@/components/common/pageComponent";
import oddsWrapMixin from "../oddsWrapMixin";
export default {
  mixins: [oddsWrapMixin],
  components: { realTimeClock, pageComponent, oddsItem },
  methods: {}
};
</script>
<style lang="scss" src="@/scss/router/yabo/main/oddsTable.scss"></style>
