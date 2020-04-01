<template>
  <div>
    <!-- 0000000 -->
    <ul class="navbar-area">
      <li class="navbar-item" :class="{'NAVactive': navPath === '/today'}" @click="NavRouter('/today')">
        <div class="count">{{ getAllEventCount.Today && getAllEventCount.Today.TotalCount }}</div>
        <div class="period">{{ '今日' }}</div>
      </li>
      <li class="navbar-item" :class="{'NAVactive': navPath === '/breakfast'}" @click="NavRouter('/breakfast')">
        <div class="count">{{ getAllEventCount.Early && getAllEventCount.Early.TotalCount }}</div>
        <div class="period">{{ '早盤' }}</div>
      </li>
      <li class="navbar-item" :class="{'NAVactive': navPath === '/combo'}" @click="NavRouter('/combo')">
        <div class="count">{{ getAllEventCount.Combo && getAllEventCount.Combo.TotalCount }}</div>
        <div class="period">{{ '串關' }}</div>
      </li>
      <li class="navbar-item" :class="{'NAVactive': navPath === '/faver'}" @click="NavRouter('/faver')">
        <div class="count">{{ getAllEventCount.Favourite && getAllEventCount.Favourite.TotalCount }}</div>
        <div class="period">{{ '關注' }}</div>
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
    NavRouter(val) {
      if (val === '/faver' && this.getAllEventCount.Favourite.TotalCount === 0) {
        return
      }
      this.$router.push(val)
    }
  }
}
</script>

<style lang="scss" scoped>
.navbar-area {
  list-style: none;
  color: grey;
  // margin: 0;
  padding: 0;
  width: 100%;
  font-size: 14px;
  margin: 0 auto;
  margin-top: 10px;
  text-align: center;
  min-width: 320px;
  li {
    display: inline-block;
    width: 23%;
    background-color: #fff;
    padding-top: 5px;
    color: gray;
    font-weight: bold;
    border-radius: 5px 5px 0px 0px;
    .count {
      display: inline-block;
      border-bottom: 1px solid #FFF;
      padding-bottom: 3px;
    }
    .period {
      padding-top: 3px;
    }
  }
  .NAVactive {
    background-color: #000;
    color: #d89e48;
  }

}
</style>
