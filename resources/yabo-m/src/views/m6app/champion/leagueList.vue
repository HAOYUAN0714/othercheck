<template>
  <div class="container">
    <div v-show="getChampionList" ref="getHeight" class="sport-list clearfix">
      <div
        v-for="(item) in (allEventCount.Outright && allEventCount.Outright.Sport)"
        :key="item.SportId"
        class="sport"
        :class="{
          active: +(sportId) === +(item.SportId)
        }"
        @click="getChampionSport(item.SportId)"
      >
        {{ item.SportName }} {{ item.OutrightCount }}
      </div>
    </div>
    <div
      v-show="getChampionList"
      class="grey"
      :style="{
        top: `${sportListHeight}px`
      }"
      @click="closeSportList"
    />
    <!-- {{leagueData}} -->
    <div
      v-for="(league, key) in leagueData"
      :key="`league_${key}`"
      class="league clearfix"
      @click="toOddsPage(key)"
    >
      <div class="league-name">{{ league.CompetitionName }}</div>
      <div class="count">
        <span>{{ league.Count }}</span>
        <!-- <span>{{ league.SportId }}</span> -->
      </div>
    </div>
  </div>
</template>

<script>
import { getOutRightLeagueCount } from '@/api/m6'
import { mapGetters, mapActions } from 'vuex'

export default {
  data() {
    return {
      sportId: '',
      leagueData: '',
      sportListHeight: ''
    }
  },
  computed: {
    ...mapGetters({
      allEventCount: 'getAllEventCount',
      getChampionList: 'openChampionList'
    })
  },
  watch: {
    getChampionList() {
      this.$nextTick(() => {
        this.sportListHeight = this.$refs.getHeight.clientHeight
      })
    }
  },
  mounted() {
    this.sportId = this.$route.params.sportId
    this.getChampionApi()
    this.sportListHeight = this.$refs.getHeight.clientHeight
  },
  methods: {
    ...mapActions({
      'openChampionList': 'm6app/openChampionList'
    }),
    getChampionApi() {
      getOutRightLeagueCount(this.sportId).then((res) => {
        this.leagueData = res.league
      })
    },
    toOddsPage(val) {
      const sportId = this.$route.params.sportId
      this.$router.push(`/champion/${sportId}/${val}`)
    },
    closeSportList() {
      this.openChampionList(false)
    },
    getChampionSport(val) {
      this.$router.replace(`/champion/${val}`)
    }
  }
}
</script>

<style lang="scss" scoped>
.container {
  position: relative;
}
.sport-list {
  position: absolute;
  background-color: #000;
  width: 100%;
  opacity: .8;
  .sport {
    float: left;
    width: 25%;
    padding: 10px 5px;
    text-align: center;
    color: #fff;
    font-size: 14px;
    font-weight: bold;
    &.active {
      color: #E7B92C;
    }
  }
}
.grey {
  position: absolute;
  background-color: #EEE;
  bottom: 0;
  left: 0;
  right: 0;
  opacity: .8;
}
.league {
  padding: 10px 20px;
  border-bottom: 1px solid #E8E8E8;
  .league-name {
    float: left;
    width: 90%;
  }
  .count {
    float: right;
    width: 10%;
    text-align: right;
    span {
      display: inline-block;
      border: 1px solid #A7A7A7;
      padding: 2px 10px;
      color: #F00;
    }
  }
}
</style>
