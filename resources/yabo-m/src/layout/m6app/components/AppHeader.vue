<template>
  <div class="header">
    <div id="mySidenav" class="sidenav">
      <img src="" alt="">
      <a href="javascript:void(0)" class="closebtn" @click="closeNav()">&times;</a>
      <ul>
        <li class="bet_info">
          <div>
            賽事
          </div>
          <div>
            冠軍投注
          </div>
        </li>
        <li>
          <div>盤口設置</div>
          <div>
            <el-radio v-model="radio" label="1">备选项</el-radio>
            <el-radio v-model="radio" label="2">备选项</el-radio>
          </div>
        </li>
        <li>
          <div>賽事排序</div>
          <div>
            <el-radio v-model="radio" label="4">备选项</el-radio>
            <el-radio v-model="radio" label="7">备选项</el-radio>
          </div>
        </li>
        <li>
          <div>自動接收賠率</div>
          <el-radio-group v-model="radio">
            <el-radio :label="3">备选项</el-radio>
            <el-radio :label="6">备选项</el-radio>
            <el-radio :label="9">备选项</el-radio>
          </el-radio-group>
        </li>
        <li>
          <div>震動設置</div>
          <div>
            <el-switch
              v-model="value"
              active-color="#13ce66"
              inactive-color="#ff4949"
            />
          </div>
        </li>
        在現客服
      </ul>
    </div>
    <div class="slider_btn">
      <span @click="openNav()"><i class="fa fa-bars" /></span>
      <span class="active">體育</span>
    </div>
    <div class="money_info">
      <img src="../../../assets/img/loading_icon.png" alt="">
      <span>0.00</span>
      <img src="../../../assets/img/wallet_icon.png" alt="" @click="wallet()">
    </div>
    <div class="dialog">
      <ul>
        <li @click="recharge()">
          <span><i class="fa fa-usd" aria-hidden="true" /></span>
          充值
        </li>
        <li @click="showBet()">
          <span><i class="fa fa-heart" aria-hidden="true" /></span>
          提現
        </li>
      </ul>
    </div>
    <!-- <transition>
      <myWager :class="{ hasOpenWager: isShowBet }" @closeBet="closeBet" />
    </transition> -->
  </div>
</template>
<script>
// import myWager from '@/layout/m6app/components/myWager.vue'

export default {
  name: 'AppHeader',
  data() {
    return {
      visible: false,
      radio: '1',
      value: true,
      isShowBet: false
    }
  },
  computed: {
    // myWager,
  },
  methods: {
    openNav() {
      document.getElementById('mySidenav').style.width = '80vw'
    },
    closeNav() {
      document.getElementById('mySidenav').style.width = '0'
    },
    recharge() {
      // console.log('recharge');
    },
    withdraw() {
      // console.log('withdraw');
    },
    showBet() {
      this.isShowBet = true
    },
    closeBet(val) {
      this.isShowBet = val
    },
    wallet() {
      // console.log('wallet');
    }
  }
}
</script>

<style lang="scss" scoped>
.header {
  background: #fff;
  min-width: 320px;
  // width: 100vw;
  // height: 6vh;
  .sidenav {
    height: 100%; /* 100% Full-height */
    width: 0; /* 0 width - change this with JavaScript */
    position: fixed; /* Stay in place */
    z-index: 1; /* Stay on top */
    top: 0;
    left: 0;
    background-color: #fff; /* Black*/
    overflow-x: hidden; /* Disable horizontal scroll */
    padding-top: 60px; /* Place content 60px from the top */
    transition: 0.5s; /* 0.5 second transition effect to slide in the sidenav */
    ul {
      list-style: none;
      // padding: 0;
      // margin: 0;
      font-family: Gotham;
      color: #818181;
      font-size: 14px;
      transition: 0.3s;
      padding: 8px 8px 8px 32px;
        li {
          border-bottom: 2px solid #818181;
          // padding: 8px 8px 8px 32px;
          div {
            padding: 10px;
            span {
            }
          }
        }
        .bet_info {
          img {
            width: 20px;
          }
        }
      }
    /* The navigation menu links */
    // a {
    //   padding: 8px 8px 8px 32px;
    //   text-decoration: none;
    //   font-size: 14px;
    //   font-family: Gotham;
    //   color: #818181;
    //   display: block;
    //   transition: 0.3s
    //   // .sidenav a:hover, .offcanvas a:focus{
    //   //     color: #f1f1f1;
    //   // }
    // }
    img {
        width: 150px;
position: absolute;
        top: 10px;
        left: 10px;
    }
    .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: px;
    }
  }
  .slider_btn{
    // float: left;
    display: inline-block;
    width: 55vw;
    min-width: 185px;
    padding: 4px;
    background: linear-gradient(135deg, #000 86%, transparent 0);
    span {
      color: #fff;
      margin-right: 15px;

      padding: 5px;
      .fa-bars {
        color: #fff;
      }
    }
    .active {
      border-bottom: 3px solid #e0a54b;
      font-weight: bold;
    }
  }
  .money_info {
    // float: right;
    display: inline-block;
    text-align: right;
    // width: 41vw;
    padding: 5px;
    img {
      width: 20px;
      margin: 0px 10px;
    }
  }
  .dialog {
    display: none;
    background-color: #f5f9fb;;
    float: right;
    position: absolute;
    top: 8vh;
    right: 2vw;
    ul {
        list-style: none;
        padding: 0;
        margin: 0;
      li {
        padding: 5px 20px;
        border-bottom: 1px solid #dcdfe6;
      }
    }
    &:before {
        content: '';
        border-bottom: 10px solid #f5f9fb;
        border-left: 10px solid transparent;
        border-right: 10px solid transparent;
        position: absolute;
        top: -10px;
        right: 25%;
    }
  }
}
</style>
