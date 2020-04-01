<template>
  <div id="app" :class="{singlePage:single}">
    <router-view />
  </div>
</template>

 <script>
import yabo from "./router/yabo/yabo";
export default {
  name: "app",
  components: {
    yabo
  },
  computed: {
    single() {
      return this.$route.meta.single;
    }
  }
};
</script>


<style lang="scss" src="@/scss/common/common.scss"></style>
<style lang="scss" src="@/scss/common/transition.scss"></style>
<style lang="scss">
html,
body {
  height: 100%;
  font-family: Segoe UI;
  background-color: transparent;
}
#app {
  height: auto;
  margin: auto 0;
  background-size: cover;
  overflow: hidden;
  &.mobile {
    width: 100%;
    height: 100%;
  }
}
* {
  box-sizing: border-box;
}
body {
  margin: 0;
  background-size: cover;
}
html {
}
</style>