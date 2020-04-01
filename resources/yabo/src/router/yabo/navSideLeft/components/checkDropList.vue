<template>
  <el-collapse-transition>
    <div class="dropList">
      <div
        class="itemTab"
        :class="{selected:checkAll&& selectedList.length>0 }"
        @click="$_checkAll"
      >
        <div class="checkBox">
          <i v-show="checkAll && selectedList.length>0" class="checkIcon el-icon-check" />
        </div>
        <div class="sportName">{{$t('TEXT_ALL_SPORTS')}}</div>
        <div class="sportCount">{{allCount}}</div>
      </div>
      <div
        :class="{selected:$_includes(sport.SportId)}"
        class="itemTab"
        @click="$_clickTab(sport.SportId)"
        v-for="sport in list"
        :key="`sport-${sport.SportId}`"
      >
        <div class="checkBox">
          <i v-show="$_includes(sport.SportId)" class="checkIcon el-icon-check" />
        </div>
        <div class="sportName">{{$t(sportConfig[sport.SportId].textKey)}}</div>
        <div class="sportCount">{{sport.Events.length}}</div>
      </div>
    </div>
  </el-collapse-transition>
</template>
<script>
import { mapState } from "vuex";
import config from "../../../../config/config";
export default {
  data() {
    return {
      sportConfig: config.sportList,
      checkAll: false
    };
  },
  props: {
    list: {
      type: Array,
      default: () => []
    }
  },
  computed: {
    ...mapState({
      selectedList: state => state.status.selectedPlayListIds
    }),

    allCount() {
      let all = 0;
      for (var i = 0; i < this.list.length; i++) {
        all += this.list[i].Events.length;
      }
      return all;
    }
  },
  watch: {
    selectedList() {
      if (
        this.selectedList.length == 0 ||
        this.selectedList.length != this.list.length
      )
        this.checkAll = false;
    }
  },
  methods: {
    $_checkAll() {
      this.checkAll = !this.checkAll;
      if (this.checkAll == false) {
        this.$emit("selectPlay", []);
      } else {
        let dummy = [];

        for (var i = 0; i < this.list.length; i++) {
          dummy.push(this.list[i].SportId);
        }
        this.$emit("selectPlay", dummy);
      }
    },
    $_includes(id) {
      if (this.selectedList.includes(id)) return true;
      else return false;
    },
    $_clickTab(id) {
      var self = this;
      // this.$forceUpdate();
      let dummy = JSON.parse(JSON.stringify(this.selectedList));
      if (this.$_includes(id)) {
        var index = dummy.indexOf(id);
        dummy.splice(index, 1);
      } else {
        dummy.push(id);
      }
      this.$emit("selectPlay", dummy);
      // this.$emit("selectPlay", play);為什麼不能用?____?
      // this.$parent.$_selectPlay(play);
    }
  },
  mounted() {
    const { list } = this;
  }
};
</script>
<style lang="scss" scoped>
.dropList {
  width: 100%;

  background: var(--paleBackground);
  .itemTab {
    height: 37px;
    display: flex;
    font-size: 13px;
    padding: 0 10px;
    color: #718284;
    line-height: 37px;
    transition-duration: 0.1s;
    &.selected {
      color: var(--themeText);
    }
    &:hover {
      color: var(--primary);
      cursor: pointer;
    }

    .dotIcon {
      width: 20px;
      font-size: 6px;
      line-height: 37px;
    }
    .sportCount {
      margin-left: auto;
    }
  }
}
</style>