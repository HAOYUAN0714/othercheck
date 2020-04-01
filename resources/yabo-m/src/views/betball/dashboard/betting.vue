<template>
  <div class="betting-interface" @click="allLeave()">
    <div class="indexTop">
      <div class="betHeader clearfix">
        <div class="title">投注單</div>
        <div class="amount">
          <div class="currency">¥</div>
          <div class="money">0</div>
        </div>
      </div>
      <div class="betContain">
        <div class="info clearfix">
          <div class="details">
            <div class="bet-type">滾球</div>
            <div class="alliance">美國大學籃球</div>
            <div class="team">华盛顿 VS 波尔州立</div>
          </div>
          <div class="odds">
            <div class="betOdds"><span>@</span>1.82</div>
            <div class="score">(76:58)波尔州立 +17.5</div>
          </div>
          <div class="delete">刪除</div>
        </div>
        <div class="cash">
          <div class="input">
            <input v-model="txtVal" placeholder="限制10～5200">
          </div>
          <div class="keyboard">
            <div class="keyboard-list  clearfix">
              <div class="list-item inputboard" @click="input(1)">1</div>
              <div class="list-item inputboard" @click="input(2)">2</div>
              <div class="list-item inputboard" @click="input(3)">3</div>
              <div class="list-item inputboard quicky-add" @click="addInt('100')">+100</div>
              <div class="list-item inputboard" @click="input(4)">4</div>
              <div class="list-item inputboard" @click="input(5)">5</div>
              <div class="list-item inputboard" @click="input(6)">6</div>
              <div class="list-item inputboard quicky-add" @click="addInt('200')">+200</div>
              <div class="list-item inputboard" @click="input(7)">7</div>
              <div class="list-item inputboard" @click="input(8)">8</div>
              <div class="list-item inputboard" @click="input(9)">9</div>
              <div class="list-item inputboard quicky-add" @click="addInt('500')">+500</div>
              <div class="list-itemBottom inputboard " @click="btnPoint('.')">.</div>
              <div class="list-itemBottom inputboard" @click="input(0)">0</div>
              <div class="list-itemBottom delBtn" @click="backFloat()" />
              <div class="list-itemBottom inputboard quicky-add " @click="btnMAX()">MAX</div>
            </div>
          </div>
          <div class="confirm clearfix">
            <div class="addBet">+串</div>
            <div class="clearBet">清除</div>
            <div class="betsumit">投注
              <span class="winOdds">可嬴 {{ winMoney }}</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      txtVal: '',
      remind: '限制10～5200',
      str: 2,
      leftNum: '',
      rightNum: '',
      winMoney: 0,
      winleftNum: '',
      winrightNum: ''
    }
  },
  methods: {
    input(val) {
      val = val.toString()
      if (this.txtVal === '0') {
        this.txtVal = val
        this.winMoney = (parseFloat(this.txtVal) * 1.82 - parseFloat(this.txtVal)).toString()
      } else {
        this.txtVal += val
        if (this.txtVal.indexOf('.') !== -1) {
          this.leftNum = this.txtVal.split('.')[0]
          this.rightNum = this.txtVal.split('.')[1]
          this.txtVal = this.leftNum + '.' + this.rightNum.substring(0, 2)
        }
        this.winMoney = (parseFloat(this.txtVal) * 1.82 - parseFloat(this.txtVal)).toString()
      }
    },
    addInt(val) {
      if (this.txtVal === '') {
        this.txtVal = '0'
        this.txtVal = (parseFloat(val) + parseFloat(this.txtVal)).toString()
        this.winMoney = (parseFloat(this.txtVal) * 1.82 - parseFloat(this.txtVal)).toString()
      } else {
        this.txtVal = (parseFloat(val) + parseFloat(this.txtVal)).toString()
        this.winMoney = (parseFloat(this.txtVal) * 1.82 - parseFloat(this.txtVal)).toString()
      }
    },
    btnPoint(point) {
      if (!this.txtVal.includes('.')) {
        this.txtVal += point
      }
    },
    backFloat() {
      this.txtVal = this.txtVal.toString()
      if (this.txtVal.length > 0) {
        this.txtVal = this.txtVal.substring(0, this.txtVal.length - 1)
        this.winMoney = (parseFloat(this.txtVal) * 1.82 - parseFloat(this.txtVal)).toString()
        if (this.txtVal === '') {
          this.winMoney = '0'
        }
      }
    },
    btnMAX() {
      this.txtVal = '0'
      this.winMoney = '0'
      this.str = 2
    },
    // 離開 function
    allLeave() {
    }
  }
}
</script>
<style lang="scss" scoped>
.betting-interface{
    position: fixed;
    top: 0;
    left: 0;
    right:0;
    bottom: 0;
    background: rgba(0,0,0,.5);
}
.indexTop{
    margin-top: 40px;
    border-radius: 14px;
    background: #0149ff;
    .betHeader{
        padding: 10px;
        .title{
            font-size: 17px;
            color: #85a8ff;
            float: left;
        }
        .amount{
            color: #fff;
            float: right;
            .currency{
                float: left;
                font-size: 14px;
                margin-right:5px;
                line-height: 26px;
            }
            .money{
            float: right;
            font-size: 18px;
            }
        }
    }
    .betContain{
        border-radius: 14px;
        background: #f5f9fb;
        .info{
            border-bottom: solid 1px #eee;
            padding: 15px;
            position: relative;
            .details{
                float: left;
                color: #1a1b1f;
                font-size: 12px;
                .bet-type{
                    font-size: 14px;
                }
                .team{
                    color:#b4bdca;
                }
            }
            .odds{
                float: right;
                font-size: 12px;
                color:#333;
                text-align: right;
                .betOdds{
                    font-size: 28px;
                    color: #00f;
                }
                span{
                    font-size: 14px;
                }
            }
            .delete{
            position: absolute;
            right: 0;
            bottom: 0;
            color: #4a4a4a;
            background: #ccc;
            padding: 0 10px;
            border-radius: 14px 0 14px 0;
            font-size: 12px;
            }
        }
        .cash{
            padding: 10px;
            .input{
              line-height: 20px;
              background: #f5f9fb;
              border: 1px solid #0149ff;
              color: #0149ff;
              padding: 8px;
              border-radius: 8px;
              height: 38px;
              font-size: 20px;
                .txt{
                  color: #9e9e9f;
                  font-size: 8px;
                }
            }
            input{
              border:none;
              background: #f5f9fb;
              width: 100%;
              font-size: 15px
            }
            .keyboard{
                margin-top: 15px;
                border: 1px solid #e5e5e5;
                border-radius: 8px;

                .keyboard-list{
                    background: #FFF;
                    border-radius: 8px;
                    .list-item{
                        border-right: 1px solid #e5e5e5;
                        border-bottom: 1px solid #e5e5e5;
                        float: left;
                        height: 55px;
                        width: 25%;
                        text-align: center;
                        line-height: 55px;
                    }
                    .list-itemBottom{
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
                    }
                    .addInt{
                        font-size: 15px;
                        font-weight: 500;
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
        .confirm{
                padding: 5px;
                margin-top: 10px;
                .addBet{
                    float: left;
                    width: 16%;
                    height: 50px;
                    font-size: 17px;
                    text-align: center;
                    line-height: 50px;
                    border: 1px solid #0149ff;
                    border-radius: 6px;
                    color: #0149ff;
                }
                .clearBet{
                    display: none;
                    float: left;
                    width: 20%;
                    height: 50px;
                    font-size: 17px;
                    text-align: center;
                    line-height: 50px;
                    border: 1px solid #0149ff;
                    border-radius: 6px;
                    color: #0149ff;
                }
                .betsumit{
                    float: right;
                    width: 80%;
                    height: 50px;
                    border-radius: 6px;
                    background: #0149ff;
                    color: #FFF;
                    font-size: 18px;
                    line-height: 50px;
                    text-align: center;
                    .winOdds{
                        margin-left: 5px;
                        font-size: 16px;
                        color: #98b5ff;
                    }
                }
            }
    }
}
.clearfix{
    clear: both;
}
</style>
