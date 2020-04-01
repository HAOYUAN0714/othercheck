<template>
  <div class="solowin">
    <div
      class="betBody"
      v-if="!$_isEmpty(layoutData[marketIndex]) "
      v-for="(market,marketIndex) in 2"
      :key="market"
    >
      <div class="banner" @click="$_collapseTable(marketIndex)">
        <div class="absolute">
          <span>{{$t('TEXT_TOTAL')}}</span>
        </div>
        <div class="name">
          <span>{{$t(sportList[sportId].textKey)}}</span>
          <span
            style="padding-left:10px"
          >{{marketIndex==0?$t('TEXT_LIVE'):isCombo==true?$t('TEXT_PARLAY'):isCombo==true?$t('TEXT_PARLAY'):currentMarket==1?$t('TEXT_EARLY'):$t('TEXT_TODAY')}}</span>
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
                  v-show="!collapsedCompetitionList.includes(event.eventInfo.Competition.CompetitionId)"
                  style="width:100%"
                >
                  <div class="banner subTitle">
                    <div class="plainText" />
                    <div class="multipleTitle">
                      <div class="types">
                        <span class="typeName">{{$t('TEXT_FULL_TIME')}}</span>
                        <span class="typeName">{{$t('TEXT_HALF_TIME')}}</span>
                      </div>
                      <div class="betType" v-show="show">
                        <span>0-1</span>
                        <span>2-3</span>
                        <span>4-6</span>
                        <span>{{$t('TEXT_SEVEN_OR_HIGHER')}}</span>

                        <span>0</span>
                        <span>1</span>
                        <span>2</span>
                        <span>{{$t('TEXT_THREE_OR_HIGHER')}}</span>
                      </div>
                    </div>
                    <div class="starColumn"></div>
                  </div>
                  <div class="table eventItem">
                    <div class="timeMatchWrap">
                      <div class="timeInfo" v-if="event.eventInfo.Market==3">
                        <div
                          class="score"
                          v-show="event.eventInfo.HomeScore!=null"
                        >{{event.eventInfo.HomeScore}}:{{event.eventInfo.AwayScore}}</div>
                        <div class="score">
                          <realTimeClock
                            :event="event.eventInfo"
                            :sportId="sportId"
                            :time="event.eventInfo.RBTime"
                          />
                        </div>
                      </div>
                      <div class="timeInfo" v-else>
                        <div class="monthDate">{{$_parseDate(event.eventInfo.EventDate).md}}</div>
                        <div class="hourMinute">{{$_parseDate(event.eventInfo.EventDate).hm}}</div>
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
                    <div class="oddsTable" v-if="event['oddsList']">
                      <div class="oddsColumn" v-if="event['oddsList'][1][betType]">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],39)"
                        />
                      </div>
                      <div class="oddsColumn" v-else />
                      <div class="oddsColumn" v-if="event['oddsList'][1][betType]">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],40)"
                        />
                      </div>
                      <div class="oddsColumn" v-else />
                      <div class="oddsColumn" v-if="event['oddsList'][1][betType]">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],41)"
                        />
                      </div>
                      <div class="oddsColumn" v-else />
                      <div class="oddsColumn" v-if="event['oddsList'][1][betType]">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],42)"
                        />
                      </div>
                      <div class="oddsColumn" v-else />
                      <div class="oddsColumn" v-if="event['oddsList'][2][betType]">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][2][betType][1],39)"
                        />
                      </div>
                      <div class="oddsColumn" v-else />
                      <div class="oddsColumn" v-if="event['oddsList'][2][betType]">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][2][betType][1],40)"
                        />
                      </div>
                      <div class="oddsColumn" v-else />
                      <div class="oddsColumn" v-if="event['oddsList'][2][betType]">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][2][betType][1],41)"
                        />
                      </div>
                      <div class="oddsColumn" v-else />
                      <div class="oddsColumn" v-if="event['oddsList'][2][betType]">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][2][betType][1],42)"
                        />
                      </div>
                      <div class="oddsColumn" v-else />
                      <div class="starColumn">
                        <div
                          v-show="event.eventInfo.TotalMarketLineCount>0"
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
import pageComponent from "../../../../../components/common/pageComponent";
import oddsWrapMixin from "../oddsWrapMixin";
export default {
  mixins: [oddsWrapMixin],
  components: { realTimeClock, pageComponent, oddsItem }
};
</script>
<style lang="scss" src="@/scss/router/yabo/main/oddsTable.scss"></style>
