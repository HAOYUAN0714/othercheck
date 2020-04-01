<template>
  <div>
    <loading v-show="isFetchApi" />
    <div class="date-select-wrap">
      <div class="date-select">
        <div class="title">{{ $t('S_START_TIME') }}</div>
        <div class="start-date" @click="startDatePicker = !startDatePicker">
          <span>{{ startTime }}</span>
          <i class="el-icon-arrow-down" />
        </div>
        <datePicker
          v-show="startDatePicker"
          v-model="startTime"
          :format="formatStartDate"
          :inline="true"
          :disabled-dates="disabledDates"
          @selected="selectStartDate"
        />
      </div>
      <div class="date-select">
        <div class="title">{{ $t('S_END_TIME') }}</div>
        <div class="start-date" @click="endDatePicker = !endDatePicker">
          <span>{{ endTime }}</span>
          <i class="el-icon-arrow-down" />
        </div>
        <datePicker
          v-show="endDatePicker"
          v-model="endTime"
          :format="formatEndDate"
          :inline="true"
          :disabled-dates="disabledDates"
          @selected="selectEndDate"
        />
      </div>
      <div class="search-btn" @click="getSettledApi(startTime, endTime)">{{ $t('S_SHOW') }}</div>
    </div>
    <div class="status-tab clearfix">
      <div
        v-for="(item, index) in dateOption"
        :key="`date-${index}`"
        class="date"
        :class="{
          active: item.id === selectDateId
        }"
        @click="changeDateRange(item.id)"
      >
        {{ item.text }}
      </div>
    </div>
    <!-- bet record -->
    <div class="bets">
      <div v-if="betList.length === 0" class="no-settle-bet">{{ $t('S_NO_SETTLE_BET') }}</div>
      <div
        v-for="item in betList"
        :key="`bet-content-${item.WagerId}`"
        class="bet-content"
      >
        <!--過關-->
        <template v-if="+(item.WagerType) === 2">
          <div class="bets-title">
            <span>{{ $t('S_PARLAY_2') }}</span>&nbsp;
            <span>{{ $t(comboSelection[item.ComboSelection]) }}</span>
          </div>
          <div class="bet-odds">
            <div
              v-for="(wager, key) in item.WagerItemList"
              :key="`list-${key}`"
              class="list"
            >
              <span>{{ getSelectTeamName(wager) }}</span>
              <handicap :bet-item="wager" />
              <span>@</span>
              <span>{{ wager.Odds }}</span>
            </div>
            <div class="stake">
              <span>{{ $t('S_STAKE') }}(RMB):</span>
              <span>{{ toCurrency(item.InputtedStakeAmount) }}</span>
            </div>
            <div v-if="item.BetConfirmationStatus === 1" class="status">{{ $t('S_AWAIT') }}</div>
            <div v-if="item.BetConfirmationStatus === 3" class="status">{{ $t('S_REJECT') }}</div>
            <div v-if="item.BetConfirmationStatus === 4" class="status">{{ $t('S_CANCELED') }}</div>
            <div v-if="item.BetConfirmationStatus === 2" class="status">
              <span
                :class="[
                  item.MemberWinLossAmount >= 0 ? 'win' : 'lose'
                ]"
              >
                <template v-if="item.MemberWinLossAmount > 0">{{ $t('S_WIN') }}</template>
                <template v-if="item.MemberWinLossAmount < 0">{{ $t('S_LOSE') }}</template>
                <template v-if="item.MemberWinLossAmount === 0">{{ $t('S_DRAW') }}</template>
              </span>
            </div>
            <div class="win-money">
              <span>{{ $t('S_WIN_LOSE') }}</span>
              <span
                :class="[
                  item.MemberWinLossAmount >= 0 ? 'win' : 'lose'
                ]"
              >
                {{ toCurrency(item.MemberWinLossAmount) }}
              </span>
            </div>
          </div>
          <div class="btn-bet-detail" @click="showBetDetail(item.WagerId)">
            <span>{{ $t('S_BET_DETAIL') }}</span>
          </div>
          <comboInfo
            v-if="wagerId[item.WagerId]"
            :bet-list="item"
          />
        </template>
        <!--單注-->
        <template v-else>
          <div class="bets-title">
            {{ getSelectTeamName(item.WagerItemList[0]) }}
            <span v-if="item.WagerItemList[0].Market === 3">-{{ $t('S_LIVE_S') }}</span>
          </div>
          <div class="bet-odds">
            <div
              v-for="(wager, key) in item.WagerItemList"
              :key="`list-${key}`"
            >
              <div class="list">
                <span>{{ periodConfig[wager.PeriodId] }}{{ formatPlayTypeName(wager) }}</span>
                <handicap :bet-item="wager" />
                <span>@</span>
                <span>{{ wager.Odds }}</span>
              </div>
              <div v-if="wager.BetTypeId === 11" class="list">{{ wager.EventOutrightName }}</div>
              <div v-else class="list">{{ wager.HomeTeamName }} v {{ wager.AwayTeamName }}</div>
            </div>
            <div class="stake">
              <span>{{ $t('S_STAKE') }}(RMB):</span>
              <span>{{ toCurrency(item.InputtedStakeAmount) }}</span>
            </div>
            <div v-if="item.BetConfirmationStatus === 1" class="status">{{ $t('S_AWAIT') }}</div>
            <div v-if="item.BetConfirmationStatus === 3" class="status">{{ $t('S_REJECT') }}</div>
            <div v-if="item.BetConfirmationStatus === 4" class="status">{{ $t('S_CANCELED') }}</div>
            <div v-if="item.BetConfirmationStatus === 2" class="status">
              <span
                :class="[
                  item.MemberWinLossAmount >= 0 ? 'win' : 'lose'
                ]"
              >
                <template v-if="item.MemberWinLossAmount > 0">{{ $t('S_WIN') }}</template>
                <template v-if="item.MemberWinLossAmount < 0">{{ $t('S_LOSE') }}</template>
                <template v-if="item.MemberWinLossAmount === 0">{{ $t('S_DRAW') }}</template>
              </span>
              <span v-if="item.WagerItemList[0].Market === 3">
                {{ getCurBetScore(item.WagerItemList[0]) }}
              </span>
              <span>{{ getFTScore(item.WagerItemList[0]) }}</span>
            </div>
            <div class="win-money">
              <span>{{ $t('S_WIN_LOSE') }}</span>
              <span
                :class="[
                  item.MemberWinLossAmount >= 0 ? 'win' : 'lose'
                ]"
              >
                {{ toCurrency(item.MemberWinLossAmount) }}
              </span>
            </div>
          </div>
          <div class="btn-bet-detail" @click="showBetDetail(item.WagerId)">
            <span>{{ $t('S_BET_DETAIL') }}</span>
          </div>
          <singleInfo
            v-if="wagerId[item.WagerId]"
            :bet-list="item"
          />
        </template>
      </div>
    </div>
    <div class="bottom-menu">
      <div class="to-top" @click="toTop">{{ $t('S_BACK_TO_TOP') }}</div>
    </div>
  </div>
