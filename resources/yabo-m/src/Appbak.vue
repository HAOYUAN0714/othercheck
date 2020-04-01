<template>
  <div id="app">
    <router-view />
    <betslip2 v-if="betSlip === true" />
  </div>
</template>

<script>
import betslip2 from '@/layout/components/betSlip'
import { mapGetters } from 'vuex'

export default {
  name: 'App',

  components: {
    betslip2
  },
  computed: {
    ...mapGetters([
      'betSlip'
    ])
  },
  methods: {

  }

}
</script>

<style  scoped>

</style>
