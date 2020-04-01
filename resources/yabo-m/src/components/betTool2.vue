<template>
  <div class="tool">
    <div class="keyboard ">
      <div class="keyboard-list border-TLR-radius clearfix">
        <div class="list-item">
          <div class="inputboard" @click="append(1)">1</div>
        </div>
        <div class="list-item">
          <div class="inputboard" @click="append(2)">2</div>
        </div>
        <div class="list-item">
          <div class="inputboard" @click="append(3)">3</div>
        </div>
        <div class="list-item quicky-add">
          <div class="addInt" @click="inc(100)">+100</div>
        </div>
      </div>
      <div class="keyboard-list clearfix">
        <div class="list-item">
          <div class="inputboard" @click="append(4)">4</div>
        </div>
        <div class="list-item">
          <div class="inputboard" @click="append(5)">5</div>
        </div>
        <div class="list-item">
          <div class="inputboard" @click="append(6)">6</div>
        </div>
        <div class="list-item quicky-add">
          <div class="addInt" @click="inc(200)">+200</div>
        </div>
      </div>
      <div class="keyboard-list clearfix">
        <div class="list-item">
          <div class="inputboard" @click="append(7)">7</div>
        </div>
        <div class="list-item">
          <div class="inputboard" @click="append(8)">8</div>
        </div>
        <div class="list-item">
          <div class="inputboard" @click="append(9)">9</div>
        </div>
        <div class="list-item quicky-add">
          <div class="addInt" @click="inc(300)">+300</div>
        </div>
      </div>
      <div class="keyboard-list border-DLR-radius clearfix">
        <div class="list-item">
          <div class="inputboard" @click="appendDot()">.</div>
        </div>
        <div class="list-item">
          <div class="inputboard" @click="append(0)">0</div>
        </div>
        <div class="list-item delBtn" @click="back()">
          <!-- <font-awesome-icon :icon="['fas','caret-square-left']" /> -->
        </div>
        <div class="list-item quicky-add">
          <div class="addInt max" @click="btnMAX(errorAllertMax)">MAX</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  props: {
    mode: {
      type: String,
      default: () => ('')
    },
    betslip: {
      type: Object,
      default: () => ({})
    },
    comboId: {
      type: Number,
      default: () => (0)
    },
    errorAllertMin: {
      type: Number,
      default: () => (0)
    },
    errorAllertMax: {
      type: Number,
      default: () => (0)
    }
  },
  data() {
    return {
      // pre=實際數字 input=顯示
      pre: '',
      input: this.$t('S_STAKE'),
      maxLength: 10,
      show: {}
    }
  },
  computed: {
    ...mapGetters({
      // curBetSlip: 'getBetSlip',
      slipContent: 'getSlipContent',
      slipCombo: 'getSlipCombo'
    })
  },
  mounted() {
    // this.updateInfo()
  },
  methods: {
    ...mapActions({
      setBetSlip: 'ball/actionSetBetSlip',
      goBet: 'ball/appendBet',
      setAmount: 'ball/actionSetAmount',
      setSlipContent: 'ball/setSlipContent',
      setCombo: 'ball/setCombo',
      SetCombo_amount: 'ball/actionSetCombo_amount'
    }),
    updateInfo() {
      if (this.betslip.odds_id) {
        const newdata = this.curBetSlip.find(item => item.odds_id === this.betslip.odds_id)
        this.pre = newdata.amount
      }
      if (this.mode === 'all-single') {
        this.pre = this.slipContent.allSingleAmount
      }
      if (this.mode === 'parlay') {
        const newcombodata = this.slipCombo.find(item => item.ComboSelection === this.comboId)
        this.pre = newcombodata.amount
      }
    },
    back() {
      this.updateInfo()
      this.upDateValue(this.pre.substring(0, this.pre.length - 1))
    },
    inc(value) {
      this.updateInfo()
      this.upDateValue(Number(this.pre) + Number(value))
    },
    appendDot() {
      this.updateInfo()
      if (this.pre.indexOf('.') === -1) {
        this.upDateValue(this.pre + '.')
      }
    },
    btnMAX(value) {
      this.updateInfo()
      this.upDateValue(Number(value))
    },
    append(value) {
      this.updateInfo()
      if (typeof value === 'number') {
        value = value.toString()
      }
      this.upDateValue(this.pre + value)
    },
    upDateValue(value, force = false) {
      if (typeof value === 'number') {
        value = value.toString()
      }
      var formateValue = value.replace(/,/g, '')
      if (this.maxLength !== -1) {
        if (formateValue.length > this.maxLength) {
          if (force) {
            // 載入的時候，如果大於長度則截斷並強制載入
            formateValue = formateValue.substr(0, this.maxLength)
          } else {
            // this.input = this.inputData
            return
          }
        }
      }
      var temp = ''
      var str

      let leftNumber = formateValue.split('.')[0]
      while (leftNumber.length - 3 > 0) {
        temp =
          ',' +
          leftNumber.substring(leftNumber.length - 3, leftNumber.length) +
          temp
        leftNumber = leftNumber.substring(0, leftNumber.length - 3)
      }
      str = leftNumber + temp
      this.pre = str === '' ? 0 : str

      if (formateValue.indexOf('.') !== -1) {
        const rightNumber = formateValue.split('.')[1]
        str = this.pre + '.' + rightNumber.substring(0, 2)
      }

      // 改變的input值
      this.input = str || this.$t('S_STAKE')
      const newstr = str.replace(/,/g, '')
      this.pre = newstr

      if (this.betslip.odds_id) {
        this.setAmount({
          odds_id: this.betslip.odds_id,
          amount: this.pre,
          amountShow: this.input
        // betinfo
        })
      }

      if (this.mode === 'all-single') {
        this.curBetSlip.forEach((data) => {
          this.setAmount({
            odds_id: data.odds_id,
            amount: this.pre,
            amountShow: this.input
            // betinfo
          })
          this.setSlipContent({
            key: 'allSingleAmount',
            val: this.pre
          })
          this.setSlipContent({
            key: 'allSingleShow',
            val: this.input
          })
        })
      } else {
        this.setSlipContent({
          key: 'allSingleAmount',
          val: ''
        })
        this.setSlipContent({
          key: 'allSingleShow',
          val: this.$t('S_STAKE')
        })
      }

      if (this.mode === 'parlay') {
        this.SetCombo_amount({
          id: this.comboId,
          amount: this.pre
        })
        // this.setCombo({
        //   key: 'parlayShow',
        //   val: this.input
        // })
      }
      // this.$emit('preAmount', this.pre)
      // this.amount = this.pre
    }
  }
}
</script>

<style lang="scss" scoped>
.tool {
//   padding: 10px;
    background-color: #fff;
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
</style>
