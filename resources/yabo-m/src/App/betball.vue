<template>
  <div id="app">
    <router-view />
    <div :class="['loading', { open: isLoading }]" />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'App',
  computed: {
    ...mapGetters({
      isLoading: 'betball/getIsLoading'
    })
  },
  created() {
    // 確保每次都先進首頁
    this.$router.push('/focus')
  }
}
</script>

<style lang="scss">
.loading {
  opacity: 0;
  transition: all .5s;
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  z-index: -1;
  background: rgba(0, 0, 0, .6) url('~@/assets/betball/loading.gif') no-repeat center;

  &.open {
    opacity: 1;
    z-index: 4; // 目前最高
  }
}

html, body, p, h1, h2, h3, h4, h5, h6 {
    padding: 0;
    margin: 0;
}

div {
  box-sizing: border-box;
}

/* clear float */
.clear {
    margin: 0;
    padding: 0;
    clear: both;
}

.clearfix {
  zoom: 1;

  &:before {
      display: table;
      content: "";
  }

  &:after {
      display: table;
      content: "";
      clear: both;
  }
}
</style>
