<template>
    <div class="stream">
        <div id="dplayer" />
    </div>
</template>
<script>
import Hls from "hls.js/dist/hls.min.js";
import "dplayer/dist/DPlayer.min.css";
import DPlayer from "dplayer";

export default {
    data() {
        return {
            liveUrl: "",
            dp: ""
        };
    },
    methods: {
        playVid() {
            this.dp = new DPlayer({
                container: document.getElementById("dplayer"),
                video: {
                    url: this.m3u8Url,
                    autoplay: true,
                    type: "customHls",
                    customType: {
                        customHls: function(video, player) {
                            const hls = new Hls();
                            hls.loadSource(video.src);
                            hls.attachMedia(video);
                        }
                    }
                }
            });
        }
    },
    props: {
        m3u8Url: {
            type: String,
            default: ""
        }
    },
    mounted() {
        this.playVid();
    }
};
</script>
<style lang="scss">
.stream {
    width: 100%;
    height: 100%;
    #dplayer {
        background: black;
        height: 100%;
        width: 100%;
    }
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
    height: 100%;
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
