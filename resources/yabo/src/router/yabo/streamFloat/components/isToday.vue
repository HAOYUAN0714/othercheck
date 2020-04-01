<template>
  <div class="isToday floatBlock">
    <div class="competitionWrap" v-for="(event,index) in eventList" :key="event.EventId">
      <div
        class="header"
        v-if="event.Competition"
        v-show="index==0 || (index!=0 && eventList[index-1].Competition.CompetitionName) ==  event.Competition.CompetitionName"
      >
        <span>{{event.Competition.CompetitionName}}</span>
      </div>
      <div class="eventWrap">
        <div class="eventTime">
          <span>{{$_parseDate(event.EventDate)}}</span>
        </div>
        <div class="eventParticipant">
          <div class="home team">
            <span>{{event.HomeTeam}}</span>
          </div>
          <div class="away team">
            <span>{{event.AwayTeam}}</span>
          </div>
        </div>
      </div>
    </div>
    <div v-if="eventList.length==0" class="empty">{{$t('TEXT_NO_DATA')}}</div>
  </div>
</template>
<script>
import moment from "moment";
export default {
  props: {
    list: {
      type: Object,
      default: () => {}
    },
    sportId: {
      type: Number,
      default: () => {}
    }
  },
  methods: {
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
      return `${md} ${hr}`;
    }
  },
  computed: {
    eventList() {
      const { sportId, list } = this;
      if (sportId == 1) {
        return list.soccer;
      }
      if (sportId == 2) {
        return list.basketball;
      }
    }
  }
};
</script>
<style lang="scss" src="@/scss/router/yabo/streamFloat/streamFloat.scss"></style>