</template>

<script>
import datePicker from 'vuejs-datepicker'
import moment from 'moment'
import { getSettledBetList } from '@/utils/getBallData'
import comboSelection from '@/utils/comboSelection'
import comboInfo from './combo/betDetail'
import singleInfo from './single/betDetail'
import periodConfig from '@/utils/periodInfo'
import handicap from '@/layout/components/handicap/betRecordHandicap'
import loading from '@/layout/components/loading'

export default {
  components: {
    datePicker,
    comboInfo,
    singleInfo,
    handicap,
    loading
  },
  props: {
    refresh: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      wagerId: {},
      betList: [],
      startDatePicker: false,
      endDatePicker: false,
      startTime: moment().format('YYYY/MM/DD'),
      endTime: moment().format('YYYY/MM/DD'),
      dateOption: [
        {
          id: 'today',
          text: this.$t('S_TODAY')
        },
        {
          id: 'yesterday',
          text: this.$t('S_YESTERDAY')
        },
        {
          id: 'oneMonth',
          text: this.$t('S_PAST_30_DAYS')
        }
      ],
      selectDateId: 'today',
      comboSelection,
      periodConfig,
      isFetchApi: false,
      disabledDates: {
      }
    }
  },
  watch: {
    refresh() {
      this.getSettledApi(this.startTime, this.endTime)
    }
  },
  created() {
    this.getSettledApi(this.startTime, this.endTime)
    const startTimeStamp = moment().valueOf()
    const endTimeStamp = moment().add(-31, 'days').valueOf()
    this.disabledDates['to'] = new Date(endTimeStamp)
    this.disabledDates['from'] = new Date(startTimeStamp)
  },
  methods: {
    getSettledApi(StartDate, EndDate) {
      this.isFetchApi = true
      getSettledBetList(StartDate, EndDate).then((res) => {
        this.betList = res
        this.isFetchApi = false
        this.changeSelectDateId(StartDate, EndDate)
      })
    },
    formatStartDate(val) {
      this.startTime = moment(val).format('YYYY/MM/DD')
    },
    formatEndDate(val) {
      this.endTime = moment(val).format('YYYY/MM/DD')
    },
    selectStartDate() {
      this.startDatePicker = false
    },
    selectEndDate() {
      this.endDatePicker = false
    },
    changeDateRange(val) {
      if (val === 'today') {
        if (this.startTime === moment().format('YYYY/MM/DD') && this.endTime === moment().format('YYYY/MM/DD')) {
          return
        }
        this.startTime = moment().format('YYYY/MM/DD')
        this.endTime = moment().format('YYYY/MM/DD')
      }
      if (val === 'yesterday') {
        if (this.startTime === moment().add(-1, 'days').format('YYYY/MM/DD') && this.endTime === moment().add(-1, 'days').format('YYYY/MM/DD')) {
          return
        }
        this.startTime = moment().add(-1, 'days').format('YYYY/MM/DD')
        this.endTime = moment().add(-1, 'days').format('YYYY/MM/DD')
      }
      if (val === 'oneMonth') {
        if (this.startTime === moment().add(-30, 'days').format('YYYY/MM/DD') && this.endTime === moment().format('YYYY/MM/DD')) {
          return
        }
        this.startTime = moment().add(-30, 'days').format('YYYY/MM/DD')
        this.endTime = moment().format('YYYY/MM/DD')
      }
      this.selectDateId = val
      this.getSettledApi(this.startTime, this.endTime)
    },
    showBetDetail(id) {
      if (Object.keys(this.wagerId).includes(id)) {
        delete this.wagerId[id]
        this.$forceUpdate()
        return
      }
      this.wagerId = {
        ...this.wagerId,
        [id]: true
      }
    },
    toTop() {
      window.scrollTo(0, 0)
    },
    changeSelectDateId(startDate, endDate) {
      if (startDate === moment().format('YYYY/MM/DD') && endDate === moment().format('YYYY/MM/DD')) {
        this.selectDateId = 'today'
      }
      if (startDate === moment().add(-1, 'days').format('YYYY/MM/DD') && endDate === moment().add(-1, 'days').format('YYYY/MM/DD')) {
        this.selectDateId = 'yesterday'
      }
      if (startDate === moment().add(-30, 'days').format('YYYY/MM/DD') && endDate === moment().format('YYYY/MM/DD')) {
        this.selectDateId = 'oneMonth'
      }
    }
  }
}
</script>

