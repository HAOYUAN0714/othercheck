<template>
	<div class="video-wrap" :style="{backgroundImage: 'url(' + imageUrl + ')' }" >
        <!-- <video controls class="live-video"   src="http://clips.vorwaerts-gmbh.de/big_buck_bunny.mp4" width="720" height="360"></video> -->
        <!-- <video controls class="live-video"   src="https://pull.xizhenzhu.com/live/stream2498854.m3u8?auth_key=1585688400-0-0-4935c04a653aa31ac964403423669d15" width="720"  height="360"></video> -->
        <!-- <div class="alert-text"> - 本賽事沒有提供直播 -</div> -->
        <!-- <video controls class="live-video"  v-if="propEventData.LiveStreamingMobile"  :src="propEventData.LiveStreamingMobileUrl[0].Url" width="720"  height="360"></video>
        <div v-else class="alert-text"> - 本賽事沒有提供直播 -</div> -->
        <!-- <video controls src="http://clips.vorwaerts-gmbh.de/big_buck_bunny.mp4" width="720" height="405" ></video> -->
        <!-- <video controls v-if="propEventData.LiveStreamingMobile" :src="propEventData.LiveStreamingMobileUrl" width="720" height="360"></video> -->
        <!-- <div
            class="no-video-alert"
            v-else-if="!propEventData.LiveStreamingMobile"
            :style="{backgroundImage: 'url(' + imageUrl + ')' }"
        >
            <div class="alert-text"> - 本賽事沒有提供直播 -</div>
        </div> -->
    <div id="dplayer" class="d-player-video" v-if="propEventData.LiveStreamingMobile"  />
    <div v-else class="alert-text"> - 本賽事沒有提供直播 -</div>
	</div>
</template>

<script>
import backgroundImage from '../../../assets/dist/main-back.png';
import Hls from "hls.js/dist/hls.min.js";
import "dplayer/dist/DPlayer.min.css";
import DPlayer from "dplayer";

export default {
    props: {
        propEventData: {},
    },
    data() {
        return {
            imageUrl:  backgroundImage,
        }
    },
    methods: {
        playVid() {
            if (this.videoUrl) {
                this.dp = new DPlayer({
                    container: document.getElementById("dplayer"),
                    video: {
                        url: this.propEventData.LiveStreamingMobileUrl[0].Url,
                        // url: 'https://pull.xizhenzhu.com/live/streamcnhd328210.m3u8?auth_key=1586177100-0-0-421295f68e7f1cb0501ee9d745418fed',
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
        }
    },
    mounted () {
        this.playVid();
    }
}
</script>

