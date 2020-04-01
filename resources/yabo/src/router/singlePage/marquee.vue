<template>
  <div class="marqueePage">
    <div class="header">
      <div class="index">{{$t('TEXT_INDEX')}}</div>
      <div class="time">{{$t('TEXT_UPLOAD_TIME')}}</div>
      <div class="content">{{$t('TEXT_MARQUEE')}}</div>
    </div>
    <div class="table">
      <div class="marquee" v-for="(news,index) in sortedList" :key="index">
        <div class="index">{{index+1}}</div>
        <div
          class="time"
        >{{commonParseDate(news.PostingDate).date}} {{commonParseDate(news.PostingDate).time}}</div>
        <div class="content">{{news.Content}}</div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapActions } from "vuex";
import moment from "moment";
export default {
  data() {
    return {
      marquee: {
        CHS: [],
        ENG: []
      }
    };
  },
  computed: {
    sortedList() {
      const { marquee } = this;
      let arr = marquee.CHS;
      return arr.sort((a, b) =>
        moment(b.PostingDate).diff(moment(a.PostingDate))
      );
    }
  },
  methods: {
    ...mapActions(["apiGetAnnouncement"]),

    $_getAnnouncements() {
      this.apiGetAnnouncement().then(res => {
        let annoucements = res.data.Announcement;
        for (var i = 0; i < annoucements.length; i++) {
          if (annoucements[i].AnnouncementDetail[1].LanguageCode == "CHS") {
            annoucements[i].AnnouncementDetail[1].PostingDate =
              annoucements[i].PostingDate;
            this.marquee.CHS.push(annoucements[i].AnnouncementDetail[1]);
          }
          if (annoucements[i].AnnouncementDetail[0].LanguageCode == "CHS") {
            annoucements[i].AnnouncementDetail[0].PostingDate =
              annoucements[i].PostingDate;
            this.marquee.CHS.push(annoucements[i].AnnouncementDetail[0]);
          }
        }
      });
    }
  },
  mounted() {
    this.$_getAnnouncements();
  }
};
</script>

<style lang="scss" src="@/scss/router/yabo/main/marquee.scss"></style>