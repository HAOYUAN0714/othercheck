
import { functionBus } from '@/bblivem'
export default {
  methods: {
    clickOdds() {
      functionBus.$emit('showPopUp')
    }
  }
}