<style lang="scss" scoped>

.date-select-wrap {
  padding: 16px 6px;
  font-size: 15px;
  color: var(--history_main_text);
  .date-select {
    position: relative;
    margin-bottom: 10px;
    .title {
      margin: 0 10px;
      padding-bottom: 6px;
    }
    .start-date {
      height: 38px;
      line-height: 38px;
      padding-left: 10px;
      border: 1px solid #B4B5B5;
      border-radius: 3px;
      color: #999;
    }
    .vdp-datepicker {
      width: 300px;
      margin: 10px auto;
    }
    i {
      position: absolute;
      top: 35px;
      right: 22px;
    }
  }
  .search-btn {
    background-color: var(--history_title2_bg);
    width: 120px;
    opacity: .9;
    height: 50px;
    line-height: 50px;
    margin: 0 auto;
    color: var(--history_title2_text);
    border-radius: 3px;
    text-align: center;
  }
}
.status-tab {
  margin-top: 10px;
  .date {
    background-color: var(--history_title2_sub2_bg);
    float: left;
    width: 33.3%;
    height: 50px;
    line-height: 50px;
    text-align: center;
    font-weight: bold;
    color: var(--history_title2_sub2_text);
    &.active {
      background-color: var(--history_title2_bg);
      color: var(--history_title2_text);
      opacity: .9;
    }
  }
}

.bets {
  border-top: 1px solid #C9C6CD;
  font-size: 16px;
  color: var(--history_main_text);
  .no-settle-bet {
    padding: 30px 0 10px;
    text-align: center;
  }
  .bet-content {
    padding: 0 6px;
    padding-top: 15px;
    border-bottom: 1px solid #c9c6cd;
    .bets-title {
      font-size: 20px;
      font-weight: bold;
      color: #6E6E6E;
    }
    .bet-odds {
      padding: 10px 0;
      .list,
      .stake,
      .return,
      .status,
      .win-money {
        margin-bottom: 5px;
        .win {
          color: #008000;
        }
        .lose {
          color: #F00;
        }
      }
    }
    .btn-bet-detail {
      padding-bottom: 15px;
      font-size: 13px;
      span {
        padding: 2px 8px;
      }
    }
  }
}
.bottom-menu {
  padding: 30px 6px 100px;
  text-align: center;
  .to-top {
    background-color: var(--history_title2_bg);
    line-height: 50px;
    color: var(--history_title2_text);
    border-radius: 3px;
  }
}
</style>
