<template>
  <div class="bet-history-wrap">
    <div class="header clearfix">
      <div class="home" @click="toHomePage">
        <font-awesome-icon class="icon" :icon="['fas','home']" />
      </div>
      <div class="title">
        {{ $t('S_SPORTS') }}
      </div>
    </div>
    <div class="bet-history clearfix">
      <div class="bet-slip-type">
        {{ curBetType === 'unsettled'? $t('S_UNSETTLED') : $t('S_SETTLED') }}
        <i class="el-icon-arrow-down" />
      </div>
      <select v-model="curBetType" class="select">
        <option
          v-for="item in betSlipList"
          :key="item.id"
          :value="item.id"
        >
          {{ item.text }}
        </option>
      </select>
      <div class="refresh" @click="getBetSlipData">
        <i class="fas fa-redo-alt" />
      </div>
    </div>
    <unsettled v-show="curBetType === 'unsettled'" :bet-list="betList" :api-fetch-status="isApiFetch" />
    <settled v-show="curBetType === 'settled'" :refresh="refreshSettle" />
  </div>
</template>

<script>
import unsettled from './unsettled'
import settled from './settled'
import { getUnsettledBetList } from '@/utils/getBallData'

export default {
  components: {
    unsettled,
    settled
  },
  data() {
    return {
      betSlipList: [
        {
          id: 'unsettled',
          text: this.$t('S_UNSETTLED')
        },
        {
          id: 'settled',
          text: this.$t('S_SETTLED')
        }
      ],
      betList: [],
      refreshSettle: false,
      isApiFetch: false
    }
  },
  computed: {
    curBetType: {
      get() {
        return this.$route.params.betType
      },
      set(val) {
        this.$router.push(`/betting-history/${val}`)
      }
    }
  },
  watch: {
    curBetType() {
      this.getBetSlipData()
    }
  },
  created() {
    this.getBetSlipData()
  },
  methods: {
    getBetSlipData() {
      if (this.curBetType === 'unsettled') {
        this.isApiFetch = true
        getUnsettledBetList().then((res) => {
          this.betList = res
          this.isApiFetch = false
        })
        return
      }
      this.refreshSettle = !this.refreshSettle
    },
    toHomePage() {
      this.$store.dispatch('ball/toggleBetRecord', false) // close bet record
      this.$router.push('/focus')
    }
  }
}
</script>

<style lang="scss" scoped>

.bet-history-wrap {
  background-color: var(--history_main_bg);
  width: 100%;
  min-height: 100%;
  padding: 6px 0;
  .header {
    background: var(--history_title1_bg);
    margin: 0 6px;
    padding: 12px 10px 13px;
    font-size: 18px;
    font-weight: bold;
    color: var(--history_title1_text);
    .home,
    .title {
      float: left;
    }
    .home {
      margin-right: 10px;
    }
  }
  .bet-history {
    position: relative;
    margin-top: 20px;
    padding: 0 6px;
    .bet-slip-type {
      background-color: var(--history_title2_bg);
      float: left;
      width: calc(100% - 50px);
      height: 50px;
      line-height: 50px;
      padding: 0 10px;
      color: var(--history_title2_text);
      font-weight: bold;
      i {
        float: right;
        padding-top: 16px;
        font-weight: bold;
        font-size: 20px;
      }
    }
    .select {
      position: absolute;
      width: calc(100% - 70px);
      height: 50px;
      top: 0;
      left: 0;
      opacity: 0;
    }
    .refresh {
      background-color: var(--history_title2_sub_bg);
      float: left;
      width: 50px;
      height: 50px;
      line-height: 50px;
      padding-left: 15px;
      font-size: 18px;
      color: var(--history_title1_text);
    }
  }
}
</style>
