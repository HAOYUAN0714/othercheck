export default {
  methods: {
    getSectionName(input, specifier) {
      // 沒有需要取代的東西就直接顯示
      if (!specifier) {
        return input
      }

      // 固定邏輯，請勿隨意更動
      const pattern = /{.*}/
      const replacement = specifier.substr(specifier.match(/=/).index + 1)
      return input.replace(pattern, replacement)
    }
  }
}
