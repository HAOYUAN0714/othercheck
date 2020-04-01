
import { functionBus } from '@/yabolive'
export default {
  methods: {
    clickOdds() {
      functionBus.$emit('showPopUp')
    }
  }
}
