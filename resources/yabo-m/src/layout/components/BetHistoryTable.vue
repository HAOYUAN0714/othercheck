<template>
  <div>
    <div class="bet-list-wrap">
      <div v-if="betList.length <= 0 && betType === 'unsettled'" class="no-bet-slip">{{ $t('S_NO_UNSETTLED_BET') }}</div>
      <div v-if="betList.length <= 0 && betType === 'settled'" class="no-bet-slip">{{ $t('S_NO_SETTLED_BET_IN_24_HOURS') }}</div>
      <div
        v-for="(item, index) in betList"
        :key="`bet-unsettled-${index}`"
        class="bet-slip"
      >
        <div class="bet-header clearfix">
          <div class="detail" @click="showBetDetail(item)">i</div>
          <div v-if="item.WagerType === 1" class="odds-type">{{ formatPlayTypeName(item.WagerItemList[0]) }}</div>
          <div v-if="item.WagerType === 2" class="odds-type">{{ $t(comboSelection[item.ComboSelection]) }}</div>
        </div>
        <div
          v-for="(betItem, betItemIndex) in item.WagerItemList"
          :key="`play-type-wrap-${betItemIndex}`"
          class="play-type-wrap"
        >
          <div class="play-type">
            {{ periodConfig[betItem.PeriodId] }}{{ formatPlayTypeName(betItem) }}
            <template v-if="betItem.Market === 3">
              -{{ $t('S_RUN_BALL') }}
              {{ getCurBetScore(betItem) }}
            </template>
          </div>
          <div class="win-detail clearfix">
            <div class="inline win-team">{{ getSelectTeamName(betItem) }}</div>
            <handicap :bet-item="betItem" />
            <div class="inline split">@</div>
            <div class="inline odds">{{ betItem.Odds }}</div>
          </div>
          <div v-if="betItem.BetTypeId === 11" class="team">{{ betItem.EventOutrightName }}</div>
          <div v-else class="team">{{ betItem.HomeTeamName }} v {{ betItem.AwayTeamName }}</div>
        </div>
        <div class="bet-price-wrap clearfix">
          <div class="bet-money">
            <div class="bet-money-title">{{ $t('S_STAKE') }}</div>
            <div class="money">{{ toCurrency(item.InputtedStakeAmount) }}</div>
          </div>
          <div v-if="betType === 'unsettled'" class="price">
            <div class="bet-money-title">{{ $t('S_TO_WIN') }}</div>
            <div class="money">{{ toCurrency(item.PotentialPayout) }}</div>
          </div>
          <div v-else class="price">
            <div v-if="item.BetConfirmationStatus === 1" class="bet-money-title">{{ $t('S_AWAIT') }}</div>
            <div v-if="item.BetConfirmationStatus === 3" class="bet-money-title">{{ $t('S_REJECT') }}</div>
            <div v-if="item.BetConfirmationStatus === 4" class="bet-money-title">{{ $t('S_CANCELED') }}</div>
            <template v-if="item.BetConfirmationStatus === 2">
              <div class="bet-money-title">
                <template v-if="item.MemberWinLossAmount > 0">{{ $t('S_WIN') }}</template>
                <template v-if="item.MemberWinLossAmount < 0">{{ $t('S_LOSE') }}</template>
                <template v-if="item.MemberWinLossAmount === 0">{{ $t('S_DRAW') }}</template>
              </div>
              <div class="money">{{ toCurrency(item.MemberWinLossAmount) }}</div>
            </template>
          </div>
        </div>
        <div class="btn-sure">{{ $t('S_CONFIRMED') }}</div>
        <!--投注摘要-->
        <template v-if="isBetSlipOpen && item.WagerId === curSelectBetSlip.WagerId">
          <div class="bet-slip-modal">
            <div class="bet-summary">{{ $t('S_BET_SUMMARY') }}</div>
            <div v-if="curSelectBetSlip.WagerType === 2" class="odds-type">{{ $t('S_PARLAY') }}</div>
            <div class="close" @click="isBetSlipOpen = false">
              <i class="fas fa-times" />
            </div>
            <!--摘要注單內容-->
            <div
              v-for="(curSlipList, curIndex) in curSelectBetSlip.WagerItemList"
              :key="`bet-slip-detail-${curIndex}`"
              class="bet-slip-detail"
            >
              <div class="league">{{ curSlipList.CompetitionName }}</div>
              <div class="team">
                <span class="team-name">{{ curSlipList.HomeTeamName }}</span>
                <span class="split">v</span>
                <span class="team-name">{{ curSlipList.AwayTeamName }}</span>
              </div>
              <div class="play-type">{{ periodConfig[curSlipList.PeriodId] }}{{ formatPlayTypeName(curSlipList) }}</div>
              <div class="win-detail clearfix">
                <div class="inline win-team">{{ getSelectTeamName(curSlipList) }}</div>
                <handicap :bet-item="curSlipList" />
                <div class="inline split">@</div>
                <div class="inline odds">{{ curSlipList.Odds }}</div>
              </div>
              <div class="date">{{ formatEventTime(curSlipList.EventDateTime) }}</div>
            </div>
            <div class="bet-id-wrap">
              <div class="bet-id">
                <span>{{ $t('S_BET_ID') }}:</span>
                <span>{{ curSelectBetSlip.WagerId }}</span>
              </div>
              <div class="bet-date">
                <span>{{ $t('S_BET_PLACED') }}:</span>
                <span>{{ formatBetTime(curSelectBetSlip.WagerCreationDateTime) }}</span>
              </div>
            </div>
          </div>
        </template>
      </div>
      <div class="all-bet-slip" @click="toBetHistoryPage(betType)">
        {{ betType === 'unsettled' ? $t('S_VIEW_ALL_UNSETTLED_BET') : $t('S_VIEW_ALL_SETTLED_BET') }}
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment'
import comboSelection from '@/utils/comboSelection'
import periodConfig from '@/utils/periodInfo'
import handicap from '@/layout/components/handicap/betRecordHandicap'

