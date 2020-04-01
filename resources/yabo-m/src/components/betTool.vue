<template>
  <div class="tool">
    <!-- amount: {{ amount }}<br>
    oddId: {{ oddId }}<br>
    input: {{ input }}<br>
    pre: {{ pre }}<br>-->
    <!-- betslip: {{ betslip.odds_id }} -->
    <!-- this.betslip.betinfo.info.Competition: {{ betslip.betinfo.info.league }} -->

    <div class="sqrbtn appendbtn" @click="inc(10)">+10</div>
    <div class="sqrbtn appendbtn" @click="inc(100)">+100</div>
    <div class="sqrbtn appendbtn" @click="inc(250)">+250</div>
    <hr>
    <div class="sqrbtn" @click="append(1)">1</div>
    <div class="sqrbtn" @click="append(2)">2</div>
    <div class="sqrbtn" @click="append(3)">3</div>
    <div class="sqrbtn" @click="append(4)">4</div>
    <div class="sqrbtn" @click="append(5)">5</div>
    <div class="sqrbtn" @click="append(6)">6</div>
    <div class="sqrbtn" @click="append(7)">7</div>
    <div class="sqrbtn" @click="append(8)">8</div>
    <div class="sqrbtn" @click="append(9)">9</div>
    <div class="sqrbtn" @click="append(0)">0</div>
    <div class="sqrbtn" @click="appendDot()">.</div>
    <div class="sqrbtn font-1rem" @click="back()">
      <font-awesome-icon :icon="['fas','caret-square-left']" />
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  props: {
    mode: {
      type: String,
      default: ''
    },
    betslip: {
      type: Object,
      default: () => ({})
    },
    amount: {
      type: String,
      default: '0'
    },
    oddId: {
      type: Number,
      default: 0
    },
    comboId: {
      type: Number,
      default: 0
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
      curBetSlip: 'getBetSlip',
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
    append(value) {
      this.updateInfo()
      if (typeof value === 'number') {
        // console.log('append-1')
        value = value.toString()
      }
      // console.log('append-2')
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
</style>
