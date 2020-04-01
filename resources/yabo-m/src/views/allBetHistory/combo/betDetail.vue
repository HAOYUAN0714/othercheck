<template>
  <div>
    <div
      v-for="(detail, detailKey) in betList.WagerItemList"
      :key="`detail-list-${detailKey}`"
      class="detail-list"
    >
      <div v-if="detail.BetTypeId === 11" class="list">{{ detail.EventOutrightName }}</div>
      <div v-else class="list">
        {{ detail.HomeTeamName }} v {{ detail.AwayTeamName }}
      </div>
      <div class="list">{{ detail.CompetitionName }}</div>
      <div class="list">
        <span>{{ periodConfig[detail.PeriodId] }}{{ formatPlayTypeName(detail) }}</span>
        <span v-if="detail.Market === 3">
          {{ ` - ${$t('S_RUN_BALL')}` }}
          {{ getCurBetScore(detail) }}
          {{ getFTScore(detail) }}
        </span>
      </div>
      <div class="list">
        <span>{{ getSelectTeamName(detail) }}</span>
        <handicap :bet-item="detail" />
        <span>@</span>
        <span>{{ detail.Odds }}</span>
      </div>
      <div class="list">{{ formatEventDate(detail.EventDateTime) }}</div>
    </div>
    <div class="detail-list">
      <div class="list">
        <span>{{ $t(comboSelection[betList.ComboSelection]) }}</span>
        <span>1 x {{ betList.InputtedStakeAmount }}</span>
      </div>
      <div class="list">
        <span>{{ $t('S_BET_ID') }}: {{ betList.WagerId }}</span>
      </div>
      <div class="list">
        <span>{{ formatBetDate(betList.WagerCreationDateTime) }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import comboSelection from '@/utils/comboSelection'
import moment from 'moment'
import periodConfig from '@/utils/periodInfo'
import handicap from '@/layout/components/handicap/betRecordHandicap'

export default {
  components: {
    handicap
  },
  props: {
    betList: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      comboSelection,
      periodConfig
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
