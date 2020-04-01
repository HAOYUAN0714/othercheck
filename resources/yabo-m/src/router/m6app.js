import Vue from 'vue'
import Router from 'vue-router'
import VueCookie from 'vue-cookie'

Vue.use(Router)

/* Layout */
// 不知道為啥把index.vue拿掉會吃到index.js
import Layout from '@/layout/m6app/index.vue'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  // {
  // path: '/login',
  // component: () => import('@/views/login/index'),
  // hidden: true
  // },

  {
    path: '/404',
    component: () => import('@/views/404'),
    hidden: true
  },
  // {
  //   path: '/moreSport',
  //   name: 'moreSport',
  //   component: () => import('@/views/m6app/moreSport')
  // },
  // {
  //   path: '/moreSportToday',
  //   name: 'moreSportToday',
  //   component: () => import('@/views/m6app/today/moreSport')
  // },
  // {
  //   path: '/moreSportFuture',
  //   name: 'moreSportFuture',
  //   component: () => import('@/views/m6app/breakfast/moreSport')
  // },
  // {
  //   path: '/moreSportCombo',
  //   name: 'moreSportCombo',
  //   component: () => import('@/views/m6app/combo/moreSport')
  // },
  // {
  //   path: '/moreSportFaver',
  //   name: 'moreSportFaver',
  //   component: () => import('@/views/m6app/faver/moreSport')
  // },
  {
    path: '/wager',
    name: 'wager',
    component: () => import('@/views/m6app/wager')
  },
  {
    path: '/comboBet',
    name: 'comboBet',
    component: () => import('@/views/m6app/comboBet')
  },
  {
    path: '/filterCompetition',
    name: 'filterCompetition',
    component: () => import('@/views/m6app/filterCompetition')
  },
  {
    path: '/',
    component: Layout,
    redirect: '/today',
    children: [
      // {
      //   path: '/focus',
      //   name: 'focus',
      //   component: () => import('@/views/m6app/index')
      // },
      // {
      //   path: '/focus2/:key?',
      //   name: 'focus2',
      //   component: () => import('@/views/m6app/index2')
      // },
      // test
      {
        name: 'live',
        path: 'live/:sportId',
        component: () => import('@/views/live/index')
      },
      {
        name: 'today',
        path: '/today',
        component: () => import('@/views/m6app/today/index')
      },
      {
        name: 'breakfast',
        path: 'breakfast',
        component: () => import('@/views/m6app/breakfast/index')
      },
      {
        name: 'combo',
        path: 'combo',
        component: () => import('@/views/m6app/combo/index')
      },
      {
        name: 'faver',
        path: 'faver',
        component: () => import('@/views/m6app/faver/index')
      },
      // today有用到嗎
      {
        path: 'today/:sportId',
        component: () => import('@/views/today/index')
      },
      {
        name: 'menuPage',
        path: 'sport/:sportid',
        component: () => import('@/views/menu')
      },
      {
        path: '/moreSportToday',
        name: 'moreSportToday',
        component: () => import('@/views/m6app/today/moreSport')
      },
      {
        path: '/moreSportFuture',
        name: 'moreSportFuture',
        component: () => import('@/views/m6app/breakfast/moreSport')
      },
      {
        path: '/moreSportCombo',
        name: 'moreSportCombo',
        component: () => import('@/views/m6app/combo/moreSport')
      },
      {
        path: '/moreSportFaver',
        name: 'moreSportFaver',
        component: () => import('@/views/m6app/faver/moreSport')
      },
      {
        path: '/detail/:sportId/:gameId/:routername',
        name: 'sportDetail',
        component: () => import('@/views/m6app/sportDetail')
      },
      {
        path: '/champion',
        component: () => import('@/views/m6app/champion'),
        children: [
          {
            path: '/champion/:sportId',
            name: 'champion',
            component: () => import('@/views/m6app/champion/leagueList')
          },
          {
            path: '/champion/:sportId/:leagueId',
            name: 'championOdds',
            component: () => import('@/views/m6app/champion/championOdds')
          }
        ]
      }
      //
    ]
  },
  // 404 page must be placed at the end !!!
  {
    path: '*',
    redirect: '/',
    hidden: true
  }
]

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({
    y: 0
  }),
  routes: constantRoutes
})

const router = createRouter()

router.beforeEach((to, from, next) => {
  if (from.path === '/') {
    // console.log('member token checking....')
    const regex = /[^&=?]+=[^&#]*/g
    const url = (location.href)
    const target = url.match(regex)
    const tran = {
      hall: 'abs-hall',
      token: 'abs-token',
      LanguageCode: 'abs-lang',
      memberCode: 'abs-mem'
    }
    if (target) {
      target.forEach(val => {
        const [key, value] = val.split('=')
        if (tran[key]) {
          // console.log('save key=', key, tran[key], ',val=', value)
          VueCookie.set(tran[key], value)
        }
      })
    } else {
      // console.log('target is null')
    }
    history.replaceState(null, null, '/exsport/')
  } else {
    // 先移除測試
    // const regex2 = /exsport{1}/
    // if (!location.pathname.match(regex2)) {
    //   location.href = '/'
    // }
  }
  next()
})

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
