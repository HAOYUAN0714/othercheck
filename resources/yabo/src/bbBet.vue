<template>
    <div id="app">
        <LiveVideo :propEventData="activeEventData" />
        <TopScore
            :testImageProp="testImage"
            :propEventData="activeEventData"
            :propHomeIcon="homeIcon"
            :propAwayIcon="awayIcon"
        />
        <GameLsit v-on:emitActiveEvent="sendActiveEvent" />
    </div>
</template>

<script>
import LiveVideo from "./components/common/bbBet/liveVideo";
import TopScore from "./components/common/bbBet/topScore";
import GameLsit from "./components/common/bbBet/gamesList";
import imgUrl from "./assets/test/team-test-icon-trans.png";

export default {
    name: "app",
    data() {
        return {
            testImage: imgUrl,
            initialData: [],
            activeEventData: {},
            homeIcon: '',
            awayIcon: '',
        };
    },
    methods: {
        sendActiveEvent(activeEvent) {
            this.activeEventData = activeEvent;
            const apiHome = '/exsport/img/TeamImageFile/'+this.activeEventData.HomeTeamId+'.png';
            const apiAway = '/exsport/img/TeamImageFile/'+this.activeEventData.AwayTeamId+'.png';

            this.$http.get(apiHome).then(res => {
                this.homeIcon = apiHome
                console.log(res);
            }).catch(err => {
                this.homeIcon = ''
                console.log(err)
            });
            this.$http.get(apiAway).then(res => {
                this.awayIcon = apiAway;
                console.log(res);
            }).catch(err => {
                this.awayIcon = ''
                console.log(err)
            });

        }
    },
    components: {
        LiveVideo,
        TopScore,
        GameLsit
    }
};
</script>


<style lang="scss">
html,
body {
    padding: 0;
    margin: 0;
    font-family: Segoe UI;
    background-color: transparent;
}

* {
    box-sizing: border-box;
}

.d-none {
    display: none;
}

.clearfix {
    clear: both;
}
</style>

<style lang="scss" src="@/scss/common/bbBet/bbBet.scss"></style>
