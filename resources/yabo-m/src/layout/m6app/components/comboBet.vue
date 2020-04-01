<template>
  <div class="container">
    <div class="allLeave" @click="allLeave" />
    <div class="indexTop">
      <div v-if="betData.betinfo !== undefined" class="betHeader clearfix ">
        <div class="title">{{ betData.betinfo.info.league }}</div>
        <div class="team">{{ betData.betinfo.info.HomeTeam }} <strong> VS </strong> {{ betData.betinfo.info.AwayTeam }}</div>
        <div class="info">
          <div class="name">
            <!-- <div class="betTeam">{{7}}</div>
            <div class="betTeam">{{7}}</div> -->
            <div class="betGame">{{ betData.betinfo.info.BetTypeName }}</div>
          </div>
          <div class="money">
            <div class="handicap">{{ formatCap(info.Handicap) }}</div>
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
            <div class="input">{{ txtVal }}</div>
            <div class="betsumit" @click="toBet()">投注</div>
          </div>
          <div class="keyboard ">
            <div class="keyboard-list border-TLR-radius clearfix">
              <div class="list-item">
                <input value="1" type="button" class="inputboard" @click="input('1')">
              </div>
              <div class="list-item">
                <input value="2" type="button" class="inputboard" @click="input('2')">
              </div>
              <div class="list-item">
                <input value="3" type="button" class="inputboard" @click="input('3')">
              </div>
              <div class="list-item quicky-add">
                <input value="+100" type="button" class="addInt" @click="addInt('100')">
              </div>
            </div>
            <div class="keyboard-list clearfix">
              <div class="list-item">
                <input value="4" type="button" class="inputboard" @click="input('4')">
              </div>
              <div class="list-item">
                <input value="5" type="button" class="inputboard" @click="input('5')">
              </div>
              <div class="list-item">
                <input value="6" type="button" class="inputboard" @click="input('6')">
              </div>
              <div class="list-item quicky-add">
                <input value="+200" type="button" class="addInt" @click="addInt('200')">
              </div>
            </div>
            <div class="keyboard-list clearfix">
              <div class="list-item">
                <input value="7" type="button" class="inputboard" @click="input('7')">
              </div>
              <div class="list-item">
                <input value="8" type="button" class="inputboard" @click="input('8')">
              </div>
              <div class="list-item">
                <input value="9" type="button" class="inputboard" @click="input('9')">
              </div>
              <div class="list-item quicky-add">
                <input value="+500" type="button" class="addInt" @click="addInt('500')">
              </div>
            </div>
            <div class="keyboard-list border-DLR-radius clearfix">
              <div class="list-item">
                <input value="." type="button" class="inputboard" @click="btnPoint('.')">
              </div>
              <div class="list-item">
                <input value="0" type="button" class="inputboard" @click="input('0')">
              </div>
              <div class="list-item delBtn" @click="backFloat()" />
              <div class="list-item quicky-add">
                <input value="MAX" type="button" class="addInt max" @click="btnMAX()">
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'
// import { getUnsettledBetList, getBetInfoApi, placeBet, getBalance } from '@/api/m6'
import { getBetInfo, placeBet, getBalance } from '@/utils/getBallData'
import periodConfig from '@/utils/periodInfo'
// import { getEventInfoByPage } from '@/api/betball'

