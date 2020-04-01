<template>
  <div class="container">
    <div class="bet">
      <div class="bet-Header" />
      <div class="bet-Nav clearfix">
        <div class="chgButtom floatL" :class="chgColor===1 ? 'chgBlue':''" @click="selBetList(1)"><span>{{ $t('S_UNSETTLEMMENT') }}</span></div>
        <div class="chgButtom floatR" :class="chgColor===2 ? 'chgBlue':''" @click="selBetList(2)"><span>{{ $t('S_SETTLED_LIST') }}</span></div>
      </div>
      <div v-if="listYet" class="bet-Content">
        <div v-if="BetList=== undefined || BetList.length <= 0" class="empty-box">
          <div class="empty-reminder" />
          {{ $t('S_NO_DATA') }}
        </div>
        <div v-else> <span>{{ BetList }}</span></div>
      </div>
      <div v-if="listFinish" class="bet-Content">
        <div v-if="Statement=== undefined || Statement.length <= 0" class="empty-box">
          <div class="empty-reminder" />
          {{ $t('S_NO_DATA') }}
        </div>
        <div v-else> <span>{{ Statement }}</span></div>
      </div>
    </div>
    <div class="overlay" @click="overlay()" />
  </div>
</template>
<script>
import { getBetList, getStatement } from '@/api/betball'
export default {
  data() {
    return {
      chgColor: 1,
      listYet: true,
      listFinish: false,
      BetList: {},
      Statement: {}
    }
  },
  created() {
    getBetList().then((res) => {
      this.BetList = res
    })
  },
  methods: {
    overlay() {
      this.$emit('closeBet', false)
    },
    selBetList(betId) {
      this.chgColor = betId
      if (betId === 1) {
        this.listYet = true
        this.listFinish = false
        getBetList().then((res) => {
          this.BetList = res
        })
      } else {
        this.listFinish = true
        this.listYet = false
        getStatement().then((res) => {
          this.Statement = res
          // console.log(res)
        })
      }
    }
  }

}
</script>
<style lang="scss" scoped>
.container{
  width: 100%;
  height: 100%;
  position: fixed;
  top: 0;
  transform: translateX(100%);
  transition: transform .5s;

    .bet{
      z-index: 15;
      position: fixed;
      top:0;
      left: 20%;
      width: 80%;
      height: 100%;
      background-color: #f5f7fa;

        &-Header{
          background-color: #000;
          color: #fff;
          // height: 20px;
          text-align: center;
          padding: 10px 0px;
          font-size: 20px;
        }

        &-Nav{
          height: 40px;
          border-bottom: 1px solid #eee;
          .chgButtom{
            width: 50%;
            font-size: 16px;
            text-align: center;
            color: #7d7e80;
            padding:10px;
          }
          .floatL{
            float: left;
          }
          .floatR{
            float: right;
          }
          .chgBlue{
            color: #0149ff;
          }
        }
    }
    .overlay{
      z-index: 13;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(48, 47, 47, 0.7);
    }
    .empty-box{
      text-align: center;
      color: #909399;
      margin-top: 40px;
    }
    .empty-reminder{
      margin: 0 auto;
      width: 250px;
      height: 165px;
      background: url('~@/assets/betball/empty-reminder.png') no-repeat center/contain;
    }
}
.clearfix{
  clear: both;
}

</style>
