<template>
  <div class="bet-history-wrap">
    <div class="bet-history">
      <div class="title clearfix">
        <div class="left">{{ $t('S_MY_BET') }}</div>
        <div class="right" @click="closeBetRecord">
          <i class="fas fa-times" />
        </div>
      </div>
      <div class="bet-slip-list">
        <div class="list clearfix" @click="clickBetSlip('unsettled')">
          <div class="left">{{ $t('S_UNSETTLED') }}</div>
          <div class="right">
            <span class="count">{{ unsettledBetList.length }}</span>
            <i class="el-icon-arrow-down" />
          </div>
        </div>
        <!--未結算-->
        <BetHistoryTable
          v-show="openBetSlip === 'unsettled'"
          :bet-list="unsettledBetList"
          :bet-type="'unsettled'"
        />
      </div>
      <div class="bet-slip-list">
        <div class="list clearfix" @click="clickBetSlip('settled')">
          <div class="left">{{ $t('S_SETTLED') }}</div>
          <div class="right">
            <span class="count">{{ settledBetList.length }}</span>
            <i class="el-icon-arrow-down" />
          </div>
        </div>
        <!--已結算-->
        <BetHistoryTable
          v-show="openBetSlip === 'settled'"
          :bet-list="settledBetList"
          :bet-type="'settled'"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { getUnsettledBetList, getSettledBetList } from '@/utils/getBallData'
import moment from 'moment'
import BetHistoryTable from './BetHistoryTable'

export default {
  components: {
    BetHistoryTable
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
      openBetSlip: 'unsettled',
      isBetSlipOpen: false,
      unsettledBetList: [],
      settledBetList: []
    }
  },
  created() {
    const StartDate = moment().add(-1, 'days').format('YYYY-MM-DD')
    const EndDate = moment().format('YYYY-MM-DD')

    getUnsettledBetList().then((res) => {
      this.unsettledBetList = res
    })
    getSettledBetList(StartDate, EndDate).then((res) => {
      this.settledBetList = res
    })
  },
  methods: {
    closeBetRecord() {
      this.$store.dispatch('ball/toggleBetRecord', false)
    },
    clickBetSlip(val) {
      if (this.openBetSlip === val) {
        this.openBetSlip = ''
        return
      }
      this.openBetSlip = val
    }
  }
}
</script>

<style lang="scss" scoped>

.bet-history-wrap {
    background: var(--side_history_sub_bg);
    position: fixed;
    top: 0;
    right: 0;
    width: 242px;
    height: 100%;
    z-index: 1;
    overflow-y: scroll;
}
.bet-history {
    color: var(--history_title2_text);
    .title {
        height: 50px;
        line-height: 50px;
        padding: 0 10px;
        font-weight: bold;
        // background: linear-gradient(to right, #955d2c, #f3d382 70%, #f3d382 80%, #955d2c 100%);
        background-color: var(--history_title2_bg);
        .left {
            float: left;
        }
        .right {
            float: right;
            color: var(--history_title2_text);
            .fa-times {
                width: 20px;
                height: 20px;
            }
        }
    }
    .list {
        height: 50px;
        line-height: 50px;
        padding: 0 10px;
        .left {
            float: left;
        }
        .right {
            float: right;
            .count {
                display: inline-block;
                background: var(--side_count_bg);
                color: var(--side_count_text);
                width: 24px;
                height: 24px;
                line-height: 24px;
                position: relative;
                border-radius: 50%;
                text-align: center;
            }
        }
    }
}
/* clear float */
.clear {
    margin: 0;
    padding: 0;
    clear: both;
}
.clearfix {
    *zoom: 1;
    &:before {
        display: table;
        content: "";
    }
    &:after {
        display: table;
        content: "";
        clear: both;
    }
}
</style>
