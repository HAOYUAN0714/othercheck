<template>
  <span
    v-if="showHandicap(betItem.BetTypeId)"
    class="inline cap"
    :class="[error ? 'white' : 'green']"
  >
    {{ formatCap(betItem) }}
  </span>
</template>

<script>
export default {
  props: {
    betItem: {
      type: Object,
      default: () => ({})
    },
    error: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    showHandicap(val) {
      return +(val) === 1 || +(val) === 2
    },
    formatCap(val) {
      if (!val.Handicap && val.Handicap !== 0) {
        return
      }

      const mod = val.Handicap % 0.5
      const betTypeId = +(val.BetTypeId)
      const selectionId = +(val.BetTypeSelectionId)

      if (mod === 0) {
        if (betTypeId === 1 && selectionId === 1) {
          if (val.Handicap > 0) {
            return `-${val.Handicap}`
          }
          return `${val.Handicap === 0 ? 0 : +Math.abs(val.Handicap)}`
        }
        if (betTypeId === 1 && selectionId === 2) {
          if (val.Handicap > 0) {
            return `+${val.Handicap}`
          }
          return `${val.Handicap === 0 ? 0 : -Math.abs(val.Handicap)}`
        }
        return val.Handicap
      }

      if (betTypeId === 1 && selectionId === 1) {
        if (val.Handicap > 0) {
          return `-${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
        }
        return `+${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
      }
      if (betTypeId === 1 && selectionId === 2) {
        if (val.Handicap > 0) {
          return `+${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
        }
        return `-${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
      }
      if (betTypeId === 2) {
        return `${Math.abs(val.Handicap - mod)}/${Math.abs(val.Handicap + mod)}`
      }
      return val.Handicap
    }
  }
}
</script>
