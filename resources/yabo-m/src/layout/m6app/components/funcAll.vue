<template>
  <div class="container">
    <div class="allLeave" @click="allLeave" />
    <div v-if="betsuccesfull===false" class="indexTop">
      <div v-if="betData.betinfo !== undefined" class="betHeader clearfix ">
        <div class="title">{{ betData.betinfo.info.league }}</div>
        <!-- {{ betData.betinfo.info }} -->
        <div v-if="betData.betinfo.info.Market ===3" class="team">
          <span>{{ betData.betinfo.info.HomeTeam }}</span>
          <strong> {{ betData.betinfo.info.HomeScore }}&nbsp; - &nbsp;{{ betData.betinfo.info.AwayScore }} </strong>
          <span>{{ betData.betinfo.info.AwayTeam }}</span>
        </div>
        <div v-else class="team">{{ betData.betinfo.info.HomeTeam }} <strong> VS </strong> {{ betData.betinfo.info.AwayTeam }}</div>
        <!-- <div v-else class="team" /> -->
        <div class="info">
          <div class="name">
            <div class="betTeam"><span v-if="betData.betinfo.info.Market ===3">滾球</span> &nbsp; {{ selectTeam%2 === 0?betData.betinfo.info.AwayTeam:betData.betinfo.info.HomeTeam }} {{ formatCap(info.Handicap) }}</div>
            <!-- <div class="betTeam">{{ betData.betinfo.info.SelectionName }}</div> -->
            <div class="betGame">
              <span>{{ betData.betinfo.info.BetTypeName }}</span>
              <span>{{ betData.betinfo.info.PeriodName }}</span>
              <!-- <span v-if="betData.betinfo.info.Market ===3">{{ parsedString(betData.betinfo.info.gameTime) }}</span> -->
            </div>
          </div>
          <div class="money">
            <!-- <div class="handicap">{{ formatCap(info.Handicap) }}</div> -->
            <div class="odds">@{{ info.Odds }}</div>
          </div>
        </div>
      </div>
      <div class="betContain">
        <div class="info clearfix">
          <div class="txt">限制: {{ MinStakeAmount }} ~ {{ MaxStakeAmount }}</div>
          <div class="currency">餘額 <strong>{{ memberBalance }}</strong></div>
        </div>
        <div class="cash">
          <div class="screen">
            <div class="input">{{ isValid(txtVal) }}</div>
            <!-- txtVal:{{ isValid(Number(getPoint(txtVal))) }} -->
            <div class="betsumit" :class="valid === true ? 'canBet': ''" @click="toBet()">投注</div>
          </div>
          <div class="keyboard ">
            <div class="keyboard-list border-TLR-radius clearfix">
              <div class="list-item">
                <div class="inputboard" @click="input('1')">1</div>
                <!-- <input value="1" type="button" class="inputboard" @click="input('1')"> -->
              </div>
              <div class="list-item">
                <div class="inputboard" @click="input('2')">2</div>
                <!-- <input value="2" type="button" class="inputboard" @click="input('2')"> -->
              </div>
              <div class="list-item">
                <div class="inputboard" @click="input('3')">3</div>
                <!-- <input value="3" type="button" class="inputboard" @click="input('3')"> -->
              </div>
              <div class="list-item quicky-add">
                <div class="addInt" @click="addInt('100')">+100</div>
                <!-- <input value="+100" type="button" class="addInt" @click="addInt('100')"> -->
              </div>
            </div>
            <div class="keyboard-list clearfix">
              <div class="list-item">
                <div class="inputboard" @click="input('4')">4</div>
                <!-- <input value="4" type="button" class="inputboard" @click="input('4')"> -->
              </div>
              <div class="list-item">
                <div class="inputboard" @click="input('5')">5</div>
                <!-- <input value="5" type="button" class="inputboard" @click="input('5')"> -->
              </div>
              <div class="list-item">
                <div class="inputboard" @click="input('6')">6</div>
                <!-- <input value="6" type="button" class="inputboard" @click="input('6')"> -->
              </div>
              <div class="list-item quicky-add">
                <div class="addInt" @click="addInt('200')">+200</div>
                <!-- <input value="+200" type="button" class="addInt" @click="addInt('200')"> -->
              </div>
            </div>
            <div class="keyboard-list clearfix">
              <div class="list-item">
                <div class="inputboard" @click="input('7')">7</div>
                <!-- <input value="7" type="button" class="inputboard" @click="input('7')"> -->
              </div>
              <div class="list-item">
                <div class="inputboard" @click="input('8')">8</div>
                <!-- <input value="8" type="button" class="inputboard" @click="input('8')"> -->
              </div>
              <div class="list-item">
                <div class="inputboard" @click="input('9')">9</div>
                <!-- <input value="9" type="button" class="inputboard" @click="input('9')"> -->
              </div>
              <div class="list-item quicky-add">
                <div class="addInt" @click="addInt('500')">+500</div>
                <!-- <input value="+500" type="button" class="addInt" @click="addInt('500')"> -->
              </div>
            </div>
            <div class="keyboard-list border-DLR-radius clearfix">
              <div class="list-item">
                <div class="inputboard" @click="btnPoint('.')">.</div>
                <!-- <input value="." type="button" class="inputboard" @click="btnPoint('.')"> -->
              </div>
              <div class="list-item">
                <div class="inputboard" @click="input('0')">0</div>
                <!-- <input value="0" type="button" class="inputboard" @click="input('0')"> -->
              </div>
              <div class="list-item delBtn" @click="backFloat()" />
              <div class="list-item quicky-add">
                <div class="addInt max" @click="btnMAX(MaxStakeAmount)">MAX</div>
                <!-- <input value="MAX" type="button" class="addInt max" @click="btnMAX()"> -->
              </div>
            </div>
          </div>
          <!-- <div class="confirm clearfix">
              <div class="addBet">+串</div>
              <div class="clearBet">清除</div>
              <div class="betsumit">投注
                <span class="winOdds">可嬴 0</span>
              </div>
            </div> -->
        </div>
      </div>
    </div>
    <div v-if="statusCode === 102" class="errorPage">
      <div class="error">{{ errorMsg }}</div>
      <div class="buttonArea">
        <div class="betbotton-deleteALL" @click="trashAll()">{{ $t('S_CONFIRMED') }}</div>
      </div>
    </div>
    <div v-if="betsuccesfull===true" class="successPage">
      <div v-if="betData.betinfo !== undefined" class="betHeader clearfix ">
        <div class="success-header">
          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20pt" height="20pt" viewBox="0 0 20 20" version="1.1">
            <g id="surface10507706">
              <path style=" stroke:none;fill-rule:nonzero;fill:rgb(18.039216%,80%,44.313725%);fill-opacity:1;" d="M 18.332031 10 C 18.332031 14.601562 14.601562 18.332031 10 18.332031 C 5.398438 18.332031 1.667969 14.601562 1.667969 10 C 1.667969 5.398438 5.398438 1.667969 10 1.667969 C 14.601562 1.667969 18.332031 5.398438 18.332031 10 Z M 18.332031 10 " />
              <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,100%,100%);fill-opacity:1;" d="M 14.410156 6.078125 L 8.757812 11.738281 L 6.421875 9.410156 L 5.246094 10.589844 L 8.757812 14.09375 L 15.589844 7.253906 Z M 14.410156 6.078125 " />
            </g>
          </svg>
          投注成功
        </div>
        <!-- {{curBetSlip}} -->
        <div class="all">
          <div class="allBet">
            {{ '總投注' }}<br>
            <strong>{{ Number(amount) }}</strong>
          </div>
          <div class="allWin">
            {{ '最高可贏' }}<br>
            <strong>{{ getPoint(Number(amount)*EstimatedPayoutAmount) }}</strong>
          </div>
        </div>
      </div>
      <div class="success-betinfo">
        <div class="info">
          <div class="list">
            <span class="betTypeName">{{ selectTeam%2 === 0?betData.betinfo.info.AwayTeam:betData.betinfo.info.HomeTeam }} &nbsp; </span>
            <span class="handicap">{{ formatCap(info.Handicap) }}</span>
            <span class="odds">@{{ info.Odds }}</span>
          </div>
          <div class="team"> <span v-if="betData.betinfo.info.Market ===3"> 滾球 &nbsp;</span>  {{ betData.betinfo.info.BetTypeName }} {{ betData.betinfo.info.PeriodName }} </div>
          <div v-if="betData.betinfo.info.Market ===3" class="team"> {{ betData.betinfo.info.HomeTeam }}&nbsp;{{ betData.betinfo.info.HomeScore }} <strong>  &nbsp; VS  &nbsp; </strong>{{ betData.betinfo.info.AwayScore }} &nbsp; {{ betData.betinfo.info.AwayTeam }} </div>
          <div v-else class="team">{{ betData.betinfo.info.HomeTeam }} <strong> VS </strong> {{ betData.betinfo.info.AwayTeam }}</div>
        </div>
        <div class="buttonArea">
          <div class="betbotton-deleteALL" @click="trashAll()">{{ $t('S_CONFIRMED') }}</div>
        </div>
      </div>
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
// import { getUnsettledBetList, getBetInfoApi, placeBet, getBalance } from '@/api/m6'
import { getBetInfo, placeBet, getBalance } from '@/utils/getBallData'
// import periodConfig from '@/utils/periodInfo'
// import { getEventInfoByPage } from '@/api/betball'

