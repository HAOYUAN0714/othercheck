<template>
  <div class="header-wrap">
    <li class="btn-sidebar" @click="openSidebar" />
    <li class="wager" @click="toWagerPage" />
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  methods: {
    ...mapActions({
      actonSetSidebar: 'm6app/actonSetSidebar'
    }),
    openSidebar() {
      this.actonSetSidebar(true)
    },
    toWagerPage() {
      this.$router.push('/wager')
    }
  }
}
</script>

<style lang="scss" scoped>
.header-wrap {
  width: 100%;
  background-color: #000;
  height: 35px;
  // position: fixed;
  // top: 0;
  .btn-sidebar {
    position: absolute;
    top: 5px;
    left: 0px;
    background: url('~@/assets/img/btn_menu.png') 50% 0 no-repeat;
    width: 36px;
    height: 36px;
  }
  .wager {
    position: absolute;
    top: 5px;
    right: 0px;
    background: url('~@/assets/m6app/ic_withdraw_balance.png') 50% 0 no-repeat;
    width: 36px;
    height: 36px;
  }
}
</style>
