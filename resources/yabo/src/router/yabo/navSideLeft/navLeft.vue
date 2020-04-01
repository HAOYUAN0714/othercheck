<template>
  <div class="navLeft">
    <loadingMask :trigger="gettinBetInfo" />
    <div class="balance">
      <div>
        <i class="el-icon-refresh refresh" v-if="!isRefreshing" @click="$_refreshBalance()" />
      </div>
      <div>
        <i class="el-icon-loading" v-if="isRefreshing" />
      </div>
      <div class="money">
        <span>RMB {{commonToCurrency(commonToFixed(balance))}}</span>
      </div>
    </div>
    <div class="panel">
      <div
        class="underBorder"
        v-show="!placingBet && !betPlaced"
        :style="`transform:translateX(${selectedTab * (218/2)}px)`"
      />
      <div
        class="tbr"
        @click="$_selectTab(0)"
        :class="{selected:!placingBet && !betPlaced && selectedTab==0}"
      >
        <!-- <i class="el-icon-circle-plus-outline plus" /> -->
        {{$t('TEXT_MENU')}}
      </div>
      <div
        class="tbr"
        @click="$_selectTab(1)"
        :class="{selected:!placingBet && !betPlaced &&selectedTab==1}"
      >
        {{$t('TEXT_BET_RECORD')}}
        <!-- <span class="greenDot"></span> -->
      </div>
    </div>
    <placingBet @closeFunc="$_closeBet" v-if="placingBet || betPlaced" />
    <sportMenu v-if="selectedTab==0" />
    <betRecords v-if="selectedTab==1" />
  </div>
</template>
<script>
import { mapState, mapActions, mapMutations } from "vuex";
import loadingMask from "./loadingMask";
import sportMenu from "./components/sportMenu";
import placingBet from "./components/placingBet";
import betRecords from "./components/betRecords";
export default {
  components: {
    loadingMask,
    sportMenu,
    betRecords,
    placingBet
  },
  data() {
    return {
      selectedTab: 0,
      isRefreshing: false
    };
  },
  computed: {
    gettinBetInfo() {
      return this.$store.state.wager.gettinBetInfo;
    },
    wagerList() {
      return this.$store.state.wager.wagerList;
    },
    wager() {
      return this.$store.state.wager.wager;
    },
    balance() {
      return this.$store.state.common.availableBalance;
    },
    placingBet() {
      return this.$store.state.wager.placingBet;
    },
    betPlaced() {
      return this.$store.state.wager.betPlaced;
    }
  },
  watch: {
    placingBet(flag) {
      if (flag == true) {
        // this.selectedTab = 2;
      } else {
        //    this.selectedTab = 0;
        // this.selectedTab = 1;
      }
    }
  },
  methods: {
    ...mapActions(["apiGetBalance"]),
    $_selectTab(key) {
      if (key == 1) {
        this.$store.commit("$_clearWager"); //清空bet
        this.$store.commit("$_clearBetSetting");
      }
      this.$store.commit("$_setPlacingBet", false); //取消下注狀態
      this.$store.commit("$_setBetPlaced", false);
      this.selectedTab = key;
    },
    $_isEmpty(obj) {
      for (var key in obj) {
        if (obj.hasOwnProperty(key)) return false;
      }
      return true;
    },
    $_refreshBalance() {
      this.isRefreshing = true;
      this.apiGetBalance()
        .then(res => {
          this.isRefreshing = false;
        })
        .catch(err => {
          this.isRefreshing = false;
        });
    },

    $_closeBet() {
      this.selectedTab = 0;
    }
  }
};
</script>
<style lang="scss" src="@/scss/router/yabo/navLeft/navLeft.scss"></style>