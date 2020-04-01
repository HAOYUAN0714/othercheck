<template>
  <span class="detail">{{ parsedString }}{{ timePart }}</span>
</template>
<script>
import moment from 'moment'
export default {
  props: {
    time: {
      type: String,
      default: ''
    },
    sportId: {
      type: Number,
      default: 0
    },
    timeStatus: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      stringPart: '',
      timePart: '',
      interval: null // interval實體
    }
  },
  computed: {
    countOrder() {
      const { sportId } = this
      if (sportId === 2) return -1
      return 1
    },
    parsedString() {
      const { stringPart } = this

      return stringPart
        .replace('Q1', this.$t('S_FIRST_QUAR'))
        .replace('Q2', this.$t('S_SECOND_QUAR'))
        .replace('Q3', this.$t('S_THIRD_QUAR'))
        .replace('Q4', this.$t('S_FOURTH_QUAR'))
        .replace('1H', this.$t('S_FIRST_HALF'))
        .replace('HT', this.$t('S_HT'))
        .replace('2H', this.$t('S_SECOND_HALF'))
        .replace('!LIVE', this.$t('S_LIVE'))
        .replace('OT', this.$t('S_OT'))
    }
  },
  watch: {
    time() {
      this.$_parseTime()
    }
  },
  mounted() {
    this.$_parseTime()
    if (this.timeStatus !== 3) {
      this.interval = setInterval(() => this.$_updateClock(), 1000)
    }
  },
  beforeDestroy() {
    clearInterval(this.interval)
  },
  methods: {
    $_parseTime() {
      const { time } = this
      if (!time) {
        return
      }
      this.stringPart = (time).split(' ')[0] + ' '
      this.timePart = (time).split(' ')[1]
    },
    $_updateClock() {
      if (this.timePart && this.timePart !== '00' && this.timePart !== '00:00') {
        const { countOrder } = this
        let minus = false
        let multi = 1
        const timeSet = this.timePart.split(':')
        if (timeSet[0]) {
          if (Number(timeSet[0]) >= 60) {
            this.timePart = String(Number(timeSet[0]) % 60) + ':' + timeSet[1]
            multi = parseInt(timeSet[0] / 60)
            minus = true
          }
        }
        this.timePart = moment(this.timePart, 'mm:ss')
          .add(countOrder, 'second')
          .format('mm:ss')
        if (minus) {
          const timeSet2 = this.timePart.split(':')
          this.timePart =
            String(Number(timeSet2[0]) + 60 * multi) + ':' + timeSet2[1]
        }
      }
    }
  }
}
</script>
