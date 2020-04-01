<template>
  <div :class="{'has-logo':showLogo}">
    <div class="topItem sideTab">
      <div class="closeIcon" @click="$_closeNav">
        <i class="fas fa-times" />
      </div>
    </div>
    <!--active:routerPath == navItem.path-->
    <div
      class="sideTab home"
      :class="{
        active: $route.name === 'home'
      }"
      @click="$_toRoute('/focus')"
    >
      {{ $t('S_HOME_PAGE') }}
    </div>
    <div
      v-if="Object.keys(curGameCount.live || {}).length > 1"
      class="sideTab runball"
      :class="{
        active: $route.name === 'live'
      }"
      @click="$_toRoute(`/live/${Object.keys(curGameCount.live || {})[0]}`)"
    >
      {{ $t('S_RUN_BALL') }}
    </div>
    <div
      class="sideTab comming"
      :class="{
        active: $route.name === 'coming'
      }"
      @click="$_toRoute('/coming')"
    >
      {{ $t('S_COMMING_MATCH') }}
    </div>
    <div
      class="sideTab pass"
      :class="{
        active: $route.name === 'pass'
      }"
      @click="$_toRoute('/pass')"
    >
      {{ $t('S_MULTIPLES') }}
    </div>
    <div class="sideTab notLink sport">{{ $t('S_SPORTS') }}</div>
    <div
      v-for="(navItem,index) in curGameCount.sportMapping"
      :key="`${navItem.path}-${index}`"
      class="sideTab"
      :class="{
        active: $route.name === 'menuPage' && +($route.params.sportid) === +(navItem.orderNumber)
      }"
      @click="$_toSportRoute(navItem.orderNumber)"
    >
      <span class="sport-name">{{ navItem.sportName }}</span>
      <span class="icon" :class="[getIcon(index),]" />
    </div>
    <div class="sideTab notLink">
      {{ $t('S_SETTING') }}
    </div>
    <div class="sideTab">
      <div>{{ $t('S_ODDS_TYPE') }}</div>
      <select v-model="curOddType" class="select">
        <option
          v-for="item in oddsType"
          :key="item.key"
          :value="item.key"
        >
          {{ item.name }}
        </option>
      </select>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import variables from '@/styles/variables.scss'
import icon from '@/utils/sportIconList'

export default {
  data() {
    return {
      navList: [
        {
          name: this.$t('S_HOME_PAGE'),
          path: '/focus',
          hasLink: true
        },
        {
          name: this.$t('S_RUN_BALL'),
          path: '/live',
          hasLink: true
        },
        {
          name: this.$t('S_COMMING_MATCH'),
          path: '/coming',
          hasLink: true
        },
        {
          name: this.$t('S_MULTIPLES'),
          path: '/pass',
          hasLink: true
        },
        {
          name: this.$t('S_SPORTS'),
          path: '/empty',
          hasLink: false
        }
      ],
      sidebarItem: [],
      oddsType: [
        // {
        //   key: 1,
        //   name: this.$t('S_MALA_ODDS_TYPE')
        // },
        {
          key: 2,
          name: this.$t('S_HK_ODDS_TYPE')
        },
        {
          key: 3,
          name: this.$t('S_EU_ODDS_TYPE')
        }
        // {
        //   key: 4,
        //   name: this.$t('S_INDO_ODDS_TYPE')
        // }
      ],
      icon
    }
  },
  computed: {
    ...mapGetters(['sidebar', 'curGameCount']),
    routes() {
      return this.$router.options.routes
    },
    routerPath() {
      return this.$router.currentRoute.fullPath
    },
    activeMenu() {
      const route = this.$route
      const { meta, path } = route
      // if set path, the sidebar will highlight the path you set
      if (meta.activeMenu) {
        return meta.activeMenu
      }
      return path
    },
    showLogo() {
      return this.$store.state.settings.sidebarLogo
    },
    variables() {
      return variables
    },
    curOddType: {
      get() {
        return this.$cookie.get('odds_type')
      },
      set(val) {
        this.$cookie.set('odds_type', val)
        location.reload()
      }
    }
  },
  mounted() {
    this.$_getSportSidebar()
  },
  methods: {
    $_toRoute(navItem) {
      this.$_closeNav()
      this.$router.push(navItem)
    },
    $_toSportRoute(sportId) {
      this.$_closeNav()
      this.$router.push({ path: '/sport/' + sportId })
    },
    $_closeNav() {
      this.$store.dispatch('app/closeSideBar', { withoutAnimation: false })
    },
    $_getSportSidebar() {
      // getHomeInfo().then((res) => {
      //   this.sidebarItem = res.sportMapping
      // })
    },
    getIcon(sportId) {
      if (this.icon.includes(sportId)) {
        return `icon-${sportId}`
      } else {
        return 'icon-1'
      }
    }
  }
}
</script>

<style lang="scss" scoped>

#app .sidebar-container{
    background: var(--side_top_bg);
}
#app .mobile .sidebar-container>.sideTab.topItem .closeIcon {
    font-size: 28px;
}
.icon {
  float: right;
  width: 25px;
  height: 38px;
}
</style>
