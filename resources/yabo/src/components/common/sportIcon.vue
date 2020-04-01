<template>
  <div class="sportIcon">
    <component :is="iconKey" />
    <!--  <img :src="`@/assets/sportIcon/${iconKey}.svg`" />-->
  </div>
</template>
<script>
//import Soccer from "@/assets/sportIcon/soccer.svg";
import Soccer from "vue-material-design-icons/Soccer.vue";
import Basketball from "vue-material-design-icons/Basketball.vue";
import Tennis from "vue-material-design-icons/Tennis.vue";
import Boxing from "vue-material-design-icons/BoxingGlove.vue";
import Hockey from "vue-material-design-icons/HockeyPuck";
import Badminton from "vue-material-design-icons/Badminton.vue";
import Baseball from "vue-material-design-icons/BaseballBat.vue";
import Amfb from "vue-material-design-icons/Football.vue";
import Golf from "vue-material-design-icons/GolfTee.vue";
import Handball from "vue-material-design-icons/Handball.vue";
import Racecar from "vue-material-design-icons/CarSports.vue";
import Snooker from "vue-material-design-icons/BilliardsRack.vue";
import Volleyball from "vue-material-design-icons/Volleyball.vue";
import Beauty from "vue-material-design-icons/FaceWoman.vue";
import Lottery from "vue-material-design-icons/Ticket";
import Economy from "vue-material-design-icons/CashUsd";
import Bike from "vue-material-design-icons/BikeFast";
import Entertainment from "vue-material-design-icons/Microphone";
import Dart from "vue-material-design-icons/BullseyeArrow";
import Boat from "vue-material-design-icons/Sailing";
import Football from "vue-material-design-icons/Football";
import Cricket from "vue-material-design-icons/Cricket";
import WaterBall from "vue-material-design-icons/Volleyball";
import GrassHockey from "vue-material-design-icons/HockeySticks";
import Athletics from "vue-material-design-icons/Run";
import VirtualBasketball from "vue-material-design-icons/VirtualReality";
import VirtualSoccer from "vue-material-design-icons/VirtualReality";
import VirtualWorldCup from "vue-material-design-icons/VirtualReality";
import AsianSnooker from "vue-material-design-icons/Billiards";
import VirtualNationalCup from "vue-material-design-icons/VirtualReality";
export default {
  components: {
    Dart,
    AsianSnooker,
    WaterBall,
    GrassHockey,
    Athletics,
    Cricket,
    Football,
    Boat,
    VirtualWorldCup,
    VirtualNationalCup,
    Entertainment,
    Volleyball,
    Snooker,
    VirtualBasketball,
    VirtualSoccer,
    Racecar,
    Handball,
    Golf,
    Amfb,
    Basketball,
    Baseball,
    Badminton,
    Hockey,
    Boxing,
    Tennis,
    Soccer,
    Beauty,
    Lottery,
    Economy,
    Bike
  },
  props: {
    iconKey: {
      type: String,
      default: () => "Soccer"
    }
  }
};
</script>
<style scoped>
.sportIcon {
  font-size: 20px;
  line-height: 20px;
}
</style>