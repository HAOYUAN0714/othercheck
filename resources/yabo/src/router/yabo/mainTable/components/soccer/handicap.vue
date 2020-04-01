<template>
  <div class="handicap">
    <div
      class="betBody"
      v-if="!$_isEmpty(layoutData[marketIndex]) "
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
            <span class="typeName">{{$t('TEXT_FULL_TIME')}}</span>
            <span class="typeName">{{$t('TEXT_HALF_TIME')}}</span>
          </div>
          <div class="betType" v-show="show">
            <span>{{$t('TEXT_1X2')}}</span>
            <span></span>
            <span>{{$t('TEXT_HANDICAP')}}</span>
            <span></span>
            <span>{{$t('TEXT_OVERUNDER')}}</span>

            <span>{{$t('TEXT_1X2')}}</span>
            <span></span>
            <span>{{$t('TEXT_HANDICAP')}}</span>
            <span></span>
            <span>{{$t('TEXT_OVERUNDER')}}</span>
          </div>
          <div class="betType" v-show="!show">
            <span>{{$t('TEXT_HANDICAP')}}</span>
            <span>{{$t('TEXT_HOME_S_TEAM')}}</span>
            <span>{{$t('TEXT_AWAY_S_TEAM')}}</span>
            <span>{{$t('TEXT_SCORES')}}</span>
            <span>{{$t('TEXT_OVER')}}</span>
            <span>{{$t('TEXT_UNDER')}}</span>
            <span>{{$t('TEXT_HANDICAP')}}</span>
            <span>{{$t('TEXT_HOME_S_TEAM')}}</span>
            <span>{{$t('TEXT_AWAY_S_TEAM')}}</span>
            <span>{{$t('TEXT_SCORES')}}</span>
            <span>{{$t('TEXT_OVER')}}</span>
            <span>{{$t('TEXT_UNDER')}}</span>
          </div>
        </div>
        <div class="starColumn"></div>
      </div>
      <el-collapse-transition>
        <div style="width:100%" v-show="!collapseTable[marketIndex]">
          <transition-group name="list">
            <div
              class="odds"
              v-if="layoutData[marketIndex]"
              v-for="(event,index) in layoutData[marketIndex]"
              :key="event.eventInfo.EventId"
            >
              <div
                class="league"
                v-if="$_sameLeague(event,layoutData[marketIndex][index-1],index)"
                @click="$_collapseEvent(event.eventInfo.Competition.CompetitionId)"
              >
                {{event.eventInfo.Competition.CompetitionName}}
                <!-- <div class="joinChat" @click="$_selectChatRoom(event.eventInfo)">
                  <i class="el-icon-chat-dot-round" />
                </div>-->
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
                    <!-- <div class="star" v-if="i==1">
                      <favoriteStar />
                    </div>-->
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
                        <!-- <div
                        class="monthDate"
                        v-show="i==1"
                        >{{$_parseDate(event.eventInfo.EventDate).yr}}</div>-->
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
                            v-show="i==1 && event.eventInfo.HomeRedCard!=0 &&  event.eventInfo.HomeRedCard!=null"
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
                            v-show="i==1 && event.eventInfo.AwayRedCard!=0 &&  event.eventInfo.AwayRedCard!=null"
                            class="redCard"
                          >{{event.eventInfo.AwayRedCard}}</span>
                        </div>
                        <div v-show="i==1 && show">{{$t('TEXT_DRAW')}}</div>
                      </div>
                    </div>
                    <div class="oddsTable" v-if="event['oddsList'][1]" v-show="show">
                      <!--全場獨贏-->
                      <div class="oddsColumn noneCentering" v-if="event['oddsList'][1][3][i]">
                        <div
                          class="oddsWrap"
                          v-for="(odds,oIndex) in event['oddsList'][1][3][i]"
                          :key="odds.WagerSelectionId"
                        >
                          <oddsItem :oddsSet="odds" />
                        </div>
                      </div>
                      <div class="oddsColumn noneCentering" v-else />
                      <div class="oddsColumn noneCentering"></div>
                      <!--全場讓球，有時候會沒有該賠率-->
                      <div class="oddsColumn noneCentering" v-if="event['oddsList'][1][1][i]">
                        <div
                          class="oddsWrap"
                          v-for="(odds,oIndex) in event['oddsList'][1][1][i]"
                          :key="odds.WagerSelectionId"
                        >
                          <oddsItem :oddsSet="odds" />
                        </div>
                      </div>
                      <div class="oddsColumn noneCentering" v-else />
                      <div class="oddsColumn noneCentering"></div>
                      <!--全場大小-->
                      <div class="oddsColumn noneCentering" v-if="event['oddsList'][1][2][i]">
                        <div
                          class="oddsWrap"
                          v-for="(odds,oIndex) in event['oddsList'][1][2][i]"
                          :key="odds.WagerSelectionId"
                        >
                          <oddsItem :oddsSet="odds" />
                        </div>
                      </div>
                      <div class="oddsColumn noneCentering" v-else />
                      <!--上半獨贏-->
                      <div class="oddsColumn noneCentering" v-if="event['oddsList'][2][3][i]">
                        <div
                          class="oddsWrap"
                          v-for="(odds,oIndex) in event['oddsList'][2][3][i]"
                          :key="odds.WagerSelectionId"
                        >
                          <oddsItem :oddsSet="odds" />
                        </div>
                      </div>
                      <div class="oddsColumn noneCentering" v-else />
                      <div class="oddsColumn noneCentering"></div>
                      <!--上半讓球-->
                      <div class="oddsColumn noneCentering" v-if="event['oddsList'][2][1][i]">
                        <div
                          class="oddsWrap"
                          v-for="(odds,oIndex) in event['oddsList'][2][1][i]"
                          :key="odds.WagerSelectionId"
                        >
                          <oddsItem :oddsSet="odds" />
                        </div>
                      </div>
                      <div class="oddsColumn noneCentering" v-else />
                      <div class="oddsColumn noneCentering"></div>
                      <!--上半大小-->
                      <div class="oddsColumn noneCentering" v-if="event['oddsList'][2][2][i]">
                        <div
                          class="oddsWrap"
                          v-for="(odds,oIndex) in event['oddsList'][2][2][i]"
                          :key="odds.WagerSelectionId"
                        >
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
                    <div
                      class="oddsTable"
                      :class="{row:!show}"
                      v-if="event['oddsList'][1]"
                      v-show="!show"
                    >
                      <!--全場讓球，有時候會沒有該賠率-->
                      <div class="oddsColumn" v-if="event['oddsList'][1][1][i]">
                        <div class="oddsWrap hdc">{{$_parseHandicap(event['oddsList'][1][1][i])}}</div>
                        <div
                          class="oddsWrap"
                          v-for="(odds,oIndex) in event['oddsList'][1][1][i]"
                          :key="odds.WagerSelectionId"
                        >
                          <oddsItem :oddsSet="odds" />
                        </div>
                      </div>
                      <div class="oddsColumn" v-else />
                      <!--全場大小-->
                      <div class="oddsColumn" v-if="event['oddsList'][1][2][i]">
                        <div class="oddsWrap hdc">{{$_parseHandicap(event['oddsList'][1][2][i])}}</div>
                        <div
                          class="oddsWrap"
                          v-for="(odds,oIndex) in event['oddsList'][1][2][i]"
                          :key="odds.WagerSelectionId"
                        >
                          <oddsItem :oddsSet="odds" />
                        </div>
                      </div>
                      <div class="oddsColumn" v-else />

                      <!--上半讓球-->
                      <div class="oddsColumn" v-if="event['oddsList'][2][1][i]">
                        <div class="oddsWrap hdc">{{$_parseHandicap(event['oddsList'][2][1][i])}}</div>
                        <div
                          class="oddsWrap"
                          v-for="(odds,oIndex) in event['oddsList'][2][1][i]"
                          :key="odds.WagerSelectionId"
                        >
                          <oddsItem :oddsSet="odds" />
                        </div>
                      </div>
                      <div class="oddsColumn" v-else />
                      <!--上半大小-->
                      <div class="oddsColumn" v-if="event['oddsList'][2][2][i]">
                        <div class="oddsWrap hdc">{{$_parseHandicap(event['oddsList'][2][2][i])}}</div>
                        <div
                          class="oddsWrap"
                          v-for="(odds,oIndex) in event['oddsList'][2][2][i]"
                          :key="odds.WagerSelectionId"
                        >
                          <oddsItem :oddsSet="odds" />
                        </div>
                      </div>
                      <div class="oddsColumn" v-else />
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

    <!--   -->
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
import realTimeClock from "@/components/common/realTimeClock";
import oddsItem from "../oddsItem";
import favoriteStar from "@/components/common/favoriteStar";
import pageComponent from "@/components/common/pageComponent";
import oddsWrapMixin from "../oddsWrapMixin";
export default {
  mixins: [oddsWrapMixin],
  components: {
    pageComponent,
    favoriteStar,
    oddsItem,
    realTimeClock
  },
  methods: {
    $_parseHandicap(homeAndAwayOdds) {
      for (let k = 0; k < homeAndAwayOdds.length; k++) {
        let oddsSet = homeAndAwayOdds[k];
        if (oddsSet.BetTypeId != 1 && oddsSet.BetTypeId != 2) return;
        let Handicap = oddsSet.Handicap;
        if (Handicap == null) return;
        const selectionId = oddsSet.SelectionId;
        const sportId = oddsSet.SportId;
        let hdc = Handicap < 0 ? Handicap * -1 : Handicap;
        let mod = hdc % 0.5;
        let returnVal = `${hdc - mod}/${hdc + mod}`;
        if (sportId == 2 && (selectionId == 3 || selectionId == 4)) {
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
              return hdc;
            }
            return returnVal;
          }
        }
        if (Handicap > 0) {
          if (selectionId == 1 || selectionId == 3) {
            if (mod == 0) {
              //如果是5的整數
              return hdc;
            }
            return returnVal;
          }
        }
        if (Handicap == 0) {
          if (selectionId == 1 || selectionId == 3) {
            if (mod == 0) {
              //如果是5的整數
              return hdc;
            }
            return returnVal;
          }
        }
      }
    }
  }
};
</script>
<style lang="scss" src="@/scss/router/yabo/main/oddsTable.scss"></style>
<style lang="scss">
.oddsWrap {
  &.hdc {
    color: #ff3d3d;
    font-size: 12px;
    text-align: center;
    width: 50px;
  }
}
</style>