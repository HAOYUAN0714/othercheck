<template>
  <div class="widget">
    <!-- <loadingMask :trigger="first" /> -->
    <div class="noDataWrap" v-show="mid==0">
      <canvas id="panel"></canvas>
      <span class="noStream">{{$t('TEXT_NO_STREAM')}}</span>
    </div>
    <div id="widget_hook" v-show="mid!=0" />
    <div class="visualization_wrap" v-show="mid!=0"></div>
  </div>
</template>
<script>
import axios from "axios";
export default {
  props: {
    mid: {
      type: Number,
      default: () => 0
    },
    event: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      first: true
    };
  },
  watch: {
    mid() {
      if (this.mid != 0) {
        this.$_createWidget(this.mid);
      }
    },
    event() {}
  },
  methods: {
    $_createNoise() {
      var canvas = document.getElementById("panel"),
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
    $_createWidget(mid) {
      var hook = document.getElementById("widget_hook");
      var ifrm = document.createElement("iframe");
      ifrm.setAttribute(
        "src",
        `https://wstt.66chile.com/exsport/api/get-widget-loader?EventId=${mid}`
      );
      ifrm.setAttribute("id", "widget_hook");
      ifrm.setAttribute("class", "widget_iframe");
      hook.parentNode.replaceChild(ifrm, hook);
    }
  },
  mounted() {
    this.$_createNoise();
    this.$_createWidget(this.mid);
  }
};
</script>
<style lang="scss" >
.widget {
  height: 100%;
  width: 100%;
  position: relative;
  overflow: hidden;
  display: block;
  background: rgb(57, 57, 57);
  .widget_iframe {
    border: none;
    width: 330px;
    height: 160px;
  }
  .noDataWrap {
    display: flex;
    height: 100%;
    width: 100%;
    -ms-flex-pack: center;
    justify-content: center;
    -ms-flex-align: center;
    align-items: center;
    position: relative;
    canvas {
      position: absolute;
      left: 0;
    }
    .noStream {
      z-index: 9999;
      padding: 0 5px;
      color: white;
    }
  }
}
</style>