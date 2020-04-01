<template>
  <div>
    <div class="header clearfix">
      <img src="@/icons/png/backWhite.png" class="back" @click="$router.go(-1)">
      <div class="title" @click="openSportList">
        冠军投注
        <font-awesome-icon
          class="icon"
          :icon="[
            'fas',
            getChampionList ? 'angle-up' : 'angle-down'
          ]"
        />
      </div>
      <div class="wager" @click="toWager" />
    </div>
    <router-view />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  computed: {
    ...mapGetters({
      getChampionList: 'openChampionList'
    })
  },
  beforeDestroy() {
    this.openChampionList(false)
  },
  methods: {
    ...mapActions({
      'openChampionList': 'm6app/openChampionList'
    }),
    openSportList() {
      this.openChampionList(true)
    },
    toWager() {
      this.$router.push('/wager')
    }
  }
}
</script>

<style lang="scss" scoped>
.header {
  background-color: #000;
  text-align: center;
  color: #FFF;
  .back {
    float: left;
    width: 35px;
    height: 30px;
    margin: 7px 0 0 10px;
  }
  .title {
    display: inline-block;
    padding-top: 8px;
    margin-right: 8px;
  }
  .wager {
    float: right;
    background: url('~@/assets/m6app/ic_withdraw_balance.png') 50% 5px no-repeat;
    width: 43px;
    height: 29px;
  }
}
</style>
