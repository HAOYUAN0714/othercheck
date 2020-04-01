<template>
  <div>
    <ul class="morenavbar-area">
      <li class="morenavbar-item">
        <div class="logo" @click="back()">
          <font-awesome-icon class="icon" :icon="['fas','arrow-left']" />
        </div>
      </li>
      <li class="morenavbar-item" :class="{'NAVactive': navPath === '/moreSportToday'}" @click="navRouterReplace('/moreSportToday')">
        <div>{{ '今日' }}</div>
      </li>
      <li class="morenavbar-item" :class="{'NAVactive': navPath === '/moreSportFuture'}" @click="navRouterReplace('/moreSportFuture')">
        <div>{{ '早盤' }}</div>
      </li>
      <li class="morenavbar-item" :class="{'NAVactive': navPath === '/moreSportCombo'}" @click="navRouterReplace('/moreSportCombo')">
        <div>{{ '串關' }}</div>
      </li>
      <li class="morenavbar-item" :class="{'NAVactive': navPath === '/moreSportFaver'}" @click="navRouterReplace('/moreSportFaver')">
        <div>{{ '關注' }}</div>
      </li>
      <li class="morenavbar-item" :class="{'NAVactive': navPath === '/moreSportFaver'}" @click="navRouterReplace('/wager')">
        <img src="@/assets/m6app/bet_record_icon.png" alt="">
      </li>
    </ul>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  computed: {
    ...mapGetters([
      'getAllEventCount'
    ]),
    navPath() {
      return this.$route.path
    }
  },
  methods: {
    back() {
      this.$router.go(-1) // 返回上一层
    },
    navRouterReplace(val) {
      if (val === '/moreSportFaver' && this.getAllEventCount.Favourite.TotalCount === 0) {
        return
      }
      this.$router.replace(val)
    }
  }
}
</script>

<style lang="scss" scoped>
.morenavbar-area {
  list-style: none;
  margin: 0;
  padding: 0;
  background-color: #000;
  color: #fff;
  // position: fixed;
  // top: 0;
  // width: 100vw;
  // z-index: 1001;
  li {
    display: inline-block;
    font-size: 14px;
    padding: 10px 5px;
    &:last-child {
      float: right;
      clear: both;
      padding: 5px;
      img {
        width: 20px;
        height: 25px;
      }
    }
  }
  .NAVactive {
    font-size: 16px;
    font-weight: bold;
    div {
      border-bottom: 1px solid #e6a23c;
      box-shadow: 0px 2px 0px 0px #e6a23c;
    }
  }
}
</style>
