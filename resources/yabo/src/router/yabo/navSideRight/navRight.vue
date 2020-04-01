<template>
  <div class="navRight">
    <div class="welcome">{{$t('TEXT_HELLO')}}{{user}}</div>
    <div class="tab">
      {{$t('TEXT_SPORT_STREAM')}}
      <!-- <div class="tutorial">{{$t('TEXT_BET_TUTORIAL')}}</div> -->
    </div>
    <div class="matchInfo" v-if="selectedEvent.EventId">
      <div class="home">{{selectedEvent.HomeTeam}}</div>
      <div class="vs">VS</div>
      <div class="away">{{selectedEvent.AwayTeam}}</div>
    </div>
    <div v-else class="matchInfo" />
    <div class="iframe">
      <visualWidget :event="selectedEvent" :mid="selectedEvent.BREventId" />
    </div>
    <div class="options">
      <div class="underBorder" :style="`transform:translateX(${selectedOption * (329/2)}px)`"></div>
      <div @click="selectedOption=0" :class="{active:selectedOption==0}">{{$t('TEXT_STREAM_GAMES')}}</div>
      <div @click="selectedOption=1" :class="{active:selectedOption==1}">{{$t('TEXT_APP_DOWNLOAD')}}</div>
      <!-- <div
        :title="$t('TEXT_COMING_SOON')"
        :class="{active:selectedOption==1}"
      >{{$t('TEXT_CHAT_ROOM')}}</div>
      <div :class="{active:selectedOption==2}">{{$t('TEXT_GOODIES')}}</div>-->
    </div>
    <div class="selectedOptionWrap">
      <div class="option-1" v-show="selectedOption==0">
        <div v-for="sport in Sports" class="sportWrap" :key="sport.SportId">
          <div
            class="sportTab"
            @click="$_selectSport(sport)"
            :class="{sticky:selectedSport==sport.SportId,active:selectedSport==sport.SportId}"
          >
            <sportIcon :iconKey="sportConfig[sport.SportId].iconKey" class="sportIcon" />

            <div class="sportName">{{$t(sportConfig[sport.SportId].textKey)}}</div>
            <div class="right">
              {{sport.Events.length}}
              <div
                class="expandIcon"
                :class="{active:selectedSport==sport.SportId}"
                style="margin-left:10px;"
              >
                <i class="el-icon-arrow-down" />
              </div>
            </div>
          </div>

          <el-collapse-transition>
            <div class="eventWrap" v-show="selectedSport==sport.SportId">
              <div
                class="event"
                v-for="(event,eIndex) in sport.Events"
                :class="{active:selectedEvent.EventId==event.EventId}"
                @click="$_selectEvent(event)"
                :key="`${event.EventId}_${eIndex}`"
              >
                <div class="left">
                  <div class="eventName">{{event.Competition.CompetitionName}}</div>
                  <div class="teamInfo">
                    <div class="home">{{event.HomeTeam}}</div>
                    <div class="away">{{event.AwayTeam}}</div>
                  </div>
                </div>
                <div class="right">
                  <div class="time" v-if="selectedEvent.EventId!=event.EventId">
                    <div class="hourMinute">{{$_parseDate(event.EventDate).hm}}</div>
                  </div>
                  <div class="streaming" v-else>{{$t('TEXT_STREAMING')}}</div>
                  <div class="vid">
                    <div
                      class="anime"
                      :class="{blocked:event.BREventId==0,active: selectedEvent.EventId == event.EventId}"
                    >{{$t('TEXT_ANIMATION')}}</div>
                    <!-- <div
                      class="live"
                      @click="$_selectViewType(1)"
                      :class="{active:selectedViewType==1 && selectedEvent.EventId == event.EventId}"
                    >{{$t('TEXT_LIVE_SOURCE')}}</div>-->
                  </div>
                </div>
              </div>
            </div>
          </el-collapse-transition>
        </div>
        <div v-if="Sports.length==0" class="empty">{{$t("TEXT_NO_LIVE_MATCHES")}}</div>
      </div>
      <div class="option-2" v-show="selectedOption==1">
        <!-- <chatRoom /> -->
        <img src="@/assets/app_download.jpg" />
        <img :src="qrCode" class="qrCode" />
      </div>
      <!-- <img class="option-3" src="@/assets/goodies.png" v-show="selectedOption==2" /> -->
    </div>
  </div>
</template>
<script>
import { mapState } from "vuex";
import visualWidget from "@/components/common/visualWidget";
import chatRoom from "./chatRoom";
import config from "@/config/config";
import sportIcon from "@/components/common/sportIcon";
import $ from "jquery";
export default {
  components: {
    sportIcon,
    visualWidget,
    chatRoom
  },
  data() {
    return {
      sportConfig: config.sportList,
      selectedOption: 0,
      selectedSport: 1,
      selectedEvent: {
        EventId: null
      }
    };
  },
  watch: {
    selectedOption() {
      //0 1 2
      //  $(".underBorder");
    },
    Sports: {
      handler() {
        if (this.Sports.length != 0) {
          if (!this.selectedEvent.EventId) {
            //如果不存在selectedEvent
            this.selectedSport = this.Sports[0].SportId;
            this.selectedEvent = this.Sports[0].Events[0];
          } else
            for (var i = 0; i < this.Sports.length; i++) {
              for (var j = 0; j < this.Sports[i].Events.length; j++) {
                if (
                  this.selectedEvent.EventId == this.Sports[i].Events[j].EventId
                )
                  return; //如果賽事還存在
              }
            }

          this.selectedEvent = this.Sports[0].Events[0]; //如果賽事已經不存在了
        } else {
          this.selectedEvent = {}; //沒有任何滾球賽事Ï
        }
      },
      deep: true
    }
  },
  computed: {
    ...mapState({
      user: state => state.common.user,
      Sports: state => state.status.liveSports,
      qrCode: state => state.common.qrCode
    })
  },
  methods: {
    $_selectSport(sport) {
      this.selectedSport = sport.SportId;
      this.$_selectEvent(sport.Events[0]);
    },
    $_selectEvent(event) {
      this.selectedEvent = event;
      //加減讓他切換動pop
    },
    $_parseDate(ISO) {
      let date = new Date(ISO);
      let year = date.getFullYear();
      let month = date.getMonth() + 1;
      let dt = date.getDate();
      let hr = date.getHours();
      let mm = date.getMinutes();
      let ss = date.getSeconds();

      month = month < 10 ? "0" + month : month;
      dt = dt < 10 ? "0" + dt : dt;
      mm = mm < 10 ? "0" + mm : mm;
      ss = ss < 10 ? "0" + ss : ss;
      return {
        md: `${month}-${dt}`,
        hm: `${hr}:${mm}`
      };
    }
  }
};
</script>
<style lang="scss" src="@/scss/router/yabo/navRight/navRight.scss"></style>