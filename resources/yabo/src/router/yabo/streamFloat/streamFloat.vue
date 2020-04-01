<template>
  <div class="streamFloatWrap">
    <div class="navRightFloat">
      <div class="top">
        <div class="underBorder" :style="`transform:translateX(${liveSelect * 165}px)`" />
        <div
          class="option"
          @click="liveSelect=0"
          :class="{active:liveSelect==0}"
        >{{$t('TEXT_IS_LIVE')}}</div>
        <div
          class="option"
          @click="liveSelect=1"
          :class="{active:liveSelect==1}"
        >{{$t('TEXT_IS_TODAY')}}</div>
      </div>
      <div class="body">
        <isLive :list="liveSports" :sportId="selectedSport" v-show="liveSelect==0" />
        <isToday :list="todayList" :sportId="selectedSport" v-show="liveSelect==1" />
      </div>
    </div>
    <div class="selectSet">
      <div class="header">{{$t('TEXT_LIVE_SOURCE')}}/{{$t('TEXT_ANIMATION')}}</div>
      <div class="body">
        <!--足球-->
        <div class="sportButton" @click="$_selectTab(1)" :class="{active:selectedSport==1}">
          <sportIcon :iconKey="`Soccer`" />
          <div class="sportCount">
            <span>{{$t('TEXT_SOCCER')}}</span>
            <span>({{soccerLive.length}})</span>
          </div>
        </div>
        <!--籃球-->
        <div class="sportButton" @click="$_selectTab(2)" :class="{active:selectedSport==2}">
          <sportIcon :iconKey="`Basketball`" />
          <div class="sportCount">
            <span>{{$t('TEXT_BASKETBALL')}}</span>
            <span>({{basketballLive.length}})</span>
          </div>
        </div>
        <div
          class="solidSquare"
          v-show="selectedSport!=0"
          :style="`transform:translateY(${(selectedSport-1) * 75}px)`"
        />
      </div>
    </div>
  </div>
</template>
<script>
import moment from "moment";
import sportIcon from "@/components/common/sportIcon";
import isLive from "./components/isLive";
import isToday from "./components/isToday";
import visualWidget from "@/components/common/visualWidget";
export default {
  data() {
    return {
      //現在選擇的球種
      selectedSport: 1,
      //選擇進行中以及未開始
      liveSelect: 0,
      //今日清單
      todayList: {
        basketball: [],
        soccer: []
      }
    };
  },
  components: {
    sportIcon,
    isLive,
    visualWidget,
    isToday
  },
  computed: {
    liveSports() {
      const { soccerLive, basketballLive } = this;
      return {
        soccer: soccerLive,
        basketball: basketballLive
      };
    },
    liveList() {
      return this.$store.state.status.liveSports;
    },
    soccerLive() {
      const { liveList } = this;

      for (let i = 0; i < liveList.length; i++) {
        if (liveList[i].SportId == 1) {
          return liveList[i].Events;
        }
      }
      return [];
    },
    basketballLive() {
      const { liveList } = this;
      for (let i = 0; i < liveList.length; i++) {
        if (liveList[i].SportId == 2) {
          return liveList[i].Events;
        }
      }
      return [];
    }
  },
  methods: {
    $_selectTab(key) {
      this.selectedSport = key;
    },
    $_updateFunc() {
      const { selectedSport, today } = this;
      this.$store
        .dispatch("$_getEventInfo", {
          SportId: 1,
          Market: 2,
          OddsType: 1,
          CompetitionIds: [],
          IsCombo: false,
          BetTypeIds: [-1],
          loadingMask: false
        })
        .then(res => {
          if (res.data.Sports) {
            this.todayList.soccer = res.data.Sports[0]
              ? res.data.Sports[0].Events
              : [];
          }
        });
      this.$store
        .dispatch("$_getEventInfo", {
          SportId: 2,
          Market: 2,
          OddsType: 1,
          CompetitionIds: [],
          IsCombo: false,
          BetTypeIds: [-1],
          loadingMask: false
        })
        .then(res => {
          if (res.data.Sports) {
            this.todayList.basketball = res.data.Sports[0]
              ? res.data.Sports[0].Events
              : [];
          }
        });
    }
  },
  mounted() {
    this.$_updateFunc();
  }
};
</script>

<style lang="scss" src="@/scss/router/yabo/streamFloat/streamFloat.scss"></style>