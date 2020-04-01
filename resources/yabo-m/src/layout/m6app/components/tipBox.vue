<template>
  <div class="container">
    <div class="allLeave" @click="allLeave" />
    <div class="indexTop">
      <div class="betHeader clearfix ">
        <div class="info">
          <p class="tip">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20pt" height="20pt" viewBox="0 0 20 20" version="1.1">
              <g id="surface10507706">
                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(18.039216%,80%,44.313725%);fill-opacity:1;" d="M 18.332031 10 C 18.332031 14.601562 14.601562 18.332031 10 18.332031 C 5.398438 18.332031 1.667969 14.601562 1.667969 10 C 1.667969 5.398438 5.398438 1.667969 10 1.667969 C 14.601562 1.667969 18.332031 5.398438 18.332031 10 Z M 18.332031 10 " />
                <path style=" stroke:none;fill-rule:nonzero;fill:rgb(100%,100%,100%);fill-opacity:1;" d="M 14.410156 6.078125 L 8.757812 11.738281 L 6.421875 9.410156 L 5.246094 10.589844 L 8.757812 14.09375 L 15.589844 7.253906 Z M 14.410156 6.078125 " />
              </g>
            </svg>
            投注成功
          </p>
          <div>
            <div class="all">
              <div class="allBet">
                {{ '所有投注' }}<br>
                <span class="green">{{ resultBet.amounts }}</span>
              </div>
              <div class="allWin">
                {{ '最高可贏' }}<br>
                <span>{{ resultBet.canWin }}</span>
                <!-- <div v-for="(combo,comboindex) in slipCombo" :key="'parlayResult-'+comboindex">
                  <div v-if="combo.amount>0 && combo.betResultCode" class="box">
                    <div class="success-input-right">
                      <div class="combo-text">{{ getComboTowin(combo.amount, combo.EstimatedPayoutAmount) }}</div>
                    </div>
                  </div>
                </div> -->
              </div>
            </div>
            <div class="area">
              <div v-for="(combo,comboindex) in slipCombo" :key="'parlayResult-'+comboindex">
                <div class="inputBox" :class="{errorBg: combo.betResultCode && (combo.betResultCode !== 100 || combo.betResult.BetStatusMessage !== '100') }">
                  <div v-if="combo.amount>0 && combo.betResultCode" class="box">
                    <div>{{ getcomboName(combo.ComboSelection) }}
                      <span v-if="combo.ComboSelection === Object.keys(curBetSlip).length + 7">@ <span>{{ combo.EstimatedPayoutAmount.toFixed(2) }}</span></span>
                      <div class="origin">本金: {{ combo.amount }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="suc-betbotton">
            <div class="buttonArea">
              <div class="betbotton-deleteALL" @click="gotoList()">{{ $t('S_CONFIRMED') }}</div>
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
import { getBalance } from '@/utils/getBallData'
// import periodConfig from '@/utils/periodInfo'
import comboSet from '@/config/combo_set'
// import { getEventInfoByPage } from '@/api/betball'

export default {
  props: {
    resultBet: {
      type: Object,
      default: () => ({})
    },
    slipCombo: {
      type: Array,
      default: () => ([])
    },
    betsuccesfull: {
      type: Boolean,
      default: () => (false)
    },
    comboStatus: {
      type: Number,
      default: () => (0)
    }
  },
  data() {
    return {
      comboSet
    }
  },
  computed: {
    ...mapGetters({
      curBetSlip: 'getBetSlip'
    })
    // resultBet() {
    //   let resultarr = {}
    //   let resultarr_combo = []
    //   let total_success_CanWin = 0
    //   let total_success_Amount = 0
    //   let total_success_CanWin_combo = 0
    //   let total_success_Amount_combo = 0
    //   let success_count = 0
    //   let success_count_combo = 0
    //   // console.log('resultBet:', this.curBetSlip)
    //   // 成功的單注
    //   this.curBetSlip.forEach((element, index, arr) => {
    //     resultarr = arr.filter(item => item.betResult.StatusCode === 100)
    //   })
    //   // console.log('resultarr:', resultarr)
    //   resultarr.forEach((element, index, arr) => {
    //     const success_Amount = Number(element.amount)
    //     const success_Win_odd = element.betinfo.apiInput ? element.betinfo.apiInput.BetSetting[0].EstimatedPayoutAmount : 1
    //     const success_CanWin = success_Amount * success_Win_odd
    //     success_count = Number(index + 1)
    //     total_success_Amount += success_Amount
    //     total_success_CanWin += success_CanWin
    //   })
    //   // 成功的串關
    //   if (this.slipCombo.betResult) {
    //     this.slipCombo.betResult.forEach((element, index, arr) => {
    //       // console.log('串關下注成功-slipCombo', this.slipCombo)
    //       resultarr_combo = arr.filter(item => item.StatusCode === 100)
    //     })
    //   }
    //   const comboCheck = this.slipCombo.filter(val => val.betResult && val.betResult.BetStatusMessage && val.betResult.BetStatusMessage === '100')
    //   comboCheck.forEach((ele) => {
    //     success_count_combo += 1
    //     total_success_Amount_combo += Number(ele.amount) * ele.NoOfCombination
    //     total_success_CanWin_combo += Number(ele.amount) * ele.EstimatedPayoutAmount
    //   })
    //   resultarr_combo.forEach((element, index, arr) => {
    //     const success_Amount_combo = Number(element.amount)
    //     const success_Win_odd_combo = element.EstimatedPayoutAmount
    //     const success_CanWin_combo = success_Amount_combo * success_Win_odd_combo
    //     success_count_combo = Number(index + 1)
    //     total_success_Amount_combo += success_Amount_combo
    //     total_success_CanWin_combo += success_CanWin_combo
    //   })
    //   // 如果有串關
    //   if (this.comboStatus === 4) {
    //     // console.log('有串關')
    //     const pre_combo_towin = total_success_CanWin + total_success_CanWin_combo
    //     return {
    //       amounts: total_success_Amount + total_success_Amount_combo,
    //       count: success_count + success_count_combo,
    //       canWin: this.$_toFixed(pre_combo_towin)
    //     }
    //   }
    //   // 成功的 金額 ＆ 可營金
    //   return {
    //     amounts: total_success_Amount,
    //     count: success_count,
    //     canWin: this.$_toFixed(total_success_CanWin)
    //   }
    // }
  },
  watch: {
    curBetSlip(val) {
      this.betData = val[val.length - 1]
      this.getBetSlipData(this.betData)
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
      removeBetSlipAll: 'ball/actionRemoveBetSlipAll'
    }),
    allLeave() {
      this.$router.push({ path: '/moreSportCombo' })
      // this.removeBetSlipAll()
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
    num(init) {
      return init + init
    },
    getcomboName(id) {
      return this.comboSet[id]
    },
    gotoList() {
      this.removeBetSlipAll()
      // this.betsuccesfull = false
      this.$router.push({ path: '/moreSportCombo' })
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
    top: 45px;
    right: 0;
    // bottom: 0;
    width: 100vw;
    height: 82.5vh;
    background-color: rgba(19, 18, 18, 0.6);
    .clearfix {
      clear: both;
    }
    .betHeader{
        .info {
            background-color: #f5f7fa;
            height: 100%;
            position: absolute;
            top: 30%;
            width: 100%;
            .tip {
                text-align: center;
                padding: 35px;
                font-weight: bold;
            }
            div {
                .all {
                    width: 90vw;
                    margin: 0 auto;
                    // border: 1px solid;
                    .allWin {
                        padding: 10px 38px;
                        display: inline-block;
                        text-align: center;
                        box-shadow: 0 0 15px #c0c4cc;
                    }
                    .allBet {
                        padding: 10px 13%;
                        display: inline-block;
                        text-align: center;
                        box-shadow: 0 0 15px #c0c4cc;
                    }
                }
                .area {
                  height: 145px;
                  overflow-y: scroll;
                  padding: 0px 15px;
                  div {
                    .inputBox {
                    font-size: 1rem;
                    // height: 10em;
                    // padding: 0 0px 10px 0px;
                    // border-bottom: #808080 solid 1px;
                    & .box {
                        padding: 25px 10px;
                        div {
                            span {
                                color: #e6a23c;
                            }
                            .origin {
                                float: right;
                            }
                        }
                        & .input {
                            .input-area {
                              border: 1px solid gray;
                            }
                        }
                    }
                    }
                  }
                }
            }
            .suc-betbotton {
                margin: 10px;
                .buttonArea {
                    text-align: center;
                    background-color: #e6a23c;
                    padding: 15px;
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
}

</style>
