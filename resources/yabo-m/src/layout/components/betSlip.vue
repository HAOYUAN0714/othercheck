<template>
  <div class="betbox">
    <div v-if="!betsuccesfull" class="bet-slip">
      <div class="header">
        <div class="header-left">
          <div class="box">
            <p style="font-weight: 500;">{{ $t('S_BET_SLIP') }}</p>
            <p class="header-sub" style="font-size:11px;margin-top:5px;">{{ memberBalance }} RMB</p>
            <!-- curBetSlip: {{ curBetSlip }},
            test: {{ test }},
            test2: {{ test2 }} -->
          </div>
          <div class="header-right" @click="checkClose()">
            <font-awesome-icon :icon="['fas','times']" />
          </div>
        </div>
      </div>
      <div class="betMain">
        <div v-for="(BetSlip,index) in curBetSlip" :key="index">
          <div class="inputBox">
            <div class="box">
              <!-- 紅標 openParlay: {{ BetSlip.openParlay }}<br>
              collisionCheck: {{ BetSlip.collisionCheck }}<br> -->
              <!-- BetSlip: {{ BetSlip }} -->
              <div class="title">
                <font-awesome-icon class="greenIcon" :icon="['fas','exclamation-circle']" @click="letShowInfo(BetSlip.betinfo)" />
                <template>
                  <div v-if="BetSlip.openParlay === true && Object.keys(curBetSlip).length > 1 && BetSlip.betinfo.apiInput.WagerSelectionInfos.OddsType === 3 && BetSlip.collisionCheck !== checkRepeat" class="normal">
                    {{ getMarket(BetSlip.betinfo.apiInput.WagerSelectionInfos.Market) }}&nbsp;
                    {{ getPeriodId(BetSlip) }}
                    {{ showTeamName(BetSlip.betinfo) }}
                    &nbsp;
                    <span
                      v-show="BetSlip.betinfo.apiInput.WagerSelectionInfos.Market == 3 &&
                        BetSlip.betinfo.apiInput.WagerSelectionInfos.HomeScore != null &&
                        BetSlip.betinfo.apiInput.WagerSelectionInfos.AwayScore != null"
                    >({{ getScore(BetSlip.betinfo.apiInput.WagerSelectionInfos.HomeScore) }}-{{ getScore(BetSlip.betinfo.apiInput.WagerSelectionInfos.AwayScore) }})</span>

                    <!-- <handicap :bet-item="BetSlip.betinfo.apiInput.WagerSelectionInfos" /> -->
                  </div>
                  <div v-else class="normal" :class="{redError:Object.keys(curBetSlip).length > 1}">
                    {{ getMarket(BetSlip.betinfo.apiInput.WagerSelectionInfos.Market) }}&nbsp;
                    {{ getPeriodId(BetSlip) }}
                    {{ showTeamName(BetSlip.betinfo) }}
                    &nbsp;
                    <span
                      v-show="BetSlip.betinfo.apiInput.WagerSelectionInfos.Market === 3 &&
                        BetSlip.betinfo.apiInput.WagerSelectionInfos.HomeScore != null &&
                        BetSlip.betinfo.apiInput.WagerSelectionInfos.AwayScore != null"
                    >({{ getScore(BetSlip.betinfo.apiInput.WagerSelectionInfos.HomeScore) }}-{{ getScore(BetSlip.betinfo.apiInput.WagerSelectionInfos.AwayScore) }})</span>
                    <!-- <handicap :bet-item="BetSlip.betinfo.apiInput.WagerSelectionInfos" :error="Object.keys(curBetSlip).length > 1" /> -->
                  </div>
                </template>
                <div class="deletIcon" @click="trash(BetSlip.odds_id)">
                  <font-awesome-icon :icon="['fas','trash-alt']" />
                </div>
              </div>
              <div class="vontent">
                <template>
                  <!-- <span v-if="BetSlip.betinfo.info.SelectionName !== ''">{{ showTeamName(BetSlip.betinfo) }}</span> -->
                  <span>{{ getSelectionName(BetSlip) }}</span>
                </template>
                <handicap :bet-item="BetSlip.betinfo.apiInput.WagerSelectionInfos" />@
                <span class="green">{{ BetSlip.betinfo.apiInput.WagerSelectionInfos.Odds }}</span>
                <!-- infoArea -->
                <div v-if="showInfo[BetSlip.betinfo.apiInput.WagerSelectionInfos.WagerSelectionId] === true" class="infoArea">
                  <div class="showInfo">
                    <div class="showInfo-title">{{ BetSlip.betinfo.info.league }}</div>
                    <div class="showInfo-close" @click="letShowInfo(BetSlip.betinfo)"><font-awesome-icon :icon="['fas','times']" /></div>
                  </div>
                  <div class="">{{ BetSlip.betinfo.info.HomeTeam }}<span v-show="BetSlip.betinfo.apiInput.WagerSelectionInfos.BetTypeId !== 11" class="greenV">v</span>{{ BetSlip.betinfo.info.AwayTeam }}</div>
                  <div>{{ showTeamName(BetSlip.betinfo) }}({{ BetSlip.betinfo.apiInput.WagerSelectionInfos.Handicap }})</div>
                  <div>{{ $t('S_MINI_BET') }}：{{ BetSlip.betinfo.apiInput ? BetSlip.betinfo.apiInput.BetSetting[0].MinStakeAmount : '' }}RMB</div>
                  <div>{{ $t('S_MAX_BET') }}：{{ BetSlip.betinfo.apiInput ? BetSlip.betinfo.apiInput.BetSetting[0].MaxStakeAmount : '' }}RMB</div>
                </div>
              </div>
              <template>
                <div v-if="BetSlip.betinfo.info.AwayTeam">
                  <div class="vontent2">{{ BetSlip.betinfo.info.HomeTeam }}<span class="greenV">v</span>{{ BetSlip.betinfo.info.AwayTeam }}</div>
                </div>
                <div v-else />
              </template>
              <div class="input">
                <div class="input-area" :class="{errorBg:block_b || (block_a && showSingle[BetSlip.odds_id] !== false)}">
                  <div class="input-number" @click="showKeyboard(BetSlip.odds_id)">
                    {{ BetSlip.amountShow }}</div>
                  <div class="input-btn" @click="clear(BetSlip.odds_id)">Ｘ</div>
                </div>
                <div class="input-text">{{ getPoint(toWinItem(BetSlip)) }}</div>
                <!-- <div class="input-text">{{ BetSlip.amountShow * BetSlip.betinfo.apiInput?BetSlip.betinfo.apiInput.BetSetting.EstimatedPayoutAmount:1 }}</div> -->
              </div>
            </div>
            <div v-if="BetSlip.amountAlert_min" class="un-comboStatus">
              <font-awesome-icon :icon="['fas','exclamation-triangle']" />
              {{ $t('S_MIN_BET_warm') }}
            </div>
            <div v-if="BetSlip.amountAlert_max" class="un-comboStatus">
              <font-awesome-icon :icon="['fas','exclamation-triangle']" />
              {{ $t('S_MAX_BET_warm') }}
            </div>
            <betTool
              v-show="show[BetSlip.odds_id] === true"
              :betslip="BetSlip"
            />
          </div>
        </div>
        <!-- 別刪掉 -->
        <!-- 單注 -->
        <!-- <div v-if=" Object.keys(curBetSlip).length > 1" class="single">
          <div class="header-left">
            {{ $t('S_SINGLE_BET') }}
          </div>
          <div class="inputBox">
            <div class="box">
              <div class="input">
                <div class="input-area">
                  <div class="input-number" @click="showKeyboard('all-single')">
                    {{ slipContent.allSingleShow }}</div>
                  <div class="input-btn" @click="clear('all-single')">Ｘ</div>
                </div>
                <div class="input-text">{{ $t('S_TO_WIN') }}</div>
              </div>
            </div>
            <betTool
              v-show="show['all-single'] === true"
              :mode="'all-single'"
            />
          </div>
        </div> -->
        <!-- 別刪掉 -->
        <!-- 串關 -->
        <div v-if=" Object.keys(curBetSlip).length > 1">
          <!-- curBetSlip:{{ curBetSlip }} -->
          <div class="header-left passTitle">
            {{ $t('S_MULTIPLE_BETS') }}
          </div>
          <template>
            <div v-if="judgeComboStatus === 2 && Object.keys(curBetSlip).length > 1 ">
              <div v-for="(combo,index) in slipCombo" :key="index">
                <div class="inputBox">
                  <div class="box">
                    <div>{{ getcomboName(combo.ComboSelection) }}
                      <span v-if="combo.ComboSelection === Object.keys(curBetSlip).length + 7">＠<span class="green">{{ (combo.EstimatedPayoutAmount + 1).toFixed(2) }}</span></span>
                    </div>
                    <div class="input">
                      <div class="input-area" :class="{errorBg:block_a === true}">
                        <div class="input-number" @click="showKeyboard(combo.ComboSelection)">
                          {{ combo.amount>0? getPoint(combo.amount): $t('S_STAKE') }}</div>
                        <div class="input-btn" @click="clear('parlay',combo.ComboSelection)">Ｘ</div>
                      </div>
                      <div class="input-combo">X {{ combo.NoOfCombination }}</div>
                      <div class="input-combo-combo-text"> {{ getPoint(getComboTowin(combo.amount, combo.EstimatedPayoutAmount)) }} </div>
                    </div>
                  </div>
                  <div v-if="combo.combo_amountAlert_min" class="un-comboStatus">
                    <font-awesome-icon :icon="['fas','exclamation-triangle']" />
                    {{ $t('S_MIN_BET_WARNING_SIGN') }}：{{ errorAlert_comboMin }}RMB
                  </div>
                  <div v-if="combo.combo_amountAlert_max" class="un-comboStatus">
                    <font-awesome-icon :icon="['fas','exclamation-triangle']" />
                    {{ $t('S_MAX_BET_WARNING_SIGN') }}：{{ errorAlert_comboMax }}RMB
                  </div>
                  <betTool
                    v-show="show[combo.ComboSelection] === true"
                    :mode="'parlay'"
                    :combo-id="combo.ComboSelection"
                  />
                </div>
              </div>
            </div>
            <div v-else>
              <div v-if="comboStatus < 0" class="un-comboStatus">
                <font-awesome-icon :icon="['fas','exclamation-triangle']" />
                {{ $t('S_UN_COMBO') }}
              </div>
            </div>
          </template>

        </div>
        <!-- 臨時測試區<br>
        過關測試：({{ comboStatus }}) -->
        <div class="betbotton">
          <!-- totalBet:{{ totalBet }} -->
          <div class="text">
            <div class="title">
              <div class="introduction">{{ totalBet.count }}&nbsp;{{ $t('S_ALL_BET_SLIP') }}：</div>
              <div class="input"> <p style="color:#fff">{{ getPoint(totalBet.amounts_display) }}&nbsp; RMB</p> </div>
            </div>
            <div class="title">
              <div class="introduction">{{ $t('S_TO_WIN_MONEY') }}:</div>
              <div class="input"> <p>{{ getPoint(totalBet.total_canWin) }}&nbsp; RMB</p> </div>
            </div>
            <div class="title" style="color:#fff"> **{{ $t('S_SYSTEM_AUTOMATE') }}</div>
          </div>
          <div class="buttonArea">
            <div v-if="errorMsg !== ''" class="error">{{ errorMsg }}</div>
            <template>
              <div v-if="mem && token" class="betbotton-OK" @click="placeBetPre()">{{ $t('S_BET') }}</div>
              <div v-else class="betbotton-OK">{{ $t('S_PLZ_RE_LOGIN') }}</div>
            </template>
            <div class="betbotton-ADD" @click="checkClose()">{{ $t('S_ADD_MORE_GAME') }}</div>
            <div class="betbotton-deleteALL" @click="trashAll">{{ $t('S_ALL_DELETE') }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- 投注後頁面 -->
    <div v-else class="bet-slip success-bg">
      <div class="header">
        <div class="header-left success-header">
          <div class="box">
            <p style="font-weight: 800;">{{ $t('S_BET_SLIP') }}</p>
          </div>
        </div>
      </div>
      <div class="betMain">
        <div v-for="(BetSlip,index) in curBetSlip" :key="index">
          <div v-if="!(BetSlip.betResult.StatusCode) && BetSlip.amount > BetSlip.betinfo.apiInput.BetSetting[0].MinStakeAmount" class="mask">
            {{ $t('S_CONFIRMING') }}...
          </div>
          <div v-if="BetSlip.amount > 0 " class="inputBox" :class="{errorBg: BetSlip.betResult.StatusCode !== 100 && BetSlip.amount > 0 }">
            <div class="box">
              <div class="title">
                <font-awesome-icon class="greenIcon" :icon="['fas','exclamation-circle']" @click="letShowInfo(BetSlip.betinfo)" />
                {{ getPeriodId(BetSlip) }}
                {{ BetSlip.betinfo.info.BetTypeName }}
                &nbsp;
                <handicap :bet-item="BetSlip.betinfo.apiInput.WagerSelectionInfos" />
              </div>
              <div class="vontent">
                {{ showTeamName(BetSlip.betinfo) }}
                <handicap :bet-item="BetSlip.betinfo.apiInput.WagerSelectionInfos" />@
                <span class="green">{{ BetSlip.betinfo.apiInput.WagerSelectionInfos.Odds }}</span>
                <!-- infoArea -->
                <div v-if="showInfo[BetSlip.betinfo.apiInput.WagerSelectionInfos.WagerSelectionId] === true" class="infoArea">
                  <div class="showInfo">
                    <div class="showInfo-title">{{ BetSlip.betinfo.info.league }}</div>
                    <div class="showInfo-close" @click="letShowInfo(BetSlip.betinfo)"><font-awesome-icon :icon="['fas','times']" /></div>
                  </div>
                  <div class="">{{ BetSlip.betinfo.info.HomeTeam }}<span class="greenV">v</span>{{ BetSlip.betinfo.info.AwayTeam }}</div>
                  <div>{{ showTeamName(BetSlip.betinfo) }}({{ BetSlip.betinfo.apiInput.WagerSelectionInfos.Handicap }})</div>
                  <div>{{ $t('S_MINI_BET') }}：{{ BetSlip.betinfo.apiInput ? BetSlip.betinfo.apiInput.BetSetting[0].MinStakeAmount : '' }}RMB</div>
                  <div>{{ $t('S_MAX_BET') }}：{{ BetSlip.betinfo.apiInput ? BetSlip.betinfo.apiInput.BetSetting[0].MaxStakeAmount : '' }}RMB</div>
                </div>
              </div>
              <template>
                <div v-if="BetSlip.betinfo.info.AwayTeam">
                  <div class="vontent2">{{ BetSlip.betinfo.info.HomeTeam }}<span class="greenV">v</span>{{ BetSlip.betinfo.info.AwayTeam }}</div>
                </div>
                <div v-else />
              </template>
              <div v-if="BetSlip.amount == 0" class="noAmount-confired">{{ $t('S_CONFIRMED') }}</div>
              <div v-if="BetSlip.amount > 0" class="input">
                <div class="success-input">
                  <div class="success-input-left">
                    {{ $t('S_STAKE') }}<br> <span class="success-toWin">{{ BetSlip.amount }}</span>
                  </div>
                  <template>
                    <div v-if="BetSlip.betResult.StatusCode && BetSlip.betResult.StatusCode === 100">{{ $t('S_BET_ID') }}：{{ BetSlip.betResult.WagerSelectionInfos[0].WagerId }}</div><br>
                    <div v-if="BetSlip.betResult.StatusCode && BetSlip.betResult.StatusCode === 100" class="confired-st">{{ $t('S_CONFIRMED') }}</div>
                    <div v-if="BetSlip.amount < BetSlip.betinfo.apiInput.BetSetting[0].MinStakeAmount" class="fail-st">{{ $t('S_FAIL') }}</div>
                    <div v-if="BetSlip.betResult.StatusCode && BetSlip.betResult.StatusCode !== 100 " class="fail-st">{{ $t('S_FAIL') }}
                      <span class="green">{{ getError(BetSlip.betResult.StatusCode) }}</span>
                      <span v-if="BetSlip.betResult.WagerSelectionInfos.length > 0" class="green">{{ getError(BetSlip.betResult.WagerSelectionInfos[0].BetStatusMessage) }}</span>
                    </div>
                    <div v-if="!BetSlip.betResult.StatusCode && BetSlip.amount > BetSlip.betinfo.apiInput.BetSetting[0].MinStakeAmount">{{ $t('S_CONFIRMING') }}</div>
                  </template>
                </div>
                <div class="success-input-right">{{ canWin }}
                  <div class="success-toWin">{{ toWinItem(BetSlip) }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- 過關結果 -->
      <div v-if="comboStatus === 4">
        <!-- slipContent.parlayShow: {{ slipContent.parlayShow }}
        comboBetResult.WagerSelectionInfos:{{ comboWait }} -->
        <div class="header-left passTitle">
          {{ $t('S_MULTIPLE_BETS') }}
        </div>
        <div v-for="(combo,comboindex) in slipCombo" :key="'parlayResult-'+comboindex">
          <div class="inputBox" :class="{errorBg: combo.betResultCode && (combo.betResultCode !== 100 || combo.betResult.BetStatusMessage !== '100') }">
            <div v-if="combo.amount>0 && combo.betResultCode" class="box">
              <!-- combo.betResult: {{ combo.betResult }} -->
              <div>{{ getcomboName(combo.ComboSelection) }}
                <span v-if="combo.ComboSelection === Object.keys(curBetSlip).length + 7">@ <span class="green">{{ combo.EstimatedPayoutAmount.toFixed(2) }}</span></span>
                <div class="success-input combo-success">
                  <div class="success-input-left combo-left">
                    {{ $t('S_STAKE') }}<br>
                    <span class="green">{{ combo.amount }} X {{ combo.NoOfCombination }}</span>
                  </div>
                  <div class="success-input-right">{{ canWin }}
                    <div class="combo-text">{{ getComboTowin(combo.amount, combo.EstimatedPayoutAmount) }}</div>
                  </div>
                </div>
                <div class="wagerID">
                  <div v-if="combo.betResult && combo.betResult.WagerId">
                    {{ $t('S_BET_ID') }}：{{ combo.betResult.WagerId }}
                    <template>
                      <div v-if="combo.betResult.BetStatusMessage === '100'" class="noAmount-confired">{{ $t('S_CONFIRMED') }}</div>
                      <div v-else class="fail-st-demo">{{ $t('S_FAIL') }}
                        {{ getError(combo.betResult.BetStatusMessage) }}
                      </div>
                    </template>
                  </div>
                  <div v-else>
                    <div class="fail-st-demo">{{ $t('S_FAIL') }}
                      {{ getError(combo.betResultCode) }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="suc-betbotton">
        <div class="text">
          <div class="title">
            <div class="introduction">{{ resultBet.count }}&nbsp;{{ $t('S_ALL_BET_SLIP') }}：</div>
            <div class="input"> <p style="color:#fff">{{ resultBet.amounts }}&nbsp; RMB</p> </div>
          </div>
          <div class="title">
            <div class="introduction">{{ $t('S_TO_WIN_MONEY') }}:</div>
            <div class="input"> <p>{{ resultBet.canWin }}&nbsp; RMB</p> </div>
          </div>
        </div>
        <!-- <div class="check">
          <div class="confired-st">
            <font-awesome-icon :icon="['fas','check-circle']" /> {{ $t('S_CONFIRMED') }}
          </div>
        </div> -->
        <div class="check2" />
        <div class="buttonArea">
          <!-- <div class="betbotton-ADD" @click="keepOption">{{ $t('S_KEEP_OPTION') }}</div> -->
          <div class="betbotton-deleteALL" @click="trashAll">{{ $t('S_CONFIRMED') }}</div>
        </div>
      </div>
    </div>
  </div></template>
<script>
import { mapGetters, mapActions } from 'vuex'
import betTool from '@/components/betTool'
import { getBetInfo, placeBet, getBalance } from '@/utils/getBallData'
import periodConfig from '@/utils/periodInfo'
import handicap from '@/layout/components/handicap'
import comboSet from '@/config/combo_set'

export default {
  components: {
    betTool,
    handicap
  },
  props: {
    openBetSlip: {
      type: Boolean,
      default: false
    },
    passbetting: {
      type: Boolean
    }
  },
  data() {
    return {
      judgeComboStatus: 0,
      times: 0,
      timeOut: '',
      betLock: false,
      showSingle: {},
      comboSet,
      combodata: [],
      trigger_total: false,
      comboAction: false,
      showAlert: false,
      // pre=實際數字 input=顯示
      errorAlert_comboMin: '',
      errorAlert_comboMax: '',
      pre: 0,
      checkRepeat: 0,
      // input: 0,
      maxLength: 10,
      show: {},
      apiBackBetInfo: {},
      betsuccesfull: false,
      // comboStatus 0初始 1取資訊 2可下過關（可能騙你）3準備過關 4以過關
      comboStatus: 0,
      comboAmount: 11,
      infoArea: false,
      max_bet: '10,400.00',
      showInfo: {},
      comboBetResult: {},
      canWin: this.$t('S_TO_WIN'),
      errorMsg: '',
      errorShow: false,
      toWin_item: 0,
      memberBalance: '',
      comboOdd: 0,
      mem: this.$cookie.get('abs-mem'),
      token: this.$cookie.get('abs-token'),
      periodConfig,
      comboWait: {},
      start: false,
      block_a: false,
      block_b: false
    }
  },
  computed: {
    ...mapGetters({
      curBetSlip: 'getBetSlip',
      slipContent: 'getSlipContent',
      slipCombo: 'getSlipCombo'
    }),
    block() {
      const trigger_a = this.curBetSlip.filter(item => item.amount > 0)
      const trigger_b = this.slipCombo.filter(item => item.amount > 0)
      if (trigger_a.length > 0) {
        return 'block_a'
        // 表示有單注
      }
      if (trigger_b.length > 0) {
        return 'block_b'
        // 表示有串關
      }
      return ''
    },
    totalBet() {
      let amounts = 0
      let count = 0
      let combo_amounts = 0
      let combo_count = 0
      let canWin = 0
      let combo_canwin = 0
      let trigger_total = false
      let total_canWin = 0
      let amounts_display = 0
      // let combo_bet = 0
      // const singleBet = !isNaN(canWin) ? canWin.toFixed(2) : 0
      // console.log('totalBet:', this.curBetSlip)
      // console.log('this.slipContent.parlayShow', this.slipContent.parlayShow)
      this.curBetSlip.forEach((element, index, arr) => {
        const trigger = arr.filter(item => item.amount > 0)
        if (trigger.length > 0) {
          trigger_total = true
          // 表示單注有投注
        }
      })
      // 算串關
      this.slipCombo.forEach((element, index, arr) => {
        combo_amounts += Number(element.amount)
        combo_count = arr.filter(item => item.amount > 0).length
        const pre_combo_canwin = element.amount * element.EstimatedPayoutAmount
        amounts_display += Number(element.amount) * element.NoOfCombination
        combo_canwin += Number(pre_combo_canwin)
      })

      if (trigger_total || combo_amounts > 0) {
        // console.log('this.slipContent.parlayShow-2', this.slipContent.parlayShow)
        this.curBetSlip.forEach((element, index, arr) => {
          if (element.betinfo.apiInput.BetSetting) {
            amounts_display += Number(element.amount)
            amounts += Number(element.amount)
            const canWin_odd = element.betinfo.apiInput ? element.betinfo.apiInput.BetSetting[0].EstimatedPayoutAmount : 1
            if (amounts > 0) {
              count = Object(arr.filter(item => item.amount > 0)).length
              canWin = element.amount * canWin_odd
              total_canWin += canWin
            } else {
              count = 0
              amounts = 0
              canWin = this.$t('S_TO_WIN')
              total_canWin = 0
            }
          }
        })
      }

      if (amounts > 0 && combo_amounts === 0) {
        // console.log('totalBet-1')
        const preToWin = combo_canwin > 0 ? Number(total_canWin) + combo_canwin : total_canWin
        return {
          amounts_display,
          amounts: Number(amounts),
          count: count,
          total_canWin: this.$_toFixed(preToWin)
        }
      }
      // 串關
      if (amounts > 0 && combo_amounts > 0) {
        // console.log('totalBet-2')
        const preToWin = combo_canwin > 0 ? Number(total_canWin) + combo_canwin : total_canWin
        return {
          amounts_display,
          amounts: Number(combo_amounts) + Number(amounts),
          count: count + combo_count,
          total_canWin: this.$_toFixed(preToWin)
        }
      }
      // 串關
      if (amounts === 0 && combo_amounts > 0) {
        // console.log('totalBet-3')
        // console.log('第一頁處理串關-count', count)
        return {
          amounts_display,
          amounts: Number(combo_amounts) + Number(amounts),
          count: count + combo_count,
          total_canWin: this.$_toFixed(combo_canwin)
        }
      } else {
        return {
          amounts: 0,
          amounts_display: 0,
          total_amounts: 0,
          count: 0,
          canWin: this.$t('S_TO_WIN'),
          total_canWin: this.$t('S_TO_WIN')
        }
      }
    },
    resultBet() {
      let resultarr = {}
      let resultarr_combo = []
      let total_success_CanWin = 0
      let total_success_Amount = 0
      let total_success_CanWin_combo = 0
      let total_success_Amount_combo = 0
      let success_count = 0
      let success_count_combo = 0
      // console.log('resultBet:', this.curBetSlip)
      // 成功的單注
      this.curBetSlip.forEach((element, index, arr) => {
        resultarr = arr.filter(item => item.betResult.StatusCode === 100)
      })
      // console.log('resultarr:', resultarr)
      resultarr.forEach((element, index, arr) => {
        const success_Amount = Number(element.amount)
        const success_Win_odd = element.betinfo.apiInput ? element.betinfo.apiInput.BetSetting[0].EstimatedPayoutAmount : 1
        const success_CanWin = success_Amount * success_Win_odd
        success_count = Number(index + 1)
        total_success_Amount += success_Amount
        total_success_CanWin += success_CanWin
      })
      // 成功的串關
      if (this.slipCombo.betResult) {
        this.slipCombo.betResult.forEach((element, index, arr) => {
          // console.log('串關下注成功-slipCombo', this.slipCombo)
          resultarr_combo = arr.filter(item => item.StatusCode === 100)
        })
      }
      const comboCheck = this.slipCombo.filter(val => val.betResult && val.betResult.BetStatusMessage && val.betResult.BetStatusMessage === '100')
      comboCheck.forEach((ele) => {
        success_count_combo += 1
        total_success_Amount_combo += Number(ele.amount) * ele.NoOfCombination
        total_success_CanWin_combo += Number(ele.amount) * ele.EstimatedPayoutAmount
      })
      resultarr_combo.forEach((element, index, arr) => {
        const success_Amount_combo = Number(element.amount)
        const success_Win_odd_combo = element.EstimatedPayoutAmount
        const success_CanWin_combo = success_Amount_combo * success_Win_odd_combo
        success_count_combo = Number(index + 1)
        total_success_Amount_combo += success_Amount_combo
        total_success_CanWin_combo += success_CanWin_combo
      })
      // 如果有串關
      if (this.comboStatus === 4) {
        // console.log('有串關')
        const pre_combo_towin = total_success_CanWin + total_success_CanWin_combo
        return {
          amounts: total_success_Amount + total_success_Amount_combo,
          count: success_count + success_count_combo,
          canWin: this.$_toFixed(pre_combo_towin)
        }
      }
      // 成功的 金額 ＆ 可營金
      return {
        amounts: total_success_Amount,
        count: success_count,
        canWin: this.$_toFixed(total_success_CanWin)
      }
    },
    judgeMarket() {
      const marketArr = []
      if (marketArr.length !== this.curBetSlip.length) {
        this.curBetSlip.forEach((item) => {
          if (item.betinfo.apiInput.WagerSelectionInfos.Market !== undefined) {
            marketArr.push(item.betinfo.apiInput.WagerSelectionInfos.Market)
          }
        })
      }
      return marketArr
    }
  },
  watch: {
    openBetSlip(status) {
      if (status === true) {
        this.updateAllBetInfo()
        this.slipContent.parlayShow = this.$t('S_STAKE')
        this.start = true
        this.block_a = false
        this.block_b = false
        this.combo_amountAlert_min = false
        this.combo_amountAlert_max = false
        this.curBetSlip.forEach((data) => {
          data.amountAlert = false
        })
      }
    },
    comboBetResult(val) {
      if (val.WagerSelectionInfos) {
        this.comboWait = val
      }
    },
    judgeMarket(val, old) {
      if (val.length !== old.length || this.passbetting === true) {
        if (val.length === this.curBetSlip.length) {
          this.$emit('getbetting', false)
          this.getApiSeconds()
          // console.log('val & old is diff.')
        }
      }
    }
  },
  created() {
    getBalance().then((res) => {
      if (res) {
        this.memberBalance = res.toFixed(2)
        return
      }
      this.memberBalance = '0.00'
    })
    this.updateAllBetInfo()
  },
  beforeDestroy() {
    clearInterval(this.timeOut)
  },
  mounted() {
    // this.timeOut = setInterval(() => { getBetInfo() }, this.times)
  },
  methods: {
    ...mapActions({
      goBet: 'ball/appendBet',
      switchDisplay: 'ball/toggleBetSlip',
      removeBetSlip: 'ball/actionRemoveBetSlip',
      setAmount: 'ball/actionSetAmount',
      setSlipContent: 'ball/setSlipContent',
      setCombo: 'ball/setCombo',
      resetCombo: 'ball/resetCombo',
      removeBetSlipAll: 'ball/actionRemoveBetSlipAll'
    }),
    getJudgeComboStatus() {
      this.judgeComboStatus = this.comboStatus
      // console.log('getJudgeComboStatus():', this.judgeComboStatus)
    },
    $_toFixed(num) {
      return parseFloat(Number(num))
        .toFixed(3)
        .slice(0, -1)
    },
    getComboTowin(amount, es) {
      if (amount > 0) {
        const newGetComboTowin = amount * es
        return this.$_toFixed(newGetComboTowin)
      } else {
        return this.$t('S_TO_WIN')
      }
    },
    getPoint(money) {
      return money.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
    },
    getcomboName(id) {
      return this.comboSet[id]
    },
    toWinItem(BetSlip) {
      // console.log('BetSlip-EstimatedPayoutAmount:', BetSlip)
      if (BetSlip.betinfo.apiInput && BetSlip.betinfo.apiInput.BetSetting.length > 0) {
        const val = (BetSlip.amount * BetSlip.betinfo.apiInput.BetSetting[0].EstimatedPayoutAmount)
        if (val === 0) {
          return this.$t('S_TO_WIN')
        }
        return val.toFixed(2)
      }
      return this.$t('S_TO_WIN')
    },
    showTeamName(val) {
      const specifiers = val.info.BetTypeName
      if (!specifiers || specifiers === '') {
        return val.SelectionName
      }
      const Specifiers_ele = val.apiInput.WagerSelectionInfos.Specifiers ? val.apiInput.WagerSelectionInfos.Specifiers : ''
      let selectionName = val.info.BetTypeName
      const regex = /[^&=?]+=[^&#]*/g
      const target = Specifiers_ele.match(regex)
      if (target) {
        target.forEach((item, index) => {
          const [key, value] = item.split('=')
          selectionName = selectionName.replace(`{${key}}`, value)
        })
      }
      return selectionName
    },
    showSelectionName(val) {
      const firstChar = val.info.SelectionName.indexOf('(')
      const lastChar = val.info.SelectionName.indexOf(')')
      if (firstChar > -1) {
        const num = (val.apiInput.WagerSelectionInfos.Specifiers || '').split('=')[1]
        const [scoreX, scoreY] = num.split(':')

        return val.info.SelectionName.substring(firstChar + 1, lastChar)
          .replace('{x-y}', scoreX - scoreY)
          .replace('{y-x}', scoreY - scoreX)
      }
      const specifiers = val.apiInput.WagerSelectionInfos.Specifiers
      if (!specifiers || specifiers === '') {
        return val.info.SelectionName
      }

      let selectionName = val.info.SelectionName
      const regex = /[^&=?]+=[^&#]*/g
      const target = specifiers.match(regex)

      if (target) {
        target.forEach((item, index) => {
          const [key, value] = item.split('=')
          selectionName = selectionName.replace(`{${key}}`, value)
        })
      }
      return selectionName
    },
    getSelectionName(betData) {
      // 如有時間，需要優化
      if (betData.betinfo.apiInput.WagerSelectionInfos.BetTypeId === 1 || betData.betinfo.apiInput.WagerSelectionInfos.BetTypeId === 3 || betData.betinfo.apiInput.WagerSelectionInfos.BetTypeId === 4) {
        if (betData.betinfo.apiInput.WagerSelectionInfos.BetTypeSelectionId === 1 || betData.betinfo.apiInput.WagerSelectionInfos.BetTypeSelectionId === 5 || betData.betinfo.apiInput.WagerSelectionInfos.BetTypeSelectionId === 8) {
          return betData.betinfo.info.SelectionName
        }
        if (betData.betinfo.apiInput.WagerSelectionInfos.BetTypeSelectionId === 2 || betData.betinfo.apiInput.WagerSelectionInfos.BetTypeSelectionId === 6 || betData.betinfo.apiInput.WagerSelectionInfos.BetTypeSelectionId === 9) {
          return betData.betinfo.info.SelectionName
        }
        return this.$t('S_DRAW')
      }
      if (betData.betinfo.apiInput.WagerSelectionInfos.BetTypeId === 11) {
        return betData.betinfo.info.SelectionName
      }
      if (betData.betinfo.apiInput.WagerSelectionInfos.BetTypeId === 35) {
        const num = (betData.betinfo.apiInput.WagerSelectionInfos.Specifiers || '').split('=')[1]
        const [scoreX, scoreY] = num.split(':')
        return betData.betinfo.info.SelectionName
          .replace('{x-y}', scoreX - scoreY)
          .replace('{y-x}', scoreY - scoreX)
      }
      if (betData.betinfo.apiInput.WagerSelectionInfos.BetTypeId === 81) {
        const score = betData.betinfo.apiInput.WagerSelectionInfos.Specifiers.split('=')[1]
        return `${betData.betinfo.info.SelectionName}先得${score}分`
      }
      const specifiers = betData.betinfo.apiInput.WagerSelectionInfos.Specifiers
      if (!specifiers || specifiers === '') {
        return betData.betinfo.info.SelectionName
      }
      let selectionName = betData.betinfo.info.SelectionName
      const regex = /[^&=?]+=[^&#]*/g
      const target = specifiers.match(regex)

      if (target) {
        target.forEach((item, index) => {
          const [key, value] = item.split('=')
          selectionName = selectionName.replace(`{${key}}`, value)
        })
      }
      return selectionName
    },
    getScore(score) {
      if (score == null) {
        return 0
      } else {
        return score
      }
    },
    getError(code) {
      return this.$t('S_ERROR_STATUS_' + code)
    },
    getMarket(id) {
      if (id === 3) {
        return this.$t('S_LIVE_S')
      }
    },
    keepOption() {
      this.curBetSlip.forEach((data) => {
        data.amount = ''
      })
      this.slipCombo.forEach((data) => {
        data.amount = ''
      })
      this.block_a = false
      this.block_b = false
      this.updateAllBetInfo()
      this.betsuccesfull = false
    },
    // 預處理
    updateAllBetInfo() {
      let finalInfo = []
      const collision = {}
      this.comboStatus = 0
      // console.log('judgeComboStatus', this.judgeComboStatus)
      if (this.comboStatus === 0 && this.judgeComboStatus === 2) {
        this.comboStatus = 1
      } else {
        this.judgeComboStatus = 0
      }
      this.errorMsg = ''
      this.show = {}
      this.curBetSlip.forEach((data) => {
        const base = data.betinfo.apiInput
        // console.log('check', data.collisionCheck)
        // 過關預處理
        if (this.comboStatus === 0 || this.comboStatus === 1) {
          if (base.WagerSelectionInfos.OddsType === 3 &&
          data.openParlay === true
          ) {
            // 同一個 collisionCheck id 不能下注
            if (!collision[data.collisionCheck]) {
              this.comboStatus = 1
              finalInfo.push(base.WagerSelectionInfos)
              collision[data.collisionCheck] = true
              this.checkRepeat = 0
            } else {
              this.checkRepeat = data.collisionCheck
              this.comboStatus = -1
              finalInfo = []
            }
            // this.comboInfo = '串關處理中'
          } else {
            // this.comboInfo = '含有非歐洲盤賠率 無法串關'
            this.comboStatus = -1
            finalInfo = []
          }
        }

        //
        const apiInput = {
          WagerType: 1, // 1單注 2過關
          ReturnNearestHandicap: false,
          WagerSelectionInfos: [base.WagerSelectionInfos]
        }
        getBetInfo(apiInput).then((res) => {
          if (res.StatusCode === 100 && res.sys_code === 200) {
            base.WagerSelectionInfos =
            { ...base.WagerSelectionInfos,
              ...res.WagerSelectionInfos[0]
            }
            base.BetSetting = res.BetSetting
            base.OuterStateCode = res.BetInfoStatus
          } else {
            // console.log('api error 1', res)
            // const code = res.StatusCode
            // this.errorMsg = this.$t('S_ERROR_STATUS_' + code)
            this.errorMsg = res.sys_result
          }
        })
      })

      if (this.comboStatus === 1) {
        // console.log('過關的傳值：', finalInfo)
        const apiComboInput = {
          WagerType: 2, // 1單注 2過關
          ReturnNearestHandicap: false,
          WagerSelectionInfos: finalInfo
        }
        getBetInfo(apiComboInput).then((res) => {
          if (res.StatusCode === 100 && res.sys_code === 200) {
            this.comboStatus = 2
            this.getJudgeComboStatus()
            this.combodata = res.BetSetting
            this.setCombo(res.BetSetting)
            const comboOddData = res.BetSetting.find(item => item.ComboSelection === finalInfo.length + 7)
            this.comboOdd = comboOddData ? comboOddData.EstimatedPayoutAmount : 1
            this.errorAlert_comboMin = res.BetSetting[0].MinStakeAmount
            this.errorAlert_comboMax = res.BetSetting[0].MaxStakeAmount
          } else {
            // const code = res.StatusCode
            // this.errorMsg = this.$t('S_ERROR_STATUS_' + code)
            this.errorMsg = res.sys_result
          }
        })
      }
    },
    getApiSeconds() {
      const marketType = this.judgeMarket.indexOf(3)
      // console.log('type', marketType)
      if (marketType !== -1) {
        this.times = 5000
      } else {
        this.times = 10000
      }
      this.timeOut = setInterval(() => { this.updateAllBetInfo() }, this.times)
      // console.log('getApiSeconds():', this.timeOut)
      // console.log('T', this.times)
    },
    // 預處理結束
    placeBetWaiting() {
      this.betsuccesfull = true
    },
    // 投注按鈕
    placeBetPre() {
      if (this.betLock) {
        return
      }
      this.betLock = true
      this.errorMsg = ''
      let case1 = true
      let total_amounts = 0
      let total_combo_amounts = 0
      this.show = {}
      // console.log('placeBetPre_curBetSlip:', this.curBetSlip)
      // 先檢查單注下注額
      this.curBetSlip.forEach((data, index, arr) => {
        const amountAlert = data
        const minAmount = data.betinfo.apiInput.BetSetting[0].MinStakeAmount
        const maxAmount = data.betinfo.apiInput.BetSetting[0].MaxStakeAmount
        amountAlert.amountAlert_min = false
        amountAlert.amountAlert_max = false
        total_amounts += Number(data.amount)
        // 單注
        if (data.amount < minAmount && Number(data.amount) > 0) {
          amountAlert.amountAlert_min = true
          case1 = false
          return
        }
        if (data.amount > maxAmount && Number(data.amount) > 0) {
          amountAlert.amountAlert_max = true
          case1 = false
          return
        }
      })

      // 串關
      this.slipCombo.forEach((data, index, arr) => {
        total_combo_amounts += Number(data.amount)
        if (data.amount > 0 && data.amount < data.MinStakeAmount) {
          // console.log('串關金額過小')
          data.combo_amountAlert_min = true
          data.combo_amountAlert_max = false
          case1 = false
          return
        }
        if (data.amount > data.MaxStakeAmount) {
          // console.log('串關金額過大')
          data.combo_amountAlert_max = true
          data.combo_amountAlert_min = false
          case1 = false
          return
        }
      })

      // 全部都沒有下
      if (total_amounts === 0 && total_combo_amounts === 0) {
        case1 = false
        this.errorMsg = '請輸入投注金額'
        this.betLock = false
        return
      }

      if (case1) {
        let finalInfo = []
        const collision = {}
        this.curBetSlip.forEach((data) => {
          // console.log(data)
          const base = data.betinfo.apiInput
          const dataForBet = data
          const minAmount = data.betinfo.apiInput.BetSetting[0].MinStakeAmount
          // 過關預處理
          if (this.comboStatus === 2 || this.comboStatus === 3) {
            if (base.WagerSelectionInfos.OddsType === 3 &&
          data.openParlay === true
            ) {
              if (!collision[data.collisionCheck]) {
                this.comboStatus = 3
                finalInfo.push(base.WagerSelectionInfos)
                collision[data.collisionCheck] = true
              } else {
                this.comboStatus = -2
                finalInfo = []
              }
            } else {
              this.comboStatus = -2
              finalInfo = []
            }
          }
          if (data.amount >= minAmount) {
            const apiInput = {
              WagerType: 1, // single
              CustomerIp: 'CustomerIp',
              ServerIp: 'ServerIp',
              WagerSelectionInfos: [base.WagerSelectionInfos],
              ComboSelections: [
                {
                  ComboSelection: 0,
                  StakeAmount: data.amount
                }
              ]
            }
            placeBet(apiInput).then((res) => {
              // console.log('bet result detail:', res)
              dataForBet.betResult = res
              // console.log('bet after curBetSlip', this.curBetSlip)
              this.betsuccesfull = true
              this.betLock = false
              getBalance().then((res) => {
                if (res) {
                  this.memberBalance = res.toFixed(2)
                  return
                }
                this.memberBalance = '0.00'
              })
            })
          }
        })
        // combo
        if (this.comboStatus === 3) {
          const comboContent = []
          const dataTemp = {}
          this.slipCombo.forEach((data) => {
            if (Number(data.amount) > 0) {
              comboContent.push({
                ComboSelection: data.ComboSelection,
                StakeAmount: data.amount
              })
              dataTemp[data.ComboSelection] = data
            }
          })
          // console.log('dataTemp', dataTemp)
          if (comboContent.length > 0) {
            const apiComboInput = {
              WagerType: 2, // 1單注 2過關
              // CustomerIp: 'CustomerIp',
              // ServerIp: 'ServerIp',
              WagerSelectionInfos: finalInfo,
              ComboSelections: comboContent
            }
            placeBet(apiComboInput).then((res) => {
              this.comboStatus = 4
              // thiscombo_dataForBet.betResult = res
              // this.comboBetResult = res
              if (res.WagerSelectionInfos.length > 0) {
                res.WagerSelectionInfos.forEach((resEach) => {
                  dataTemp[resEach.ComboSelectionId].betResult = resEach
                  dataTemp[resEach.ComboSelectionId].betResultCode = res.StatusCode
                })
              } else {
                Object.keys(dataTemp).forEach((val) => {
                  dataTemp[val].betResultCode = res.StatusCode
                })
              }
              this.betsuccesfull = true
              this.betLock = false
              this.$forceUpdate()
              getBalance().then((res) => {
                if (res) {
                  this.memberBalance = res.toFixed(2)
                  return
                }
                this.memberBalance = '0.00'
              })
            })
          }
          // console.log('dataTemp', dataTemp)
        }
        // 舊版
        // if (this.comboStatus === 3) {
        //   this.slipCombo.forEach((data) => {
        //     if (Number(data.amount) > 0) {
        //       console.log('test', data.amount)
        //       const combo_dataForBet = data
        //       // if (data.amount === '') {
        //       //   data.amount = 0
        //       // }
        //       const apiComboInput = {
        //         WagerType: 2, // 1單注 2過關
        //         CustomerIp: 'CustomerIp',
        //         ServerIp: 'ServerIp',
        //         WagerSelectionInfos: finalInfo,
        //         ComboSelections: [
        //           {
        //             ComboSelection: data.ComboSelection,
        //             StakeAmount: data.amount
        //           }
        //         ]
        //       }
        //       placeBet(apiComboInput).then((res) => {
        //         this.comboStatus = 4
        //         combo_dataForBet.betResult = res
        //         this.comboBetResult = res
        //         this.betsuccesfull = true
        //         this.$forceUpdate()
        //       })
        //     }
        //   })
        // }
      } else {
        this.betLock = false
      }
    },
    // preAmount(preAmount) {
    //   this.input = preAmount
    // },
    letShowInfo(info) {
      const id = info.apiInput.WagerSelectionInfos.WagerSelectionId
      if (!this.showInfo[id]) {
        this.showInfo[id] = true
        // this.showInfo = this.showInfo.filter(item => item.id === id)
      } else {
        delete this.showInfo[id]
      }
      this.$forceUpdate()
    },
    trash(id) {
      clearInterval(this.timeOut)
      this.removeBetSlip(id)
      if (this.curBetSlip.length < 1) {
        this.checkClose()
      } else {
        this.updateAllBetInfo()
      }
    },
    trashAll() {
      if (this.betLock) {
        return
      }
      this.removeBetSlipAll()
      this.betsuccesfull = false
      this.checkClose()
    },
    checkClose() {
      // this.goBet(this.betList[0])
      // this.switchDisplay(false)
      if (this.betLock) {
        return
      }
      clearInterval(this.timeOut)
      this.$emit('getbetting', true)
      this.$emit('closeBetSlip', false)
    },
    showKeyboard(id) {
      clearInterval(this.timeOut)
      this.$emit('getbetting', true)
      console.log('Keyboard is finsh.')
      if (this.block === '' && id < 18) {
        if (!this.show[id]) {
          this.show = {}
          this.show[id] = true
        } else {
          delete this.show[id]
        }
        this.block_a = false
        this.block_b = true
        return
      }
      if (this.block === '' && id > 18) {
        if (!this.show[id]) {
          this.show = {}
          this.show[id] = true
        } else {
          delete this.show[id]
        }
        this.showSingle = {}
        this.block_a = true
        this.block_b = false
        this.showSingle[id] = false
        this.$forceUpdate()
        return
      }
      if (this.block === 'block_b') {
        if (!this.show[id] && id < 18) {
          this.show = {}
          this.show[id] = true
        } else {
          this.block_b = true
          delete this.show[id]
        }
        return
      }
      if (this.block === 'block_a') {
        if (this.showSingle[id] === false && id > 18) {
          this.showSingle[id] = false
          this.$forceUpdate()
        } else {
          this.showSingle[id] = true
          // delete this.showSingle[id]
          this.$forceUpdate()
        }
        return
      }
      this.$forceUpdate()
    },
    // 輸入內容處理函數
    clear(id, comboId) {
      if (id === 'all-single') {
        this.curBetSlip.forEach((data) => {
          this.setAmount({
            odds_id: data.odds_id,
            amount: '',
            amountShow: this.$t('S_STAKE')
            // betinfo
          })
          this.setSlipContent({
            key: 'allSingleAmount',
            val: ''
          })
          this.setSlipContent({
            key: 'allSingleShow',
            val: this.$t('S_STAKE')
          })
        })
      } else if (id === 'parlay') {
        this.resetCombo(comboId)
      } else {
        const newAmount = this.curBetSlip.find(item => item.odds_id === id)
        newAmount.amount = ''
        newAmount.amountShow = this.$t('S_STAKE')
      }
    },
    deleteItem(id) {
      this.gobet(id)
    },
    getPeriodId(val) {
      return periodConfig[val.betinfo.apiInput.WagerSelectionInfos.PeriodId]
    }
  }
}

</script>
<style lang="scss" scoped>

.mask{
  height: 10.5em;
  width: 100%;
  background-color: rgba(253, 224, 151, 0.69);
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;
}
.redError{
  display: initial;
  background-color: rgb(152,53,56);
  color: #fff;
}
.normal{
  display: initial;
}
.showInfo{
  display: block;
  font-size: .8rem;
  font-weight: 800;
    white-space: pre;
  & .showInfo-title{
    float: left;
  }
  & .showInfo-close{
    float: right;
    font-size: 1.5rem;
  }
}
.un-comboStatus {
  padding: 15px 10px;
  font-weight: 700;
  color: #fff;
  background-color: rgb(152,53,56);
}
.confired-st {
  color: #005f15;
}
.fail-st {
  color: red;
}
.green {
  color: #68c751;
  font-weight: 800;
}
.greenV {
  color: #68c751;
  margin: 0 10px;
  font-weight: 800;
}
.greenIcon {
  color: #68c751;
  font-size: 1.1rem;
}
.white {
  color: #FFF;
}
.font-1rem{
  font-size: 1rem;
}
.vontent {
  padding-top: 6px;
  font-size: .9rem;
}
.vontent2 {
  font-size: .8rem;
  padding-top: 6px;
  color: #005f15;
}
.header {
  display: flex;
  // background: linear-gradient(to right, #955d2c, #f3d382 70%, #f3d382 80%, #955d2c 100%);
  background: var(--betslip_top_bg);
  color:  var(--betslip_top_text);
  &-left {
    width: 100%;
    display: flex;
    padding: 10px;

    & .header-right {
      font-size: 20px;
      right: 14px;
      position: absolute;
    }
  }
  &-sub {
    color:  var(--betslip_top_sub_text);
  }
}
.betbox {
  top: 0;
  z-index: 99;
  position: fixed;
  width: 100%;
  height: 100%;
  background-color: rgba(51, 51, 51, 0.452);
  overflow: scroll;
  padding: 10px;
}
.bet-slip {
  margin: 10px auto;
  z-index: 100;
  position: relative;
  width: 97%;
  height: auto;
  // min-height: 100%;
  background-color: rgb(230, 230, 230);
  border: #502a07 solid 4px;
}
.betMain {
  padding: 0;
}
.noAmount-confired{
  color: #005f15;
  font-size: .9rem;
  font-weight: 800;
  padding-top: 10px;
}
.fail-st-demo {
  color: red;
  font-size: .9rem;
  font-weight: 800;
  padding-top: 10px;
}
.confirming-demo {
  color: #333;
  font-size: .9rem;
  font-weight: 800;
  padding-top: 10px;
}
.sqrbtn {
  display: inline-block;
  text-align: center;
  line-height: 40px;
  height: 40px;
  width: 40px;
  background-color: #cecccc;
  color: #353535;
  margin: 4px;
  border-radius: 5px;
}
.appendbtn {
  width: 29%;
}
.tool {
  padding: 10px;
  background-color: #fff;
}
.inputBox {
  font-size: 1rem;
  // height: 10em;
  // padding: 0 0px 10px 0px;
  border-bottom: #808080 solid 1px;
  & .box {
    padding: 10px;
  }
  & .title {
    font-size: .8rem;
    padding: 5px 0;
    align-items: center;
    & .deletIcon {
      float: right;
      color: var(--betslip_middle_icon);
      font-size: 1.1rem;
    }
  }
  & .input {
    width: 100%;
    display: flex;
    margin: 9px 0;
    &-area {
      display: flex;
      width: 50%;
      font-size: 16px;
      background-color: #fff;
      padding: 10px;
      color: #989898;
      font-weight: 500;
    }
    &-number {
      width: 90%;
    }
    &-btn {
      float: right;
    }
    &-combo {
      width: 20%;
      text-align: start;
      padding-left: 5px;
      padding-top: 12px;
      font-size: .8rem;
      &-combo-text {
        width: 30%;
        text-align: end;
        padding-top: 12px;
        font-size: .8rem;
      }
    }
    &-text {
      width: 50%;
      text-align: end;
      padding-top: 12px;
      font-size: .8rem;
    }
  }
}
.betbotton {
  color:  var(--betslip_sub_text);
  font-size: 15px;
  background: var(--betslip_bottom_bg);
  padding: 10px;
  font-weight: bold;
  bottom: 0px;
  // position: absolute;
  width: -webkit-fill-available;

  &-OK {
    width: 100%;
    color: var(--betslip_bottom_btn1_text);
    background:var(--betslip_bottom_btn1_bg);
    vertical-align: middle;
    line-height: 50px;
    border-radius: 3px;
    margin: 5px 0;
  }
  &-ADD {
    width: 100%;
    background: var(--betslip_bottom_btn2_bg);
    color:var(--betslip_bottom_btn2_text);
    vertical-align: middle;
    line-height: 50px;
    border-radius: 3px;
    margin: 5px 0;
  }
  &-deleteALL {
    width: 100%;
    color: var(--betslip_bottom_btn3_text);
    vertical-align: middle;
    height: 40px;
    line-height: 40px;
    border-radius: 3px;
    margin: 5px 0;
  }
}
.buttonArea {
  text-align: center;
}
.text {
  width: 100%;
  font-weight: 400;
  & .title {
    width: 100%;
    display: flex;
    & .introduction {
      width: 35%;
      margin: 3px 0;
    }
    & .input {
      width: 65%;
      display: block;
      margin: 3px 0;
      float: right;
      text-align: right;
    }
  }
}
.success-bg {
  background: #f2f9ce;
}
.success-header {
  justify-content: center;
  padding: 14px 10px;
}
.success-input {
  font-size: .9rem;
  width: 70%;
  color: #929292;
  font-weight: 800;
  & .success-input-left {
    padding-bottom: 8px;
  }
}
.success-input-right {
    width: 30%;
    text-align: end;
    font-size: .8rem;
    color: #929292;
    font-weight: 800;
    & .success-toWin{
      padding-top: 5px;
      color: darkblue;
    }
  }
.suc-betbotton {
  color: var(--betslip_sub_text);
  font-size: 15px;
  background:var(--betslip_bottom_bg);
  padding: 10px 0;
  font-weight: bold;
  bottom: 0px;
  width: -webkit-fill-available;
  & .text {
    padding: 5px 10px;
  }
  & .buttonArea {
    padding: 0 10px;
    & .betbotton-deleteALL{
      color: var(--betslip_bottom_check_text);
    }
  }
}
.combo-success {
  padding-top: 5px;
  width: 100%;
  display: -webkit-inline-box;
  & .combo-left {
    width: 70%;
  }
  & .combo-text {
    text-align: end;
    padding-top: 2px;
    font-size: .8rem;
  }
}
.check{
  background: var(--betslip_check_bg);
  color: #353535;
  padding: 16px 10px;
}
.check2 {
  height: 2px;
  background: var(--betslip_check_bg);
}
.infoArea {
  position: absolute;
  z-index: 999;
  background: rgb(255, 255, 255);
  border: 3px solid rgb(51, 51, 51);
  padding: 8px;
  color: #5a5a5a;
  font-size: .5rem;
  line-height: 20px;
  min-width: 270px;
}
.passTitle {
  padding: 15px 10px;
  background-color: #f3d383;
  border-top: #222 1px solid;
}
.single {
  background-color: #d4d4d4;
  & .header-left {
    padding: 10px 10px 0px 10px;
  }
  & .box {
    padding: 0 10px;
  }
  & .input {
    margin-bottom: 11px;
  }
}
.wagerID {
  padding-top: 8px;
  border-top: 1px solid #333;
  color: #848484;
  font-weight: 800;
}
.errorBg {
  background-color: #fdb8b8  !important;
  .greenIcon {
    color: #fd6262;
    font-size: 1.1rem;
  }
  .green {
    color: #fd6262;
    font-weight: 800;
  }
  .greenV{
    color: #fd6262;
    font-weight: 800;
  }
}
.error {
  color: #ff5c61;
  font-weight: 500;
  font-size: 1.2rem;
  padding: 8px;
}
</style>
