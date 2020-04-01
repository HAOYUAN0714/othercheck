<template>
  <div class="time">{{parsedString}}{{timePart}}</div>
</template>
<script>
import moment from "moment";
export default {
  props: {
    time: {
      type: String,
      default: () => {}
    },
    sportId: {
      type: Number,
      default: 1
    },
    event: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      stringPart: "",
      timePart: "",
      interval: null //interval實體
    };
  },
  watch: {
    time() {
      this.$_parseTime();
    }
  },
  computed: {
    RBTimeStatus() {
      return this.event.RBTimeStatus;
    },
    countOrder() {
      const { sportId } = this;
      if (sportId == 2) return -1;
      return 1;
      // if (this.stringPart.startsWith("Q")) return -1;
      // return 1;
    },
    parsedString() {
      const { stringPart } = this;
      return stringPart
        .replace("Q1", this.$t("TEXT_FIRST_PERIOD"))
        .replace("Q2", this.$t("TEXT_SECOND_PERIOD"))
        .replace("Q3", this.$t("TEXT_THIRD_PERIOD"))
        .replace("Q4", this.$t("TEXT_FORTH_PERIOD"))
        .replace("1H", this.$t("TEXT_PERIOD_2"))
        .replace("HT", this.$t("TEXT_HALF_TIME_"))
        .replace("2H", this.$t("TEXT_PERIOD_3"))
        .replace("OT", this.$t("TEXT_OVER_TIME"))
        .replace("!LIVE", this.$t("TEXT_IN_LIVE"));
    }
  },
  methods: {
    $_parseTime() {
      const { time } = this;
      this.stringPart = time.split(" ")[0] + " ";
      this.timePart = time.split(" ")[1];
    },
    $_updateClock() {
      if (this.timePart && this.timePart != "00" && this.timePart != "00:00") {
        const { countOrder } = this;
        let minus = false;
        let multi = 1;
        let timeSet = this.timePart.split(":");
        if (timeSet[0]) {
          if (Number(timeSet[0]) >= 60) {
            this.timePart = String(Number(timeSet[0]) % 60) + ":" + timeSet[1];
            multi = parseInt(timeSet[0] / 60);
            minus = true;
          }
        }
        this.timePart = moment(this.timePart, "mm:ss")
          .add(countOrder, "second")
          .format("mm:ss");
        if (minus) {
          let timeSet2 = this.timePart.split(":");
          this.timePart =
            String(Number(timeSet2[0]) + 60 * multi) + ":" + timeSet2[1];
        }
      }
    }
  },
  beforeDestroy() {
    clearInterval(this.interval);
  },
  mounted() {
    this.$_parseTime();
    if (this.RBTimeStatus != 3)
      this.interval = setInterval(() => this.$_updateClock(), 1000);
  }
};
</script>