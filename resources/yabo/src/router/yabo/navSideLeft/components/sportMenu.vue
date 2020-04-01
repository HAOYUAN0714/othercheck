<template>
  <div class="scroll sportMenu">
    <div class="hasComboWager" @click="$_toComboWager" v-show="wagerList.length>0">
      <div>{{$t('TEXT_HAS_COMBO_WAGER')}}</div>
      <div class="count">{{wagerList.length}}</div>
    </div>

    <div class="tab" v-show="liveSports.length>0" @click="$_collapse('live')">{{$t('TEXT_IN_LIVE')}}</div>
    <checkBoxList
      v-show="collapse['live'] && liveSports.length>0"
      @selectPlay="$_selectLiveList"
      :list="liveSports"
    />
    <div class="tab" @click="$_collapse('menu')">{{$t('TEXT_SPORT_MENU')}}</div>
    <el-collapse-transition>
      <div class="menu" v-show="collapse['menu']">
        <div class="timeRange">
          <div class="underBorder" :style="`transform:translateX(${borderPosition * (210/3)}px)`"></div>
          <!--今日-->
          <div
            class="time"
            @click="$_selectTime(2)"
            :class="{active:selectedTime==2}"
          >{{$t('TEXT_TODAY')}}</div>
          <!--早盤-->
          <div
            class="time"
            @click="$_selectTime(1)"
            :class="{active:selectedTime==1}"
          >{{$t('TEXT_EARLY')}}</div>
          <div
            class="time"
            @click="$_setIsCombo(true)"
            :class="{active:isCombo}"
          >{{$t('TEXT_PARLAY')}}</div>
        </div>
        <div class="tabsWrap" :key="selectedTime">
          <div
            v-for="sport in navList['sportMain']"
            v-show="sport.count!=0"
            :key="`${sport.sportId}-sportList`"
            class="sportTab"
          >
            <div
              class="inside"
              @click="$_selectSport(sport)"
              :class="{active:selectedSport==sport.sportId}"
            >
              <sportIcon :iconKey="sportConfig[sport.sportId].iconKey" class="sportIcon" />
              <div class="sportName">{{$t(sportConfig[sport.sportId].textKey)}}</div>
              <div class="set">
                <div
                  class="liveIcon"
                  v-if="liveSportsIds.includes(sport.sportId) && market !=1"
                >LIVE</div>
                <div class="sportCount">{{sport.count}}</div>
              </div>
            </div>
            <el-collapse-transition>
              <dropList
                v-show="selectedSport==sport.sportId"
                @selectPlay="$_selectPlay"
                :betType="sport.betTypes"
                :selectedPlay="selectedBetType"
                :list="sportConfig[sport.sportId].betTypes"
                :checkbox="false"
              />
            </el-collapse-transition>
          </div>

          <div class="tab rest">
            <img src="@/assets/dist/options-w.png" class="unfold" />
            {{$t('TEXT_SPORT_ATOZ')}}
          </div>
          <div
            v-for="sport in navList['sportRest']"
            v-show="sport.count!=0"
            :key="`${sport.sportId}-sportRest`"
            class="sportTab"
          >
            <div
              class="inside"
              @click="$_selectSport(sport)"
              :class="{active:selectedSport==sport.sportId}"
            >
              <sportIcon :iconKey="sportConfig[sport.sportId].iconKey" class="sportIcon" />
              <div class="sportName">{{$t(sportConfig[sport.sportId].textKey)}}</div>
              <div class="set">
                <div class="liveIcon" v-if="liveSportsIds.includes(sport.sportId)">LIVE</div>
                <div class="sportCount">{{sport.count}}</div>
              </div>
            </div>
            <el-collapse-transition>
              <dropList
                v-show="selectedSport==sport.sportId"
                @selectPlay="$_selectPlay"
                :selectedPlay="selectedBetType"
                :betType="sport.betTypes"
                :list="sportConfig[sport.sportId].betTypes"
                :checkbox="false"
              />
            </el-collapse-transition>
          </div>
        </div>
      </div>
    </el-collapse-transition>
  </div>
