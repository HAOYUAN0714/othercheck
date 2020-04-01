<template>
  <div class="func">
    <div class="prefer">
      <div class="PreferNav">
        <!-- <div class="filter-option" @click="dropDown">
          <span>{{ title }}</span>
          <img src="@/icons/png/down.png" class="optionImg">
          <div v-if="isShowItem" class="option-menu">
            <div class="arrow" />
            <div class="option-content">
              <div class="option-item" @click="selOption(1)">
                {{ $t('S_SORT_BY_TIME') }}
                <img
                  v-show="imgCheck === 1"
                  src="@/icons/png/leftBar/i-check.png"
                  class="optionR"
                >
              </div>
              <div class="option-item border0" @click="selOption(2)">
                {{ $t('S_SORT_BY_ALLIANCE') }}

                <img
                  v-show="imgCheck === 2"
                  src="@/icons/png/leftBar/i-check.png"
                  class="optionR"
                >
              </div>
            </div>
          </div>
        </div> -->
        <div class="filter-option" @click="dropDownGm">
          <span>{{ games }}</span>
          <img src="@/icons/png/down.png" class="optionImg">
          <div v-if="isShowItem2" class="option-menuGm">
            <div class="arrowGm" />
            <div class="option-content">
              <div class="option-item" @click="selOptionGm(1)">
                {{ $t('S_HANDICAP') }}
                <img
                  v-show="preferPlayGroup === 1"
                  src="@/icons/png/leftBar/i-check.png"
                  class="optionR"
                >
              </div>
              <div class="option-item" @click="selOptionGm(2)">
                {{ $t('S_OVER_UNDER') }}
                <img
                  v-show="preferPlayGroup === 2"
                  src="@/icons/png/leftBar/i-check.png"
                  class="optionR"
                >
              </div>
              <div class="option-item border0" @click="selOptionGm(4)">
                {{ $t('S_SOLOWIN') }}
                <img
                  v-show="preferPlayGroup === 4"
                  src="@/icons/png/leftBar/i-check.png"
                  class="optionR"
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="PreferLeave" @click="preferLeave()" />
  </div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  data() {
    return {
      isShowItem: false,
      isShowItem2: false,
      title: this.$t('S_SORT_BY_TIME'),
      games: this.$t('S_HANDICAP'),
      imgCheck: 1
    }
  },
  computed: {
    ...mapGetters({
      preferPlayGroup: 'betball/getPreferPlayGroup'
    })
  },
  methods: {
    ...mapActions({
      actionSetPreferPlayGroup: 'betball/actionSetPreferPlayGroup'
    }),
    preferLeave() {
      this.$emit('closeFuncPre', false)
    },
    dropDown() {
      this.isShowItem = !this.isShowItem
      this.isShowItem2 = false
    },
    dropDownGm() {
      console.log('sss')
      this.isShowItem2 = !this.isShowItem2
      this.isShowItem = false
    },
    selOption(val) {
      if (val === 1) {
        this.title = this.$t('S_SORT_BY_TIME')
        this.imgCheck = val
      } else {
        this.title = this.$t('S_SORT_BY_ALLIANCE')
        this.imgCheck = val
      }
    },
    selOptionGm(val) {
      if (val === 1) {
        this.games = this.$t('S_HANDICAP')
      } else if (val === 2) {
        this.games = this.$t('S_OVER_UNDER')
      } else if (val === 4) {
        this.games = this.$t('S_SOLOWIN')
      }

      this.actionSetPreferPlayGroup(val)
    }
  }
}
</script>
<style lang="scss" scoped>
.func{
  position: absolute;
  top: 95px;
  left: 0;
  height: 100%;
  width: 100%;
  z-index: 1;

  .prefer{
    position: relative;
    z-index: 2;
    height: 40px;
    background: #fff;
    border-radius: 0 0 15px 15px;
    .PreferNav{
        padding: 7px 10px;
        .filter-option{
          padding: 5px 15px;
          color: #333;
          font-size: 14px;
          font-weight: 400;
          .optionImg{
            height: 10px;
          }
          .optionR{
            width: auto;
            height: 15px;
            float: right;
          }
          .optionNone{
            display: none;
          }
          .option-menu{
            position: absolute;
            left:5%;
            top: 140%;
            background: #fff;
            border-radius: 8px;
            width: 40%;
          }

          .option-menuGm{
            position: absolute;
            left: 5px;
            top: 48px;
            background: #FFF;
            border-radius: 8px;
            width: 120px;
          }
          .arrow{
            background: #fff;
            position: absolute;
            top: -8%;
            left: 22%;
            width: 15px;
            height: 15px;
            transform: rotate(45deg);
          }
          .arrowGm{
            background: #fff;
            position: absolute;
            top: -5%;
            left: 22%;
            width: 15px;
            height: 15px;
            transform: rotate(45deg);
          }
          .option-item{
            padding: 10px 15px;
            border-bottom: 1px solid #eeeeee;
          }
          .border0{
            border-bottom: 0
          }
        }
      }
    }
  .PreferLeave{
    z-index: 1;
    width: 100%;
    height: 100%;
    position: fixed;
    background: rgba(48, 47, 47, 0.7);
  }
}
</style>
