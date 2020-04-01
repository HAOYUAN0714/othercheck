<template>
  <div class="betbox">
    <div class="Header">
      <div class="back" @click="back()">
        <font-awesome-icon class="icon" :icon="['fas','angle-left']" />
      </div>
      <div class="title" @click="trashAll">
        <span>{{ $t('S_MULTIPLE_BETS') }}</span>
        <span v-if="curBetSlip.length > 0" class="total">{{ curBetSlip.length }}</span>
      </div>
    </div>
    <div class="clear" />
    <div v-if="!betsuccesfull" class="screen">
      <div class="memberBalance">餘額(元) <strong>{{ memberBalance }}</strong> </div>
      <div class="betbotton-deleteALL" @click="trashAll">{{ $t('S_ALL_DELETE') }}</div>
    </div>
    <div class="clear" />
    <div class="bet-slip">
      <!-- betsuccesfull : {{ betsuccesfull }} -->
      <div class="betMain">
        <div v-for="(BetSlip,index) in curBetSlip" :key="index">
          <div class="inputBox">
            <div class="box">
              <div class="title">
                <div class="vontent">
                  <template>
                    <span> {{ showSelectionName(BetSlip.betinfo) }} </span>
                  </template>
                  <handicap :bet-item="BetSlip.betinfo.apiInput.WagerSelectionInfos" />
                  <span class="odds"> @{{ BetSlip.betinfo.apiInput.WagerSelectionInfos.Odds }}</span>
                </div>
                <div class="deletIcon" @click="trash(BetSlip.odds_id)">X</div>
              </div>
              <template>
                <div v-if="BetSlip.openParlay === true && Object.keys(curBetSlip).length > 1 && BetSlip.betinfo.apiInput.WagerSelectionInfos.OddsType === 3 && BetSlip.collisionCheck !== checkRepeat" class="vontent2">
                  {{ getMarket(BetSlip.betinfo.apiInput.WagerSelectionInfos.Market) }}&nbsp;
                  {{ showTeamName(BetSlip.betinfo) }}
                  &nbsp;
                </div>
                <div v-else class="vontent2" :class="{redError:Object.keys(curBetSlip).length > 1}">
                  {{ getMarket(BetSlip.betinfo.apiInput.WagerSelectionInfos.Market) }}&nbsp;
                  {{ showTeamName(BetSlip.betinfo) }}
                  &nbsp;
                  <handicap :bet-item="BetSlip.betinfo.apiInput.WagerSelectionInfos" :error="Object.keys(curBetSlip).length > 1" />
                </div>
              </template>
              <template>
                <div v-if="BetSlip.betinfo.info.AwayTeam">
                  <div class="vontent2">{{ BetSlip.betinfo.info.league }}</div>
                </div>
                <div v-else />
              </template>
              <template>
                <div v-if="BetSlip.betinfo.info.AwayTeam">
                  <div class="vontent2">{{ BetSlip.betinfo.info.HomeTeam }} VS {{ BetSlip.betinfo.info.AwayTeam }}</div>
                </div>
                <div v-else />
              </template>
            </div>
            <div v-if="BetSlip.amountAlert_min" class="un-comboStatus">
              <font-awesome-icon :icon="['fas','exclamation-triangle']" />
              {{ $t('S_MIN_BET_warm') }}
            </div>
            <div v-if="BetSlip.amountAlert_max" class="un-comboStatus">
              <font-awesome-icon :icon="['fas','exclamation-triangle']" />
              {{ $t('S_MAX_BET_warm') }}
            </div>
            <!-- BetSlip{{ BetSlip }} -->
            <betTool2
              v-show="show[BetSlip.odds_id] === true"
              :betslip="BetSlip"
            />
          </div>
        </div>
      </div>
    </div>
    <!-- 串關 -->
    <el-collapse v-if=" Object.keys(curBetSlip).length > 1" v-model="activeNames" @change="handleChange">
      <el-collapse-item title="展開串關方式">
        <template>
          <div v-if="comboStatus === 2 && Object.keys(curBetSlip).length > 1 ">
            <div v-for="(combo,index) in slipCombo" :key="index">
              <div class="inputBox">
                <ul>
                  <li class="game">
                    <div>{{ getcomboName(combo.ComboSelection) }}
                      <span v-if="combo.ComboSelection === Object.keys(curBetSlip).length + 7">＠<span>{{ (combo.EstimatedPayoutAmount + 1).toFixed(2) }}</span></span>
                    </div>
                  </li>
                  <li class="input-combo">
                    <div>{{ combo.NoOfCombination }} *</div>
                  </li>
                  <li class="input-area">
                    <div class="input" :class="{errorBg:block_a === true}">
                      <div class="input-number" @click="showKeyboard(combo.ComboSelection)">
                        {{ combo.amount>0? combo.amount: `最小限額 ${errorAllert_min}` }}
                      </div>
                      <div class="input-btn" @click="clear('parlay',combo.ComboSelection)">Ｘ</div>
                    </div>
                  </li>
                </ul>
                <div v-if="combo.combo_amountAlert && Number(combo.amount) < errorAllert_min" class="un-comboStatus">
                  <font-awesome-icon :icon="['fas','exclamation-triangle']" />
                  {{ '最小投注額' }}：{{ errorAllert_min }}RMB
                </div>
                <div v-if="combo.combo_amountAlert && Number(combo.amount) > errorAllert_max" class="un-comboStatus">
                  <font-awesome-icon :icon="['fas','exclamation-triangle']" />
                  {{ '最大投注額' }}：{{ errorAllert_max }}RMB
                </div>
                <!-- mode {{ 'parlay' }}
                combo.ComboSelection{{ combo.ComboSelection }} -->
                <betTool2
                  v-show="show[combo.ComboSelection] === true"
                  :mode="'parlay'"
                  :error-allert-min="errorAllert_min"
                  :error-allert-max="errorAllert_max"
                  :combo-id="combo.ComboSelection"
                />
              </div>
            </div>
          </div>
          <div v-else>
            <div v-if="comboStatus < 0" class="un-comboStatus">
              <font-awesome-icon :icon="['fas','exclamation-triangle']" />
              {{ '紅色选项不可以结合并行过关投注' }}
            </div>
          </div>
        </template>
      </el-collapse-item>
    </el-collapse>
    <!--過關：({{ comboStatus }}) -->
    <div v-if="statusCode === 102" class="betbotton">
      <div class="error">{{ errorMsg }}</div>
      <div class="buttonArea">
        <div class="betbotton-deleteALL" @click="trashAll()">{{ $t('S_CONFIRMED') }}</div>
      </div>
    </div>
    <div v-else class="betbotton">
      <div class="text">
        <div class="title">
          <div class="introduction">{{ totalBet.count }}&nbsp;{{ $t('S_ALL_BET_SLIP') }}：</div>
          <div class="input"> <p style="color:#606266">{{ isValid(totalBet.amounts_display) }}&nbsp; RMB</p> </div>
        </div>
        <!-- <div class="title">
              <div class="introduction">{{ $t('S_TO_WIN_MONEY') }}:</div>
              <div class="input"> <p>{{ totalBet.total_canWin }}&nbsp; RMB</p> </div>
            </div> -->
      </div>
      <div class="buttonArea">
        <!-- <div v-if="errorMsg !== ''" class="error">{{ errorMsg }}</div> -->
        <template>
          <div class="betbotton-OK" :class="valid === true ? 'canBet': ''" @click="placeBetPre()">
            {{ $t('S_BET') }}
            <p>{{ $t('S_TO_WIN_MONEY') }} : {{ getPoint(totalBet.total_canWin) }}&nbsp; RMB</p>
          </div>
        </template>
      </div>
    </div>
    <!-- 投注後頁面 -->
    <!-- <div v-if="betsuccesfull"> 123 </div> -->
    <div v-if="betsuccesfull" class="success success-bg">
      <transition>
        <tipBox
          v-show="isShowFuncAll"
          :open-bet-slip="isShowFuncAll"
          :result-bet="resultBet"
          :slip-combo="slipCombo"
          :betsuccesfull="betsuccesfull"
          :combo-status="comboStatus"
          @closeFuncAll="closeFuncAll"
        />
      </transition>
    </div>
    <div v-if="error===true && betsuccesfull===false" class="errorPage">
      <div class="error">{{ errorMsg }}</div>
      <div class="buttonArea">
        <div class="betbotton-deleteALL" @click="trashAll()">{{ $t('S_CONFIRMED') }}</div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import betTool2 from '@/components/betTool2'
