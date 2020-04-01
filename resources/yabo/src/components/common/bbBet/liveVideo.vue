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

    <video class="live-video" width="720" height="360"
        controls="controls" autoplay="autoplay"
        x-webkit-airplay="true" x5-video-player-fullscreen="true"
        preload="auto" playsinline="true" webkit-playsinline
        x5-video-player-typ="h5">
        <source type="application/x-mpegURL" src="https://pull.xizhenzhu.com/live/stream2498854.m3u8?auth_key=1585688400-0-0-4935c04a653aa31ac964403423669d15">
    </video>

	</div>
</template>

<script>
import backgroundImage from '../../../assets/dist/main-back.png'

export default {
    props: {
        propEventData: {},
    },
    data() {
        return {
            imageUrl:  backgroundImage,
        }
    },
}
</script>

