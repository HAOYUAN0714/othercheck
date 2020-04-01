import Vue from 'vue'
import Router from 'vue-router'
import VueCookie from 'vue-cookie'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'

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
export const constantRoutes = [{
  path: '/login',
  component: () => import('@/views/login/index'),
  hidden: true
},

{
  path: '/404',
  component: () => import('@/views/404'),
  hidden: true
},

{
  path: '/',
  component: Layout,
  redirect: '/focus',
  children: [
    {
      name: 'home',
      path: 'focus',
      component: () => import('@/views/dashboard/index')
    },
    {
      name: 'live',
      path: 'live/:sportId',
      component: () => import('@/views/live/index')
    },
    {
      name: 'coming',
      path: 'coming',
      component: () => import('@/views/coming/index')
    },
    {
      name: 'pass',
      path: 'pass',
      component: () => import('@/views/pass/index')
    },
    // today有用到嗎 <= 有 首頁的功能
    {
      path: 'today/:sportId',
      component: () => import('@/views/today/index')
    },
    {
      name: 'menuPage',
      path: 'sport/:sportid',
      component: () => import('@/views/menu')
    },
    //
    {
      path: 'league/:leagueid/:sportid/:market/:isCombo/:date',
      component: () => import('@/views/league')
    },
    {
      path: 'league/:leagueid/:sportid/:market/:isCombo',
      component: () => import('@/views/league')
    },
    {
      path: 'league/:leagueid/:sportid/:market/:date',
      component: () => import('@/views/league')
    },
    {
      path: 'league/:leagueid/:sportid/:market',
      component: () => import('@/views/league')
    },
    //
    {
      path: 'champion/:sportid/:championid/:pass',
      component: () => import('@/views/champion')
    },
    {
      path: 'detail/:isComboNew/:sportid/:gameid',
      component: () => import('@/views/moregame1')
    },
    {
      path: 'detail/:sportid/:gameid',
      component: () => import('@/views/moregame1')
    },
    {
      path: 'join/:isComboNew/:sportid',
      component: () => import('@/views/join')
    },
    {
      path: 'join/:isComboNew/:sportid/:date',
      component: () => import('@/views/join')
    }
  ]
},
{
  path: '/betting-history/:betType',
  component: () => import('@/views/allBetHistory')
},

{
  path: '/moregamedemo',
  component: Layout,
  redirect: '/',
  children: [{
    path: 'detail/:gameid',
    name: 'moregamedemo',
    component: () => import('@/views/moregame1'),
    meta: {
      title: 'moregamedemo'
    }
  }]
  // component: () => import('@/views/moregame'),
  // name: 'moregame',

  // meta: {
  //   title: 'moregame'
  // }
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
  if (to.path === '/focus' && from.path === '/') {
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