import { getBetInfo, placeBet, getBalance } from '@/utils/getBallData'
import periodConfig from '@/utils/periodInfo'
import handicap from '@/layout/components/handicap'
import comboSet from '@/config/combo_set'
import tipBox from '@/layout/m6app/components/tipBox'

export default {
  name: 'ComboBet',
  components: {
    betTool2,
    handicap,
    tipBox
  },
  // props: {
  //   openBetSlip: {
  //     type: Boolean,
  //     default: false
  //   }
  // },
  data() {
    return {
      activeNames: ['1'],
      betLock: false,
      showSingle: {},
      comboSet,
      combodata: [],
      trigger_total: false,
      combo_amountAlert: false,
      comboAction: false,
      showAlert: false,
      // pre=實際數字 input=顯示
      // errorAllert_combo: '',
      errorAllert_min: '',
      errorAllert_max: '',
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
      // mem: this.$cookie.get('abs-mem'),
      // token: this.$cookie.get('abs-token'),
      mem: '',
      token: '',
      periodConfig,
      comboWait: {},
      start: false,
      block_a: false,
      block_b: false,
      isopen: false,
      setting: [],
      isShowFuncAll: false,
      error: false,
      valid: false,
      statusCode: 100
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
      // console.log('a:', this.curBetSlip.map(item => item.amount), 'trigger_a', trigger_a)
      // console.log('a:', this.slipCombo.map(item => item.amount), 'trigger_b', trigger_b)
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
        // console.log(this.curBetSlip.forEach((x)=> {console.log(x)}), 'this.curBetSlip intotalBet')
        this.curBetSlip.forEach((element, index, arr) => {
          // console.log(element, 'element')
          // console.log('算串關', element)
          // console.log('element:', element, 'index:', index, 'arr:', arr)
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
          canWin: 0,
          total_canWin: 0
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
    }
  },
  watch: {
    isopen(val) {
      if (val === true) {
        this.updateAllBetInfo()
        this.slipContent.parlayShow = this.$t('S_STAKE')
        this.start = true
        this.block_a = false
        this.block_b = false
        this.combo_amountAlert = false
        this.curBetSlip.forEach((data) => {
          data.amountAlert = false
        })
      }
    },
    comboBetResult(val) {
      if (val.WagerSelectionInfos) {
        this.comboWait = val
      }
    }
  },
  created() {
    getBalance().then((res) => {
      if (res) {
        this.memberBalance = res.toFixed(2)
        return this.memberBalance
      }
      this.memberBalance = '0.00'
    })
  },
  mounted() {
    this.isopen = true
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
    getPoint(money) {
      // console.log(money.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','), '111')
      // console.log(this.$_toFixed(money.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')), '123')
      return this.$_toFixed1(money.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','))
      // return this.$_toFixed(money.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','))
    },
    $_toFixed1(num) {
      return parseFloat(num)
        .toFixed(3)
        .slice(0, -1)
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
    handleChange(val) {
      console.log(val)
    },
    // getTotal(amount, es) {
    //   if (amount > 0) {
    //   } else {
    //     return this.$t('S_TO_WIN')
    //   }
    // },
    back() {
      this.$router.go(-1) // 返回上一层
      // this.removeBetSlipAll()
    },

    getcomboName(id) {
      return this.comboSet[id]
    },
    // toWinItem(BetSlip) {
    //   // console.log('BetSlip-EstimatedPayoutAmount:', BetSlip)
    //   if (BetSlip.betinfo.apiInput && BetSlip.betinfo.apiInput.BetSetting.length > 0) {
    //     const val = (BetSlip.amount * BetSlip.betinfo.apiInput.BetSetting[0].EstimatedPayoutAmount)
    //     if (val === 0) {
    //       return this.$t('S_TO_WIN')
    //     }
    //     return val.toFixed(2)
    //   }
    //   return this.$t('S_TO_WIN')
    // },
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
    showSelectionName(val) { // 選擇主客隊
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
      if (!specifiers || specifiers === '') { // 選擇隊伍名稱
        if (val.info.SelectionName === '大' || val.info.SelectionName === '主') {
          return val.info.HomeTeam
        } else {
          return val.info.AwayTeam
        }
        // return val.info.SelectionName
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
    getError(code) {
      return this.$t('S_ERROR_STATUS_' + code)
    },
    getMarket(id) {
      console.log(id, 'getMarket')
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
      // console.log('updateAllBetInfo this.curBetSlip', this.curBetSlip)
      let finalInfo = []
      const collision = {}
      this.comboStatus = 0
      this.errorMsg = ''
      this.show = {}
      // OddsType: 赔率类型 1 = 马来盘 / 2 = 香港盘 / 3 = 欧洲盘 / 4 = 印尼盘
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
        // console.log(finalInfo, 'finalInfo')
        const apiInput = {
          WagerType: 1, // 1單注 2過關
          IsComboAcceptAnyOdds: true,
          ReturnNearestHandicap: false,
          WagerSelectionInfos: [base.WagerSelectionInfos]
        }
        getBetInfo(apiInput).then((res) => {
          if (res.StatusCode === 100 && res.sys_code === 200) {
            // console.log('api success')
            base.WagerSelectionInfos =
            { ...base.WagerSelectionInfos,
              ...res.WagerSelectionInfos[0]
            }
            base.BetSetting = res.BetSetting
            base.OuterStateCode = res.BetInfoStatus
          } else {
            // console.log('api error 1', res)
            const code = res.StatusCode
            this.statusCode = res.StatusCode
            this.errorMsg = this.$t('S_ERROR_STATUS_' + code)
            this.errorMsg = res.sys_result
          }
        })
      })

      if (this.comboStatus === 1) {
        // console.log('過關的傳值：', finalInfo)
        const apiComboInput = {
          WagerType: 2, // 1單注 2過關
          IsComboAcceptAnyOdds: true,
          ReturnNearestHandicap: false,
          WagerSelectionInfos: finalInfo
        }
        getBetInfo(apiComboInput).then((res) => {
          if (res.StatusCode === 100 && res.sys_code === 200) {
            this.comboStatus = 2
            this.combodata = res.BetSetting
            // console.log(res.BetSetting, 'setCombo')
            this.setCombo(res.BetSetting)
            const comboOddData = res.BetSetting.find(item => item.ComboSelection === finalInfo.length + 7)
            this.comboOdd = comboOddData ? comboOddData.EstimatedPayoutAmount : 1
            console.log(res.BetSetting[0].MaxStakeAmount, 'res.BetSetting[0]')
            this.errorAllert_min = res.BetSetting[0].MinStakeAmount
            this.errorAllert_max = res.BetSetting[0].MaxStakeAmount
            // this.errorAllert_combo = res.BetSetting[0].MinStakeAmount
          } else {
            const code = res.StatusCode
            this.statusCode = res.StatusCode
            this.errorMsg = this.$t('S_ERROR_STATUS_' + code)
            this.errorMsg = res.sys_result
          }
        })
      }
    },
    // 預處理結束
    placeBetWaiting() {
      this.betsuccesfull = true
    },
    isValid(val) {
      if (val >= 5 && Number(this.memberBalance) >= val) {
        this.valid = true
      } else {
        this.valid = false
      }
      return val
    },
    // 投注按鈕
    placeBetPre() {
      // console.log('---1')
      if (this.betLock) {
        // console.log('---2')
        return
      }
      // console.log('---3')
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
        // console.log('---4')
        // console.log('1test')
        total_combo_amounts += Number(data.amount)
        // console.log(data.amount, 'data.amount', data.MinStakeAmount, 'data.MinStakeAmount')
        if (data.amount > 0 && data.amount < data.MinStakeAmount) {
          // console.log('串關金額過小')
          // console.log(data.MinStakeAmount, 'data.MinStakeAmount')
          // console.log('2test')
          data.combo_amountAlert = true
          case1 = false
          return
        }
        if (data.amount > data.MaxStakeAmount) {
          // console.log('串關金額過大')
          // console.log('3test')
          data.combo_amountAlert = true
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
              IsComboAcceptAnyOdds: true,
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
              // console.log('isbetsuccesfull:', this.betsuccesfull)
              this.betsuccesfull = true
              this.betLock = false
              this.isShowFuncAll = !this.isShowFuncAll
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
              IsComboAcceptAnyOdds: true,
              // CustomerIp: 'CustomerIp',
              // ServerIp: 'ServerIp',
              WagerSelectionInfos: finalInfo,
              ComboSelections: comboContent
            }
            placeBet(apiComboInput).then((res) => {
              // console.log('test==========', res)
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
              if (res.sys_result === 'Success') {
                // console.log('res', res)
                this.betsuccesfull = true
                this.isShowFuncAll = !this.isShowFuncAll
                // console.log('isbetsuccesfull:', this.betsuccesfull)
              } else {
                this.betsuccesfull = false
                this.error = true
                this.errorMsg = res.sys_result
              }

              this.betLock = false
              this.$forceUpdate() // null
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
      } else {
        this.betLock = false
      }
      // this.isShowFuncAll = !this.isShowFuncAll
    },
    closeFuncAll(val) {
      this.isShowFuncAll = val
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
      this.$forceUpdate() // null
    },
    trash(id) {
      this.removeBetSlip(id)
      if (this.curBetSlip.length < 1) {
        this.checkClose()
      } else {
        this.updateAllBetInfo()
      }
    },
    gotoList() {
      if (this.betLock) {
        return
      }
      this.removeBetSlipAll()
      this.betsuccesfull = false
      this.$router.push({ path: '/moreSportCombo' })
    },
    trashAll() {
      if (this.betLock) {
        return
      }
      this.removeBetSlipAll()
      this.betsuccesfull = false
      this.checkClose()
      this.$router.go(-1) // 返回上一层
    },
    checkClose() {
      // this.goBet(this.betList[0])
      // this.switchDisplay(false)
      if (this.betLock) {
        return
      }
      this.$emit('closeBetSlip', false)
    },
    showKeyboard(id) {
      // console.log(id, 'showKeyboard', this.block, 'this.block')
      if (this.block === '' && id < 18) {
        // console.log('1')
        if (!this.show[id]) {
          // console.log('2')
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
        // console.log('test', 'this.show[id]', !this.show[id])
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
      // console.log(comboId, 'comboId')
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
        // this.betLock = false
        // this.combo_amountAlert = false
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
    },
    openNav() {
      document.getElementById('bet').style.height = '70vh'
    },
    closeNav() {
      document.getElementById('bet').style.height = '30vh'
    }
  }
}

</script>

<style lang="scss" scoped>
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
// .green {
//   color: #68c751;
//   font-weight: 800;
// }
.white {
  color: #FFF;
}
.font-1rem{
  font-size: 1rem;
}
.vontent {
  font-weight: bold;
  padding-top: 6px;
  font-size: .9rem;
  .odds {
    color: #e6a23c;
  }
}
.vontent2 {
  font-size: .8rem;
  padding-top: 6px;
  color: #606266;
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
  width: 100%;
  height: 100%;
  overflow: scroll;
  background: #ebeef5eb;
  .screen {
    background-color: #e6a23c;
    height: 45px;
    .memberBalance{
      float: left;
      width: 60vw;
      padding: 10px;
      font-size: 14px;
      strong {
        font-size: 18px;
      }
    }
    .betbotton {
      &-deleteALL {
        float: right;
        width: 20vw;
        padding: 5px;
      }
    }
  }
  .Header {
    background-color: #000;
    color:#fff;
    height: 45px;
    .back {
      width: 25vw;
      float: left;
      padding: 10px;
    }
    .title {
      float: left;
      padding-top: 7px;
      .total {
        background: red;
        border-radius: 50px;
        padding: 1px 5px;
      }
    }
  }
  .clear {
    clear: both;
  }
}
.bet {
  // background-color: aliceblue;
  // width: 100vw;
  // height: 50vh;
  // position: absolute;
  // bottom: 0;
    width: 100vw;
    height: 50%;
    position: fixed;
    z-index: 1;
    bottom: 0;
    background-color: aliceblue;;
    overflow-y: hidden;
    transition: 0.5s;
    box-shadow: 0 0 15px #606266;
    .title {
      padding: 10px;
      background-color: #dcdfe6;
    }
    a {
      position: absolute;
      right: 0;
      top: 0;
      // padding: 10px;
    }
    .betbotton {
      &-BET {
        width: 100vw;
        text-align: center;
        padding: 10px;
        background-color: #909399;
        color: #fff;
        position: absolute;
        bottom: 0;
        // top: 100vh
      }
    }
}
.bet-slip {
  background-color: #f5f7fa;
  width: 90vw;
  margin: 0 auto;
  height: 60%;
  overflow-y: scroll;
}
>>>.el-collapse {
    margin-top: 10px;
  .el-collapse-item {
    .el-collapse-item__header {
      color: #606266;
      padding-left: 25px;
      background-color: #dcdfe6;
    }
    .is-active {
      color: #606266;
      padding-left: 25px;
      background-color: #dcdfe6;
      i {
        padding: 0;
      }
    }
  }
  .is-active {
  }
  .el-collapse-item__wrap {
    // background-color: #005f15;
    .el-collapse-item__content {
      padding-bottom: 0px;
      background-color:#f5f7fa;
      // padding-left: 20px;
      // background-color: pink;
      .inputBox {
        ul {
          padding-left: 25px;
        }
      }
    }
  }
}
// .success {
//   width: 100vw;
//   position: fixed;
//   bottom: 0;
// }
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
  border-bottom: #e4e4e4 solid 1px;
  .box {
    padding: 10px;
    .title {
      .vontent {
        display: inline-block;
        width: 90%;
      }
      .deletIcon {
        display: inline-block;
        color: #606266;
      }
    }
  }
  ul {
    padding: 10px;
    margin: 0;
    list-style: none;
    // background-color: #fff;
    li {
      display: inline-block;
    }
    .game {
      width: 40%;
      div {
        span {
          color: #e6a23c;
        }
      }
    }
    .input-combo {
      width: 12%;
      font-size: .8rem;
      div {
        font-weight: bold;
      }
    }
    .input-area {
      width: 40%;
      .input {
        // background-color: #e4e7ed;
        display: flex;
        padding: 5px;
        padding: 10px 5px;
        border: 1px solid #e4e7ed;
        color: #909399;
        .input-number {
          width: 80%;
          font-size: 14px;
        }
        .input-btn {
          width: 10%;
        }
      }
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
    // background-color: #eaac4d;
    // margin: 5px 0;
  }
}

.buttonArea {
  .betbotton-OK {
    line-height: 25px;
    text-align: center;
    width: 82vw;
    margin: 5px auto;
    background-color: #909399;
    color: #fff;
    p {
      font-size: 12px;
      font-weight: 500;
    }
  }
  .canBet {
    background-color: #e6a23c;
  }
}
.text {
  width: 100%;
  font-weight: 400;
  & .title {
    width: 100%;
    display: flex;
    margin-bottom: 20px;
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
  background: #f2f6fc;
  position: absolute;
  width: 100%;
  top: 62%;
  .all {
    width: 90vw;
    margin: 0 auto;
    // border: 1px solid;
    .allWin {
      width: 49%;
      padding: 5% 0px;
      display: inline-block;
      text-align: center;
      box-shadow: 0 0 15px #c0c4cc;
    }
    .allBet {
      width: 49%;
      padding: 5% 0px;
      display: inline-block;
      text-align: center;
      box-shadow: 0 0 15px #c0c4cc;
    }
  }
  .allCombo {
    width: 90vw;
    margin: 10px auto;
    // border-bottom: 1px solid gray;
  }

}
.success-header {
  justify-content: center;
  padding: 35px 10px;
}
// .success-input {
//   font-size: .9rem;
//   width: 70%;
//   color: #929292;
//   font-weight: 800;
//   & .success-input-left {
//     padding-bottom: 8px;
//   }
// }
// .success-input-right {
//     width: 30%;
//     text-align: end;
//     font-size: .8rem;
//     color: #929292;
//     font-weight: 800;
//     & .success-toWin{
//       padding-top: 5px;
//       color: darkblue;
//     }
//   }
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
      background-color: #e6a23c;
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
  // border-top: 1px solid #333;
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
  text-align: center;
}
.buttonArea {
  .betbotton-deleteALL {
    text-align: center;
    // width: 90vw;
    width: 84vw;
    margin: 5px auto;
    background-color: #e6a23c;
    // padding: 10px;
    padding: 0px;
  }
}
</style>