export default {
  props: {},
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
      periodConfig: 0,
      MinStakeAmount: 0,
      MaxStakeAmount: 1000,
      // comboStatus 0初始 1取資訊 2可下過關（可能騙你）3準備過關 4以過關
      comboStatus: 0,
      checkRepeat: 0,
      comboOdd: 0,
      // pre=實際數字 input=顯示
      errorAllert_combo: ''
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
    curBetSlip(val) {
      // console.log(val, 'val')
      // this.betData = val 所有注單
      let finalInfo = []
      const collision = {}
      this.comboStatus = 0
      this.errorMsg = ''
      this.show = {}
      val.forEach((data) => {
        // console.log(data.betinfo.apiInput, 'data.betinfo.apiInput')
        const base = data.betinfo.apiInput
        // console.log(base, 'base')
        if (this.comboStatus === 0 || this.comboStatus === 1) {
          if (base.WagerSelectionInfos.OddsType === 3 && data.openParlay === true) {
            // 同一個 collisionCheck id 不能下注
            if (!collision[data.collisionCheck]) {
              this.comboStatus = 1
              finalInfo.push(base.WagerSelectionInfos)
              // console.log(finalInfo, 'finalInfo')
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
        if (this.comboStatus === 1) {
          // console.log('過關的傳值：', finalInfo)
          const apiComboInput = {
            WagerType: 2, // 1單注 2過關
            ReturnNearestHandicap: false,
            WagerSelectionInfos: finalInfo
          }
          // console.log('apiComboInput', apiComboInput)
          getBetInfo(apiComboInput).then((res) => {
            // console.log(res, 'res')
            if (res.StatusCode === 100 && res.sys_code === 200) {
              this.comboStatus = 2
              this.combodata = res.BetSetting // setting
              this.setCombo(res.BetSetting)
              const comboOddData = res.BetSetting.find(item => item.ComboSelection === finalInfo.length + 7) // ???? UNDEFINED
              this.comboOdd = comboOddData ? comboOddData.EstimatedPayoutAmount : 1
              this.errorAllert_combo = res.BetSetting[0].MinStakeAmount
            } else {
              // const code = res.StatusCode
              // this.errorMsg = this.$t('S_ERROR_STATUS_' + code)
              this.errorMsg = res.sys_result
            }
          })
        }
      })
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
  methods: {
    ...mapActions({
      setBet: 'm6app/actionBet',
      setBetSlip: 'm6app/actionSetBetSlip',
      removeBetSlip: 'm6app/actionRemoveBetSlip',
      setCombo: 'ball/setCombo',
      resetCombo: 'ball/resetCombo'
    }),
    input(val) {
      this.remind = ''
      if (this.txtVal === '0') {
        this.txtVal = val
      } else {
        if (this.txtVal.includes('.') && this.str > 0) {
          this.txtVal += val
          this.str = this.str - 1
        } else if (!this.txtVal.includes('.')) {
          this.txtVal += val
        }
      }
    },
    addInt(val) {
      this.remind = ''
      if (this.txtVal === '') {
        this.txtVal = '0'
        this.txtVal = parseFloat(val) + parseFloat(this.txtVal)
      } else {
        this.txtVal = parseFloat(val) + parseFloat(this.txtVal)
      }
    },
    btnPoint(val) {
      this.remind = ''
      if (this.txtVal !== this.txtVal.includes('.')) {
        this.txtVal += val
      }
    },
    backFloat() {
      this.txtVal = this.txtVal.toString()
      if (this.txtVal.length > 0) {
        this.txtVal = this.txtVal.substring(0, this.txtVal.length - 1)
        this.str = 2
      } else {
        this.remind = '限制10～5200'
      }
    },
    btnMAX() {
      this.txtVal = '0'
      this.str = 2
    },
    allLeave() {
      this.$emit('closeFuncAll', false)
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
            StakeAmount: this.txtVal.toString() // 暫時預設
          }
        ]
      }
      // 需做條件; invalid 按鈕為灰色 / valid 按鈕為黃色 ***
      placeBet(allData).then((res) => {
        // console.log('bet result detail:', res)
        // 成功失敗彈窗 ***
        this.txtVal = ''
        // dataForBet.betResult = res
        // this.betsuccesfull = true
      })
    },
    getBetSlipData(val) {
      // console.log(val, 'getBetSlipData')

    //   getBetInfo(apiInput).then((res) => {
    //       if (res.StatusCode === 100 && res.sys_code === 200) {
    //         console.log('api success',res)
    //         this.info =
    //           {
    //             ...res.WagerSelectionInfos[0]
    //           }
    //         this.setting = res.BetSetting
    //         // console.log(res.BetSetting, 'res.BetSetting')
    //         this.MaxStakeAmount = res.BetSetting[0].MaxStakeAmount
    //         this.MinStakeAmount = res.BetSetting[0].MinStakeAmount
    //         // base.OuterStateCode = res.BetInfoStatus
    //       } else {
    //         const code = res.StatusCode
    //         this.errorMsg = this.$t('S_ERROR_STATUS_' + code)
    //         this.errorMsg = res.sys_result
    //       }
    //     })
    //   if (this.curBetType === 'unsettled') {
    //     getUnsettledBetList().then((res) => {
    //       this.betList = res
    //     })
    //     return
    //   }
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
    top: 100px;
    // bottom: 0;
    width: 100vw;
    height: 82.5vh;
    background-color: rgba(19, 18, 18, 0.6);
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
            padding: 15px 15px;
            font-size: 12px;
            background: linear-gradient(107deg, #363535 91%, transparent 0);
          }
          .money {
            width: 35%;
            float: right;
            color: #fff;
            padding: 8px 0px;
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
                        font-size: 15px;
                        font-weight: 500;
                        padding: 10px 15px;
                        background-color: #1a1b1f;;
                        border: none;
                        color: #fff;
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

    }
  }
}

</style>
