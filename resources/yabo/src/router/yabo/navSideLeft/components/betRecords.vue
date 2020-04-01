<template>
  <div class="betRecords">
    <div class="pendingBet">
      <div class="tab" @click="$_selectTab(0)">
        {{$t('TEXT_UNSETTLED_BET')}}
        <div class="expandIcon" :class="{active:selectedCollapse==0}">
          <i class="el-icon-arrow-down"></i>
        </div>
      </div>
      <el-collapse-transition>
        <div class="body" v-show="selectedCollapse==0">
          <div class="empty" v-if="unsettledList.length==0">{{$t('TEXT_NO_SETTLED_BET')}}</div>
          <div class="wagerList" v-else>
            <wagerWrap :settled="false" :wagerArr="unsettledList" />
          </div>
        </div>
      </el-collapse-transition>
      <div class="tab" @click="$_selectTab(1)">
        <div class="title">
          <div class="set">
            <div class="top">
              <div>{{$t('TEXT_SETTLED_BET')}}</div>
            </div>
            <div class="bottom">{{$t('TEXT_LAST_24_HRS')}}</div>
          </div>

          <div class="wagerAmount">{{settledList.length}}</div>
        </div>

        <div class="expandIcon" :class="{active:selectedCollapse==1}">
          <i class="el-icon-arrow-down"></i>
        </div>
      </div>
      <el-collapse-transition>
        <div class="body" v-show="selectedCollapse==1">
          <div
            class="empty"
            v-if="settledList.length==0"
          >{{$t('TEXT_LAST_24_HRS')}} {{$t('TEXT_NO_SETTLED_WAGER')}}</div>
          <div class="wagerList" v-else>
            <wagerWrap :settled="true" :wagerArr="settledList" />
          </div>
        </div>
      </el-collapse-transition>
    </div>
  </div>
</template>
<script>
import { mapActions } from "vuex";
import wagerWrap from "./wagerWrap/wagerWrap";
import moment from "moment";
import config from "@/config/config";
export default {
  data() {
    return {
      selectedCollapse: 0,
      selectedFilter: 0
    };
  },
  components: {
    wagerWrap
  },
  computed: {
    BetTypeConfig() {
      return config.BetTypeId;
    },
    unsettledList() {
      return this.$store.state.betList.unsettledBetList;
    },
    settledList() {
      return this.$store.state.betList.settledBetList;
    }
  },
  methods: {
    ...mapActions(["apiGetSettled", "apiGetBetList"]),
    $_selectTab(key) {
      if (this.selectedCollapse == key) {
        this.selectedCollapse = -1;
      } else this.selectedCollapse = key;
    },
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
      return { date: `${year}/${md}`, time: hr };
    },

    $_updateFunc() {
      this.$_getBetList();
      this.$_getSettledBet();
    },
    $_getSettledBet() {
      //第一次先取一天
      const today = new Date();
      const yesterday = new Date(today);
      yesterday.setDate(yesterday.getDate() - 1);
      this.apiGetSettled({
        DateType: 2,
        StartDate: this.$_parseDate(yesterday).date,
        EndDate: this.$_parseDate(today).date
      }).then(res => {
        this.$store.commit("$_setSettledBet", res.data.WagerList);
        //   this.wagerList = res.data.WagerList;
      });
    },
    $_getBetList() {
      this.apiGetBetList({
        BetConfirmationStatus: [1, 2, 3, 4]
      });
    }
  },

  mounted() {
    this.$_updateFunc();
  }
};
</script>

<style lang="scss" src="@/scss/router/yabo/navLeft/betRecords.scss"></style>