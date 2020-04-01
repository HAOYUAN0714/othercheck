<template>
  <div class="widget">
    <loading v-if="isLoading" />
    <div id="visualization_wrap" />
  </div>
</template>
<script>
import loading from './loading'
import { mapGetters } from 'vuex'

// if (document.getElementById('jsHook')) return // was already loaded
var scriptTag = document.createElement('script')
scriptTag.src = 'http://cs.betradar.com/ls/widgets/?/inplaymatrix/zh/America:Santo_Domingo/widgetloader/widgets'
scriptTag.id = 'jsHook'
document.getElementsByTagName('head')[0].appendChild(scriptTag)

export default {
  components: {
    loading
  },
  props: {
    matchId: {
      type: Number,
      default: () => 1000
    }
  },
  data() {
    return {
    }
  },
  computed: {
    ...mapGetters({
      isLoading: 'betball/getIsWidgetLoading'
    })
  },
  watch: {
    matchId() {
      // if (this.matchId !== 0) {
      //   console.log(this.matchId)
      //   // this.$_loadScript()
      //   this.$_createWidget(this.matchId)
      // }
    }
  },
  mounted() {
    // this.$_loadScript()
    setTimeout(() => {
      console.log(this.matchId)
      this.$_createWidget(this.matchId)
    }, 1000)
  },
  beforeDestroy() {
    // Stop Merory Leak
    // SRLive.destroy()
  },
  methods: {
    // $_loadScript() {
    //   if (document.getElementById('jsHook')) return // was already loaded
    //   var scriptTag = document.createElement('script')
    //   scriptTag.src = 'http://cs.betradar.com/ls/widgets/?/inplaymatrix/zh/America:Santo_Domingo/widgetloader/widgets'
    //   scriptTag.id = 'jsHook'
    //   document.getElementsByTagName('head')[0].appendChild(scriptTag)
    // },
    $_createWidget(matchId) {
      SRLive.addWidget({
        name: 'widgets.lmts',
        config: {
          container: '#visualization_wrap',
          showTitle: false,
          matchId,
          sidebarLayout: 'bottom',
          pitchCrowd: true,
          showScoreboard: false,
          showStats: false,
          collapse_enabled: false,
          showMomentum: false,
          showMomentum2: false
        },
        callback: function() {
          console.log('ing')
        }
      })
    }
  }
}
</script>
<style lang="scss" >
.widget {
  // min-height: 100px;
  position: relative;
  background: rgb(57, 57, 57);

  .noSource {
    background: #222;
    width: 100%;
    height: 100%;
    padding-top: 100px;
    text-align: center;
    justify-content: center;
    color: white;
  }
}
</style>
