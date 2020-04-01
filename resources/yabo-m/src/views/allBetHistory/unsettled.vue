<template>
  <div>
    <div class="title">{{ $t('S_ALL_UNSETTLED_BET') }}</div>
    <loading v-show="apiFetchStatus" />
    <div class="transaction">
      <div class="bets">
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
              <div class="return">
                <span>{{ $t('S_TO_WIN') }}:</span>
                <span>{{ toCurrency(item.PotentialPayout) }}</span>
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
              <template v-if="item.WagerItemList[0].Market === 3">
                -{{ $t('S_RUN_BALL') }}
                {{ getCurBetScore(item.WagerItemList[0]) }}
              </template>
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
              <div class="return">
                <span>{{ $t('S_TO_WIN') }}:</span>
                <span>{{ toCurrency(item.PotentialPayout) }}</span>
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
    </div>
    <div class="bottom-menu">
      <div class="to-top" @click="toTop">{{ $t('S_BACK_TO_TOP') }}</div>
    </div>
  </div>
</template>

<script>
import comboSelection from '@/utils/comboSelection'
import comboInfo from './combo/betDetail'
import singleInfo from './single/betDetail'
import periodConfig from '@/utils/periodInfo'
import handicap from '@/layout/components/handicap/betRecordHandicap'
import loading from '@/layout/components/loading'

export default {
  components: {
    comboInfo,
    singleInfo,
    handicap,
    loading
  },
  props: {
    betList: {
      type: Array,
      default: () => []
    },
    apiFetchStatus: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      wagerId: {},
      comboSelection,
      periodConfig
    }
  },
  methods: {
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
    }
  }
}
</script>

<style lang="scss" scoped>

.title {
  padding: 16px 6px;
  font-size: 17px;
  color: #848484;
}
.transaction {
  .bets {
    border-top: 1px solid #C9C6CD;
    font-size: 16px;
    color: #6E6E6E;
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
        .return {
          margin-bottom: 5px;
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
