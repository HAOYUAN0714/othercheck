<template>
  <div class="betOptions">
    <div class="bettingAmountWrap" v-show="checkIsCombo">
      <div class="comboInfo" v-if="isCombo && wagerList.length>1">
        <span>{{$t(`TEXT_COMBO_${betOption.ComboSelection}`)}}</span>
        <span>@</span>
        <span>{{commonToFixed(betOption.EstimatedPayoutAmount+1)}}</span>
      </div>
      <div class="infoTab">
        <input
          type="text"
          @click="$_selectBetOption"
          @blur="stakeAmount=commonToFixed(stakeAmount)"
          v-model="stakeAmount"
          :class="{low:stakeAmount<betOption.MinStakeAmount}"
          spellcheck="false"
          :placeholder="$t('TEXT_BETTING_AMOUNT')"
        />
        <span class="wagerAmount" v-show="isCombo">X{{betOption.NoOfCombination}}</span>
        <span v-if="winnableAmount==0" class="right big">{{$t('TEXT_WINNABLE_AMOUNT')}}</span>
        <span
          v-else
          :title="commonToCurrency(winnableAmount)"
          class="right big"
        >{{ commonToCurrency(winnableAmount)}}</span>
      </div>
      <div class="infoTab">
        {{$t('TEXT_MIN_AMOUNT')}}：
        <span
          class="right"
        >{{ commonToCurrency(betOption.MinStakeAmount)}} RMB</span>
      </div>
      <div class="infoTab">
        {{$t('TEXT_MAX_AMOUNT')}}：
        <span
          class="right"
        >{{ commonToCurrency(betOption.MaxStakeAmount)}} RMB</span>
      </div>
    </div>
    <div class="betButtonsWrap" v-show="checkIsCombo && selected">
      <div class="buttonSet">
        <div
          class="button"
          :class="{blocked:Number(stakeAmount)+25>betOption.MaxStakeAmount}"
          @click="$_addAmount(25)"
        >+25</div>
        <div
          class="button"
          :class="{blocked:Number(stakeAmount)+50>betOption.MaxStakeAmount}"
          @click="$_addAmount(50)"
        >+50</div>
        <div
          class="button"
          :class="{blocked:Number(stakeAmount)+100>betOption.MaxStakeAmount}"
          @click="$_addAmount(100)"
        >+100</div>
      </div>
      <div class="buttonSet">
        <div
          class="button"
          :class="{blocked:Number(stakeAmount)+250>betOption.MaxStakeAmount}"
          @click="$_addAmount(250)"
        >+250</div>
        <div
          class="button"
          :class="{blocked:Number(stakeAmount)+500>betOption.MaxStakeAmount}"
          @click="$_addAmount(500)"
        >+500</div>
        <div
          class="button"
          :class="{blocked:Number(stakeAmount)+1000>betOption.MaxStakeAmount}"
          @click="$_addAmount(1000)"
        >+1,000</div>
      </div>
      <div class="clearText" @click="$_clearBet">{{$t('TEXT_CLEAR')}}</div>
    </div>
  </div>
</template>
<script>
import { mapState } from "vuex";
export default {
  props: {
    betOption: {
      type: Object,
      default: () => {}
    },
    selected: {
      type: Boolean,
      default: () => {
        return false;
      }
    },
    index: {
      type: Number,
      default: () => {}
    }
  },
  watch: {
    stakeAmount(newStake, oldStake) {
      let newValue = String(newStake).replace(/[^\d\.]/g, "");
      if (isNaN(newValue)) {
        newValue = 0;
      }
      if (String(newValue).length >= 12) {
        newValue = newValue.slice(0, -1);
      }
      this.stakeAmount = newValue;
    }
  },
  data() {
    return {
      stakeAmount: ""
    };
  },
  computed: {
    ...mapState({
      wager: state => state.wager.wager,
      wagerList: state => state.wager.wagerList,
      isCombo: state => state.status.isCombo
    }),
    actualStakeAmount() {
      const { stakeAmount, betOption, isCombo } = this;
      if (!isCombo) {
        return Number(stakeAmount);
      }
      if (isCombo) {
        return betOption.NoOfCombination * Number(stakeAmount);
      }
    },
    winnableAmount() {
      return this.commonToFixed(
        Number(this.stakeAmount) * this.betOption.EstimatedPayoutAmount
      );
    },
    checkIsCombo() {
      const { isCombo, wagerList } = this;
      if (!isCombo) return true;
      if (isCombo) {
        if (wagerList.length < 2) return false;
        else {
          return true;
        }
      }
    }
  },
  methods: {
    $_clearBet() {
      this.stakeAmount = "";
    },
    $_selectBetOption() {
      this.$emit("selectBetOption", this.index);
    },

    $_addAmount(add) {
      if (Number(this.stakeAmount) + add <= this.betOption.MaxStakeAmount)
        this.stakeAmount = this.commonToFixed(Number(this.stakeAmount) + add);
    }
  }
};
</script>
<style lang="scss" scoped>
</style>