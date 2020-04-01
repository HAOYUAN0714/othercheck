<template>
  <div class="isLive floatBlock">
    <div class="iframe">
      <visualWidget :event="selectedEvent" :mid="selectedEvent.BREventId" />
    </div>
    <div class="bottom">
      <div class="buttons">
        <div class="underBorder" :style="`transform:translateX(${selected * 165}px)`" />
        <div
          class="button"
          @click="selected=0"
          :class="{active:selected==0}"
        >{{$t('TEXT_STREAM_GAMES')}}</div>
        <div
          class="button"
          @click="selected=1"
          :class="{active:selected==1}"
        >{{$t('TEXT_APP_DOWNLOAD')}}</div>
      </div>
      <div class="body">
        <div class="content" v-show="selected==0">
          <div
            class="eventWrap"
            @click="selectedEvent=event"
            v-for="event in eventList"
            :key="event.EventId"
          >
            <div class="competition">{{event.Competition.CompetitionName}}</div>
            <div class="eventBody" :class="{active:selectedEvent.EventId==event.EventId}">
              <div class="team">{{event.HomeTeam}}</div>
              <div class="team">{{event.AwayTeam}}</div>
            </div>
          </div>
          <div v-if="eventList.length==0" class="empty">{{$t('TEXT_NO_DATA')}}</div>
        </div>
        <div class="content2" v-show="selected==1">
          <img class="appPng" src="@/assets/app_download_bibet.png" />
          <img :src="qrCode" class="qrCode" />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapState } from "vuex";
import visualWidget from "@/components/common/visualWidget";
export default {
  data() {
    return {
      selected: 0,
      selectedEvent: {}
    };
  },
  components: {
    visualWidget
  },
  props: {
    list: {
      type: Object,
      default: () => {}
    },
    sportId: {
      type: Number,
      default: () => {}
    }
  },
  watch: {
    sportId: {
      handler() {
        const { eventList } = this;
        if (!this.selectedEvent.EventId) {
          if (eventList.length > 0) {
            this.selectedEvent = eventList[0];
          }
        } else {
          for (let i = 0; i < eventList.length; i++) {
            if (eventList[i].EventId == this.selectedEvent.EventId) return;
          }
          if (eventList.length > 0) {
            this.selectedEvent = eventList[0];
          }
        }
      }
    },
    eventList: {
      handler(list, oldList) {
        const { eventList } = this;
        if (!this.selectedEvent.EventId) {
          if (eventList.length > 0) {
            this.selectedEvent = eventList[0];
          }
        } else {
          for (let i = 0; i < eventList.length; i++) {
            if (eventList[i].EventId == this.selectedEvent.EventId) return;
          }
          if (eventList.length > 0) {
            this.selectedEvent = eventList[0];
          }
        }
      },
      deep: true
    }
  },
  computed: {
    ...mapState({
      qrCode: state => state.common.qrCode
    }),

    eventList() {
      const { sportId, list } = this;
      if (sportId == 1) {
        return list.soccer;
      }
      if (sportId == 2) {
        return list.basketball;
      }
      return [];
    }
  },
  methods: {
    $_createNoise() {
      var canvas = document.getElementById("nodataPanel"),
        ctx = canvas.getContext("2d");

      canvas.width = 328;
      canvas.height = 150;
      ctx.fillStyle = "rgb(0, 0, 0)";

      // initial veniceeters

      var UNIT = 5,
        COLS = Math.ceil(canvas.width / UNIT),
        ROWS = Math.ceil(canvas.height / UNIT);

      var sMat = [];

      function noise() {
        for (var row = 0; row < ROWS; row++) {
          sMat[row] = [];
          for (var col = 0; col < COLS; col++) {
            var grayScale = Math.random() * 50;
            sMat[row][col] = grayScale;
          }
        }
      }

      function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        for (var row = 0; row < ROWS; row++) {
          for (var col = 0; col < COLS; col++) {
            var fill = Math.round(sMat[row][col]);

            ctx.beginPath();

            ctx.fillStyle = "rgb(" + fill + "," + fill + "," + fill + ")";

            ctx.rect(col * UNIT, row * UNIT, UNIT, UNIT);

            ctx.fill();

            ctx.closePath();
          }
        }
      }

      var timeoutEvt = null;

      function animate(time) {
        time = time || 1000 / 60;

        // initial noise screen
        noise();
        // draw noise screen
        draw();
        // repeat these steps
        timeoutEvt = setTimeout(function() {
          animate(time);
        }, time);
      }

      // start the noise animation 60 fpsＦ
      animate(1000 / 60);

      // resizing event handling
      window.onresize = function() {
        clearTimeout(timeoutEvt);

        canvas.width = 325;
        canvas.height = 150;

        (COLS = Math.ceil(canvas.width / UNIT)),
          (ROWS = Math.ceil(canvas.height / UNIT));

        sMat = [];

        // 60 fps
        animate(1000 / 60);
      };
    },
    mounted() {
      this.$_createNoise();
    }
  }
};
</script>
<style lang="scss" src="@/scss/router/yabo/streamFloat/streamFloat.scss"></style>
<style lang="scss">
.noDataWrap {
  display: flex;
  height: 150px;
  width: 100%;
  -ms-flex-pack: center;
  justify-content: center;
  -ms-flex-align: center;
  align-items: center;
  position: relative;
  canvas {
    position: absolute;
    left: 0;
    background: black;
  }
  .noStream {
    z-index: 9999;
    padding: 0 5px;
    color: white;
  }
}
</style>