<template>
  <div class="checkbox">
    <i class="el-icon-check check" />
  </div>
</template>
<script>
export default {};
</script>
<style lang="scss" scoped>
.checkbox {
  width: 12px;
  height: 12px;
  border-radius: 3px;
  position: relative;
  background: white;
  .check {
    color: white;
    z-index: 99;
    position: absolute;
    top: 0;
    height: 12px;
    width: 12px;
    font-weight: 1000;
  }
  &.active {
    background: #00acac;
  }
}
</style>