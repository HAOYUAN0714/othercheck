<template>
  <div class="stream">
    <div class="mainContainer">
      <video
        id="videoElement"
        class="centeredVideo"
        controls
        autoplay
        muted="muted"
        width="487"
        height="243"
        parent="true"
      >
        Your browser is too old which doesn't support HTML5 video.
      </video>
    </div>
    <br>
    <!-- <div class="controls">
            <button @click="flv_start()">Play</button>
            <button @click="flv_pause()">Pause</button>
            <button @click="flv_destroy()">Stop</button>
        </div> -->
    <!-- <div>{{ flvUrl }}</div> -->
  </div>
</template>
<script>
export default {
  props: {
    flvUrl: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      liveUrl: ''
    }
  },
  created() {
    const recaptchaScript = document.createElement('script')
    recaptchaScript.setAttribute(
      'src',
      'https://bilibili.github.io/flv.js/dist/flv.js'
    )
    document.head.appendChild(recaptchaScript)
  },
  mounted() {
    const self = this
    setTimeout(function() {
      self.createdWidget()
    }, 1000)
  },
  methods: {
    // flv_start() {
    //   player.play()
    // },
    // flv_pause() {
    //   player.pause()
    // },
    // flv_destroy() {
    //   player.pause()
    //   player.unload()
    //   player.detachMediaElement()
    //   player.destroy()
    //   player = null
    // },
    createdWidget() {
      var player = document.getElementById('videoElement')
      if (flvjs.isSupported()) {
        var str = this.flvUrl
        const liveUrl = str.replace('&amp;', '&')
        var flvPlayer = flvjs.createPlayer({
          type: 'flv',
          url: liveUrl
        })
        flvPlayer.attachMediaElement(player)
        flvPlayer.load()
        player.play()
      } else {
        alert('flv not ready')
      }
    }
  }
}
</script>
<style lang="scss">
.stream {
    width: 100%;
    height: 100%;
    background-color: #000;
}
.mainContainer {
    display: block;
    width: 100%;
    margin-left: auto;
    margin-right: auto;
}
.handle {
    z-index: 999999 !important;
}
.urlInput {
    display: block;
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    margin-top: 8px;
    margin-bottom: 8px;
}
.centeredVideo {
    display: block;
    width: 100%;
    height: 215px;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: auto;
}
.controls {
    display: block;
    width: 100%;
    text-align: left;
    margin-left: auto;
    margin-right: auto;
}
</style>
