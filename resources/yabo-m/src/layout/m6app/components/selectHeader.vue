<template>
    <div>
    <!-- {{navHeader}} -->
        <div class="sport-header">
            <div class="hLeft" @click="$router.go(-1)">
                <img src="@/icons/png/backWhite.png" class="back">
            </div>
            <!-- <div class="hLeft"> -->
            <div class="select">
                <select name="slct" id="slct">
                    <option selected disabled>Choose an option</option>
                    <option value="1">11111</option>
                    <option value="2">22222</option>
                    <option value="3">33333</option>
                </select>
            </div>
            <!-- </div> -->
            <div class="hRight">
                <div class="dline">
                <router-link to="wager">
                    <img src="../../../icons/png/leftBar/i-brasketball.png" class="hImg">
                </router-link>
                </div>
            </div>
        </div>
        <transition>
            <myWager :class="{ hasOpenWager: isShowBet }" @closeBet="closeBet" />
        </transition>
    </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'
import myWager from '@/layout/m6app/components/myWager.vue'
import { getAllSportCount, getSportKind, getBalance, getEventInfoByPage, getSelectEventInfo } from '@/api/m6'

export default {
  components: {
    myWager,
  },
  data() {
    return {
        isShowBet: false,
        options: [{
          value: '选项1',
          label: '黄金糕'
        }, {
          value: '选项2',
          label: '双皮奶'
        }, {
          value: '选项3',
          label: '蚵仔煎'
        }, {
          value: '选项4',
          label: '龙须面'
        }, {
          value: '选项5',
          label: '北京烤鸭'
        }],
        value: '黄金糕'
    }
  },
  computed: {},
  created() {},
  mounted() {},
  methods: {
    showBet() {
      this.isShowBet = true
    },
    closeBet(val) {
      this.isShowBet = val
    },
  }
}
</script>
<style lang="scss" scoped>
.sport {
  &-header {
    height: 45px;
    color: #fff;
    background-color: #000;
    // background-color: #e4e4e4;
    // border-bottom: 1px solid #eee;
    .back {
      height: 30px;
      margin: 5px 10px;
    }

    .hLeft {
        float: left;
        height: 45px;
        line-height: 2;
        font-weight: bold;
        font-size: 20px;
    }
    .select {
            float: left;
            width: 60vw;
            border: none;
            margin: 0 auto;
            cursor: pointer;
            select {
                border: none;
                padding: 10px;
                color: #fff;
                width: 60vw;
                background-color: #000;
                height: 45px;
                font-size: 16px;
                font-weight: bold;
                /* Remove IE arrow */
                &::-ms-expand {
                    display: none;
                }
            }
        }
    .hRight {
      float: right;
      height: 45px;
      
      .dline{
        float: left;
        margin-right: 10px;
        span{
          line-height: 30px;
          font-size: 22px;
          font-weight: 500;
          float: left;
          padding: 7px 0;
        }
      }
      .hImg {
        height: 30px;
        padding-top: 8px;
      }
    }
  }
}

.hasOpenWager {
  transform: translateX(0);
  z-index: 2;
}
</style>

