<template>
	<div class="top-scroe">
		<div class="home-team">
            <img :src="propHomeIcon" class="home-team-icon" v-if="propHomeIcon">
            <img :src="testImageProp" class="home-team-icon" v-else>
            <div class="home-team-name">{{ propEventData.HomeTeam }}</div>
            <div class="clearfix"></div>
		</div>
		<div class="scroe-resault">
            <div class="union" v-if="propEventData.Competition">{{ propEventData.Competition.CompetitionName }}</div>
            <div class="separate-line"></div>
            <div class="current-result">
                <span v-if="propEventData.HomeScore" class="home-team-score">{{propEventData.HomeScore}}</span>
                <span v-else class="home-team-score">-</span>
                <span class="contrast-icon"> : </span>
                <span v-if="propEventData.AwayScore"  class="away-team-score">{{propEventData.AwayScore}}</span>
                <span v-else  class="away-team-score">-</span>
            </div>
		</div>
		<div class="away-team">
            <img :src="propAwayIcon" class="away-team-icon" v-if="propAwayIcon">
            <img :src="testImageProp" class="away-team-icon" v-else>
            <div class="away-team-name">{{ propEventData.AwayTeam }}</div>
            <div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
	</div>
</template>

<script>

export default {
    props: {
        testImageProp: '',
        propEventData: {},
        propHomeIcon: '',
        propAwayIcon: '',
    },
}
</script>