</template><script>
import dropList from "../components/dropList";
import checkBoxList from "../components/checkDropList";
import config from "../../../../config/config";
import sportIcon from "../../../../components/common/sportIcon";
export default {
  components: {
    dropList,
    checkBoxList,
    sportIcon
  },
  data() {
    return {
      collapse: {
        live: true,
        menu: true,
        rest: true
      },
      sportConfig: config.sportList
      // selectedTime: this.$store.state.status.Market
    };
  },
  computed: {
    liveSports() {
      return this.$store.state.status.liveSports;
    },
    wagerList() {
      return this.$store.state.wager.wagerList;
    },
    selectedOddsType() {
      return this.$store.state.status.selectedOddsType;
    },
    liveSportsIds() {
      const { liveSports } = this;
      let arr = [];
      for (let i = 0; i < liveSports.length; i++) {
        arr.push(liveSports[i].SportId);
      }
      return arr;
    },
    selectedTime() {
      const { isCombo, market } = this;
      if (isCombo == true) {
        return 3;
      } else {
        return market;
      }
    },
    navList() {
      return this.$store.state.navList[this.currentKey] || {};
    },
    market() {
      return this.$store.state.status.Market;
    },
    isCombo() {
      return this.$store.state.status.isCombo;
    },
    selectedSport() {
      return this.$store.state.status.selectedSport;
    },
    borderPosition() {
      switch (this.selectedTime) {
        case 2:
          return 0;
          break;
        case 1:
          return 1;
          break;
        case 3:
          return 2;
          break;
        default:
          break;
      }
    },
    currentKey() {
      return this.isCombo == true ? 3 : this.market == 2 ? 1 : 2;
    },
    selectedBetType() {
      return this.$store.state.oddsTable.selectedBetType;
    }
  },
  methods: {
    $_toComboWager() {
      this.$store.commit("$_setPlacingBet", true);
    },
    $_collapse(key) {
      if (this.collapse[key] == undefined) {
        this.collapse[key] = false;
      }
      this.collapse[key] = !this.collapse[key];
    },
    $_setIsCombo(flag) {
      //if (flag) this.selectedTime = 3;
      this.$_getListCount(3);
      this.$store.commit("$_setPlayListIds", []);
      this.$store.commit("$_setIsCombo", flag);
    },
    $_selectPlay(betType) {
      this.$store.commit("$_setPlayListIds", []);
      this.$store.commit("$_changeSelectedBetType", betType);
    },
    $_selectLiveList(list) {
      if (list.length > 0) {
        this.$store.commit("$_clearMorePlayEvent"); //清空更多玩法賽事
        this.$store.commit("$_commonLoadingMask", true);
        this.$store
          .dispatch("$_getLiveEventInfo", {
            SportIds: list,
            OddsType: this.selectedOddsType,
            BetTypeIds: [1, 2, 3, 4, 5, 6, 7, 9], //這個只需要讓球的選項
            IsCombo: this.isCombo,
            OrderBy: 2
          })
          .then(res => {
            for (var i = 0; i < res.data.Sports.length; i++) {
              this.$store.commit("$_setPlayList", {
                sportId: res.data.Sports[i]["SportId"],
                data: res.data.Sports[i]
              });
            }
            this.$store.commit(
              "$_setPlayListIds",
              JSON.parse(JSON.stringify(list))
            );

            this.$store.commit("$_commonLoadingMask", false);
            //幹，沒有特別重新parse過，他會一直指著這個，所以還沒loading完就直接進去
          })
          .catch(err => {
            this.$store.commit("$_commonLoadingMask", false);
          });
      } else {
        this.$store.commit("$_setPlayListIds", []); //清空
      }
    },

    $_selectSport(sport) {
      this.$store.commit("$_setPlayListIds", []);
      this.$store.commit("$_changeSelectedSport", sport.sportId);
      this.$store.commit("$_changeSelectedBetType", 1);
    },
    $_selectTime(timeId) {
      if (timeId == 1) {
        //如果是早盤，要清空
        this.$store.commit("$_setPlayListIds", []);
      }
      this.$_getListCount(timeId == 1 ? 2 : 1);
      //this.selectedTime = timeId;
      this.$store.commit("$_changeMarket", timeId);
      this.$store.commit("$_setIsCombo", false);
      this.$store.commit("$_clearWagerList"); //清空bet
    },
    $_getListCount(key = 3) {
      this.$store
        .dispatch("$_getListCount", {
          Key: key
        })
        .then(res => {
          this.$store.commit("$_setListCount", {
            key: key,
            list: {
              sportMain: res.data.sportMain,
              sportRest: res.data.sportRest
            }
          });
        });
    }
  },
  mounted() {}
};
</script>
<style lang="scss" src="@/scss/router/yabo/navLeft/navLeft.scss"></style>