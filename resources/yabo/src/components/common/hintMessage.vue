<template>
  <transition name="hint">
    <div class="hintWrap" :key="hintType" v-show="flag">
      <div class="top" :class="hintType">{{$t('TEXT_ERROR')}}</div>
      <div class="hint" :class="hintType">
        <div class="hintIcon">
          <i
            :class="{
            'el-icon-warning':hintType=='warning',
            'el-icon-error':hintType=='error',
            'el-icon-success':hintType=='success'}"
          />
        </div>
        <span class="message">{{$t(message)}}</span>
      </div>
    </div>
  </transition>
</template>
<script>
import { mapState } from "vuex";
export default {
  computed: {
    ...mapState({
      message: state => state.common.hint.message,
      flag: state => state.common.hint.flag,
      hintType: state => state.common.hint.type
    })
  }
};
</script>
<style lang="scss">
.hintWrap {
  position: absolute;
  color: #333;
  text-align: left;
  font-size: 13px;
  z-index: 999999;
  width: 100%;
  left: 0;
  top: 20px;
  font-weight: 400;
  .top {
    margin: 0 auto;
    font-size: 13px;
    height: 25px;
    line-height: 25px;
    text-align: center;
    width: 250px;
    &.success {
      border-left: solid 4px var(--primary);
      background: red;
      color: white;
      .hintIcon {
        color: var(--white);
      }
    }
    &.warning {
      background: rgb(255, 119, 0);
      .hintIcon {
        color: rgb(255, 119, 0);
      }
    }
    &.error {
      color: var(--white);
      background: rgb(255, 64, 64);
      .hintIcon {
        color: rgb(255, 64, 64);
      }
    }
  }
  .hint {
    margin: 0 auto;
    width: 250px;
    padding-left: 10px;
    height: 30px;
    display: flex;
    line-height: 30px;
    .hintIcon {
      margin-right: 5px;
    }
    &.success {
      background: red;
      color: white;
      .hintIcon {
        color: var(--white);
      }
    }
    &.warning {
      background: rgb(255, 208, 166);
      .hintIcon {
        color: rgb(255, 119, 0);
      }
    }
    &.error {
      background: rgb(255, 195, 195);
      .hintIcon {
        color: rgb(255, 64, 64);
      }
    }
  }
}
</style>