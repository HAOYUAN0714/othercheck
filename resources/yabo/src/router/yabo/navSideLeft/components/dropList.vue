<template>
  <div class="dropList">
    <div
      class="itemTab"
      :class="{selected:selectedPlay==betTypeId}"
      @click="$_clickTab(betTypeId)"
      v-for="betTypeId in list"
      :key="`sport-${betTypeId}`"
    >
      <i v-if="!checkbox" class="el-icon-arrow-right dotIcon" />    
      <div class="sportName" v-show="betTypeId!=1">{{$t(betTypeConfig[betTypeId].textKey)}}</div>
      <div class="sportName" v-show="betTypeId==1">{{$t('TEXT_HANDICAP_AND_OU')}}</div>
      <div class="sportCount">{{$_getCount(betTypeId)}}</div>
    </div>
  </div>
</template>
<script>
import config from "../../../../config/config";
export default {
  data() {
    return {
      betTypeConfig: config.BetTypeId
    };
  },
  props: {
    selectedPlay: {
      type: Number,
      default: () => 1
    },
    checkbox: {
      type: Boolean,
      default: () => false
    },
    betType: {
      type: Object,
      default: () => {}
    },
    list: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    $_clickTab(play) {
      var self = this;
      this.selectedItem = play;
      this.$emit("selectPlay", play);
    },
    $_getCount(betTypeId) {
      const { betType } = this;
      return betType[betTypeId] ? betType[betTypeId].count : 0;
    }
  }
};
</script>
<style lang="scss" scoped>
@import "@/scss/common/mixin";
.dropList {
  width: 100%;
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.5, 1);
  background: var(--paleBackground);
  .itemTab {
    height: 37px;
    display: flex;
    width: 100%;
    font-size: 13px;
    padding: 0 10px;
    color: #718284;
    line-height: 37px;
    transition-duration: 0.1s;
    &.selected {
      color: var(--themeText);
    }
    &:hover {
      color: var(--primary);
      cursor: pointer;
    }
    .checkBox {
      width: 14px;
      height: 14px;
      margin: auto 0;
      margin-right: 10px;
      position: relative;
      border: solid 1px rgb(160, 160, 160);
      background: white;
      & > .checkIcon {
        top: 0;
        position: absolute;
      }
    }
    .sportName {
      @include overFlowDot();
    }
    .dotIcon {
      width: 20px;
      font-size: 6px;
      line-height: 37px;
    }
    .sportCount {
      margin-left: auto;
    }
  }
}
</style>