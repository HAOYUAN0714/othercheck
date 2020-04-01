<template>
  <div>
    <div
      v-for="(detail, detailKey) in betList.WagerItemList"
      :key="`detail-list-${detailKey}`"
      class="detail-list"
    >
      <div class="list">{{ detail.CompetitionName }}</div>
      <div class="list">{{ formatEventDate(detail.EventDateTime) }}</div>
      <div class="list">{{ $t('S_BET_ID') }}: {{ betList.WagerId }}</div>
      <div class="list">{{ formatBetDate(betList.WagerCreationDateTime) }}</div>
    </div>
  </div>
</template>

<script>
import comboSelection from '@/utils/comboSelection'
import moment from 'moment'

export default {
  props: {
    betList: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      comboSelection
    }
  },
  methods: {
    formatEventDate(val) {
      return moment(val).utcOffset(-4).format('YYYY-MM-DD  HH:mm')
    },
    formatBetDate(val) {
      return moment(val).utcOffset(-4).format('YYYY-MM-DD  HH:mm:ss')
    }
  }
}
</script>

<style lang="scss" scoped>
.detail-list {
  padding: 15px 0;
  border-top: 1px solid #C9C6CD;
  .list {
    margin-bottom: 5px;
  }
}
</style>