export default {
  props: {
    openBetSlip: {
      type: Boolean,
      default: () => (false)
    }
  },
  data() {
    return {
      txtVal: '',
      remind: '',
      limit: '限制10～5200',
      str: 2,
      Handicap: 0,
      MarketlineId: 0,
      MarketlineStatusId: 0,
      PeriodId: 0,
      Odds: 0,
      OddsType: 0,
      WagerSelectionId: 0,
      setting: [],
      info: [],
      betData: [],
      memberBalance: 0,
      // periodConfig: 0,
      MinStakeAmount: 0,
      MaxStakeAmount: 1000,
      betsuccesfull: false,
      amount: 0,
      errorMsg: '',
      error: false,
      EstimatedPayoutAmount: 0,
      valid: false,
      statusCode: 100,
      selectTeam: 0
    }
  },
  computed: {
    // ...mapGetters({
    //   getBetInfo: 'm6app/getBetInfo',
    //   getcurBetSlip: 'm6app/getcurBetSlip',
    // })
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
      // return 1===1? '111':'2222'
      // return trigger_a.length > 0 ? 'block_a': 'block_b'
      return this.slipCombo
    }
  },
  watch: {
    openBetSlip(val) {
      // console.log(this.curBetSlip, 'this.curBetSlip-----')
      if (val === true) {
        this.error = false
      }
    },
    curBetSlip(val) {
      // console.log(val, 'val')
      // this.betData = val 所有注單
      // 單注時 取curBetSlip 最新的那個
      this.betData = val[val.length - 1]
      this.getBetSlipData(this.betData)
      // console.log(this.betData, 'this.betData')
    }
  },
  created() {
    getBalance().then((res) => {
      // console.log(res, 'res')
      if (res) {
        this.memberBalance = res.toFixed(2)
        return
      }
      this.memberBalance = '0.00'
    })
  },
  mounted() {
    if (this.openBetSlip === true) {
      this.error = false
    }
    // console.log(this.curBetSlip, 'mouted curBetSlip')
    this.betData = this.curBetSlip[this.curBetSlip.length - 1]
    this.getBetSlipData(this.betData)
  },
  methods: {
    ...mapActions({
      setBet: 'm6app/actionBet',
      setBetSlip: 'm6app/actionSetBetSlip',
      removeBetSlip: 'm6app/actionRemoveBetSlip',
      removeBetSlipAll: 'ball/actionRemoveBetSlipAll',
      setFuncState: 'm6app/actonSetFuncBetOpen'
    }),
    parsedString(val) {
      // console.log(val, 'val')
      if (val.indexOf('Q1') > -1) {
        return '第一节'
      } else if (val.indexOf('Q2') > -1) {
        return '第二节'
      } else if (val.indexOf('Q3') > -1) {
        return '第三节'
      } else if (val.indexOf('Q4') > -1) {
        return '第四节'
      } else if (val.indexOf('1H') > -1) {
        return '上半场'
      } else if (val.indexOf('HT') > -1) {
        return '中场'
      } else if (val.indexOf('2H') > -1) {
        return '下半场'
      } else if (val.indexOf('!LIVE') > -1) {
        return '滚球中'
      } else if (val.indexOf('OT') > -1) {
        return '加时赛'
      }

      // return stringPart
      //   .replace('Q1', this.$t('S_FIRST_QUAR'))
      //   .replace('Q2', this.$t('S_SECOND_QUAR'))
      //   .replace('Q3', this.$t('S_THIRD_QUAR'))
      //   .replace('Q4', this.$t('S_FOURTH_QUAR'))
      //   .replace('1H', this.$t('S_FIRST_HALF'))
      //   .replace('HT', this.$t('S_HT'))
      //   .replace('2H', this.$t('S_SECOND_HALF'))
      //   .replace('!LIVE', this.$t('S_LIVE'))
      //   .replace('OT', this.$t('S_OT'))
    },
    isValid(value) {
      if (value >= this.MinStakeAmount && value <= this.MaxStakeAmount && value <= Number(this.memberBalance)) {
        this.valid = true
      } else {
        this.valid = false
      }
      return value
    },
    getPoint(money) {
      // console.log(money.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','), '111')
      // console.log(this.$_toFixed(money.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')), '123')
      return this.$_toFixed(money.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','))
      // return this.$_toFixed(money.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','))
    },
    $_toFixed(num) {
      return parseFloat(num)
        .toFixed(3)
        .slice(0, -1)
    },
    input(val) {
      this.remind = ''
      if (this.txtVal === '0') {
        this.txtVal = val
        this.amount = this.txtVal
      } else {
        if (String(this.txtVal).includes('.') && this.str > 0) {
          this.txtVal += val
          this.amount = this.txtVal
          this.str = this.str - 1
        } else if (!String(this.txtVal).includes('.')) {
          this.txtVal += val
          this.amount = this.txtVal
        }
      }
    },
    addInt(val) {
      // console.log(Number(val), 'add')
      this.remind = ''
      if (this.txtVal === '' || this.txtVal === '.') {
        this.txtVal = '0'
        this.txtVal = parseFloat(Number(val)) + parseFloat(this.txtVal)
        this.txtVal = this.getPoint(this.txtVal)
        this.amount = this.txtVal
      } else {
        this.txtVal = Number(val) + Number(this.txtVal)
        // this.txtVal = String(this.txtVal)
        // console.log(this.getPoint(this.txtVal), '-----test')
        this.amount = this.txtVal
      }
    },
    btnPoint(val) {
      if (!String(this.txtVal).includes('.')) {
        this.txtVal += val
        this.amount = this.txtVal
      }
    },
    backFloat() {
      this.txtVal = this.txtVal.toString()
      if (this.txtVal.length > 0) {
        this.txtVal = this.txtVal.substring(0, this.txtVal.length - 1)
        this.str = 2
        this.amount = this.txtVal
      } else {
        this.remind = `限制${this.MinStakeAmount}～${this.MaxStakeAmount}`
      }
    },
    btnMAX(val) {
      this.txtVal = String(val)
      // this.str = 2
      this.amount = this.txtVal
    },
    trashAll() {
      if (this.betLock) {
        return
      }
      this.removeBetSlipAll()
      this.betsuccesfull = false
      this.allLeave()
    },
    allLeave() {
      this.$emit('closeFuncAll', false)
      this.removeBetSlipAll()
      this.setFuncState(false)
    },
    formatCap(val) {
      const betTypeId = this.betData.betinfo.apiInput.WagerSelectionInfos.BetTypeId
      const selectionId = this.betData.betinfo.apiInput.WagerSelectionInfos.BetTypeSelectionId
      const mod = val % 0.5
      if (mod === 0) {
        if (betTypeId === 1 && selectionId === 1) {
          if (val > 0) {
            return `-${val}`
          }
          return `${val === 0 ? 0 : +Math.abs(val)}`
        }
        if (betTypeId === 1 && selectionId === 2) {
          if (val > 0) {
            return `+${val}`
          }
          return `${val === 0 ? 0 : -Math.abs(val)}`
        }
        return val
      }
      if (betTypeId === 1 && selectionId === 1) {
        if (val > 0) {
          return `-${Math.abs(val - mod)}/${Math.abs(val + mod)}`
        }
        return `+${Math.abs(val - mod)}/${Math.abs(val + mod)}`
      }
      if (betTypeId === 1 && selectionId === 2) {
        if (val > 0) {
          return `+${Math.abs(val - mod)}/${Math.abs(val + mod)}`
        }
        return `-${Math.abs(val - mod)}/${Math.abs(val + mod)}`
      }
      if (betTypeId === 2) {
        return `${Math.abs(val - mod)}/${Math.abs(val + mod)}`
      }
      return val
    },
    toBet() {
      const data = { // 需要的資料
        BetTypeId: this.betData.betinfo.apiInput.WagerSelectionInfos.BetTypeId,
        AwayScore: this.info.AwayScore,
        HomeScore: this.info.HomeScore,
        BetTypeSelectionId: this.info.BetTypeSelectionId,
        EventId: this.info.EventId,
        Handicap: this.info.Handicap,
        Market: this.info.Market,
        MarketlineId: this.info.MarketlineId,
        MarketlineStatusId: this.info.MarketlineStatusId,
        Odds: this.info.Odds,
        OddsType: this.info.OddsType,
        OutrightTeamId: this.info.OutrightTeamId,
        PeriodId: this.betData.betinfo.apiInput.WagerSelectionInfos.PeriodId,
        Specifiers: this.info.Specifiers,
        SportId: this.info.SportId,
        Status: this.info.Status,
        WagerSelectionId: this.info.WagerSelectionId
      }
      const allData = {
        WagerType: 1, // single
        CustomerIp: 'CustomerIp',
        ServerIp: 'ServerIp',
        WagerSelectionInfos: [data],
        ComboSelections: [
          {
            ComboSelection: 0,
            StakeAmount: this.txtVal // 暫時預設
          }
        ]
      }
      // console.log(allData, 'allData')
      // console.log(this.amount, 'amount in bettings')
      // 需做條件; invalid 按鈕為灰色 / valid 按鈕為黃色 ***
      placeBet(allData).then((res) => {
        // console.log('bet result detail:', res)
        if (res.sys_result === 'Success') {
          this.betsuccesfull = true
          this.txtVal = ''
          // this.EstimatedPayoutAmount = res.BetSetting[0].EstimatedPayoutAmount
          // console.log(this.EstimatedPayoutAmount, 'this.EstimatedPayoutAmount')
        } else {
          // 成功失敗彈窗 ***
          this.txtVal = ''
          // console.log(res.sys_result)
          this.betsuccesfull = false
          this.error = true
          this.errorMsg = res.sys_result
          // dataForBet.betResult = res
          // this.betsuccesfull = true
        }
      })
    },
    getBetSlipData(val) {
      // console.log(val, 'val getBetSlipData')
      if (val === undefined) {
        // console.log('no data')
        this.betData = []
      } else {
        this.selectTeam = val.betinfo.apiInput.WagerSelectionInfos.BetTypeSelectionId
        const apiInput = {
          WagerType: 1, // single
          WagerSelectionInfos: [val.betinfo.apiInput.WagerSelectionInfos],
          ComboSelections: [
            {
              ComboSelection: 0,
              StakeAmount: this.txtVal.toString() // 暫時預設
            }
          ]
        }
        getBetInfo(apiInput).then((res) => {
          if (res.StatusCode === 100 && res.sys_code === 200) {
            // console.log('api success', res)
            this.info =
              {
                ...res.WagerSelectionInfos[0]
              }
            this.setting = res.BetSetting
            // console.log(res.BetSetting, 'res.BetSetting')
            this.MaxStakeAmount = res.BetSetting[0].MaxStakeAmount
            this.MinStakeAmount = res.BetSetting[0].MinStakeAmount
            this.EstimatedPayoutAmount = res.BetSetting[0].EstimatedPayoutAmount
            // base.OuterStateCode = res.BetInfoStatus
          } else {
            const code = res.StatusCode
            this.statusCode = res.StatusCode
            this.errorMsg = this.$t('S_ERROR_STATUS_' + code)
            this.errorMsg = res.sys_result
          }
        })
        // if (this.curBetType === 'unsettled') {
        //   getUnsettledBetList().then((res) => {
        //     this.betList = res
        //   })
        //   return
        // }
        // console.log(this.info, 'this.info')
        // console.log([val.betinfo.apiInput.WagerSelectionInfos][0].PeriodId, 'val')
      }
      // this.refreshSettle = !this.refreshSettle
    }
  }
}
</script>
<style lang="scss" scoped>
.container{
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  z-index: 1;
  .allLeave{
    position: absolute;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(48, 47, 47, 0.7);
  }

  .indexTop {
    position: fixed;
    top: 20%;
    right: 0;
    // bottom: 0;
    width: 100vw;
    height: 82.5vh;
    background-color: rgba(19, 18, 18, 0.6);
    @media (max-width: 320px) {
      top: 13%;
    }
    .clearfix {
      clear: both;
    }
    .betHeader{
        .title{
            padding: 5px 0px;
            color: #c0c4cc;
            font-size: 12px;
            text-align: center;
        }
        .team {
          text-align: center;
          color: #c0c4cc;
          background-color: #1a1b1f;
          padding: 10px 0px;
          strong {
            color: #fff;
          }
        }
        .info {
          background-color: #e6a23c;
          height: 62px;
          .name {
            width: 60%;
            float: left;
            color: #c0c4cc;
            padding: 5px 15px;
            height: 60px;
            font-size: 12px;
            background: linear-gradient(107deg, #363535 91%, transparent 0);
          }
          .money {
            width: 35%;
            float: right;
            color: #fff;
            padding: 16px 0px;
            background: linear-gradient(-75deg, #000 85%, transparent 0);
            .handicap {
              font-size: 14px;
              text-align: right;
              padding-right: 10px;
            }
            .odds {
              font-size: 25px;
              text-align: right;
              padding-right: 10px;
              color: #e6a23c;
            }
          }
        }
    }
    .betContain{
        background: #f5f9fb;
        .info{
            border-bottom: solid 1px #eee;
            padding: 15px;
            position: relative;
            .txt {
              color: #a6a9af;
              width: 60vw;
              float: left;
              font-size: 14px;
            }
            .currency {
              width: 30vw;
              float: right;
              font-size: 14px;
              text-align: right;
              strong {
                font-size: 18px;
              }
            }
        }
        .cash{
            .screen {
              div {
                display: inline-block;
              }
              .input{
                line-height: 20px;
                background: #f5f9fb;
                border: 1px solid #c0c4cc;
                color: #000;
                padding: 8px;
                height: 38px;
                font-size: 20px;
                width: 60vw;
                margin-left: 15px;
                overflow: hidden;
              }
              .betsumit {
                background-color: #c0c4cc;
                line-height: 20px;
                background: #c0c4cc;
                border: 1px solid #c0c4cc;
                color: #fff;
                padding: 8px;
                height: 38px;
                font-size: 14px;
                width: 30vw;
                vertical-align: top;
                text-align: center;
              }
              .canBet {
                background-color: #e6a23c;
              }
            }
            .keyboard{
                margin-top: 15px;
                border: 1px solid #e5e5e5;
                .border-TLR-radius{}
                .border-DLR-radius{}
                .keyboard-list{
                    border-top: 1px solid #e5e5e5;
                    background: #FFF;
                    .list-item{
                        border-right: 1px solid #e5e5e5;
                        float: left;
                        height: 55px;
                        width: 25%;
                        text-align: center;
                        line-height: 55px;
                    }
                    .inputboard{
                        font-size: 20px;
                        font-weight: 700;
                        background-color: #fff;
                        border: none;
                    }
                    .addInt{
                        width: 85%;
                        height: 65%;
                        font-size: 15px;
                        font-weight: 500;
                        padding: 8px 15px;
                        background-color: #1a1b1f;
                        border: none;
                        color: #fff;
                        margin: 10px auto;
                        line-height: 1.5;
                        border-radius: 3px;
                    }
                    .max {
                      background-color: #e6a23c;
                      color: #000;
                    }
                    .quicky-add{
                        font-size: 16px;
                        border-right: 0;
                        background: rgba(180,189,202,0.1);
                    }
                    .delBtn{
                        background: url('~@/icons/png/clearBtn.png') no-repeat center;
                        background-size: 100px;
                    }
                }
            }
        }
        // .confirm{
        //         padding: 5px;
        //         margin-top: 10px;
        //         .addBet{
        //             float: left;
        //             width: 16%;
        //             height: 50px;
        //             font-size: 17px;
        //             text-align: center;
        //             line-height: 50px;
        //             border: 1px solid #0149ff;
        //             border-radius: 6px;
        //             color: #0149ff;
        //         }
        //         .clearBet{
        //             display: none;
        //             float: left;
        //             width: 20%;
        //             height: 50px;
        //             font-size: 17px;
        //             text-align: center;
        //             line-height: 50px;
        //             border: 1px solid #0149ff;
        //             border-radius: 6px;
        //             color: #0149ff;
        //         }
        //         .betsumit{
        //             float: right;
        //             width: 80%;
        //             height: 50px;
        //             border-radius: 6px;
        //             background: #0149ff;
        //             color: #FFF;
        //             font-size: 18px;
        //             line-height: 50px;
        //             text-align: center;
        //             .winOdds{
        //                 margin-left: 5px;
        //                 font-size: 16px;
        //                 color: #98b5ff;
        //             }
        //         }
        //     }
    }
  }
  .successPage {
    position: fixed;
    top: 44.5%;
    right: 0;
    width: 100vw;
    height: 55.5vh;
    background-color: #fff;
    .clearfix {
      clear: both;
    }
    .betHeader{
      .success-header {
        text-align: center;
        padding: 20px;
        svg {
          vertical-align: middle;
        }
      }
      .all {
        // background-color: palegoldenrod;
        width: 90vw;
        margin: 0 auto;
        box-shadow: 0 0 15px #c0c4cc;
        border-right: 1px solid #e4e4e4;
        // .div {}
        .allBet {
          width: 42vw;
          display: inline-block;
          padding: 15px 0px;
          text-align: center;
          border-right: 1px solid #e4e7ed;
          font-size: 12px;
          color: #606266;
          strong {
            color: #000;
            font-size: 20px;
          }
        }
        .allWin {
          width: 42vw;
          display: inline-block;
          padding: 15px 0px;
          text-align: center;
          font-size: 12px;
          color: #606266;
          strong {
            color: #000;
            font-size: 20px;
          }
        }
      }
    }
    .success-betinfo {
      .info {
        padding: 20px;
        // background-color: #e6a23c;
        // border: 1px solid;
        .team {
          color: #606266;
          font-size: 14px;
        }
        .betGame {
          color: #606266;
          font-size: 14px;
        }
        .list {
          font-size: 18px;
          .handicap {
            font-weight: bold;
          }
          .odds {
            color: #e6a23c;
          }
        }
      }
      .buttonArea {
        .betbotton-deleteALL {
          text-align: center;
          width: 90vw;
          margin: 5px auto;
          background-color: #e6a23c;
          padding: 10px;
        }
      }
    }
  }
  .errorPage {
    position: fixed;
    top: 35%;
    right: 0;
    width: 100vw;
    height: 20vh;
    background-color: #909399;
    .clearfix {
      clear: both;
    }
    .error {
      // background-color: red;background-color: #f56c6c;
      color: #fff;
      width: 100%;
      height: 45%;
      font-size: 20px;
      text-align: center;
      line-height: 3;
    }
    .buttonArea {
        .betbotton-deleteALL {
          text-align: center;
          width: 90vw;
          margin: 5px auto;
          background-color: #e6a23c;
          padding: 10px;
        }
      }
  }
}

</style>
