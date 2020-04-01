<template>
  <div class="container">
    <div
      v-for="(hItem, hIndex) in historyData"
      :key="`history-${hIndex}`"
      class="history-outer"
    >
      <div v-if="hItem.WagerItemList.length > 1">
        <div>
          <div class="inner-title">
            <div class="team-info">{{ `[${sportId[hItem.WagerItemList[0].SportId]}]` }} 連串過關 ({{ hItem.WagerItemList.length }}) - {{ $t(comboSelection[hItem.ComboSelection]) }} x {{ hItem.NoOfCombination }}</div>
            <div class="time-info">
              {{ formatEventDate(hItem.WagerCreationDateTime) }}<br>
              單號{{ hItem.WagerId }}
              <span
                v-clipboard:copy="hItem.WagerId"
                v-clipboard:success="onCopy"
                v-clipboard:error="onError"
              >複製</span>
            </div>
          </div>
          <div v-if="mode === 'statement' && hItem.BetConfirmationStatus === 2 && hItem.InputtedStakeAmount + hItem.MemberWinLossAmount > 0" class="hint-bar-win" />
          <div v-if="mode === 'statement' && hItem.BetConfirmationStatus === 2 && hItem.InputtedStakeAmount + hItem.MemberWinLossAmount <= 0" class="hint-bar-lose" />
          <div class="inner-content">
            <div
              v-for="(dItem, dIndex) in hItem.WagerItemList"
              :key="`detail-${dIndex}`"
              class="detail-outer clearfix"
            >
              <div class="content-top">
                <div class="team">{{ dItem.HomeTeamName }} VS {{ dItem.AwayTeamName }}</div>
                <div class="league">{{ dItem.CompetitionName }}</div>
                <div class="play">
                  <span>{{ formatPlayTypeName(dItem) }} &nbsp; {{ periodId[hItem.WagerItemList[0].PeriodId] }}</span>
                  <!-- test -->
                  <!-- {{ `(${hItem.WagerItemList[0].WagerHomeTeamScore}:${hItem.WagerItemList[0].WagerAwayTeamScore})` }} -->
                  <span v-if="hItem.WagerItemList[0].WagerHomeTeamScore!==null &&hItem.WagerItemList[0].WagerAwayTeamScore!==null">{{ `(${hItem.WagerItemList[0].WagerHomeTeamScore}:${hItem.WagerItemList[0].WagerAwayTeamScore})` }}</span>
                  <!-- <span v-if="hItem.WagerItemList[0].WagerHomeTeamScore &&hItem.WagerItemList[0].WagerAwayTeamScore">{{ `(${hItem.WagerItemList[0].WagerHomeTeamScore}:${hItem.WagerItemList[0].WagerAwayTeamScore})` }}</span> -->
                  <!-- <span v-else>{{ `(${hItem.WagerItemList[0].WagerHomeTeamScore}:${hItem.WagerItemList[0].WagerAwayTeamScore})` }}</span> -->
                </div>
                <div class="mix-info clearfix">
                  <div class="left">
                    <span class="bet">
                      <span v-if="dItem.Market === 3">滾球</span>
                      {{ getSelectTeamName(dItem) }} {{ dItem.Handicap }}
                    </span>
                    <span class="odds">
                      @ {{ dItem.Odds }}
                    </span>
                  </div>
                  <div v-if="mode === 'statement'" class="right">
                    <span class="result">
                      賽事結果 {{ dItem.HomeTeamFTScore }} - {{ dItem.AwayTeamFTScore }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="content-bottom clearfix">
              <div class="left-area">
                <span class="gold">本金</span>
                <span class="amount">{{ (hItem.InputtedStakeAmount).toFixed(2) }}</span>
              </div>
              <div v-if="mode === 'statement'" class="right-area">
                <span class="win-text">返還 </span>
                <span v-if="hItem.BetConfirmationStatus === 2">
                  <span v-if="hItem.InputtedStakeAmount + hItem.MemberWinLossAmount > 0">
                    <span class="amount pre-red">{{ (hItem.InputtedStakeAmount + hItem.MemberWinLossAmount).toFixed(2) }}</span>
                    <span class="status pre-red">{{ $t('S_WIN') }}</span>
                  </span>
                  <span v-else>
                    <span class="amount pre-green">{{ (hItem.InputtedStakeAmount + hItem.MemberWinLossAmount).toFixed(2) }}</span>
                    <span class="status pre-green">{{ $t('S_LOSE') }}</span>
                  </span>
                </span>
                <span v-if="hItem.BetConfirmationStatus === 1" class="status">{{ $t('S_AWAIT') }}</span>
                <span v-if="hItem.BetConfirmationStatus === 3" class="status">{{ $t('S_REJECT') }}</span>
                <span v-if="hItem.BetConfirmationStatus === 4" class="status">{{ $t('S_CANCELED') }}</span>
              </div>
              <div v-else class="right-area">
                <span class="win-text">最高可贏 </span>
                <span class="amount pre-red">{{ (hItem.PotentialPayout).toFixed(2) }}</span>
                <span v-if="hItem.BetConfirmationStatus === 1" class="status">{{ $t('S_AWAIT') }}</span>
                <span v-if="hItem.BetConfirmationStatus === 2" class="status">{{ $t('S_CONFIRMED') }}</span>
                <span v-if="hItem.BetConfirmationStatus === 3" class="status">{{ $t('S_REJECT') }}</span>
                <span v-if="hItem.BetConfirmationStatus === 4" class="status">{{ $t('S_CANCELED') }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else>
        <div>
          <div class="inner-title">
            <div class="team-info">{{ `[${sportId[hItem.WagerItemList[0].SportId]}]` }}{{ hItem.WagerItemList[0].CompetitionName }}</div>
            <div class="time-info">
              {{ formatEventDate(hItem.WagerCreationDateTime) }}<br>
              單號{{ hItem.WagerId }}
              <span
                v-clipboard:copy="hItem.WagerId"
                v-clipboard:success="onCopy"
                v-clipboard:error="onError"
              >複製</span>
            </div>

          </div>
          <div v-if="mode === 'statement' && hItem.BetConfirmationStatus === 2 && hItem.InputtedStakeAmount + hItem.MemberWinLossAmount > 0" class="hint-bar-win" />
          <div v-if="mode === 'statement' && hItem.BetConfirmationStatus === 2 && hItem.InputtedStakeAmount + hItem.MemberWinLossAmount <= 0" class="hint-bar-lose" />
          <div class="inner-content">
            <div class="content-top">
              <!-- 因為冠軍不會出現在過關 所以上面沒有這段 -->
              <div v-if="hItem.WagerItemList[0].BetTypeId !== 11" class="team">{{ hItem.WagerItemList[0].HomeTeamName }} VS {{ hItem.WagerItemList[0].AwayTeamName }}</div>
              <div v-else class="team">{{ hItem.WagerItemList[0].EventOutrightName }}</div>
              <div class="play">
                <span v-if="hItem.WagerItemList[0].Market === 3">滾球</span>
                <span> &nbsp;{{ formatPlayTypeName(hItem.WagerItemList[0]) }} &nbsp; {{ periodId[hItem.WagerItemList[0].PeriodId] }}</span>
                <!-- test -->
                <!-- {{ `(${hItem.WagerItemList[0].WagerHomeTeamScore}:${hItem.WagerItemList[0].WagerAwayTeamScore})` }} -->
                <span v-if="hItem.WagerItemList[0].WagerHomeTeamScore!==null &&hItem.WagerItemList[0].WagerAwayTeamScore!==null">{{ `(${hItem.WagerItemList[0].WagerHomeTeamScore}:${hItem.WagerItemList[0].WagerAwayTeamScore})` }}</span>
                <!-- <span v-if="hItem.WagerItemList[0].WagerHomeTeamScore===null &&hItem.WagerItemList[0].WagerAwayTeamScore===null">test<span>
                </span>
                </span> -->
              </div>
              <div class="mix-info clearfix">
                <div class="left">
                  <span class="bet">
                    {{ getSelectTeamName(hItem.WagerItemList[0]) }} {{ hItem.WagerItemList[0].Handicap }}
                  </span>
                  <span class="odds">
                    @ {{ hItem.WagerItemList[0].Odds }}
                  </span>
                </div>
                <div v-if="mode === 'statement'" class="right">
                  <span class="result">
                    賽事結果 {{ hItem.WagerItemList[0].HomeTeamFTScore }} - {{ hItem.WagerItemList[0].AwayTeamFTScore }}
                  </span>
                </div>
              </div>
            </div>
            <div class="content-bottom clearfix">
              <div class="left-area">
                <span class="gold">本金</span>
                <span class="amount">{{ (hItem.InputtedStakeAmount).toFixed(2) }}</span>
              </div>
              <div v-if="mode === 'statement'" class="right-area">
                <span class="win-text">返還 </span>
                <span v-if="hItem.BetConfirmationStatus === 2">
                  <span v-if="hItem.InputtedStakeAmount + hItem.MemberWinLossAmount > 0">
                    <span class="amount pre-red">{{ (hItem.InputtedStakeAmount + hItem.MemberWinLossAmount).toFixed(2) }}</span>
                    <span class="status pre-red">{{ $t('S_WIN') }}</span>
                  </span>
                  <span v-else>
                    <span class="amount pre-green">{{ (hItem.InputtedStakeAmount + hItem.MemberWinLossAmount).toFixed(2) }}</span>
                    <span class="status pre-green">{{ $t('S_LOSE') }}</span>
                  </span>
                </span>
                <span v-if="hItem.BetConfirmationStatus === 1" class="status pre">{{ $t('S_AWAIT') }}</span>
                <span v-if="hItem.BetConfirmationStatus === 3" class="status pre-red">{{ $t('S_REJECT') }}</span>
                <span v-if="hItem.BetConfirmationStatus === 4" class="status pre-red">{{ $t('S_CANCELED') }}</span>
              </div>
              <div v-else class="right-area">
                <span class="win-text">最高可贏 </span>
                <span class="amount pre-red">{{ (hItem.PotentialPayout).toFixed(2) }}</span>
                <span v-if="hItem.BetConfirmationStatus === 1" class="status pre">{{ $t('S_AWAIT') }}</span>
                <span v-if="hItem.BetConfirmationStatus === 2" class="status pre">{{ $t('S_CONFIRMED') }}</span>
                <span v-if="hItem.BetConfirmationStatus === 3" class="status pre-red">{{ $t('S_REJECT') }}</span>
                <span v-if="hItem.BetConfirmationStatus === 4" class="status pre-red">{{ $t('S_CANCELED') }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment'
import comboSelection from '@/utils/comboSelection'

export default {
  components: {
  },
  props: {
    historyData: {
      type: Array,
      default: () => []
    },
    mode: {
      type: String,
      default: 'statement'
    }
  },
  data() {
    return {
      comboSelection,
      periodId: {
        1: '全场',
        2: '上半场',
        3: '下半场'
      },
      sportId: {
        1:	'足球',
        2:	'篮球',
        3:	'网球',
        6:	'田径',
        7:	'羽毛球',
        8:	'棒球',
        11:	'拳击',
        13:	'板球',
        15:	'飞镖',
        18:	'草地曲棍球',
        19:	'美式足球',
        21:	'高尔夫球',
        23:	'手球',
        25:	'冰上曲棍球',
        29:	'赛车运动',
        31:	'橄榄球',
        32:	'帆船',
        34:	'斯诺克 / 美式台球',
        39:	'虚拟足球',
        40:	'排球',
        41:	'水球',
        43:	'虚拟篮球',
        44:	'虚拟世界杯',
        45:	'娱乐投注',
        46:	'虚拟国家杯'
      }
    }
  },
  created() {
  },

  methods: {
    formatEventDate(val) {
      return moment(val).utcOffset(-4).format('YYYY-MM-DD HH:mm:ss')
    },
    onCopy(e) {
      alert('copied: ' + e.text)
    },
    onError(e) {
      alert('Failed to copy texts')
    }
  }

}
</script>

<style lang="scss" scoped>
.container {
  background-color: #F7F7F7;
}
.hint-bar-lose {
  margin: 0 10px;
  height: 5px;
  background-color: green;
}
.hint-bar-win {
  margin: 0 10px;
  height: 5px;
  background-color:#f00;
}
.inner-title {
  margin: 0 10px;
  background:linear-gradient(125deg,#ECECEC 50%,
  #EEB53F 0,#EEB53F 57%,#FCFCFC 0);
  // height: 50px;
  display: flex;
  padding: 5px 0px;
  justify-content: space-between;
  align-items: center;
  .team-info {
    font-size: 14px;
    font-weight: bold;
    padding-left: 6px;
    max-width: 50%;
  }
  .time-info {
    color: #AFAFAF;
    font-size: 10px;
    line-height: 18px;
    text-align: right;
    padding-right: 6px;
    span {
      color: #66b1ff;
      white-space: nowrap;
    }
  }
}
.inner-content {
  margin: 0 10px;
  .content-top {
    background: #fff;
    padding: 15px;
    border-bottom: solid 1px #ECECEC;
    font-size: 15px;
    line-height: 24px;
    .team {
      font-weight: bold;
    }
    .league {
      color: #757A85
    }
    .play {
      color:#AFAFAF;
    }
    .mix-info {
      .left {
        float: left;
        .bet {
          color:#AFAFAF;
        }
        .odds {
          color: #E8C06A;
        }
      }
      .right {
        float: right;
        .result {
          color: #000;
        }
      }
    }

  }
  .content-bottom {
    background: #fff;
    padding: 15px;
    font-size: 14px;
    margin-bottom: 10px;
    .left-area {
      float: left;
      .gold {
        font-weight: bold;
        padding-left: 4px;
        color: #000;
      }
      .amount {
        font-family: Arial Narrow;
        font-weight: bold;
        font-size: 18px;
        color: #000;
        padding-right: 5px;
      }
    }
    .right-area {
      float: right;
        .win-text {
        color:#AFAFAF;
      }
      .amount {
        font-family: Arial Narrow;
        font-weight: bold;
        font-size: 18px;
        color: #000;
        padding-right: 11px;
        border-right: solid 1px #AFAFAF;
        &.pre-red {
          color:#f00;
        }
        &.pre-black {
          color: #000;
        }
        &.pre-green {
          color: green;
        }
      }
      .status {
        font-weight: bold;
        padding-left: 7px;
        color: #000;
        &.pre-black {
          color: #000;
        }
        &.pre-red {
          color: #f00;
        }
        &.pre-green {
          color: green;
        }
      }
    }

  }

}
</style>
