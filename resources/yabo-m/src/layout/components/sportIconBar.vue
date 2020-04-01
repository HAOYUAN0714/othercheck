<template>
  <div class="dashboard-choose">
    <div v-for="(item,index) in sport" :key="index" class="dashboard-choose-item">
      <div @click="routerPush(item.path)">
        <div class="dashboard-choose-item-icon">
          <font-awesome-icon :icon="['fas',(item.icon)]" />
        </div>
        <div class="dashboard-choose-item-text">{{ item.name }}</div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'Dashboard',
  data() {
    return {
      sport: [
        {
          icon: 'futbol',
          name: '足球',
          path: 'today/football'
        },
        {
          icon: 'baseball-ball',
          name: '籃球',
          path: 'today/basketball'
        },
        {
          icon: 'table-tennis',
          name: '網球',
          path: 'today/tennis'
        },
        {
          icon: 'volleyball-ball',
          name: '排球',
          path: 'today/vollyball'
        },
        {
          icon: 'football-ball',
          name: '美式足球',
          path: 'today/USfootball'
        },
        {
          icon: 'gamepad',
          name: '電競',
          path: 'today/game'
        },
        {
          icon: 'hockey-puck',
          name: '冰球',
          path: 'today/puck'
        },
        {
          icon: 'futbol',
          name: '足球',
          path: 'today/football'
        },
        {
          icon: 'baseball-ball',
          name: '籃球',
          path: 'today/basketball'
        },
        {
          icon: 'table-tennis',
          name: '網球',
          path: 'today/tennis'
        },
        {
          icon: 'volleyball-ball',
          name: '排球',
          path: 'today/vollyball'
        },
        {
          icon: 'football-ball',
          name: '美式足球',
          path: 'today/USfootball'
        },
        {
          icon: 'gamepad',
          name: '電競',
          path: 'today/game'
        },
        {
          icon: 'hockey-puck',
          name: '冰球',
          path: 'today/puck'
        }
      ]
    }
  },
  methods: {
    routerPush(navItem) {
      console.log('navItem', navItem)
      this.$router.push({ path: navItem })
    }
  }
}
</script>

<style lang='scss' scoped  src="@/styles/dashboard.scss"></style>
