<template>
  <div class="pageComponent">
    <div class="prevIcon" @click="$_goPage(-1)">
      <i class="el-icon-arrow-left" />
    </div>
    <div class="pageInfo">
      <span class="current">{{currentPage}}</span>
      <span>/</span>
      <span class="total">{{totalPage}}</span>
    </div>
    <div class="nextIcon" @click="$_goPage(1)">
      <i class="el-icon-arrow-right" />
    </div>
    <div class="returnTop" @click="$_goTop">{{$t('TEXT_RETURN_TOP')}}</div>
  </div>
</template>
<script>
export default {
  props: {
    totalPage: {
      type: Number,
      default: () => 1
    },
    currentPage: {
      type: Number,
      default: () => 1
    }
  },
  methods: {
    $_goPage(action) {
      const { currentPage, totalPage } = this;
      if (currentPage + action > 0 && currentPage + action <= totalPage)
        this.$emit("goPage", currentPage + action);
    },
    $_goTop() {
      this.$emit("goTop");
    }
  }
};
</script>
<style lang="scss" scoped>

.pageComponent {
  display: inline-flex;
  margin-left: auto;
  width: auto;
  height: 25px;
  align-items: center;
  text-align: center;
  padding-top: 5px;
  padding-right: 10px;
  line-height: 25px;
  & > div {
    margin: 0 3px;
  }
  .current {
    color: rgb(187, 2, 2);
  }
  .current,
  .total {
    padding: 0 5px;
  }
  .prevIcon,
  .nextIcon {
    background: var(--primary);
    border-radius: 50%;
    width: 18px;
    height: 18px;
    cursor: pointer;
    align-items: center;
    text-align: center;
    line-height: 18px;
    & > i {
      color: white;
      font-weight: 800;
    }
  }
  .returnTop {
    width: auto;
    padding: 0px 10px;

    border: solid 1px var(--primary);
    color: var(--primary);
    &:hover {
      cursor: pointer;
      background: var(--primary);
      color: white;
    }
  }
}
</style>