export default {
  components: {
    handicap
  },
  props: {
    betList: {
      type: Array,
      default: () => []
    },
    betType: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      isBetSlipOpen: false,
      curSelectBetSlip: '',
      comboSelection,
      periodConfig
    }
  },
  methods: {
    toBetHistoryPage(val) {
      this.$router.push(`/betting-history/${val}`)
    },
    showBetDetail(item) {
      this.curSelectBetSlip = item
      this.isBetSlipOpen = !this.isBetSlipOpen
    },
    formatBetTime(val) {
      return moment(val).utcOffset(-4).format('DD/MM YYYY HH:mm:ss')
    },
    formatEventTime(val) {
      return moment(val).utcOffset(-4).format('DD/MM HH:mm')
    }
  }
}
</script>

<style lang="scss" scoped>

.bet-list-wrap {
  color: #2B2B2B;
  .bet-slip {
    position: relative;
    background-color: #E3F3D4;
    padding: 10px;
    border-top: 1px solid #0A2900;
    font-size: 13px;
    .bet-header {
      margin-bottom: 6px;
      .detail {
        background: #97d45b;
        float: left;
        width: 22px;
        height: 25px;
        font-size: 20px;
        text-align: center;
        color: #fff;
        font-weight: bold;
      }
      .odds-type {
        float: left;
        padding: 6px 0 0 8px;
        color: #5A6E32;
      }
    }
    .play-type-wrap {
      padding-bottom: 6px;
      .play-type {
        color: #5A6E32;
      }
      .win-detail {
        padding-top: 8px;
        color: #282828;
        font-size: 14px;
        .inline {
          float: left;
          margin-right: 3px;
        }
        .cap,
        .odds {
          font-weight: bold;
        }
      }
      .team {
        padding: 8px 0;
        border-bottom: 1px solid #B2C498;
        color: #5A6E32
      }
    }
    .bet-price-wrap {
      .bet-money {
        float: left;
        text-align: left;
        .bet-money-title {
          color: #8C8C8C;
          font-size: 13px;
        }
        .money {
          color: #003A54;
          font-size: 14px;
          font-weight: bold;
        }
      }
      .price {
        float: right;
        text-align: right;
        .bet-money-title {
          color: #8C8C8C;
          font-size: 13px;
        }
        .money {
          color: #003A54;
          font-size: 14px;
          font-weight: bold;
        }
      }
    }
    .btn-sure {
      padding-top: 6px;
      color: #5A6E32;
    }
  }
  .all-bet-slip {
    background-color: #414548;
    height: 50px;
    padding-top: 16px;
    font-size: 15px;
    margin-top: 1px;
    color: #fff;
    text-align: center;
  }
}
.no-bet-slip {
  background-color: #E8E8E8;
  padding: 10px 6px;
  color: #2B2B2B;
  // clear: both;
  text-align: left;

}

.bet-slip-modal {
  background-color: #ECECEC;
  position: absolute;
  top: 40px;
  left: 10px;
  width: 92%;
  padding: 10px;
  border: 4px solid #003A54;
  z-index: 1;
  .bet-summary {
    padding: 1px 0 10px;
    font-size: 16px;
    font-weight: bold;
  }
  .odds-type {
    padding-bottom: 6px;
    font-weight: bold;
    font-size: 13px;
  }
  .close {
    position: absolute;
    top: 10px;
    right: 14px;
    font-size: 20px;
    color: #565656;
  }
  .bet-slip-detail {
    padding-bottom: 10px;
    font-size: 13px;
    border-top: 1px solid #A0A0A0;
    color: #848484;
    .league {
      padding-top: 10px;
      font-weight: bold;
    }
    .team,
    .play-type {
      padding-top: 6px;
      .team-name,
      .split {
        margin-right: 6px;
      }
      .split {
        color: #006EA2;
      }
    }
    .win-detail {
      padding-top: 6px;
      color: #282828;
      font-size: 14px;
      .inline {
        float: left;
        margin-right: 6px;
      }
      .odds {
        font-weight: bold;
      }
    }
    .date {
      padding-top: 6px;
    }
  }
  .bet-id-wrap {
    border-top: 1px solid #A0A0A0;
    font-size: 14px;
    color: #848484;
    .bet-id {
      padding-top: 10px;
    }
    .bet-date {
      padding-top: 6px;
    }
  }
}
</style>
