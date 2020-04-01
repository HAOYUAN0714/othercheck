<template>
  <button class="fave" :class="{faved:faved}" @click="$_click" aria-label="Favourite"></button>
</template>
<script>
import $ from "jquery";
export default {
  data() {
    return {
      clickDisabled: false,
      faved: false
    };
  },
  methods: {
    $_click() {
      const self = this;
      if (self.clickDisabled) {
        return; // do nothing
      }

      this.faved = !this.faved;
      // Set correct aria-label
      var label =
        $(this).attr("aria-label") == "Favourite" ? "Unfavourite" : "Favourite";

      $(this).attr("aria-label", label);

      self.clickDisabled = true;

      // Timeout value should match transition length
      setTimeout(function() {
        self.clickDisabled = false;
      }, 1000);
    }
  }
};
</script>
<style lang="scss">
.fave {
  background: none;
  border: 0;
  cursor: pointer;
  padding: 0;
  width: 70px;
  z-index: 9999999;
  height: 45px;
  background: url(~@/assets/dist/star.png) no-repeat;
  background-position: 0 0;
  transition: background 1s steps(55);
  outline: 0;
  &:hover {
    cursor: pointer;
  }
}
.fave:hover {
  background-position: -3519px 0;
  transition: background 1s steps(55);
}
.fave.faved {
  background-position: -3519px 0;
  transition: background 1s steps(55);
}
</style>