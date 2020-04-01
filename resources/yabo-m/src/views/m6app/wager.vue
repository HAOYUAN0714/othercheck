<template>
  <div class="container">
    <div class="header">
      <div @click="$router.go(-1)">
        <!-- 11 -->
        <img src="@/icons/png/backWhite.png" class="back">
      </div>
      <div class="title">
        <span>體育投注</span>
      </div>
      <div>
        <!-- <img src="../../icons/png/prefer(betball)Blue.png" class="back"> -->
      </div>
    </div>
    <div class="betNav clearfix">
      <div class="chgButtom floatL" :class="active===1 ? 'active':''" @click="selBetList(1)"><span>{{ '已結算' }}</span></div>
      <div class="chgButtom floatR" :class="active===2 ? 'active':''" @click="selBetList(2)"><span>{{ '未結算' }}</span></div>
    </div>
    <div v-if="listYet" class="content">
      <div v-if="BetList=== undefined || BetList.length <= 0" class="emptyBox">
        <div class="emptyReminder" />
        <span v-if="initBetList">{{ '沒有資料' }}</span>
        <span v-else>{{ 'loading...' }}</span>
      </div>
      <div v-else>
        <wagerInfo :history-data="BetList" :mode="'betlist'" />
      </div>
    </div>
    <div v-if="listFinish" class="content">
      <div v-if="Statement=== undefined || Statement.length <= 0" class="emptyBox">
        <div class="emptyReminder" />
        <span v-if="initStatement">{{ '沒有資料' }}</span>
        <span v-else>{{ 'loading...' }}</span>
      </div>
      <div v-else>
        <wagerInfo :history-data="Statement" :mode="'statement'" />
      </div>
    </div>
    <!-- <div class="overlay" @click="overlay()" /> -->
  </div>
</template>

<script>
import { getBetList, getStatement } from '@/api/m6'
import wagerInfo from './wagerInfo'

export default {
  components: {
    wagerInfo
  },
  data() {
    return {
      active: 1,
      listYet: false,
      listFinish: true,
      BetList: [],
      Statement: [],
      initStatement: false,
      initBetList: false
    }
  },
  created() {
    getStatement().then((res) => {
      // console.log(res, 'res')
      this.Statement = res
    })
  },
  methods: {
    selBetList(betId) {
      this.active = betId
      if (betId === 2) {
        this.listYet = true
        this.listFinish = false
        getBetList().then((res) => {
          this.BetList = res
          this.initBetList = true
        })
      } else {
        this.listFinish = true
        this.listYet = false
        getStatement().then((res) => {
          this.Statement = res
          this.initStatement = true
        })
      }
    }
  }

}
</script>

<style lang="scss" scoped>
.container {
    height: 100vh;
    background-color: #f4f4f5;
    .header {
        background-color: #000;
        color: #fff;
        div {
            width: 15vw;
            display: inline-block;
            text-align: center;
            padding: 5px 0px;
            img {
                width: 30px;
            }
            &:nth-child(2) {
                width: 65vw;
            }
        }

    }
    .betNav {
        background-color: #FFF;
        padding: 0 19%;
        color: #909399;
        .chgButtom {
            width: 25%;
            background-color: #FFF;
            text-align: center;
            padding: 10px 0px;
        }
        .active {
            color: #000;
            border-bottom: 4px solid #E8C06A;
        }
        .floatL {
            float: left;
        }
        .floatR {
            float: right;
        }
    }
    .content {
        margin-top: 10px;
        height: 85vh;
        overflow: auto;
        .emptyBox {
            text-align: center;
            color: #909399;
            margin-top: 40px;
            .emptyReminder{
                margin: 0 auto;
                width: 250px;
                height: 165px;
                background: url('~@/assets/betball/empty-reminder.png') no-repeat center/contain;
            }
        }
    }

}

</style>
