import store from '@/store'

// const { body } = document
// const WIDTH = 1024 // refer to Bootstrap's responsive design

export default {
  watch: {
    $route(route) {
      if (this.device === 'mobile' && this.sidebar.opened) {
        store.dispatch('app/closeSideBar', { withoutAnimation: false })
      }
    }
  },
  beforeMount() {
    window.addEventListener('resize', this.$_resizeHandler)
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.$_resizeHandler)
  },
  mounted() {
    store.dispatch('app/toggleDevice', 'mobile')
    store.dispatch('app/closeSideBar', { withoutAnimation: true })
  },
  methods: {
    $_resizeHandler() {
      if (!document.hidden) {
        store.dispatch('app/closeSideBar', { withoutAnimation: true })
      }
    }
  }
}
