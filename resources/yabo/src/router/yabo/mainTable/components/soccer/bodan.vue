<template>
  <div class="halfAll" v-cloak>
    <div
      class="betBody"
      v-if="!$_isEmpty(layoutData[marketIndex]) "
      v-for="(market,marketIndex) in 2"
      :key="market"
    >
      <div class="banner" @click="$_collapseTable(marketIndex)">
        <div class="absolute">
          <span>{{$t('TEXT_CORRECT_SCORE')}}</span>
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
                  style="widspan:100%"
                >
                  <div class="banner subTitle">
                    <div class="plainText">
                      <span>{{$t('TEXT_FULL_TIME')}}</span>
                    </div>
                    <div class="multipleTitle">
                      <div class="betType">
                        <span>1-0</span>
                        <span>2-0</span>
                        <span>2-1</span>
                        <span>3-0</span>
                        <span>3-1</span>
                        <span>3-2</span>
                        <span>4-0</span>
                        <span>4-1</span>
                        <span>4-2</span>
                        <span>4-3</span>
                        <span>0-0</span>
                        <span>1-1</span>
                        <span>2-2</span>
                        <span>3-3</span>
                        <span>4-4</span>
                        <span>{{$t('TEXT_OTHER')}}</span>
                      </div>
                    </div>
                    <div class="starColumn"></div>
                  </div>

                  <!--賠率table-->
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
                        <div class="monspanDate">{{$_parseDate(event.eventInfo.EventDate).md}}</div>
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
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],17)"
                        />
                        <!--[全場][betType][marketline]-->
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],13)"
                        />
                      </div>
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],22)"
                        />
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],14)"
                        />
                      </div>
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],23)"
                        />
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],19)"
                        />
                      </div>
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],27)"
                        />
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],15)"
                        />
                      </div>
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],28)"
                        />
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],20)"
                        />
                      </div>
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],29)"
                        />
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],25)"
                        />
                      </div>
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],32)"
                        />
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],16)"
                        />
                      </div>
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],33)"
                        />
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],21)"
                        />
                      </div>
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],34)"
                        />
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],26)"
                        />
                      </div>
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],35)"
                        />
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],31)"
                        />
                      </div>
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],12)"
                        />
                      </div>
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],18)"
                        />
                      </div>
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],24)"
                        />
                      </div>
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],30)"
                        />
                      </div>
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],36)"
                        />
                      </div>
                      <div class="oddsColumn" v-if="!$_isEmpty(event['oddsList'][1][betType])">
                        <oddsItem
                          :oddsSet="$_getOddsBySelectionId(event['oddsList'][1][betType][1],439)"
                        />
                      </div>
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
import realTimeClock from "@/components/common/realTimeClock";
import oddsItem from "../oddsItem";
import pageComponent from "@/components/common/pageComponent";
import oddsWrapMixin from "../oddsWrapMixin";
export default {
  mixins: [oddsWrapMixin],
  components: { realTimeClock, pageComponent, oddsItem },
  props: {
    list: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {};
  }
};
</script>
<style lang="scss" src="@/scss/router/yabo/main/oddsTable.scss"></style>
