<template>
  <div class="page">
    <!--更新ｔｉｍｅｒ-->
    <update-timer
      style="display:none"
      ref="updateTimer"
      :updateTime="updateTime"
      @updateFunc="$_updateFunc"
    ></update-timer>
    <!--更新ｔｉｍｅｒ-->

    <hintMessage />
    <navLeft />
    <mainTable />
    <!--威尼斯人版面配置-->
    <div v-if="env=='6F3832'">
      <navRight />
    </div>
    <div v-if="env == 'FB76C8'">
      <streamFloat />
    </div>
  </div>
</template>
<script>
import { mapActions } from "vuex";
import streamFloat from "./streamFloat/streamFloat";
import hintMessage from "@/components/common/hintMessage";
import moment from "moment";
import updateTimer from "@/components/common/updateTimer";
import mainTable from "./mainTable/mainTable";
import navLeft from "./navSideLeft/navLeft";
import navRight from "./navSideRight/navRight";
export default {
  components: {
    mainTable,
    navLeft,
    streamFloat,
    hintMessage,
    updateTimer,
    navRight
  },
  watch: {
    "$store.state.common.lang"() {
      this.$_getLiveEvent();
    }
  },
  data() {
    return {
      updateTime: 30
    };
  },
  computed: {
    isCombo() {
      return this.$store.state.status.isCombo;
    },
    env() {
      return process.env.VUE_APP_PROJECT_DIR; //開發環境
    },
    market() {
      return this.$store.state.status.Market;
    }
  },
  watch: {},

  methods: {
    ...mapActions(["apiGetSettled", "apiGetBetList", "apiGetBalance"]),
    $_parseDate(date) {
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
      return `${year}/${month}/${dt}`;
    },
    $_getListCount(
      navKey = this.isCombo == true ? 3 : this.market == 2 ? 1 : 2
    ) {
      this.$store
        .dispatch("$_getListCount", {
          Key: navKey
        })
        .then(res => {
          this.$store.commit("$_setListCount", {
            key: navKey,
            list: {
              sportMain: res.data.sportMain,
              sportRest: res.data.sportRest
            }
          });
        });
    },
    $_extendSession() {
      this.$store.dispatch("$_extendSession");
    },
    $_getLiveEvent() {
      this.$store
        .dispatch("$_getLiveEventInfo", {
          SportIds: [1, 2, 3, 5, 7, 8, 19, 21, 23, 25, 34, 40], //全部球種
          OddsType: 3,
          IsCombo: false,
          OrderBy: 1
        })
        .then(res => {
          if (res.data.Sports.length) {
            //把資料放盡store讓專案共用...
            this.$store.state.status.liveSports = res.data.Sports;
            this.selectedSport = res.data.Sports[0]
              ? res.data.Sports[0].SportId
              : 1;
            this.selectedEvent = JSON.parse(
              JSON.stringify(res.data.Sports[0].Events[0])
            );
          }
        });
    },
    $_updateFunc() {
      //  this.$_getListCount();
      this.$_extendSession();
      this.$_getLiveEvent();
      this.$_getQrCode();
    },
    $_getUnsettledBet() {
      this.apiGetBetList({
        BetConfirmationStatus: [1, 2, 3, 4]
      }).then(res => {
        this.$store.commit("$_setUnsettledBet", res.data.WagerList);
        //   this.wagerList = res.data.WagerList;
      });
    },
    $_getSettledBet() {
      //第一次先取一天
      const today = new Date();
      const yesterday = new Date(today);
      yesterday.setDate(yesterday.getDate() + 1);

      this.apiGetSettled({
        DateType: 2,
        StartDate: this.$_parseDate(yesterday),
        EndDate: this.$_parseDate(today)
      }).then(res => {
        this.$store.commit("$_setSettledBet", res.data.WagerList);
        //   this.wagerList = res.data.WagerList;
      });
    },
    $_getQrCode() {
      this.$store.dispatch("$_getQrCode", {
        color:
          process.env.VUE_APP_PROJECT_DIR == "FB76C8" ? "#edb83e" : "#ba8c4e"
      });
    },
    $_getUserInfo() {
      this.$store.dispatch("$_getUserInfo");
    },
    $_getAnnouncements() {
      this.$store.dispatch("$_getAnnouncement").then(res => {
        this.$store.commit("$_setAnnouncements", res.data.Announcement);
      });
    },
    $_firstGet() {
      //第一次進入時要獲得的資訊
      this.apiGetBalance();
      this.$_getUnsettledBet();
      this.$_getSettledBet();
      this.$_getUserInfo();
      //先把三個list都抓一次
      this.$_getListCount(1);
      this.$_getListCount(2);
      this.$_getListCount(3);
      this.$_getAnnouncements();
    }
  },
  mounted() {
    this.$_updateFunc();
    this.$_firstGet();
  }
};
</script>
<style lang="scss" src="@/scss/router/yabo/yabo.scss"></style>