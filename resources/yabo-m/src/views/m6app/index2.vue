<template>
  <div class="container">
    <AppHeader />
    <div class="clear" />
    <ul class="main">
      <li v-for="list in main" :key="list.index" :class="{active: list.total === 134}" @click="routerPush(list.path)">
        <div>
          <strong>{{ list.total }}</strong>
        </div>
        <hr>
        <div>{{ list.name }}</div>
      </li>
    </ul>
    <ul class="second">
      <li v-for="list in sport" :key="list.index" :class="{active: list.total === 30}" @click="routerPush(list.path)">
        <span>{{ list.total }}</span>
        <span>{{ list.name }}</span>
      </li>
    </ul>
    <ul class="feature">
      <li>
        <i class="fa fa-angle-up" aria-hidden="true" />
        <span>收起</span>
      </li>
      <li>
        <i class="fa fa-filter" aria-hidden="true" />
        <span><strong>聯賽</strong> / 時間</span>
      </li>
      <li>
        <span>more更多賽事</span>
        <i class="fa fa-chevron-right" aria-hidden="true" />
      </li>
    </ul>
    <div class="clear" />
    <SportList />
  </div>
</template>

<script>
import AppHeader from '@/layout/m6app/components/AppHeader'
import SportList from '@/layout/m6app/components/SportList'

export default {
  components: {
    AppHeader,
    SportList
  },
  data() {
    return {
      main: [
        {
          name: '今日',
          total: 134,
          path: 'today'
        },
        {
          name: '早盤',
          total: 445,
          path: 'morning'
        },
        {
          name: '串關',
          total: 575,
          path: 'passOff'
        },
        {
          name: '關注',
          total: 0,
          path: 'focus'
        }
      ],
      sport: [
        {
          name: '足球',
          total: 30,
          path: 'football'
        },
        {
          name: '籃球',
          total: 40,
          path: 'basketball'
        },
        {
          name: '網球',
          total: 5,
          path: 'tennis'
        },
        {
          name: '排球',
          total: 4,
          path: 'vollyball'
        },
        {
          name: '美式足球',
          total: 3,
          path: 'USfootball'
        },
        {
          name: '電競',
          total: 3,
          path: 'game'
        },
        {
          name: '冰球',
          total: 11,
          path: 'puck'
        },
        {
          name: '足球',
          total: 60,
          path: 'football'
        }
      ]
    }
  },
  computed: {

  },
  watch: {},
  created() {
  },
  methods: {
    routerPush(navItem) {
      // console.log('navItem', navItem)
      // console.log('this.$router', this.$router)
      this.$router.push({ name: 'focus2', params: { key: navItem }})
      // this.$router.push({ name: navItem })
      // this.$router.push({ path: navItem });
    }
  }
}
</script>

<style lang="scss" scoped>
.container {
  background: #fff;
  min-width: 250px;
  ul {
    list-style: none;
    padding: 0;
    margin: 0;
    li {
      display: inline-block;
    }
  }
  .main {
    font-size: 14px;
    margin: 0 auto;
    margin-top: 10px;
    text-align: center;
    min-width: 320px;
    li {
      padding: 5px 20px;
      background-color: #fff;
      color: gray;
      font-weight: bold;
      border-radius: 5px 5px 0px 0px;
      div {
        &:last-child {
          font-size: 12px;
        }
        strong {
          font-size: 18px;
        }
      }
      hr {
        margin: 0px;
      }
    }
    .active {
      background-color: #000;
      color: #d89e48;
    }
  }
  .second {
    display: flex;
    flex: 1;
    align-items: center;
    height: 40px;
    width: 100vw;
    overflow-x: scroll;
    padding: 6px 0px;
    background-color: #000;
    color: #fff;
    font-size: 12px;
    li {
      margin: 0px 10px;
      padding: 5px 10px;
      justify-content: center;
      white-space: nowrap;
      border-radius: 5px;
    }
    .active {
      background-color: #e6a23c;
      color: #000;
    }
  }
  .feature {
    min-width: 320px;
    li {
      font-size: 14px;
      padding: 10px 5px;
      border-right: 1px solid #e4e4e4d9;
      svg {
        color: #d89e48;
        background-color: #000;
        border-radius: 12px;
        width: 15px;
      }
      &:last-child {
        width: 50vw;
        text-align: right;
        border-right: none;
      }
      &:nth-child(2) {
        font-size: 10px;
        color: #909399b8;
        strong {
          color: #d89e48;
          font-size: 14px;
        }
      }
    }
  }
  .clear {
    clear: both;
  }
}

</style>
